<?php
/**
 * Template Name: Property Dashboard
 */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}
if (!current_user_can('edit_posts')) {
    wp_die('You do not have permission to access this page.');
}

get_header();

// Get stats
function sb_dash_count($meta_key, $meta_value) {
    $q = new WP_Query(['post_type'=>'property','posts_per_page'=>-1,'fields'=>'ids','meta_query'=>[['key'=>$meta_key,'value'=>$meta_value,'compare'=>'=']]]);
    return $q->found_posts;
}
$total_props = wp_count_posts('property')->publish;
$count_sale  = sb_dash_count('sb_status', 'for-sale');
$count_sold  = sb_dash_count('sb_status', 'sold');
$count_rent  = sb_dash_count('sb_status', 'for-rent');
?>

<!-- Dashboard Header -->
<div class="dash-header">
    <div class="dash-header-inner">
        <h1>Property Dashboard</h1>
        <a href="<?php echo esc_url(home_url('/add-property')); ?>" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add New Property
        </a>
    </div>
</div>

<!-- Stats -->
<div class="dash-stats">
    <div class="dash-stats-inner">
        <div class="dash-stat-card">
            <div class="dash-stat-num"><?php echo esc_html($total_props); ?></div>
            <div class="dash-stat-label">Total Properties</div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-num"><?php echo esc_html($count_sale); ?></div>
            <div class="dash-stat-label">For Sale</div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-num"><?php echo esc_html($count_sold); ?></div>
            <div class="dash-stat-label">Sold</div>
        </div>
        <div class="dash-stat-card">
            <div class="dash-stat-num"><?php echo esc_html($count_rent); ?></div>
            <div class="dash-stat-label">For Rent</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="dash-filters">
    <div class="dash-filters-inner">
        <div class="dash-search">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" id="dash-kw" placeholder="Search title, ref, city…" autocomplete="off">
        </div>
        <select id="dash-status" class="dash-select">
            <option value="">All statuses</option>
            <option value="for-sale">For Sale</option>
            <option value="sold">Sold</option>
            <option value="for-rent">For Rent</option>
        </select>
        <select id="dash-build" class="dash-select">
            <option value="">All types</option>
            <option value="resale">Resale</option>
            <option value="new_build">New Build</option>
        </select>
        <select id="dash-sort" class="dash-select">
            <option value="date">Newest first</option>
            <option value="price-asc">Price: Low → High</option>
            <option value="price-desc">Price: High → Low</option>
            <option value="title-asc">Title A–Z</option>
        </select>
        <span id="dash-count" class="dash-count"></span>
    </div>
</div>

<!-- Table -->
<div class="dash-body">
    <div class="dash-table-wrap">
        <table class="dash-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Property</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>City</th>
                    <th>Details</th>
                    <th>Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="dash-tbody">
                <tr class="dash-loading-row"><td colspan="8">Loading properties…</td></tr>
            </tbody>
        </table>
    </div>
    <div id="dash-pagination" class="dash-pagination"></div>
</div>

<script>
(function() {
    const kwEl     = document.getElementById('dash-kw');
    const statEl   = document.getElementById('dash-status');
    const buildEl  = document.getElementById('dash-build');
    const sortEl   = document.getElementById('dash-sort');
    const countEl  = document.getElementById('dash-count');
    const tbody    = document.getElementById('dash-tbody');
    const paginEl  = document.getElementById('dash-pagination');

    let debounce, currentPage = 1;

    function search(page) {
        currentPage = page || 1;
        const fd = new FormData();
        fd.append('action',     'sb_dash_search');
        fd.append('nonce',      '<?php echo esc_js(wp_create_nonce('sb_search')); ?>');
        fd.append('keyword',    kwEl.value);
        fd.append('status',     statEl.value);
        fd.append('build_type', buildEl.value);
        fd.append('sort',       sortEl.value);
        fd.append('paged',      currentPage);
        fd.append('per_page',   25);

        tbody.innerHTML = '<tr class="dash-loading-row"><td colspan="8">Loading…</td></tr>';
        countEl.textContent = '';

        fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    tbody.innerHTML = data.data.rows;
                    const t = data.data.total;
                    countEl.textContent = t + ' propert' + (t === 1 ? 'y' : 'ies');
                    renderPagination(data.data.pages);
                }
            })
            .catch(() => { tbody.innerHTML = '<tr><td colspan="8" class="dash-empty">Error loading properties.</td></tr>'; });
    }

    function renderPagination(pages) {
        if (pages <= 1) { paginEl.innerHTML = ''; return; }
        let html = '';
        if (currentPage > 1) html += `<button class="dash-page-btn" data-p="${currentPage-1}">← Prev</button>`;
        for (let i = 1; i <= pages; i++) {
            if (pages > 10 && i > 3 && i < pages - 2 && Math.abs(i - currentPage) > 2) {
                if (i === 4 || i === pages - 3) html += '<span style="padding:0 4px;color:#9ca3af">…</span>';
                continue;
            }
            html += `<button class="dash-page-btn${i===currentPage?' dash-page-btn--active':''}" data-p="${i}">${i}</button>`;
        }
        if (currentPage < pages) html += `<button class="dash-page-btn" data-p="${currentPage+1}">Next →</button>`;
        paginEl.innerHTML = html;
        paginEl.querySelectorAll('.dash-page-btn').forEach(btn => btn.addEventListener('click', () => search(parseInt(btn.dataset.p))));
    }

    kwEl.addEventListener('input', () => { clearTimeout(debounce); debounce = setTimeout(() => search(1), 350); });
    [statEl, buildEl, sortEl].forEach(el => el.addEventListener('change', () => search(1)));

    search(1);
})();
</script>

<?php get_footer(); ?>
