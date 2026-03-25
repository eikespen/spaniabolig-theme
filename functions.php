<?php
defined('ABSPATH') || exit;

require_once get_template_directory() . '/inc/importer.php';
require_once get_template_directory() . '/inc/meta-boxes.php';
require_once get_template_directory() . '/inc/agents.php';
require_once get_template_directory() . '/inc/import-featured.php';
require_once get_template_directory() . '/inc/sideload-images.php';
require_once get_template_directory() . '/inc/sync-featured-status.php';
require_once get_template_directory() . '/inc/property-submission.php';

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
            update_post_meta($post_id, $key, isset($_POST[$key]) ? '1' : '0');
        } elseif (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }
}
add_action('save_post_property', 'sb_save_property_meta');

/* ── Filter archive by build_type URL param ── */
function sb_filter_archive_by_build_type($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('property')) {
        $build_type = isset($_GET['build_type']) ? sanitize_key($_GET['build_type']) : '';
        if ($build_type) {
            $query->set('meta_query', [[
                'key'     => 'sb_build_type',
                'value'   => $build_type,
                'compare' => '=',
            ]]);
        }
    }
}
add_action('pre_get_posts', 'sb_filter_archive_by_build_type');

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

    $query = new WP_Query($args);
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

/* ── Rewrite flush on activation ── */
function sb_flush_rewrite() {
    sb_register_property_cpt();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'sb_flush_rewrite');
