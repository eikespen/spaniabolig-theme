<?php
/**
 * Spaniabolig Theme — functions.php
 *
 * Custom WordPress theme for Spaniabolig SL.
 * Designed, coded and built by Espen T. Eik.
 *
 * © 2025 Espen T. Eik. All rights reserved.
 * Unauthorised copying, redistribution or resale of this theme
 * or any part of it is strictly prohibited.
 */
defined('ABSPATH') || exit;

require_once get_template_directory() . '/inc/importer.php';
require_once get_template_directory() . '/inc/meta-boxes.php';
require_once get_template_directory() . '/inc/agents.php';
require_once get_template_directory() . '/inc/import-featured.php';
require_once get_template_directory() . '/inc/sideload-images.php';
require_once get_template_directory() . '/inc/sync-featured-status.php';
require_once get_template_directory() . '/inc/property-submission.php';
require_once get_template_directory() . '/inc/onboarding.php';
require_once get_template_directory() . '/inc/seo.php';

/* ── Disable Gutenberg block editor for pages ── */
// All page content is managed via custom meta boxes — the block editor is not needed
// and just gets in the way. This gives a clean title → meta boxes layout.
add_filter('use_block_editor_for_post_type', function (bool $use, string $post_type): bool {
    return $post_type === 'page' ? false : $use;
}, 10, 2);

// Also remove the unused editor textarea from pages entirely
add_action('init', function () {
    remove_post_type_support('page', 'editor');
});

/* ── Image helpers (supports external URLs from XML import) ── */
function sb_get_image_url(int $post_id, string $size = 'large'): string {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, $size) ?: '';
    }
    return (string) get_post_meta($post_id, 'sb_thumb_url', true);
}

function sb_the_image(int $post_id, string $size = 'large', string $class = 'card-image'): void {
    $url = sb_get_image_url($post_id, $size);
    if ($url) {
        echo '<img src="' . esc_url($url) . '" alt="' . esc_attr(get_the_title($post_id)) . '" class="' . esc_attr($class) . '" loading="lazy">';
    }
}

/* ── Theme Setup ── */
function sb_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption']);
    add_theme_support('responsive-embeds');

    register_nav_menus([
        'primary' => __('Primary Menu', 'spaniabolig'),
        'footer'  => __('Footer Menu', 'spaniabolig'),
    ]);
}
add_action('after_setup_theme', 'sb_setup');

/* ── Enqueue ── */
function sb_enqueue() {
    wp_enqueue_style('sb-main', get_template_directory_uri() . '/assets/css/main.css', [], filemtime(get_template_directory() . '/assets/css/main.css'));

    // Leaflet must be registered before sb-main so it can be listed as a dependency
    $js_deps = [];
    if (is_singular('property')) {
        wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4');
        wp_enqueue_script('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true);
        $js_deps = ['leaflet'];
    }

    wp_enqueue_script('sb-main', get_template_directory_uri() . '/assets/js/main.js', $js_deps, filemtime(get_template_directory() . '/assets/js/main.js'), true);
    wp_localize_script('sb-main', 'sbData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('sb_search'),
    ]);

    // ── Add Property wizard ───────────────────────────────
    if (is_page_template('page-add-property.php')) {
        wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4');
        wp_enqueue_script('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true);
        wp_enqueue_script('sb-add-property', get_template_directory_uri() . '/assets/js/add-property.js', ['leaflet'], filemtime(get_template_directory() . '/assets/js/add-property.js'), true);
        wp_localize_script('sb-add-property', 'sbAddProp', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('sb_add_property'),
        ]);
    }
}
add_action('wp_enqueue_scripts', 'sb_enqueue');

/* ── One-time DB migration: fix double-encoded entities in all property content ── */
add_action('admin_init', function() {
    if (!isset($_GET['sb_fix_content']) || !current_user_can('manage_options')) return;
    if (get_option('sb_content_fixed_v2')) { wp_die('Already done.'); }
    global $wpdb;
    // Step 1: un-double-encode &amp;ENTITY; → &ENTITY; (handles &amp;nbsp;, &amp;#13;, etc.)
    $wpdb->query("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '&amp;#13;', '&#13;') WHERE post_type = 'property'");
    $wpdb->query("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '&amp;nbsp;', '&nbsp;') WHERE post_type = 'property'");
    $wpdb->query("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '&amp;amp;', '&amp;') WHERE post_type = 'property'");
    $wpdb->query("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '&amp;lt;', '&lt;') WHERE post_type = 'property'");
    $wpdb->query("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '&amp;gt;', '&gt;') WHERE post_type = 'property'");
    // Step 2: strip bare &#13; (carriage returns) — replace double with nothing, single with nothing
    $wpdb->query("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '&#13;', '') WHERE post_type = 'property'");
    // Step 3: remove &nbsp; spacer paragraphs
    $wpdb->query("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '&nbsp;', ' ') WHERE post_type = 'property'");
    // Flush post cache
    $ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'property'");
    foreach ($ids as $id) { clean_post_cache($id); }
    update_option('sb_content_fixed_v2', true);
    wp_die('Done! All ' . count($ids) . ' property descriptions cleaned.');
});

/* ── Fallback filter: fix any remaining double-encoded entities on output ── */
add_filter('the_content', function($content) {
    if (is_singular('property')) {
        $content = preg_replace('/&amp;((?:#\d+|#x[0-9a-fA-F]+|[a-zA-Z][a-zA-Z0-9]*);)/', '&$1', $content);
        $content = str_replace(['&#13;&#13;', '&#13;', "\r"], ['', '', ''], $content);
    }
    return $content;
}, 1);

/* ── Property CPT ── */
function sb_register_property_cpt() {
    register_post_type('property', [
        'labels' => [
            'name'               => __('Properties', 'spaniabolig'),
            'singular_name'      => __('Property', 'spaniabolig'),
            'add_new'            => __('Add Property', 'spaniabolig'),
            'add_new_item'       => __('Add New Property', 'spaniabolig'),
            'edit_item'          => __('Edit Property', 'spaniabolig'),
            'all_items'          => __('All Properties', 'spaniabolig'),
            'search_items'       => __('Search Properties', 'spaniabolig'),
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'properties'],
        'menu_icon'    => 'dashicons-admin-home',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'sb_register_property_cpt');

/* ── Property Taxonomies ── */
function sb_register_property_taxonomies() {
    // Property Type (apartment, villa, townhouse, etc.)
    register_taxonomy('property_type', 'property', [
        'labels'       => ['name' => __('Property Types', 'spaniabolig'), 'singular_name' => __('Property Type', 'spaniabolig')],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => ['slug' => 'property-type'],
        'show_in_rest' => true,
    ]);
    // Location
    register_taxonomy('property_location', 'property', [
        'labels'       => ['name' => __('Locations', 'spaniabolig'), 'singular_name' => __('Location', 'spaniabolig')],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => ['slug' => 'location'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'sb_register_property_taxonomies');

/* ── Property Meta Boxes ── */
function sb_add_property_meta_boxes() {
    add_meta_box('sb_property_details', __('Property Details', 'spaniabolig'), 'sb_property_meta_box_cb', 'property', 'normal', 'high');
}
add_action('add_meta_boxes', 'sb_add_property_meta_boxes');

function sb_property_meta_box_cb($post) {
    wp_nonce_field('sb_property_meta', 'sb_property_nonce');
    $fields = [
        'sb_price'     => ['label' => 'Price (€)', 'type' => 'text', 'placeholder' => 'e.g. 250000'],
        'sb_bedrooms'  => ['label' => 'Bedrooms', 'type' => 'number'],
        'sb_bathrooms' => ['label' => 'Bathrooms', 'type' => 'number'],
        'sb_size'      => ['label' => 'Size (m²)', 'type' => 'text'],
        'sb_status'    => ['label' => 'Status', 'type' => 'select', 'options' => ['for-sale' => 'For Sale', 'for-rent' => 'For Rent', 'sold' => 'Sold']],
        'sb_ref'       => ['label' => 'Reference #', 'type' => 'text'],
        'sb_city'      => ['label' => 'City/Area', 'type' => 'text', 'placeholder' => 'e.g. Ciudad Quesada'],
        'sb_lat'       => ['label' => 'Latitude', 'type' => 'text'],
        'sb_lng'       => ['label' => 'Longitude', 'type' => 'text'],
        'sb_build_type' => ['label' => 'Build Type', 'type' => 'select', 'options' => ['resale' => 'Resale', 'new_build' => 'New Build']],
        'sb_featured'  => ['label' => 'Featured Property', 'type' => 'checkbox'],
    ];
    echo '<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:8px 0">';
    foreach ($fields as $key => $field) {
        $val = get_post_meta($post->ID, $key, true);
        echo '<div>';
        echo '<label style="display:block;font-weight:600;margin-bottom:4px">' . esc_html($field['label']) . '</label>';
        if ($field['type'] === 'select') {
            echo '<select name="' . esc_attr($key) . '" style="width:100%;padding:6px">';
            echo '<option value="">-- Select --</option>';
            foreach ($field['options'] as $optval => $optlabel) {
                echo '<option value="' . esc_attr($optval) . '"' . selected($val, $optval, false) . '>' . esc_html($optlabel) . '</option>';
            }
            echo '</select>';
        } elseif ($field['type'] === 'checkbox') {
            echo '<input type="checkbox" name="' . esc_attr($key) . '" value="1"' . checked($val, '1', false) . '>';
            echo ' <span>Yes</span>';
            if ($key === 'sb_featured' && $val === '1') {
                $featured_date = get_post_meta($post->ID, 'sb_featured_date', true);
                $date_label = $featured_date ? date('M j, Y H:i', $featured_date) : 'Not set';
                echo '<div style="margin-top:8px">';
                echo '<label><input type="checkbox" name="sb_refeature" value="1"> <strong>Re-feature</strong> <span style="color:#646970;font-size:12px">(bump to top of featured list)</span></label>';
                echo '<div style="color:#646970;font-size:12px;margin-top:4px">Featured since: ' . esc_html($date_label) . '</div>';
                echo '</div>';
            }
        } else {
            $placeholder = isset($field['placeholder']) ? ' placeholder="' . esc_attr($field['placeholder']) . '"' : '';
            echo '<input type="' . esc_attr($field['type']) . '" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '"' . $placeholder . ' style="width:100%;padding:6px">';
        }
        echo '</div>';
    }
    echo '</div>';
}

function sb_save_property_meta($post_id) {
    if (!isset($_POST['sb_property_nonce']) || !wp_verify_nonce($_POST['sb_property_nonce'], 'sb_property_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = ['sb_price','sb_bedrooms','sb_bathrooms','sb_size','sb_status','sb_ref','sb_city','sb_lat','sb_lng','sb_build_type','sb_featured'];
    foreach ($fields as $key) {
        if ($key === 'sb_featured') {
            $was_featured = get_post_meta($post_id, 'sb_featured', true);
            $is_featured = isset($_POST[$key]) ? '1' : '0';
            update_post_meta($post_id, $key, $is_featured);
            // Set featured_date when first featured, or when re-featured
            if ($is_featured === '1' && ($was_featured !== '1' || !empty($_POST['sb_refeature']))) {
                update_post_meta($post_id, 'sb_featured_date', time());
                // Bump post date so it sorts first in date-based queries
                remove_action('save_post_property', 'sb_save_property_meta');
                wp_update_post(['ID' => $post_id, 'post_date' => current_time('mysql'), 'post_date_gmt' => current_time('mysql', true)]);
                add_action('save_post_property', 'sb_save_property_meta');
            }
            if ($is_featured === '0') {
                delete_post_meta($post_id, 'sb_featured_date');
            }
        } elseif (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }
}
add_action('save_post_property', 'sb_save_property_meta');

/* ── Property archive: enforce 12/page + optional build_type filter ── */
function sb_filter_property_archive($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('property')) {
        $query->set('posts_per_page', 12);
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');

        $meta_query = [];

        $build_type = isset($_GET['build_type']) ? sanitize_key($_GET['build_type']) : '';
        if ($build_type) {
            $meta_query[] = ['key' => 'sb_build_type', 'value' => $build_type, 'compare' => '='];
        }

        if (!empty($_GET['featured'])) {
            $meta_query[] = ['key' => 'sb_featured', 'value' => '1'];
        }

        if ($meta_query) {
            $query->set('meta_query', $meta_query);
        }

        // Sort featured properties first
        $query->set('sb_featured_first', true);
    }
}
/* Sort: featured first, sold last, then by date */
add_filter('posts_clauses', function($clauses, $query) {
    if ($query->get('sb_featured_first')) {
        global $wpdb;
        $clauses['join']    .= " LEFT JOIN {$wpdb->postmeta} AS sb_feat ON ({$wpdb->posts}.ID = sb_feat.post_id AND sb_feat.meta_key = 'sb_featured')";
        $clauses['join']    .= " LEFT JOIN {$wpdb->postmeta} AS sb_stat ON ({$wpdb->posts}.ID = sb_stat.post_id AND sb_stat.meta_key = 'sb_status')";
        $clauses['orderby']  = "CASE WHEN sb_stat.meta_value = 'sold' THEN 1 ELSE 0 END ASC, COALESCE(sb_feat.meta_value, '0') DESC, {$wpdb->posts}.post_date DESC";
    }
    return $clauses;
}, 10, 2);
add_action('pre_get_posts', 'sb_filter_property_archive');

/* ── AJAX Property Search ── */
function sb_ajax_search() {
    check_ajax_referer('sb_search', 'nonce');

    $args = [
        'post_type'      => 'property',
        'posts_per_page' => isset($_POST['per_page']) ? intval($_POST['per_page']) : 12,
        'paged'          => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
        'meta_query'     => [],
        'tax_query'      => [],
    ];

    if (!empty($_POST['property_type'])) {
        $args['tax_query'][] = ['taxonomy' => 'property_type', 'field' => 'slug', 'terms' => sanitize_text_field($_POST['property_type'])];
    }
    if (!empty($_POST['location'])) {
        $args['meta_query'][] = ['key' => 'sb_city', 'value' => sanitize_text_field($_POST['location']), 'compare' => '='];
    }
    if (!empty($_POST['min_price'])) {
        $args['meta_query'][] = ['key' => 'sb_price', 'value' => intval($_POST['min_price']), 'compare' => '>=', 'type' => 'NUMERIC'];
    }
    if (!empty($_POST['max_price'])) {
        $args['meta_query'][] = ['key' => 'sb_price', 'value' => intval($_POST['max_price']), 'compare' => '<=', 'type' => 'NUMERIC'];
    }
    if (!empty($_POST['bedrooms'])) {
        $args['meta_query'][] = ['key' => 'sb_bedrooms', 'value' => intval($_POST['bedrooms']), 'compare' => '>=', 'type' => 'NUMERIC'];
    }
    if (!empty($_POST['status'])) {
        $args['meta_query'][] = ['key' => 'sb_status', 'value' => sanitize_text_field($_POST['status']), 'compare' => '='];
    }
    if (!empty($_POST['build_type'])) {
        $args['meta_query'][] = ['key' => 'sb_build_type', 'value' => sanitize_key($_POST['build_type']), 'compare' => '='];
    }
    if (!empty($_POST['keyword'])) {
        $args['s'] = sanitize_text_field($_POST['keyword']);
    }
    if (!empty($_POST['sort'])) {
        if ($_POST['sort'] === 'price-asc') {
            $args['meta_key'] = 'sb_price';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
        } elseif ($_POST['sort'] === 'price-desc') {
            $args['meta_key'] = 'sb_price';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
        }
    }

    // Sort: featured first, sold last (unless sorting by price)
    $feat_first = empty($_POST['sort']) || $_POST['sort'] === 'date';
    if ($feat_first) {
        $sb_feat_clauses = function($clauses) {
            global $wpdb;
            $clauses['join']    .= " LEFT JOIN {$wpdb->postmeta} AS sb_feat ON ({$wpdb->posts}.ID = sb_feat.post_id AND sb_feat.meta_key = 'sb_featured')";
            $clauses['join']    .= " LEFT JOIN {$wpdb->postmeta} AS sb_stat ON ({$wpdb->posts}.ID = sb_stat.post_id AND sb_stat.meta_key = 'sb_status')";
            $clauses['orderby']  = "CASE WHEN sb_stat.meta_value = 'sold' THEN 1 ELSE 0 END ASC, COALESCE(sb_feat.meta_value, '0') DESC, {$wpdb->posts}.post_date DESC";
            return $clauses;
        };
        add_filter('posts_clauses', $sb_feat_clauses);
    }

    $query = new WP_Query($args);

    if ($feat_first) {
        remove_filter('posts_clauses', $sb_feat_clauses);
    }
    $results = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = [
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'image'     => sb_get_image_url(get_the_ID(), 'large'),
                'price'     => get_post_meta(get_the_ID(), 'sb_price', true),
                'bedrooms'  => get_post_meta(get_the_ID(), 'sb_bedrooms', true),
                'bathrooms' => get_post_meta(get_the_ID(), 'sb_bathrooms', true),
                'size'      => get_post_meta(get_the_ID(), 'sb_size', true),
                'status'    => get_post_meta(get_the_ID(), 'sb_status', true),
                'city'      => get_post_meta(get_the_ID(), 'sb_city', true),
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success(['properties' => $results, 'total' => $query->found_posts, 'pages' => $query->max_num_pages]);
}
add_action('wp_ajax_sb_search', 'sb_ajax_search');
add_action('wp_ajax_nopriv_sb_search', 'sb_ajax_search');

/* ── AJAX: Get Favourites by ID ── */
function sb_ajax_get_favorites() {
    check_ajax_referer('sb_search', 'nonce');

    $ids = isset($_POST['ids']) ? array_map('intval', (array) $_POST['ids']) : [];
    $ids = array_filter($ids);

    if (empty($ids)) {
        wp_send_json_success(['html' => '']);
    }

    $query = new WP_Query([
        'post_type'      => 'property',
        'post__in'       => $ids,
        'orderby'        => 'post__in',
        'posts_per_page' => count($ids),
        'post_status'    => 'publish',
    ]);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/property-card');
        }
        wp_reset_postdata();
    }
    $html = ob_get_clean();

    wp_send_json_success(['html' => $html]);
}
add_action('wp_ajax_sb_get_favorites', 'sb_ajax_get_favorites');
add_action('wp_ajax_nopriv_sb_get_favorites', 'sb_ajax_get_favorites');

/* ── AJAX: Property Dashboard Search ── */
function sb_ajax_dash_search() {
    check_ajax_referer('sb_search', 'nonce');
    if (!current_user_can('edit_posts')) wp_send_json_error('Unauthorized', 403);

    $args = [
        'post_type'      => 'property',
        'posts_per_page' => isset($_POST['per_page']) ? intval($_POST['per_page']) : 25,
        'paged'          => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
        'post_status'    => 'publish',
        'meta_query'     => [],
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    // Sort
    $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'date';
    switch ($sort) {
        case 'price-asc':  $args['meta_key'] = 'sb_price'; $args['orderby'] = 'meta_value_num'; $args['order'] = 'ASC'; break;
        case 'price-desc': $args['meta_key'] = 'sb_price'; $args['orderby'] = 'meta_value_num'; $args['order'] = 'DESC'; break;
        case 'title-asc':  $args['orderby'] = 'title'; $args['order'] = 'ASC'; break;
    }

    if (!empty($_POST['status']))     $args['meta_query'][] = ['key'=>'sb_status',     'value'=>sanitize_text_field($_POST['status']),   'compare'=>'='];
    if (!empty($_POST['build_type'])) $args['meta_query'][] = ['key'=>'sb_build_type', 'value'=>sanitize_key($_POST['build_type']),      'compare'=>'='];
    if (!empty($_POST['keyword']))    $args['s'] = sanitize_text_field($_POST['keyword']);

    $query = new WP_Query($args);

    $status_labels = ['for-sale'=>'For Sale','for-rent'=>'For Rent','sold'=>'Sold'];
    $status_colors = ['for-sale'=>'#001d3d','for-rent'=>'#0057b7','sold'=>'#6b7280'];

    ob_start();
    if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();
            $id        = get_the_ID();
            $price     = get_post_meta($id, 'sb_price', true);
            $beds      = get_post_meta($id, 'sb_bedrooms', true);
            $baths     = get_post_meta($id, 'sb_bathrooms', true);
            $city      = get_post_meta($id, 'sb_city', true);
            $ref       = get_post_meta($id, 'sb_ref', true);
            $status    = get_post_meta($id, 'sb_status', true);
            $status_k  = str_replace('_','-',(string)$status);
            $img       = sb_get_image_url($id);
            $add_prop_page = get_page_by_path('add-property');
            $edit_url  = $add_prop_page
                ? add_query_arg('property_id', $id, get_permalink($add_prop_page->ID))
                : home_url('/add-property/?property_id=' . $id);
            $view_url  = get_permalink($id);
            $date      = get_the_date('d M Y');
            $label     = $status_labels[$status_k] ?? ucwords(str_replace(['-','_'],' ',$status));
            $color     = $status_colors[$status_k] ?? '#6b7280';
            ?>
            <tr class="dash-row" data-id="<?php echo $id; ?>">
                <td class="dash-cell dash-cell--thumb">
                    <?php if ($img): ?>
                        <img src="<?php echo esc_url($img); ?>" alt="" class="dash-thumb">
                    <?php else: ?>
                        <div class="dash-thumb dash-thumb--empty"></div>
                    <?php endif; ?>
                </td>
                <td class="dash-cell dash-cell--title">
                    <a href="<?php echo esc_url($edit_url); ?>" class="dash-title"><?php the_title(); ?></a>
                    <?php if ($ref): ?><span class="dash-ref"><?php echo esc_html($ref); ?></span><?php endif; ?>
                </td>
                <td class="dash-cell dash-cell--price"><?php echo $price ? esc_html(sb_format_price($price)) : '—'; ?></td>
                <td class="dash-cell">
                    <span class="dash-badge" style="background:<?php echo esc_attr($color); ?>"><?php echo esc_html($label); ?></span>
                </td>
                <td class="dash-cell dash-cell--city"><?php echo $city ? esc_html($city) : '—'; ?></td>
                <td class="dash-cell dash-cell--meta">
                    <?php if ($beds):  ?><span class="dash-meta-item">🛏 <?php echo esc_html($beds); ?></span><?php endif; ?>
                    <?php if ($baths): ?><span class="dash-meta-item">🚿 <?php echo esc_html($baths); ?></span><?php endif; ?>
                </td>
                <td class="dash-cell dash-cell--date"><?php echo esc_html($date); ?></td>
                <td class="dash-cell dash-cell--actions">
                    <a href="<?php echo esc_url($edit_url); ?>" class="dash-action-btn dash-action-btn--edit">Edit</a>
                    <a href="<?php echo esc_url($view_url); ?>" class="dash-action-btn" target="_blank">View</a>
                </td>
            </tr>
            <?php
        endwhile;
        wp_reset_postdata();
    else:
        echo '<tr><td colspan="8" class="dash-empty">No properties found.</td></tr>';
    endif;
    $rows = ob_get_clean();

    wp_send_json_success(['rows' => $rows, 'total' => $query->found_posts, 'pages' => $query->max_num_pages]);
}
add_action('wp_ajax_sb_dash_search', 'sb_ajax_dash_search');

/* ── Admin Menu: Property Dashboard shortcut ── */
add_action('admin_menu', function() {
    add_menu_page(
        'Property Dashboard',
        'Property Dashboard',
        'edit_posts',
        'sb-property-dashboard',
        '__return_false',          // callback not used — redirect fires in admin_init
        'dashicons-admin-home',
        3
    );
});
// Redirect before any output so headers are not yet sent
add_action('admin_init', function() {
    if (!is_admin()) return;
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    if ($page === 'sb-property-dashboard') {
        $dashboard = get_page_by_path('dashboard') ?: get_page_by_path('property-dashboard');
        $url = $dashboard ? get_permalink($dashboard->ID) : home_url('/dashboard');
        wp_redirect($url);
        exit;
    }
});

/* ── Helper: Format Price ── */
function sb_format_price($price) {
    if (!$price) return '';
    return '€ ' . number_format((float)$price, 0, ',', ' ');
}

/* ── Contact Form Handler ── */
function sb_handle_contact_form() {
    if (!isset($_POST['sb_contact_nonce']) || !wp_verify_nonce($_POST['sb_contact_nonce'], 'sb_contact')) {
        wp_redirect(home_url('/contact/?error=1'));
        exit;
    }
    $name    = sanitize_text_field($_POST['contact_name'] ?? '');
    $email   = sanitize_email($_POST['contact_email'] ?? '');
    $phone   = sanitize_text_field($_POST['contact_phone'] ?? '');
    $subject = sanitize_text_field($_POST['contact_subject'] ?? 'General enquiry');
    $message = sanitize_textarea_field($_POST['contact_message'] ?? '');
    if (!$name || !$email || !$message) {
        wp_redirect(home_url('/contact/?error=1'));
        exit;
    }
    $to      = get_option('admin_email');
    $headers = ['Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>'];
    $body    = '<p><strong>Name:</strong> ' . esc_html($name) . '</p>'
             . '<p><strong>Email:</strong> ' . esc_html($email) . '</p>'
             . '<p><strong>Phone:</strong> ' . esc_html($phone) . '</p>'
             . '<p><strong>Subject:</strong> ' . esc_html($subject) . '</p>'
             . '<p><strong>Message:</strong><br>' . nl2br(esc_html($message)) . '</p>';
    wp_mail($to, 'New enquiry from ' . $name, $body, $headers);
    wp_redirect(home_url('/contact/?sent=1'));
    exit;
}
add_action('admin_post_sb_contact_form', 'sb_handle_contact_form');
add_action('admin_post_nopriv_sb_contact_form', 'sb_handle_contact_form');

/* ── Service Inquiry AJAX Handler ── */
function sb_ajax_service_inquiry(): void {
    if (!check_ajax_referer('sb_service_inquiry', 'svc_nonce', false)) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh and try again.'], 403);
    }

    $service = sanitize_text_field(wp_unslash($_POST['svc_service'] ?? ''));
    $name    = sanitize_text_field(wp_unslash($_POST['svc_name']    ?? ''));
    $email   = sanitize_email(wp_unslash($_POST['svc_email']        ?? ''));
    $phone   = sanitize_text_field(wp_unslash($_POST['svc_phone']   ?? ''));
    $city    = sanitize_text_field(wp_unslash($_POST['svc_city']    ?? ''));
    $consent = !empty($_POST['svc_consent']);

    if (!$name || !$email || !$service) {
        wp_send_json_error(['message' => 'Please fill in all required fields.'], 422);
    }
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Please enter a valid email address.'], 422);
    }
    if (!$consent) {
        wp_send_json_error(['message' => 'Please give your consent to submit this form.'], 422);
    }

    $to      = get_option('admin_email');
    $subject = 'New service enquiry from ' . $name;
    $headers = ['Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>'];
    $body    = '<p><strong>Service:</strong> ' . esc_html($service) . '</p>'
             . '<p><strong>Name:</strong> '    . esc_html($name)    . '</p>'
             . '<p><strong>Email:</strong> '   . esc_html($email)   . '</p>'
             . '<p><strong>Phone:</strong> '   . esc_html($phone)   . '</p>'
             . '<p><strong>City:</strong> '    . esc_html($city)    . '</p>';
    wp_mail($to, $subject, $body, $headers);

    wp_send_json_success(['message' => "Thanks, {$name}! We'll be in touch shortly."]);
}
add_action('wp_ajax_sb_service_inquiry',        'sb_ajax_service_inquiry');
add_action('wp_ajax_nopriv_sb_service_inquiry', 'sb_ajax_service_inquiry');

/* ── Bulk clean property titles — visit /wp-admin/?sb_clean_titles=1 ── */
add_action('admin_init', function () {
    if (!isset($_GET['sb_clean_titles']) || !current_user_can('manage_options')) return;

    $properties = get_posts([
        'post_type'      => 'property',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ]);

    $fixed = 0;
    foreach ($properties as $id) {
        $title = get_the_title($id);
        // Strip "(Ref: XXXXX)" patterns
        $clean = preg_replace('/\s*\(Ref:\s*[^\)]+\)/i', '', $title);
        $clean = trim($clean);
        if ($clean !== $title) {
            wp_update_post(['ID' => $id, 'post_title' => $clean]);
            $fixed++;
        }
    }

    wp_die(
        '<h2>Title cleanup done</h2><p>Fixed <strong>' . $fixed . '</strong> of <strong>' . count($properties) . '</strong> properties.</p>' .
        '<p><a href="' . admin_url('edit.php?post_type=property') . '">View all properties &rarr;</a></p>',
        'Title Cleanup',
        ['response' => 200]
    );
});

/* ── One-time page seeder — visit /wp-admin/?sb_seed_pages=1 ── */
add_action('admin_init', function () {
    if (!isset($_GET['sb_seed_pages']) || !current_user_can('manage_options')) return;

    $pages = [
        [
            'title'    => 'Dictionary',
            'slug'     => 'dictionary',
            'template' => 'page-dictionary.php',
        ],
        [
            'title'    => 'Privacy Policy',
            'slug'     => 'privacy-policy',
            'template' => 'page-privacy-policy.php',
        ],
        [
            'title'    => 'Terms of Use',
            'slug'     => 'terms-of-use',
            'template' => 'page-terms-of-use.php',
        ],
        [
            'title'    => 'Cookie Policy',
            'slug'     => 'cookie-policy',
            'template' => 'page-cookie-policy.php',
        ],
        [
            'title'    => 'Property Dashboard',
            'slug'     => 'property-dashboard',
            'template' => 'page-property-dashboard.php',
        ],
        [
            'title'    => 'About',
            'slug'     => 'about',
            'template' => 'page-about.php',
        ],
        [
            'title'    => 'How It Works',
            'slug'     => 'how-it-works',
            'template' => 'page-how-it-works.php',
        ],
        [
            'title'    => 'Services',
            'slug'     => 'services',
            'template' => 'page-services.php',
        ],
        [
            'title'    => 'Contact',
            'slug'     => 'contact',
            'template' => 'page-contact.php',
        ],
    ];

    $log = [];
    foreach ($pages as $p) {
        $existing = get_page_by_path($p['slug']);
        if ($existing) {
            // Page exists — make sure the template is set correctly
            $current_tpl = get_page_template_slug($existing->ID);
            if ($current_tpl !== $p['template']) {
                update_post_meta($existing->ID, '_wp_page_template', $p['template']);
                $log[] = "Updated template for: {$p['title']} (ID {$existing->ID})";
            } else {
                $log[] = "Already exists (template OK): {$p['title']} (ID {$existing->ID})";
            }
            continue;
        }

        $post_id = wp_insert_post([
            'post_title'   => $p['title'],
            'post_name'    => $p['slug'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1,
        ]);

        if (is_wp_error($post_id)) {
            $log[] = "Error creating {$p['title']}: " . $post_id->get_error_message();
        } else {
            update_post_meta($post_id, '_wp_page_template', $p['template']);
            $log[] = "Created: {$p['title']} (ID {$post_id}, template: {$p['template']})";
        }
    }

    // Flush rewrite rules so new page slugs work immediately
    flush_rewrite_rules();

    wp_die(
        '<h2>Page Seeder</h2><ul><li>' . implode('</li><li>', array_map('esc_html', $log)) . '</li></ul>'
        . '<p><a href="' . admin_url() . '">Back to dashboard</a></p>'
    );
});

/* ── Rewrite flush on activation ── */
function sb_flush_rewrite() {
    sb_register_property_cpt();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'sb_flush_rewrite');
