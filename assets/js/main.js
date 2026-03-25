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

    /* ── Property gallery lightbox (simple) ── */
    const mainImg  = document.querySelector('.gallery-main-img');
    const thumbs   = document.querySelectorAll('.gallery-thumb');
    if (mainImg && thumbs.length) {
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', () => {
                mainImg.src = thumb.src.replace('-150x150', '');
                mainImg.srcset = '';
                thumbs.forEach(t => t.style.opacity = '0.8');
                thumb.style.opacity = '1';
            });
        });
    }

    /* ── Leaflet map (if lat/lng set) ── */
    const mapEl = document.getElementById('sb-map');
    if (mapEl && window.L) {
        const lat = parseFloat(mapEl.dataset.lat);
        const lng = parseFloat(mapEl.dataset.lng);
        const map = L.map('sb-map').setView([lat, lng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map);
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

})();
