/* Spaniabolig Theme JS */
(function () {
    'use strict';

    /* ── Mobile nav toggle ── */
    const toggle = document.querySelector('.nav-toggle');
    const nav    = document.querySelector('.site-nav');
    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const open = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', !open);
            nav.classList.toggle('nav-open', !open);
        });
    }

    /* ── Favourite heart buttons ── */
    function getFavs() {
        try { return JSON.parse(localStorage.getItem('sb_favorites') || '[]'); } catch(e) { return []; }
    }
    function saveFavs(arr) { localStorage.setItem('sb_favorites', JSON.stringify(arr)); }

    function initFavBtns() {
        const favs = getFavs();
        document.querySelectorAll('.fav-btn').forEach(btn => {
            const id = btn.dataset.id;
            if (favs.includes(id)) btn.classList.add('is-fav');

            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                let stored = getFavs();
                if (stored.includes(id)) {
                    stored = stored.filter(i => i !== id);
                    btn.classList.remove('is-fav');
                    btn.title = 'Save to favourites';
                } else {
                    stored.push(id);
                    btn.classList.add('is-fav');
                    btn.title = 'Remove from favourites';
                }
                saveFavs(stored);
            });
        });
    }
    initFavBtns();

    /* ── Gallery thumbnail click-to-swap ── */
    const mainImg = document.getElementById('gallery-main-img');
    if (mainImg) {
        document.querySelectorAll('.gallery-thumb').forEach(thumb => {
            thumb.addEventListener('click', function() {
                mainImg.src = this.dataset.full || this.src;
                document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    /* ── Leaflet map (if lat/lng set) ── */
    const mapEl = document.getElementById('sb-map');
    if (mapEl) {
        const lat = parseFloat(mapEl.dataset.lat);
        const lng = parseFloat(mapEl.dataset.lng);
        const mapSection = mapEl.closest('.property-map');
        if (!window.L || isNaN(lat) || isNaN(lng)) {
            if (mapSection) mapSection.style.display = 'none';
        } else {
            try {
                const map = L.map('sb-map').setView([lat, lng], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                L.marker([lat, lng]).addTo(map);
            } catch (e) {
                if (mapSection) mapSection.style.display = 'none';
            }
        }
    }

    /* ── Dropdown with hover delay ── */
    document.querySelectorAll('.has-dropdown').forEach(item => {
        let timer;
        const dropdown = item.querySelector('.dropdown');
        if (!dropdown) return;

        item.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            dropdown.style.display = 'block';
        });
        item.addEventListener('mouseleave', () => {
            timer = setTimeout(() => {
                dropdown.style.display = '';
            }, 150);
        });
        dropdown.addEventListener('mouseenter', () => clearTimeout(timer));
        dropdown.addEventListener('mouseleave', () => {
            timer = setTimeout(() => {
                dropdown.style.display = '';
            }, 150);
        });
    });

    /* ── Sticky header shadow ── */
    const header = document.querySelector('.site-header');
    if (header) {
        window.addEventListener('scroll', () => {
            header.style.boxShadow = window.scrollY > 10 ? '0 2px 16px rgba(0,0,0,.10)' : '';
        }, { passive: true });
    }

    /* ── Archive property filter (AJAX) ── */
    const pfGrid = document.getElementById('pf-grid');
    if (pfGrid && typeof sbData !== 'undefined') {
        const kwEl     = document.getElementById('pf-keyword');
        const typeEl   = document.getElementById('pf-type');
        const locEl    = document.getElementById('pf-location');
        const priceEl  = document.getElementById('pf-price');
        const bedsEl   = document.getElementById('pf-beds');
        const statusEl    = document.getElementById('pf-status');
        const buildTypeEl = document.getElementById('pf-build-type');
        const sortEl      = document.getElementById('pf-sort');
        const clearBtn = document.getElementById('pf-clear');
        const countEl  = document.getElementById('pf-count');
        const noRes    = document.getElementById('pf-no-results');
        const paginEl  = document.getElementById('pf-pagination');

        let debounceTimer, currentPage = 1;

        function esc(str) {
            const d = document.createElement('div');
            d.textContent = str || '';
            return d.innerHTML;
        }

        function renderCard(p) {
            const labels    = {'for-sale': 'For Sale', 'for-rent': 'For Rent', 'sold': 'Sold'};
            const statusKey = p.status ? p.status.replace(/_/g, '-') : '';
            const price     = p.price ? '\u20ac\u00a0' + parseInt(p.price).toLocaleString('de-DE') : '';
            const badge     = statusKey ? `<span class="card-badge card-badge--${statusKey}">${labels[statusKey] || statusKey}</span>` : '';
            const img    = p.image
                ? `<img src="${p.image}" alt="${esc(p.title)}" class="card-image" loading="lazy">`
                : `<div class="card-image card-image--placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" width="48" height="48"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>`;
            const loc    = p.city ? `<p class="card-location"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>${esc(p.city)}</p>` : '';
            const beds   = p.bedrooms  ? `<span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>${esc(p.bedrooms)} bed</span>` : '';
            const baths  = p.bathrooms ? `<span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" y1="5" x2="8" y2="7"/><line x1="2" y1="12" x2="22" y2="12"/></svg>${esc(p.bathrooms)} bath</span>` : '';
            const size   = p.size      ? `<span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>${esc(p.size)} m\u00b2</span>` : '';
            return `<article class="property-card">
                <a href="${p.url}" class="card-image-wrap">${img}${badge}</a>
                <div class="card-body">
                    ${price ? `<div class="card-price">${price}</div>` : ''}
                    <h3 class="card-title"><a href="${p.url}">${esc(p.title)}</a></h3>
                    ${loc}
                    <div class="card-meta">${beds}${baths}${size}</div>
                </div>
            </article>`;
        }

        function renderPagination(pages) {
            if (pages <= 1) { paginEl.innerHTML = ''; return; }
            let html = '<div class="pf-pages">';
            if (currentPage > 1) html += `<button class="pf-page-btn" data-page="${currentPage - 1}">\u2190 Prev</button>`;
            for (let i = 1; i <= pages; i++) {
                html += `<button class="pf-page-btn${i === currentPage ? ' pf-page-btn--active' : ''}" data-page="${i}">${i}</button>`;
            }
            if (currentPage < pages) html += `<button class="pf-page-btn" data-page="${currentPage + 1}">Next \u2192</button>`;
            html += '</div>';
            paginEl.innerHTML = html;
            paginEl.querySelectorAll('.pf-page-btn').forEach(btn => {
                btn.addEventListener('click', () => doSearch(parseInt(btn.dataset.page)));
            });
        }

        function doSearch(page) {
            currentPage = page || 1;
            const priceVal = priceEl.value;
            let minPrice = '', maxPrice = '';
            if (priceVal) {
                const parts = priceVal.split('-');
                minPrice = parts[0];
                maxPrice = parts[1] || '';
            }

            const fd = new FormData();
            fd.append('action',        'sb_search');
            fd.append('nonce',         sbData.nonce);
            fd.append('keyword',       kwEl.value.trim());
            fd.append('property_type', typeEl.value);
            fd.append('location',      locEl.value);
            fd.append('min_price',     minPrice);
            fd.append('max_price',     maxPrice);
            fd.append('bedrooms',      bedsEl.value);
            fd.append('status',        statusEl.value);
            if (buildTypeEl) fd.append('build_type', buildTypeEl.value);
            fd.append('sort',          sortEl.value);
            fd.append('paged',         currentPage);
            fd.append('per_page',      12);

            pfGrid.classList.add('pf-loading');

            fetch(sbData.ajaxUrl, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(res => {
                    if (!res.success) return;
                    const { properties, total, pages } = res.data;
                    countEl.textContent = total === 1 ? '1 property found' : total + ' properties found';
                    if (properties.length) {
                        pfGrid.innerHTML = properties.map(renderCard).join('');
                        pfGrid.style.display = '';
                        noRes.style.display = 'none';
                    } else {
                        pfGrid.innerHTML = '';
                        pfGrid.style.display = 'none';
                        noRes.style.display = '';
                    }
                    pfGrid.classList.remove('pf-loading');
                    renderPagination(pages);
                })
                .catch(() => pfGrid.classList.remove('pf-loading'));
        }

        kwEl.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => doSearch(1), 400);
        });
        [typeEl, locEl, priceEl, bedsEl, statusEl, buildTypeEl, sortEl].filter(Boolean).forEach(el => {
            el.addEventListener('change', () => doSearch(1));
        });
        clearBtn.addEventListener('click', () => {
            kwEl.value = typeEl.value = locEl.value = priceEl.value = bedsEl.value = statusEl.value = '';
            if (buildTypeEl) buildTypeEl.value = '';
            sortEl.value = 'date';
            doSearch(1);
        });

        // If URL params are active on page load, trigger AJAX immediately so
        // pagination renders correctly (SSR only shows one page of results).
        const _urlP = new URLSearchParams(window.location.search);
        if (_urlP.has('build_type') || _urlP.has('status') || _urlP.has('location') || _urlP.has('keyword')) {
            doSearch(1);
        }
    }

})();
