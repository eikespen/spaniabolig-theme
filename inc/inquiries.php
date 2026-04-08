<?php
/**
 * Inquiries: store every form submission as a `sb_inquiry` CPT
 * so the team can browse them in WP Admin → Inquiries.
 *
 * Email is still sent — this is an additional log + admin UI.
 */

/* ── Register the CPT ── */
add_action('init', function () {
    register_post_type('sb_inquiry', [
        'labels' => [
            'name'               => 'Inquiries',
            'singular_name'      => 'Inquiry',
            'menu_name'          => 'Inquiries',
            'all_items'          => 'All Inquiries',
            'edit_item'          => 'View Inquiry',
            'view_item'          => 'View Inquiry',
            'search_items'       => 'Search Inquiries',
            'not_found'          => 'No inquiries found',
            'not_found_in_trash' => 'No inquiries found in trash',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => false,
        'menu_position'       => 4,
        'menu_icon'           => 'dashicons-email-alt',
        'capability_type'     => 'post',
        'capabilities'        => [
            'create_posts' => 'do_not_allow', // only created via form submissions
        ],
        'map_meta_cap'        => true,
        'supports'            => ['title'],
        'has_archive'         => false,
        'rewrite'             => false,
        'exclude_from_search' => true,
    ]);
});

/* ── Helper: create an inquiry record ── */
function sb_create_inquiry($type, $data) {
    $name  = $data['name']  ?? 'Unknown';
    $email = $data['email'] ?? '';

    $title_parts = [ucfirst($type), '—', $name];
    if (!empty($data['property_title'])) {
        $title_parts[] = '— ' . $data['property_title'];
    } elseif (!empty($data['service'])) {
        $title_parts[] = '— ' . $data['service'];
    } elseif (!empty($data['subject'])) {
        $title_parts[] = '— ' . $data['subject'];
    }

    $post_id = wp_insert_post([
        'post_type'   => 'sb_inquiry',
        'post_status' => 'publish',
        'post_title'  => wp_strip_all_tags(implode(' ', $title_parts)),
    ], true);

    if (is_wp_error($post_id) || !$post_id) return 0;

    update_post_meta($post_id, '_sb_inq_type',  sanitize_key($type));
    update_post_meta($post_id, '_sb_inq_name',  sanitize_text_field($name));
    update_post_meta($post_id, '_sb_inq_email', sanitize_email($email));
    update_post_meta($post_id, '_sb_inq_phone', sanitize_text_field($data['phone'] ?? ''));

    if (!empty($data['property_id'])) {
        update_post_meta($post_id, '_sb_inq_property_id', intval($data['property_id']));
    }
    if (!empty($data['service'])) {
        update_post_meta($post_id, '_sb_inq_service', sanitize_text_field($data['service']));
    }
    if (!empty($data['city'])) {
        update_post_meta($post_id, '_sb_inq_city', sanitize_text_field($data['city']));
    }
    if (!empty($data['subject'])) {
        update_post_meta($post_id, '_sb_inq_subject', sanitize_text_field($data['subject']));
    }
    if (!empty($data['message'])) {
        update_post_meta($post_id, '_sb_inq_message', sanitize_textarea_field($data['message']));
    }
    update_post_meta($post_id, '_sb_inq_ip', sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''));
    update_post_meta($post_id, '_sb_inq_status', 'new');

    return $post_id;
}

/* ── Admin list columns ── */
add_filter('manage_sb_inquiry_posts_columns', function ($cols) {
    return [
        'cb'         => $cols['cb'] ?? '',
        'title'      => 'Inquiry',
        'inq_type'   => 'Type',
        'inq_email'  => 'Email',
        'inq_phone'  => 'Phone',
        'inq_prop'   => 'Property / Service',
        'inq_status' => 'Status',
        'date'       => 'Received',
    ];
});

add_action('manage_sb_inquiry_posts_custom_column', function ($col, $post_id) {
    switch ($col) {
        case 'inq_type':
            $type = get_post_meta($post_id, '_sb_inq_type', true);
            $colors = ['property' => '#2271b1', 'service' => '#00a32a', 'contact' => '#8c8f94'];
            $bg = $colors[$type] ?? '#8c8f94';
            echo '<span style="background:' . esc_attr($bg) . ';color:#fff;padding:3px 8px;border-radius:3px;font-size:11px;text-transform:uppercase;">' . esc_html($type ?: '—') . '</span>';
            break;
        case 'inq_email':
            $email = get_post_meta($post_id, '_sb_inq_email', true);
            echo $email ? '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>' : '—';
            break;
        case 'inq_phone':
            $phone = get_post_meta($post_id, '_sb_inq_phone', true);
            echo $phone ? '<a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>' : '—';
            break;
        case 'inq_prop':
            $prop_id = (int) get_post_meta($post_id, '_sb_inq_property_id', true);
            if ($prop_id) {
                echo '<a href="' . esc_url(get_edit_post_link($prop_id)) . '">' . esc_html(get_the_title($prop_id)) . '</a>';
            } else {
                echo esc_html(get_post_meta($post_id, '_sb_inq_service', true) ?: get_post_meta($post_id, '_sb_inq_subject', true) ?: '—');
            }
            break;
        case 'inq_status':
            $status = get_post_meta($post_id, '_sb_inq_status', true) ?: 'new';
            $colors = ['new' => '#d63638', 'read' => '#dba617', 'replied' => '#00a32a', 'archived' => '#8c8f94'];
            $bg = $colors[$status] ?? '#8c8f94';
            echo '<span style="background:' . esc_attr($bg) . ';color:#fff;padding:3px 8px;border-radius:3px;font-size:11px;text-transform:uppercase;">' . esc_html($status) . '</span>';
            break;
    }
}, 10, 2);

add_filter('manage_edit-sb_inquiry_sortable_columns', function ($cols) {
    $cols['inq_type']   = 'inq_type';
    $cols['inq_status'] = 'inq_status';
    return $cols;
});

/* ── Detail meta box on the edit screen ── */
add_action('add_meta_boxes', function () {
    add_meta_box('sb_inquiry_details', 'Inquiry Details', 'sb_render_inquiry_details', 'sb_inquiry', 'normal', 'high');
    add_meta_box('sb_inquiry_status',  'Status',          'sb_render_inquiry_status',  'sb_inquiry', 'side',   'high');
});

function sb_render_inquiry_details($post) {
    $type    = get_post_meta($post->ID, '_sb_inq_type', true);
    $name    = get_post_meta($post->ID, '_sb_inq_name', true);
    $email   = get_post_meta($post->ID, '_sb_inq_email', true);
    $phone   = get_post_meta($post->ID, '_sb_inq_phone', true);
    $message = get_post_meta($post->ID, '_sb_inq_message', true);
    $service = get_post_meta($post->ID, '_sb_inq_service', true);
    $city    = get_post_meta($post->ID, '_sb_inq_city', true);
    $subject = get_post_meta($post->ID, '_sb_inq_subject', true);
    $prop_id     = (int) get_post_meta($post->ID, '_sb_inq_property_id', true);
    $owner_id    = (int) get_post_meta($post->ID, '_sb_inq_owner_id', true);
    $owner_email = get_post_meta($post->ID, '_sb_inq_owner_email', true);
    $ip          = get_post_meta($post->ID, '_sb_inq_ip', true);

    $row = function ($label, $value) {
        if ($value === '' || $value === null) return;
        echo '<tr><th style="text-align:left;width:140px;padding:8px 0;vertical-align:top;">' . esc_html($label) . '</th><td style="padding:8px 0;">' . $value . '</td></tr>';
    };

    echo '<table style="width:100%;border-collapse:collapse;">';
    $row('Type',     esc_html(ucfirst($type)));
    $row('Name',     esc_html($name));
    $row('Email',    $email ? '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>' : '');
    $row('Phone',    $phone ? '<a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>' : '');
    if ($prop_id) {
        $row('Property', '<a href="' . esc_url(get_edit_post_link($prop_id)) . '">' . esc_html(get_the_title($prop_id)) . '</a> · <a href="' . esc_url(get_permalink($prop_id)) . '" target="_blank">view</a>');
    }
    if ($owner_id) {
        $owner_name = get_the_author_meta('display_name', $owner_id);
        $owner_str  = esc_html($owner_name);
        if ($owner_email) {
            $owner_str .= ' · <a href="mailto:' . esc_attr($owner_email) . '">' . esc_html($owner_email) . '</a>';
        }
        $owner_str .= ' <span style="color:#646970;">(CC\'d on the email)</span>';
        $row('Listing owner', $owner_str);
    }
    $row('Service',  esc_html($service));
    $row('City',     esc_html($city));
    $row('Subject',  esc_html($subject));
    $row('Message',  $message ? '<div style="background:#f6f7f7;padding:12px;border-left:3px solid #2271b1;white-space:pre-wrap;">' . esc_html($message) . '</div>' : '');
    $row('IP',       esc_html($ip));
    echo '</table>';

    if ($email) {
        $reply_subject = rawurlencode('Re: ' . $post->post_title);
        echo '<p style="margin-top:20px;"><a href="mailto:' . esc_attr($email) . '?subject=' . $reply_subject . '" class="button button-primary">Reply by Email</a></p>';
    }
}

function sb_render_inquiry_status($post) {
    $status  = get_post_meta($post->ID, '_sb_inq_status', true) ?: 'new';
    $options = ['new' => 'New', 'read' => 'Read', 'replied' => 'Replied', 'archived' => 'Archived'];
    wp_nonce_field('sb_inquiry_status', 'sb_inquiry_status_nonce');
    echo '<select name="sb_inq_status" style="width:100%;">';
    foreach ($options as $val => $label) {
        echo '<option value="' . esc_attr($val) . '"' . selected($status, $val, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
    echo '<p style="color:#646970;margin-top:8px;font-size:12px;">Click "Update" to save status.</p>';
}

add_action('save_post_sb_inquiry', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['sb_inquiry_status_nonce']) || !wp_verify_nonce($_POST['sb_inquiry_status_nonce'], 'sb_inquiry_status')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (isset($_POST['sb_inq_status'])) {
        update_post_meta($post_id, '_sb_inq_status', sanitize_key($_POST['sb_inq_status']));
    }
});

/* Auto-mark as "read" the first time an admin opens an inquiry */
add_action('load-post.php', function () {
    if (!isset($_GET['post'])) return;
    $post_id = intval($_GET['post']);
    if (get_post_type($post_id) !== 'sb_inquiry') return;
    $status = get_post_meta($post_id, '_sb_inq_status', true);
    if ($status === 'new' || $status === '') {
        update_post_meta($post_id, '_sb_inq_status', 'read');
    }
});

/* ── Scope WP Admin Inquiries list to current user (unless admin/editor) ── */
add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) return;
    if ($query->get('post_type') !== 'sb_inquiry') return;
    if (current_user_can('edit_others_posts')) return;

    $meta = (array) $query->get('meta_query');
    $meta[] = ['key' => '_sb_inq_owner_id', 'value' => get_current_user_id(), 'compare' => '='];
    $query->set('meta_query', $meta);
});

/* Block non-owners from opening an individual inquiry they don't own */
add_action('load-post.php', function () {
    if (!isset($_GET['post'])) return;
    $post_id = intval($_GET['post']);
    if (get_post_type($post_id) !== 'sb_inquiry') return;
    if (current_user_can('edit_others_posts')) return;
    $owner_id = (int) get_post_meta($post_id, '_sb_inq_owner_id', true);
    if ($owner_id !== get_current_user_id()) {
        wp_die('You do not have permission to view this inquiry.', 'Forbidden', ['response' => 403]);
    }
});

/* ── Show "New" inquiry count badge in admin menu ── */
add_action('admin_menu', function () {
    global $menu;
    $args = [
        'post_type'      => 'sb_inquiry',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'no_found_rows'  => true,
        'meta_query'     => [['key' => '_sb_inq_status', 'value' => 'new', 'compare' => '=']],
    ];
    if (!current_user_can('edit_others_posts')) {
        $args['meta_query'] = [
            'relation' => 'AND',
            ['key' => '_sb_inq_status',   'value' => 'new',                'compare' => '='],
            ['key' => '_sb_inq_owner_id', 'value' => get_current_user_id(), 'compare' => '='],
        ];
    }
    $count = (int) (new WP_Query($args))->found_posts;

    if (!$count || !is_array($menu)) return;

    foreach ($menu as $i => $item) {
        if (isset($item[2]) && $item[2] === 'edit.php?post_type=sb_inquiry') {
            $menu[$i][0] .= ' <span class="awaiting-mod count-' . $count . '"><span class="pending-count">' . $count . '</span></span>';
            break;
        }
    }
}, 999);
