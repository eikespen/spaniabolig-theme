<?php
defined('ABSPATH') || exit;

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
    wp_enqueue_style('sb-main', get_template_directory_uri() . '/assets/css/main.css', [], '1.0.1');
    wp_enqueue_script('sb-main', get_template_directory_uri() . '/assets/js/main.js', [], '1.0.1', true);
    wp_localize_script('sb-main', 'sbData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('sb_search'),
    ]);
}
add_action('wp_enqueue_scripts', 'sb_enqueue');

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

    $fields = ['sb_price','sb_bedrooms','sb_bathrooms','sb_size','sb_status','sb_ref','sb_city','sb_lat','sb_lng','sb_featured'];
    foreach ($fields as $key) {
        if ($key === 'sb_featured') {
            update_post_meta($post_id, $key, isset($_POST[$key]) ? '1' : '0');
        } elseif (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }
}
add_action('save_post_property', 'sb_save_property_meta');

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
        $args['tax_query'][] = ['taxonomy' => 'property_location', 'field' => 'slug', 'terms' => sanitize_text_field($_POST['location'])];
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

    $query = new WP_Query($args);
    $results = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = [
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'image'     => get_the_post_thumbnail_url(get_the_ID(), 'large') ?: '',
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

/* ── Rewrite flush on activation ── */
function sb_flush_rewrite() {
    sb_register_property_cpt();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'sb_flush_rewrite');
