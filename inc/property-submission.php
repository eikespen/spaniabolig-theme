<?php
/**
 * AJAX handlers for the Add Property frontend form.
 *
 * Actions registered:
 *   wp_ajax_sb_submit_property  — publish a new property post
 *   wp_ajax_sb_upload_image     — upload a single image and return attachment data
 */

defined('ABSPATH') || exit;

/* ─────────────────────────────────────────────────────────────
   Helper: verify nonce + capability for all property actions
───────────────────────────────────────────────────────────── */
function sb_verify_property_request(): void {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'You must be logged in to perform this action.'], 401);
    }

    if (!check_ajax_referer('sb_add_property', 'nonce', false)) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh and try again.'], 403);
    }

    if (!current_user_can('edit_posts')) {
        wp_send_json_error(['message' => 'You do not have permission to add properties.'], 403);
    }
}

/* ─────────────────────────────────────────────────────────────
   Handler: sb_submit_property
───────────────────────────────────────────────────────────── */
function sb_ajax_submit_property(): void {
    sb_verify_property_request();

    // ── Sanitise title (required) ──────────────────────────
    $title = sanitize_text_field(wp_unslash($_POST['ap_title'] ?? ''));
    if (empty($title)) {
        wp_send_json_error(['message' => 'Property title is required.', 'field' => 'ap_title'], 422);
    }

    // ── Status (required) ─────────────────────────────────
    $allowed_statuses = ['for-sale', 'for-rent', 'sold'];
    $status = sanitize_key(wp_unslash($_POST['ap_status'] ?? ''));
    if (!in_array($status, $allowed_statuses, true)) {
        wp_send_json_error(['message' => 'Please select a listing status.', 'field' => 'ap_status'], 422);
    }

    // ── Build type (required) ─────────────────────────────
    $allowed_build_types = ['resale', 'new_build'];
    $build_type = sanitize_key(wp_unslash($_POST['ap_build_type'] ?? ''));
    if (!in_array($build_type, $allowed_build_types, true)) {
        wp_send_json_error(['message' => 'Please select a build type.', 'field' => 'ap_build_type'], 422);
    }

    // ── Price (required) ──────────────────────────────────
    $price_raw = wp_unslash($_POST['ap_price'] ?? '');
    if ($price_raw === '' || !is_numeric($price_raw) || (float)$price_raw < 0) {
        wp_send_json_error(['message' => 'A valid price is required.', 'field' => 'ap_price'], 422);
    }
    $price = (string) abs((float) $price_raw);

    // ── Other text fields ─────────────────────────────────
    $description = sanitize_textarea_field(wp_unslash($_POST['ap_description'] ?? ''));
    $bedrooms    = absint($_POST['ap_bedrooms']  ?? 0);
    $bathrooms   = absint($_POST['ap_bathrooms'] ?? 0);
    $size        = sanitize_text_field(wp_unslash($_POST['ap_size']    ?? ''));
    $ref         = sanitize_text_field(wp_unslash($_POST['ap_ref']     ?? ''));
    $city        = sanitize_text_field(wp_unslash($_POST['ap_city']    ?? ''));
    $address     = sanitize_text_field(wp_unslash($_POST['ap_address'] ?? ''));
    $lat         = sanitize_text_field(wp_unslash($_POST['ap_lat']     ?? ''));
    $lng         = sanitize_text_field(wp_unslash($_POST['ap_lng']     ?? ''));
    $featured    = (isset($_POST['ap_featured']) && $_POST['ap_featured'] === '1') ? '1' : '0';

    // Validate lat/lng if provided
    if ($lat !== '' && (!is_numeric($lat) || (float)$lat < -90  || (float)$lat > 90)) {
        $lat = '';
    }
    if ($lng !== '' && (!is_numeric($lng) || (float)$lng < -180 || (float)$lng > 180)) {
        $lng = '';
    }

    // ── Attachment IDs ────────────────────────────────────
    $raw_ids = isset($_POST['ap_image_ids']) ? (array) $_POST['ap_image_ids'] : [];
    $image_ids = array_filter(array_map('absint', $raw_ids));
    $image_ids = array_values($image_ids); // re-index

    // ── Insert post ───────────────────────────────────────
    $post_data = [
        'post_title'   => $title,
        'post_content' => $description,
        'post_type'    => 'property',
        'post_status'  => 'publish',
        'post_author'  => get_current_user_id(),
    ];

    $post_id = wp_insert_post($post_data, true);

    if (is_wp_error($post_id)) {
        wp_send_json_error(['message' => 'Could not create property: ' . $post_id->get_error_message()], 500);
    }

    // ── Save meta ─────────────────────────────────────────
    $meta_map = [
        'sb_price'      => $price,
        'sb_bedrooms'   => (string) $bedrooms,
        'sb_bathrooms'  => (string) $bathrooms,
        'sb_size'       => $size,
        'sb_ref'        => $ref,
        'sb_city'       => $city,
        'sb_address'    => $address,
        'sb_lat'        => $lat,
        'sb_lng'        => $lng,
        'sb_status'     => $status,
        'sb_build_type' => $build_type,
        'sb_featured'   => $featured,
    ];

    foreach ($meta_map as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }

    // ── Handle images ─────────────────────────────────────
    if (!empty($image_ids)) {
        // Set featured image (first in array)
        set_post_thumbnail($post_id, $image_ids[0]);

        // Attach all images to this post (update parent)
        foreach ($image_ids as $att_id) {
            wp_update_post([
                'ID'          => $att_id,
                'post_parent' => $post_id,
            ]);
        }

        // Store array of full-size URLs for gallery use
        $image_urls = array_map('wp_get_attachment_url', $image_ids);
        update_post_meta($post_id, 'sb_image_ids',  $image_ids);
        update_post_meta($post_id, 'sb_image_urls', array_filter($image_urls));
    }

    wp_send_json_success([
        'id'  => $post_id,
        'url' => get_permalink($post_id),
    ]);
}
add_action('wp_ajax_sb_submit_property', 'sb_ajax_submit_property');


/* ─────────────────────────────────────────────────────────────
   Handler: sb_upload_image
   Uploads a single image via wp_handle_upload and returns the
   attachment ID + URLs.
───────────────────────────────────────────────────────────── */
function sb_ajax_upload_image(): void {
    sb_verify_property_request();

    if (empty($_FILES['ap_image'])) {
        wp_send_json_error(['message' => 'No file received.'], 400);
    }

    // Load WP upload helpers if needed
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $file = $_FILES['ap_image'];

    // Validate MIME type
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed_mime_types, true)) {
        wp_send_json_error(['message' => 'Invalid file type. Only JPEG, PNG, WebP and GIF are allowed.'], 415);
    }

    // Max 10 MB
    if ($file['size'] > 10 * MB_IN_BYTES) {
        wp_send_json_error(['message' => 'File exceeds the 10 MB size limit.'], 413);
    }

    $overrides = [
        'test_form' => false,
        'test_size' => true,
        'test_type' => true,
        'mimes'     => array_fill_keys($allowed_mime_types, true),
    ];

    $uploaded = wp_handle_upload($file, $overrides);

    if (isset($uploaded['error'])) {
        wp_send_json_error(['message' => $uploaded['error']], 500);
    }

    // Create attachment post
    $attachment = [
        'post_mime_type' => $uploaded['type'],
        'post_title'     => sanitize_file_name(pathinfo($uploaded['file'], PATHINFO_FILENAME)),
        'post_content'   => '',
        'post_status'    => 'inherit',
        'post_author'    => get_current_user_id(),
    ];

    $att_id = wp_insert_attachment($attachment, $uploaded['file'], 0, true);

    if (is_wp_error($att_id)) {
        wp_send_json_error(['message' => 'Could not save attachment: ' . $att_id->get_error_message()], 500);
    }

    // Generate metadata (thumbnails, srcsets, etc.)
    $metadata = wp_generate_attachment_metadata($att_id, $uploaded['file']);
    wp_update_attachment_metadata($att_id, $metadata);

    wp_send_json_success([
        'id'    => $att_id,
        'url'   => wp_get_attachment_url($att_id),
        'thumb' => wp_get_attachment_image_url($att_id, 'thumbnail'),
    ]);
}
add_action('wp_ajax_sb_upload_image', 'sb_ajax_upload_image');
