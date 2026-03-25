/**
 * add-property.js
 * Multi-step "Add Property" form logic.
 *
 * Globals injected by wp_localize_script:
 *   sbAddProp.ajaxUrl  — admin-ajax.php URL
 *   sbAddProp.nonce    — nonce for sb_add_property
 */
(function () {
  'use strict';

  /* ─── Config ─────────────────────────────────────────── */
  const MAX_PHOTOS = 20;
  const NOMINATIM  = 'https://nominatim.openstreetmap.org/search';

  /* ─── State ──────────────────────────────────────────── */
  let currentStep   = 1;
  let leafletMap    = null;
  let leafletMarker = null;
  let attachmentIds = []; // ordered list of WP attachment IDs

  /* ─── DOM refs ───────────────────────────────────────── */
  const form         = document.getElementById('ap-form');
  const successBox   = document.getElementById('ap-success');
  const successLink  = document.getElementById('ap-success-link');
  const submitError  = document.getElementById('ap-submit-error');
  const submitBtn    = document.getElementById('ap-submit-btn');
  const stepNumEl    = document.getElementById('ap-current-step-num');
  const progressFill = document.getElementById('ap-progress-fill');
  const dropZone     = document.getElementById('ap-drop-zone');
  const fileInput    = document.getElementById('ap-file-input');
  const imageGrid    = document.getElementById('ap-image-grid');
  const uploadProg   = document.getElementById('ap-upload-progress');
  const uploadBar    = document.getElementById('ap-upload-bar');
  const uploadLabel  = document.getElementById('ap-upload-label');
  const geocodeBtn   = document.getElementById('ap-geocode-btn');
  const geocodeSts   = document.getElementById('ap-geocode-status');
  const latField     = document.getElementById('ap-lat');
  const lngField     = document.getElementById('ap-lng');

  /* ══════════════════════════════════════════════════════
     STEP NAVIGATION
  ══════════════════════════════════════════════════════ */
  function goToStep(n) {
    const steps    = document.querySelectorAll('.ap-step');
    const circles  = document.querySelectorAll('.ap-progress__step');

    steps.forEach(s => {
      s.classList.toggle('ap-step--active', parseInt(s.dataset.step, 10) === n);
    });

    circles.forEach(c => {
      const num = parseInt(c.dataset.step, 10);
      c.classList.remove('ap-progress__step--active', 'ap-progress__step--done');
      if (num < n)      c.classList.add('ap-progress__step--done');
      else if (num === n) c.classList.add('ap-progress__step--active');
    });

    // Progress fill (0% at step 1, 100% after step 4 complete)
    const pct = ((n - 1) / 3) * 100;
    progressFill.style.width = pct + '%';

    if (stepNumEl) stepNumEl.textContent = n;
    currentStep = n;

    // Initialise map on step 3
    if (n === 3 && !leafletMap) {
      initMap();
    }

    // Scroll to top of form area
    const wrap = document.querySelector('.ap-progress-wrap');
    if (wrap) wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  /* ─── Attach next/back button listeners ─────────────── */
  document.addEventListener('click', function (e) {
    const nextBtn = e.target.closest('.ap-next');
    const backBtn = e.target.closest('.ap-back');

    if (nextBtn) {
      const target = parseInt(nextBtn.dataset.next, 10);
      if (validateStep(currentStep)) {
        goToStep(target);
      }
    }

    if (backBtn) {
      const target = parseInt(backBtn.dataset.back, 10);
      goToStep(target);
    }
  });

  /* ══════════════════════════════════════════════════════
     VALIDATION
  ══════════════════════════════════════════════════════ */
  function validateStep(step) {
    let valid = true;

    if (step === 1) {
      // Title
      const titleEl = document.getElementById('ap-title');
      valid = requireField(titleEl) && valid;

      // Status
      const statusChecked = document.querySelector('input[name="ap_status"]:checked');
      const statusErr     = document.getElementById('ap-status-error');
      if (!statusChecked) {
        statusErr.textContent = 'Please select a listing status.';
        statusErr.style.display = 'block';
        valid = false;
      } else {
        statusErr.textContent = '';
        statusErr.style.display = 'none';
      }

      // Build type
      const buildChecked = document.querySelector('input[name="ap_build_type"]:checked');
      const buildErr     = document.getElementById('ap-build-error');
      if (!buildChecked) {
        buildErr.textContent = 'Please select a build type.';
        buildErr.style.display = 'block';
        valid = false;
      } else {
        buildErr.textContent = '';
        buildErr.style.display = 'none';
      }
    }

    if (step === 2) {
      const priceEl = document.getElementById('ap-price');
      valid = requireField(priceEl, 'A price is required.') && valid;
      if (priceEl.value && isNaN(parseFloat(priceEl.value))) {
        showFieldError(priceEl, 'Please enter a valid number.');
        valid = false;
      }
    }

    return valid;
  }

  function requireField(el, msg) {
    if (!el) return true;
    const errorEl = el.closest('.ap-field')?.querySelector('.ap-field-error');
    if (!el.value.trim()) {
      if (errorEl) {
        errorEl.textContent = msg || 'This field is required.';
        errorEl.style.display = 'block';
      }
      el.classList.add('ap-input--error');
      el.focus();
      return false;
    }
    if (errorEl) {
      errorEl.textContent = '';
      errorEl.style.display = 'none';
    }
    el.classList.remove('ap-input--error');
    return true;
  }

  function showFieldError(el, msg) {
    const errorEl = el.closest('.ap-field')?.querySelector('.ap-field-error');
    if (errorEl) {
      errorEl.textContent = msg;
      errorEl.style.display = 'block';
    }
    el.classList.add('ap-input--error');
  }

  // Clear errors on input
  document.addEventListener('input', function (e) {
    if (e.target.classList.contains('ap-input')) {
      e.target.classList.remove('ap-input--error');
      const errorEl = e.target.closest('.ap-field')?.querySelector('.ap-field-error');
      if (errorEl) { errorEl.textContent = ''; errorEl.style.display = 'none'; }
    }
  });

  /* ══════════════════════════════════════════════════════
     RADIO CARDS
  ══════════════════════════════════════════════════════ */
  document.addEventListener('change', function (e) {
    if (e.target.classList.contains('ap-radio-input')) {
      const name  = e.target.name;
      const cards = document.querySelectorAll(`input[name="${name}"]`);
      cards.forEach(function (inp) {
        inp.closest('.ap-radio-card').classList.toggle('selected', inp === e.target);
      });
    }
  });

  // Also handle click on the card itself (label wraps input so this fires naturally,
  // but we also need it for keyboard users — handled by change event above)
  document.querySelectorAll('.ap-radio-card').forEach(function (card) {
    card.addEventListener('click', function () {
      const input = card.querySelector('.ap-radio-input');
      if (input) {
        input.checked = true;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
  });

  /* ══════════════════════════════════════════════════════
     STEPPERS (Bedrooms / Bathrooms)
  ══════════════════════════════════════════════════════ */
  document.addEventListener('click', function (e) {
    const stepper = e.target.closest('.ap-stepper__btn');
    if (!stepper) return;
    const wrap  = stepper.closest('.ap-stepper');
    const input = wrap.querySelector('.ap-stepper__input');
    if (!input) return;
    let val = parseInt(input.value, 10) || 0;
    if (stepper.classList.contains('ap-stepper__minus')) val = Math.max(0, val - 1);
    if (stepper.classList.contains('ap-stepper__plus'))  val = Math.min(20, val + 1);
    input.value = val;
  });

  /* ══════════════════════════════════════════════════════
     MAP (Leaflet + Nominatim)
  ══════════════════════════════════════════════════════ */
  function initMap() {
    if (!window.L) return;

    const defaultLat = 37.9838;
    const defaultLng = -0.6821;

    leafletMap = L.map('ap-map', { zoomControl: true }).setView([defaultLat, defaultLng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19,
    }).addTo(leafletMap);

    // Custom navy pin icon
    const navyIcon = L.divIcon({
      html: '<div class="ap-map-pin"><svg viewBox="0 0 24 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 0C5.373 0 0 5.373 0 12c0 8.4 12 18 12 18s12-9.6 12-18C24 5.373 18.627 0 12 0z" fill="#001d3d"/><circle cx="12" cy="12" r="5" fill="#fff"/></svg></div>',
      className: '',
      iconSize:   [32, 40],
      iconAnchor: [16, 40],
    });

    // Click on map → place/move marker
    leafletMap.on('click', function (e) {
      placeMarker(e.latlng.lat, e.latlng.lng);
    });

    // Pre-fill from existing lat/lng fields
    const existingLat = parseFloat(latField.value);
    const existingLng = parseFloat(lngField.value);
    if (!isNaN(existingLat) && !isNaN(existingLng)) {
      placeMarker(existingLat, existingLng);
      leafletMap.setView([existingLat, existingLng], 15);
    }

    function placeMarker(lat, lng) {
      if (leafletMarker) {
        leafletMarker.setLatLng([lat, lng]);
      } else {
        leafletMarker = L.marker([lat, lng], { icon: navyIcon, draggable: true }).addTo(leafletMap);
        leafletMarker.on('dragend', function () {
          const pos = leafletMarker.getLatLng();
          setLatLng(pos.lat, pos.lng);
        });
      }
      setLatLng(lat, lng);
    }

    // Store placeMarker on map object so geocode can call it
    leafletMap._sbPlace = placeMarker;
  }

  function setLatLng(lat, lng) {
    latField.value = parseFloat(lat).toFixed(6);
    lngField.value = parseFloat(lng).toFixed(6);
  }

  // Manual lat/lng → move marker
  [latField, lngField].forEach(function (el) {
    el.addEventListener('change', function () {
      if (!leafletMap) return;
      const lat = parseFloat(latField.value);
      const lng = parseFloat(lngField.value);
      if (!isNaN(lat) && !isNaN(lng)) {
        leafletMap._sbPlace(lat, lng);
        leafletMap.setView([lat, lng], leafletMap.getZoom());
      }
    });
  });

  // Geocode address via Nominatim
  if (geocodeBtn) {
    geocodeBtn.addEventListener('click', function () {
      const address = document.getElementById('ap-address').value.trim();
      if (!address) {
        setGeocodeStatus('Please enter an address first.', 'error');
        return;
      }

      geocodeBtn.disabled = true;
      geocodeBtn.textContent = 'Searching…';
      setGeocodeStatus('', '');

      const url = NOMINATIM + '?q=' + encodeURIComponent(address) + '&format=json&limit=1';

      fetch(url, {
        headers: { 'Accept-Language': 'en', 'User-Agent': 'SpaniaBoligTheme/1.0' },
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (!data.length) {
            setGeocodeStatus('Address not found. Try a more specific search.', 'error');
            return;
          }
          const lat = parseFloat(data[0].lat);
          const lng = parseFloat(data[0].lon);
          if (leafletMap && leafletMap._sbPlace) {
            leafletMap._sbPlace(lat, lng);
            leafletMap.setView([lat, lng], 16);
          }
          setLatLng(lat, lng);
          setGeocodeStatus('Location found: ' + data[0].display_name.split(',').slice(0, 3).join(','), 'success');
        })
        .catch(function () {
          setGeocodeStatus('Geocoding failed. Check your connection and try again.', 'error');
        })
        .finally(function () {
          geocodeBtn.disabled = false;
          geocodeBtn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg> Find on Map';
        });
    });
  }

  function setGeocodeStatus(msg, type) {
    if (!geocodeSts) return;
    geocodeSts.textContent = msg;
    geocodeSts.className = 'ap-geocode-status ap-geocode-status--' + type;
  }

  /* ══════════════════════════════════════════════════════
     IMAGE UPLOAD
  ══════════════════════════════════════════════════════ */
  if (dropZone && fileInput) {

    // Click on drop zone → trigger file input
    dropZone.addEventListener('click', function (e) {
      if (e.target === fileInput) return;
      fileInput.click();
    });
    dropZone.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fileInput.click(); }
    });

    // Drag visual feedback
    ['dragenter', 'dragover'].forEach(function (ev) {
      dropZone.addEventListener(ev, function (e) {
        e.preventDefault();
        dropZone.classList.add('ap-drop-zone--over');
      });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
      dropZone.addEventListener(ev, function (e) {
        e.preventDefault();
        dropZone.classList.remove('ap-drop-zone--over');
      });
    });

    // Drop files
    dropZone.addEventListener('drop', function (e) {
      e.preventDefault();
      const files = e.dataTransfer.files;
      if (files && files.length) handleFiles(files);
    });

    // File input change
    fileInput.addEventListener('change', function () {
      if (fileInput.files && fileInput.files.length) {
        handleFiles(fileInput.files);
        // Reset so same file can be re-selected
        fileInput.value = '';
      }
    });
  }

  function handleFiles(files) {
    const remaining = MAX_PHOTOS - attachmentIds.length;
    if (remaining <= 0) {
      alert('Maximum of ' + MAX_PHOTOS + ' photos allowed.');
      return;
    }

    const toUpload = Array.from(files).slice(0, remaining);
    uploadSequential(toUpload, 0);
  }

  function uploadSequential(files, index) {
    if (index >= files.length) {
      hideProgress();
      return;
    }
    showProgress(index + 1, files.length);
    uploadFile(files[index], function () {
      uploadSequential(files, index + 1);
    });
  }

  function uploadFile(file, onDone) {
    const data = new FormData();
    data.append('action',   'sb_upload_image');
    data.append('nonce',    sbAddProp.nonce);
    data.append('ap_image', file, file.name);

    fetch(sbAddProp.ajaxUrl, { method: 'POST', body: data })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res.success) {
          attachmentIds.push(res.data.id);
          addThumb(res.data.id, res.data.thumb || res.data.url);
        } else {
          console.error('Upload failed:', res.data?.message);
          alert('Upload failed: ' + (res.data?.message || 'Unknown error'));
        }
      })
      .catch(function (err) {
        console.error('Upload error:', err);
        alert('Upload failed. Please try again.');
      })
      .finally(onDone);
  }

  function addThumb(id, thumbUrl) {
    const isCover = attachmentIds.length === 1; // just pushed, so length 1 = first
    const thumb   = document.createElement('div');
    thumb.className = 'ap-thumb' + (isCover ? ' ap-thumb--cover' : '');
    thumb.dataset.id = id;
    thumb.innerHTML =
      '<img src="' + thumbUrl + '" alt="Photo" loading="lazy">' +
      (isCover ? '<span class="ap-thumb__badge">Cover</span>' : '') +
      '<button type="button" class="ap-thumb__remove" aria-label="Remove photo" data-id="' + id + '">' +
        '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>' +
      '</button>' +
      (!isCover ? '<button type="button" class="ap-thumb__set-cover" aria-label="Set as cover" data-id="' + id + '">Set Cover</button>' : '');
    imageGrid.appendChild(thumb);
  }

  // Remove / set-cover clicks within image grid
  imageGrid && imageGrid.addEventListener('click', function (e) {
    const removeBtn    = e.target.closest('.ap-thumb__remove');
    const setCoverBtn  = e.target.closest('.ap-thumb__set-cover');

    if (removeBtn) {
      const id    = parseInt(removeBtn.dataset.id, 10);
      const idx   = attachmentIds.indexOf(id);
      if (idx !== -1) attachmentIds.splice(idx, 1);
      const thumb = imageGrid.querySelector('[data-id="' + id + '"]');
      if (thumb) thumb.remove();
      refreshCoverBadge();
    }

    if (setCoverBtn) {
      const id  = parseInt(setCoverBtn.dataset.id, 10);
      const idx = attachmentIds.indexOf(id);
      if (idx > 0) {
        // Move to front
        attachmentIds.splice(idx, 1);
        attachmentIds.unshift(id);
        rebuildGrid();
      }
    }
  });

  function rebuildGrid() {
    // Re-order existing thumb elements to match attachmentIds order
    const thumbMap = {};
    imageGrid.querySelectorAll('.ap-thumb').forEach(function (el) {
      thumbMap[parseInt(el.dataset.id, 10)] = el;
    });
    // Remove all, re-append in order
    while (imageGrid.firstChild) imageGrid.removeChild(imageGrid.firstChild);
    attachmentIds.forEach(function (id) {
      if (thumbMap[id]) imageGrid.appendChild(thumbMap[id]);
    });
    refreshCoverBadge();
  }

  function refreshCoverBadge() {
    const thumbs = imageGrid.querySelectorAll('.ap-thumb');
    thumbs.forEach(function (thumb, i) {
      const badge   = thumb.querySelector('.ap-thumb__badge');
      const setCoverBtn = thumb.querySelector('.ap-thumb__set-cover');

      if (i === 0) {
        thumb.classList.add('ap-thumb--cover');
        if (!badge) {
          const b = document.createElement('span');
          b.className = 'ap-thumb__badge';
          b.textContent = 'Cover';
          thumb.prepend(b);
        }
        if (setCoverBtn) setCoverBtn.remove();
      } else {
        thumb.classList.remove('ap-thumb--cover');
        if (badge) badge.remove();
        if (!thumb.querySelector('.ap-thumb__set-cover')) {
          const btn = document.createElement('button');
          btn.type        = 'button';
          btn.className   = 'ap-thumb__set-cover';
          btn.textContent = 'Set Cover';
          btn.dataset.id  = thumb.dataset.id;
          btn.setAttribute('aria-label', 'Set as cover');
          thumb.appendChild(btn);
        }
      }
    });
  }

  function showProgress(current, total) {
    if (!uploadProg) return;
    uploadProg.style.display = 'flex';
    const pct = Math.round(((current - 1) / total) * 100);
    uploadBar.style.width   = pct + '%';
    uploadLabel.textContent = 'Uploading photo ' + current + ' of ' + total + '…';
  }

  function hideProgress() {
    if (!uploadProg) return;
    uploadBar.style.width   = '100%';
    uploadLabel.textContent = 'All photos uploaded';
    setTimeout(function () {
      uploadProg.style.display = 'none';
    }, 1200);
  }

  /* ══════════════════════════════════════════════════════
     EDIT MODE: pre-populate fields from sbEditProp
  ══════════════════════════════════════════════════════ */
  if (typeof sbEditProp !== 'undefined' && sbEditProp && sbEditProp.id) {

    // Basic Info
    var titleEl2 = document.getElementById('ap-title');
    if (titleEl2) titleEl2.value = sbEditProp.title || '';

    var descEl = document.getElementById('ap-description');
    if (descEl) descEl.value = sbEditProp.description || '';

    // Status radio
    if (sbEditProp.status) {
      var statusInp = document.querySelector('input[name="ap_status"][value="' + sbEditProp.status + '"]');
      if (statusInp) {
        statusInp.checked = true;
        statusInp.closest('.ap-radio-card').classList.add('selected');
      }
    }

    // Build type radio
    if (sbEditProp.build_type) {
      var buildInp = document.querySelector('input[name="ap_build_type"][value="' + sbEditProp.build_type + '"]');
      if (buildInp) {
        buildInp.checked = true;
        buildInp.closest('.ap-radio-card').classList.add('selected');
      }
    }

    // Featured toggle
    var featuredEl2 = document.getElementById('ap-featured');
    if (featuredEl2) featuredEl2.checked = sbEditProp.featured === '1';

    // Details
    var priceEl2 = document.getElementById('ap-price');
    if (priceEl2 && sbEditProp.price) priceEl2.value = sbEditProp.price;

    var sizeEl = document.getElementById('ap-size');
    if (sizeEl && sbEditProp.size) sizeEl.value = sbEditProp.size;

    var bedroomsEl2 = document.getElementById('ap-bedrooms');
    if (bedroomsEl2 && sbEditProp.bedrooms !== undefined) bedroomsEl2.value = sbEditProp.bedrooms;

    var bathroomsEl2 = document.getElementById('ap-bathrooms');
    if (bathroomsEl2 && sbEditProp.bathrooms !== undefined) bathroomsEl2.value = sbEditProp.bathrooms;

    var refEl = document.getElementById('ap-ref');
    if (refEl) refEl.value = sbEditProp.ref || '';

    var cityEl = document.getElementById('ap-city');
    if (cityEl) cityEl.value = sbEditProp.city || 'Ciudad Quesada';

    // Location
    var addressEl2 = document.getElementById('ap-address');
    if (addressEl2) addressEl2.value = sbEditProp.address || '';

    if (latField && sbEditProp.lat) latField.value = sbEditProp.lat;
    if (lngField && sbEditProp.lng) lngField.value = sbEditProp.lng;

    // Existing photos — seed attachmentIds and render thumbs
    if (sbEditProp.images && sbEditProp.images.length) {
      sbEditProp.images.forEach(function (img) {
        attachmentIds.push(img.id);
        addThumb(img.id, img.thumb || img.url);
      });
    }
  }

  /* ══════════════════════════════════════════════════════
     FORM SUBMIT
  ══════════════════════════════════════════════════════ */
  form && form.addEventListener('submit', function (e) {
    e.preventDefault();

    if (!validateStep(1) || !validateStep(2)) {
      goToStep(1);
      return;
    }

    setSubmitLoading(true);
    submitError.style.display = 'none';

    var isEditMode = typeof sbEditProp !== 'undefined' && sbEditProp && sbEditProp.id;

    const data = new FormData();
    data.append('action', isEditMode ? 'sb_update_property' : 'sb_submit_property');
    data.append('nonce',  sbAddProp.nonce);
    if (isEditMode) data.append('ap_property_id', sbEditProp.id);
    data.append('ap_title',       document.getElementById('ap-title').value.trim());
    data.append('ap_description', document.getElementById('ap-description').value);
    data.append('ap_status',      (document.querySelector('input[name="ap_status"]:checked') || {}).value || '');
    data.append('ap_build_type',  (document.querySelector('input[name="ap_build_type"]:checked') || {}).value || '');
    data.append('ap_featured',    document.getElementById('ap-featured').checked ? '1' : '0');
    data.append('ap_price',       document.getElementById('ap-price').value);
    data.append('ap_bedrooms',    document.getElementById('ap-bedrooms').value);
    data.append('ap_bathrooms',   document.getElementById('ap-bathrooms').value);
    data.append('ap_size',        document.getElementById('ap-size').value);
    data.append('ap_ref',         document.getElementById('ap-ref').value);
    data.append('ap_city',        document.getElementById('ap-city').value);
    data.append('ap_address',     document.getElementById('ap-address').value);
    data.append('ap_lat',         latField.value);
    data.append('ap_lng',         lngField.value);

    attachmentIds.forEach(function (id) {
      data.append('ap_image_ids[]', id);
    });

    fetch(sbAddProp.ajaxUrl, { method: 'POST', body: data })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res.success) {
          form.style.display = 'none';
          successBox.style.display = 'flex';
          if (successLink) successLink.href = res.data.url;
          // Update progress bar to 100%
          progressFill.style.width = '100%';
          document.querySelectorAll('.ap-progress__step').forEach(function (c) {
            c.classList.remove('ap-progress__step--active');
            c.classList.add('ap-progress__step--done');
          });
          successBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
          const msg = (res.data && res.data.message) ? res.data.message : 'Something went wrong. Please try again.';
          submitError.textContent = msg;
          submitError.style.display = 'block';
          submitError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      })
      .catch(function () {
        submitError.textContent = 'Network error. Please check your connection and try again.';
        submitError.style.display = 'block';
      })
      .finally(function () {
        setSubmitLoading(false);
      });
  });

  function setSubmitLoading(loading) {
    if (!submitBtn) return;
    submitBtn.disabled = loading;
    if (loading) {
      submitBtn.dataset.originalHtml = submitBtn.innerHTML;
      var loadingLabel = (typeof sbEditProp !== 'undefined' && sbEditProp && sbEditProp.id) ? ' Updating…' : ' Publishing…';
      submitBtn.innerHTML =
        '<svg class="ap-spin" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>' +
        loadingLabel;
    } else if (submitBtn.dataset.originalHtml) {
      submitBtn.innerHTML = submitBtn.dataset.originalHtml;
    }
  }

})();
