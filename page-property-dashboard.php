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

// Author-scoped: non-admins (authors) only see their own listings + inquiries
$is_admin_view = current_user_can('edit_others_posts');
$current_uid   = get_current_user_id();

// Get stats
function sb_dash_count($meta_key, $meta_value, $author_id = 0) {
    $args = [
        'post_type'      => 'property',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => [['key' => $meta_key, 'value' => $meta_value, 'compare' => '=']],
    ];
    if ($author_id) $args['author'] = $author_id;
    $q = new WP_Query($args);
    return $q->found_posts;
}
$author_arg  = $is_admin_view ? 0 : $current_uid;
$total_props = $is_admin_view
    ? wp_count_posts('property')->publish
    : (new WP_Query(['post_type'=>'property','posts_per_page'=>-1,'fields'=>'ids','author'=>$current_uid]))->found_posts;
$count_sale  = sb_dash_count('sb_status', 'for-sale', $author_arg);
$count_sold  = sb_dash_count('sb_status', 'sold',     $author_arg);
$count_rent  = sb_dash_count('sb_status', 'for-rent', $author_arg);

// Inquiry counts (authors only see inquiries on their own listings)
$inq_base_args = [
    'post_type'      => 'sb_inquiry',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
];
if (!$is_admin_view) {
    $inq_base_args['meta_query'] = [['key' => '_sb_inq_owner_id', 'value' => $current_uid, 'compare' => '=']];
}

$count_inq_total = (new WP_Query($inq_base_args))->found_posts;

$new_args = $inq_base_args;
$new_args['meta_query'] = $is_admin_view
    ? [['key' => '_sb_inq_status', 'value' => 'new', 'compare' => '=']]
    : [
        'relation' => 'AND',
        ['key' => '_sb_inq_owner_id', 'value' => $current_uid, 'compare' => '='],
        ['key' => '_sb_inq_status',   'value' => 'new',         'compare' => '='],
    ];
$count_inq_new = (new WP_Query($new_args))->found_posts;

// Recent inquiries (latest 10)
$recent_args = [
    'post_type'      => 'sb_inquiry',
    'post_status'    => 'publish',
    'posts_per_page' => 10,
    'orderby'        => 'date',
    'order'          => 'DESC',
];
if (!$is_admin_view) {
    $recent_args['meta_query'] = [['key' => '_sb_inq_owner_id', 'value' => $current_uid, 'compare' => '=']];
}
$recent_inquiries = get_posts($recent_args);
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
        <a href="<?php echo esc_url(admin_url('edit.php?post_type=sb_inquiry')); ?>" class="dash-stat-card" style="text-decoration:none;color:inherit;position:relative;">
            <div class="dash-stat-num">
                <?php echo esc_html($count_inq_total); ?>
                <?php if ($count_inq_new > 0) : ?>
                    <span style="background:#d63638;color:#fff;font-size:11px;padding:3px 8px;border-radius:10px;vertical-align:middle;margin-left:6px;font-weight:600;"><?php echo esc_html($count_inq_new); ?> new</span>
                <?php endif; ?>
            </div>
            <div class="dash-stat-label">Inquiries</div>
        </a>
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

    <!-- Recent Inquiries -->
    <div class="dash-inquiries">
        <div class="dash-inquiries-header">
            <h2>Recent Inquiries</h2>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=sb_inquiry')); ?>" class="dash-inquiries-link">View all →</a>
        </div>
        <?php if (empty($recent_inquiries)) : ?>
            <p class="dash-empty">No inquiries yet.</p>
        <?php else : ?>
            <div class="dash-table-wrap">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Property / Service</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_inquiries as $inq) :
                            $type    = get_post_meta($inq->ID, '_sb_inq_type', true);
                            $name    = get_post_meta($inq->ID, '_sb_inq_name', true);
                            $email   = get_post_meta($inq->ID, '_sb_inq_email', true);
                            $phone   = get_post_meta($inq->ID, '_sb_inq_phone', true);
                            $status  = get_post_meta($inq->ID, '_sb_inq_status', true) ?: 'new';
                            $prop_id = (int) get_post_meta($inq->ID, '_sb_inq_property_id', true);
                            $service = get_post_meta($inq->ID, '_sb_inq_service', true);
                            $subject = get_post_meta($inq->ID, '_sb_inq_subject', true);
                            $type_colors   = ['property' => '#2271b1', 'service' => '#00a32a', 'contact' => '#8c8f94'];
                            $status_colors = ['new' => '#d63638', 'read' => '#dba617', 'replied' => '#00a32a', 'archived' => '#8c8f94'];
                            $tbg = $type_colors[$type] ?? '#8c8f94';
                            $sbg = $status_colors[$status] ?? '#8c8f94';
                            $context = $prop_id ? get_the_title($prop_id) : ($service ?: $subject ?: '—');
                        ?>
                        <tr>
                            <td><span class="dash-badge" style="background:<?php echo esc_attr($tbg); ?>"><?php echo esc_html(ucfirst($type ?: '—')); ?></span></td>
                            <td><strong><?php echo esc_html($name ?: '—'); ?></strong></td>
                            <td><?php echo $email ? '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>' : '—'; ?></td>
                            <td><?php echo $phone ? '<a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>' : '—'; ?></td>
                            <td><?php echo esc_html($context); ?></td>
                            <td><span class="dash-badge" style="background:<?php echo esc_attr($sbg); ?>"><?php echo esc_html(ucfirst($status)); ?></span></td>
                            <td><?php echo esc_html(get_the_date('M j, Y g:i a', $inq)); ?></td>
                            <td><a class="dash-action-btn dash-action-btn--edit" href="<?php echo esc_url(get_edit_post_link($inq->ID)); ?>">View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
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
