<?php
/**
 * Template Name: Favourites
 *
 * Displays properties the visitor has saved (stored in localStorage).
 */
defined('ABSPATH') || exit;
get_header();
?>

<section class="fav-hero">
    <div class="section-inner--narrow">
        <h1>Your Saved Properties</h1>
        <p>Properties you&rsquo;ve saved while browsing. Stored in your browser — no account needed.</p>
    </div>
</section>

<section class="fav-results">
    <div class="section-inner--narrow">
        <div id="fav-loading" class="fav-loading">
            <span class="fav-spinner"></span>
            Loading your saved properties&hellip;
        </div>

        <div id="fav-empty" class="fav-empty" style="display:none;">
            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            <h2>No saved properties yet</h2>
            <p>Click the <strong>&hearts;</strong> on any property to save it here.</p>
            <a href="<?php echo esc_url(get_post_type_archive_link('property')); ?>" class="btn btn-primary">Browse properties</a>
        </div>

        <div id="fav-grid" class="property-grid" style="display:none;"></div>
    </div>
</section>

<script>
(function () {
    const grid    = document.getElementById('fav-grid');
    const empty   = document.getElementById('fav-empty');
    const loading = document.getElementById('fav-loading');

    function getIds() {
        try { return JSON.parse(localStorage.getItem('sb_favorites') || '[]'); }
        catch(e) { return []; }
    }

    const ids = getIds();

    if (!ids.length) {
        loading.style.display = 'none';
        empty.style.display   = '';
        return;
    }

    const fd = new FormData();
    fd.append('action', 'sb_get_favorites');
    fd.append('nonce',  '<?php echo esc_js(wp_create_nonce('sb_search')); ?>');
    ids.forEach(id => fd.append('ids[]', id));

    fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            loading.style.display = 'none';
            if (data.success && data.data.html) {
                grid.innerHTML     = data.data.html;
                grid.style.display = '';
                // Re-init heart buttons on returned cards
                initFavBtns(grid);
            } else {
                empty.style.display = '';
            }
        })
        .catch(() => {
            loading.style.display = 'none';
            empty.style.display   = '';
        });

    function initFavBtns(scope) {
        scope.querySelectorAll('.fav-btn').forEach(btn => {
            const id = btn.dataset.id;
            btn.classList.add('is-fav'); // all shown cards are favourites
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                // Remove from favourites & hide card
                let stored = getIds().filter(i => i !== id);
                localStorage.setItem('sb_favorites', JSON.stringify(stored));
                const card = btn.closest('.property-card');
                card.style.transition = 'opacity .3s';
                card.style.opacity = '0';
                setTimeout(() => {
                    card.remove();
                    if (!grid.querySelector('.property-card')) {
                        grid.style.display  = 'none';
                        empty.style.display = '';
                    }
                }, 300);
            });
        });
    }
})();
</script>

<?php get_footer(); ?>
