<?php
/**
 * Agent management:
 *  - Custom post type "sb_agent"
 *  - Meta fields: phone, whatsapp, email, job title
 *  - Featured image = agent photo
 *  - "Assigned Agent" meta box on property edit screen
 *  - Helper: sb_get_property_agent( $post_id ) → array of agent data
 */
defined('ABSPATH') || exit;

/* ── 1. Register CPT ── */
add_action('init', function () {
    register_post_type('sb_agent', [
        'label'               => 'Agents',
        'labels'              => [
            'name'               => 'Agents',
            'singular_name'      => 'Agent',
            'add_new'            => 'Add New Agent',
            'add_new_item'       => 'Add New Agent',
            'edit_item'          => 'Edit Agent',
            'new_item'           => 'New Agent',
            'view_item'          => 'View Agent',
            'search_items'       => 'Search Agents',
            'not_found'          => 'No agents found',
            'not_found_in_trash' => 'No agents in trash',
            'menu_name'          => 'Agents',
        ],
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 26,
        'menu_icon'           => 'dashicons-businessperson',
        'supports'            => ['title', 'thumbnail'],
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => false,
    ]);
});

/* ── 2. Agent meta box (phone, whatsapp, email, title) ── */
add_action('add_meta_boxes', function () {
    add_meta_box(
        'sb_agent_details',
        'Agent Details',
        'sb_agent_meta_box',
        'sb_agent',
        'normal',
        'high'
    );
    // Also add "Assigned Agent" box to property
    add_meta_box(
        'sb_property_agent',
        'Assigned Agent',
        'sb_property_agent_box',
        'property',
        'side',
        'default'
    );
});

function sb_agent_meta_box($post) {
    wp_nonce_field('sb_agent_save', 'sb_agent_nonce');
    $fields = [
        'sb_agent_title'    => ['Job Title',        'text',  'e.g. Property Consultant'],
        'sb_agent_phone'    => ['Phone',             'text',  'e.g. +47 47 20 24 14'],
        'sb_agent_whatsapp' => ['WhatsApp Number',   'text',  'Digits only, e.g. 4747202414'],
        'sb_agent_email'    => ['Email',             'email', 'e.g. name@spaniabolig.no'],
    ];
    echo '<table class="form-table" style="width:100%">';
    foreach ($fields as $key => [$label, $type, $placeholder]) {
        $val = get_post_meta($post->ID, $key, true);
        echo '<tr><th style="width:140px;padding:8px 10px;font-size:13px">' . esc_html($label) . '</th>';
        echo '<td style="padding:8px 10px"><input type="' . esc_attr($type) . '" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '" placeholder="' . esc_attr($placeholder) . '" style="width:100%;max-width:400px"></td></tr>';
    }
    echo '</table>';
    echo '<p style="margin-top:12px;color:#646970;font-size:13px">Set the <strong>Featured Image</strong> (top-right panel) as the agent\'s profile photo.</p>';
}

add_action('save_post_sb_agent', function ($post_id) {
    if (!isset($_POST['sb_agent_nonce']) || !wp_verify_nonce($_POST['sb_agent_nonce'], 'sb_agent_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    foreach (['sb_agent_title', 'sb_agent_phone', 'sb_agent_whatsapp', 'sb_agent_email'] as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }
});

/* ── 3. "Assigned Agent" meta box on property ── */
function sb_property_agent_box($post) {
    wp_nonce_field('sb_prop_agent_save', 'sb_prop_agent_nonce');
    $assigned = (int) get_post_meta($post->ID, 'sb_agent_id', true);
    $agents   = get_posts(['post_type' => 'sb_agent', 'posts_per_page' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC']);

    echo '<select name="sb_agent_id" style="width:100%">';
    echo '<option value="">— Default (first agent) —</option>';
    foreach ($agents as $a) {
        echo '<option value="' . $a->ID . '"' . selected($assigned, $a->ID, false) . '>' . esc_html($a->post_title) . '</option>';
    }
    echo '</select>';
    echo '<p style="margin-top:8px;font-size:12px;color:#646970">If left blank, the first agent is used.</p>';
}

add_action('save_post_property', function ($post_id) {
    if (!isset($_POST['sb_prop_agent_nonce']) || !wp_verify_nonce($_POST['sb_prop_agent_nonce'], 'sb_prop_agent_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $agent_id = isset($_POST['sb_agent_id']) ? (int) $_POST['sb_agent_id'] : 0;
    update_post_meta($post_id, 'sb_agent_id', $agent_id);
});

/* ── 4. Helper: get agent data for a property ── */
function sb_get_property_agent(int $post_id): array {
    $agent_id = (int) get_post_meta($post_id, 'sb_agent_id', true);

    // Fall back to first published agent
    if (!$agent_id) {
        $first = get_posts(['post_type' => 'sb_agent', 'posts_per_page' => 1, 'post_status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC', 'fields' => 'ids']);
        $agent_id = $first[0] ?? 0;
    }

    if (!$agent_id) {
        return [
            'name'      => 'Spaniabolig',
            'title'     => 'Real Estate',
            'phone'     => '+47 47 20 24 14',
            'whatsapp'  => '4747202414',
            'email'     => '',
            'photo_url' => '',
        ];
    }

    $phone    = get_post_meta($agent_id, 'sb_agent_phone', true);
    $wa       = get_post_meta($agent_id, 'sb_agent_whatsapp', true);
    $email    = get_post_meta($agent_id, 'sb_agent_email', true);
    $title    = get_post_meta($agent_id, 'sb_agent_title', true);
    $photo    = get_the_post_thumbnail_url($agent_id, 'thumbnail') ?: '';
    // WhatsApp: strip non-digits if user entered formatted number
    $wa_clean = preg_replace('/[^0-9]/', '', $wa ?: preg_replace('/[^0-9]/', '', $phone));

    return [
        'id'        => $agent_id,
        'name'      => get_the_title($agent_id),
        'title'     => $title ?: 'Property Consultant',
        'phone'     => $phone,
        'whatsapp'  => $wa_clean,
        'email'     => $email,
        'photo_url' => $photo,
    ];
}
