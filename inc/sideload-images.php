<?php
/**
 * Sideload remote images for featured/exclusive properties into local media library.
 * Trigger: /wp-admin/?sb_sideload_images=1  (admin only)
 *
 * Processes up to $batch_size properties per run to avoid timeouts.
 * Re-run the URL until you see "All done!" — it skips already-processed properties.
 */

add_action('admin_init', function () {
    if (!isset($_GET['sb_sideload_images']) || !current_user_can('manage_options')) return;

    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $log        = [];

    /* Use ?sb_sideload_images=all to process ALL properties (not just featured) */
    $all_mode = ($_GET['sb_sideload_images'] === 'all');

    $meta_query = [
        'relation' => 'AND',
        ['key' => 'sb_image_urls', 'compare' => 'EXISTS'],
        ['key' => 'sb_images_sideloaded', 'compare' => 'NOT EXISTS'],
    ];

    if (!$all_mode) {
        $meta_query[] = ['key' => 'sb_featured', 'value' => '1'];
    }

    /* ── First: bulk-skip all properties whose images are NOT from the old site ── */
    $skip_candidates = get_posts([
        'post_type'      => 'property',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => $meta_query,
    ]);

    $skipped = 0;
    foreach ($skip_candidates as $skip_id) {
        $urls = get_post_meta($skip_id, 'sb_image_urls', true);
        if (!is_array($urls)) $urls = [];
        $from_old = false;
        foreach ($urls as $u) {
            if (strpos($u, 'spaniabolig-old.holthe.com') !== false) { $from_old = true; break; }
        }
        if (!$from_old) {
            update_post_meta($skip_id, 'sb_images_sideloaded', '1');
            $skipped++;
        }
    }
    if ($skipped > 0) {
        $log[] = "Bulk-skipped {$skipped} properties (images not from old site)";
        $log[] = str_repeat('─', 60);
    }

    /* ── Now find properties that actually need sideloading (old site images) ── */
    $batch_size = 2; // keep low — these actually download images
    $props = get_posts([
        'post_type'      => 'property',
        'post_status'    => 'publish',
        'posts_per_page' => $batch_size,
        'meta_query'     => $meta_query,
    ]);

    if (empty($props)) {
        $scope = $all_mode ? 'All properties' : 'All featured properties';
        $log[] = '';
        $log[] = 'All done! ' . $scope . ' have local images.';
        echo '<pre style="font-family:monospace;padding:20px;background:#d4edda;white-space:pre-wrap">' . implode("\n", $log) . '</pre>';
        exit;
    }

    $log[] = 'Sideloading ' . count($props) . ' properties with old site images...';
    $log[] = str_repeat('─', 60);

    foreach ($props as $post) {
        $post_id    = $post->ID;
        $title      = $post->post_title;
        $thumb_url  = get_post_meta($post_id, 'sb_thumb_url', true);
        $image_urls = get_post_meta($post_id, 'sb_image_urls', true);
        if (!is_array($image_urls)) $image_urls = [];

        // At this point, only properties with old site images remain

        $local_ids   = [];
        $local_urls  = [];
        $errors      = [];

        /* ── Sideload each gallery image ── */
        foreach ($image_urls as $url) {
            if (empty($url)) continue;

            // Check if already sideloaded (avoid duplicates on re-run)
            $existing = get_posts([
                'post_type'  => 'attachment',
                'meta_key'   => '_sb_source_url',
                'meta_value' => $url,
                'posts_per_page' => 1,
                'fields'     => 'ids',
            ]);

            if (!empty($existing)) {
                $att_id      = $existing[0];
                $local_ids[] = $att_id;
                $local_urls[] = wp_get_attachment_url($att_id);
                continue;
            }

            $att_id = media_sideload_image($url, $post_id, null, 'id');
            if (is_wp_error($att_id)) {
                $errors[] = 'IMG ERROR: ' . $att_id->get_error_message() . ' — ' . $url;
                continue;
            }
            update_post_meta($att_id, '_sb_source_url', $url);
            $local_ids[]  = $att_id;
            $local_urls[] = wp_get_attachment_url($att_id);
        }

        /* ── Set featured image (thumbnail) ── */
        $thumb_set = false;
        if ($thumb_url) {
            // Find attachment by source URL
            $thumb_att = null;
            foreach ($local_ids as $i => $att_id) {
                if (($image_urls[$i] ?? '') === $thumb_url || get_post_meta($att_id, '_sb_source_url', true) === $thumb_url) {
                    $thumb_att = $att_id;
                    break;
                }
            }
            // If thumb_url wasn't in gallery list, sideload it separately
            if (!$thumb_att) {
                $existing_thumb = get_posts([
                    'post_type'  => 'attachment',
                    'meta_key'   => '_sb_source_url',
                    'meta_value' => $thumb_url,
                    'posts_per_page' => 1,
                    'fields'     => 'ids',
                ]);
                if (!empty($existing_thumb)) {
                    $thumb_att = $existing_thumb[0];
                } else {
                    $thumb_att = media_sideload_image($thumb_url, $post_id, null, 'id');
                    if (!is_wp_error($thumb_att)) {
                        update_post_meta($thumb_att, '_sb_source_url', $thumb_url);
                    } else {
                        $thumb_att = null;
                        $errors[] = 'THUMB ERROR: ' . $thumb_att->get_error_message();
                    }
                }
            }

            if ($thumb_att && !is_wp_error($thumb_att)) {
                set_post_thumbnail($post_id, $thumb_att);
                $thumb_set = true;
            }
        }

        /* If no dedicated thumb was set, use first gallery image */
        if (!$thumb_set && !empty($local_ids)) {
            set_post_thumbnail($post_id, $local_ids[0]);
        }

        /* ── Update meta to local URLs ── */
        if (!empty($local_urls)) {
            update_post_meta($post_id, 'sb_image_urls', $local_urls);
        }
        if (!empty($local_ids[0])) {
            update_post_meta($post_id, 'sb_thumb_url', wp_get_attachment_url($local_ids[0]));
        }

        /* Mark as done so it's skipped next run */
        update_post_meta($post_id, 'sb_images_sideloaded', '1');

        $log[] = "✓ [{$post_id}] {$title}";
        $log[] = "  → " . count($local_ids) . " images sideloaded, thumbnail set: " . ($thumb_set ? 'yes' : 'used first gallery img');
        if ($errors) {
            foreach ($errors as $e) $log[] = "  ⚠ {$e}";
        }
    }

    /* Count remaining */
    $rem_query = [
        'relation' => 'AND',
        ['key' => 'sb_image_urls', 'compare' => 'EXISTS'],
        ['key' => 'sb_images_sideloaded', 'compare' => 'NOT EXISTS'],
    ];
    if (!$all_mode) {
        $rem_query[] = ['key' => 'sb_featured', 'value' => '1'];
    }
    $remaining = get_posts([
        'post_type'      => 'property',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => $rem_query,
    ]);

    $rerun_url = $all_mode ? '?sb_sideload_images=all' : '?sb_sideload_images=1';
    $log[] = str_repeat('─', 60);
    if (count($remaining) > 0) {
        $log[] = count($remaining) . ' properties still need processing — <a href="' . $rerun_url . '">run again</a>';
    } else {
        $log[] = '🎉 All properties processed!';
    }

    $bg = count($remaining) > 0 ? '#fff3cd' : '#d4edda';
    echo '<pre style="font-family:monospace;padding:20px;background:' . $bg . ';white-space:pre-wrap">';
    echo '<strong>Image Sideload — Batch Complete</strong>' . "\n\n";
    echo implode("\n", $log);
    echo '</pre>';
    exit;
});
