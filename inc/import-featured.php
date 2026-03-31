<?php
/**
 * One-time import of featured/exclusive listings from spaniabolig.no
 * Trigger: /wp-admin/?sb_import_featured=1  (admin only, runs once)
 */

add_action('admin_init', function () {
    if (!isset($_GET['sb_import_featured']) || !current_user_can('manage_options')) return;
    if (get_option('sb_featured_imported_v1')) {
        wp_die('Already imported. Delete the option <code>sb_featured_imported_v1</code> to re-run.');
    }

    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $source = 'https://spaniabolig.no/wp-json/wp/v2';
    $log    = [];

    /* ── 1. Fetch all properties (paginated) ── */
    $all   = [];
    $page  = 1;
    $pages = 1;
    while ($page <= $pages) {
        $resp = wp_remote_get("{$source}/properties?per_page=100&page={$page}", ['timeout' => 30]);
        if (is_wp_error($resp)) break;
        $pages = (int) wp_remote_retrieve_header($resp, 'x-wp-totalpages') ?: 1;
        $data  = json_decode(wp_remote_retrieve_body($resp), true);
        if (!is_array($data) || empty($data)) break;
        $all = array_merge($all, $data);
        $page++;
    }

    /* ── 2. Filter featured ── */
    $featured = array_filter($all, fn($p) => ($p['property_meta']['fave_featured'][0] ?? '0') === '1');
    $log[]    = 'Found ' . count($featured) . ' featured properties out of ' . count($all) . ' total.';

    /* ── 3. Resolve taxonomy terms ── */
    $city_map = [];
    $city_resp = wp_remote_get("{$source}/property_city?per_page=100", ['timeout' => 15]);
    if (!is_wp_error($city_resp)) {
        foreach (json_decode(wp_remote_retrieve_body($city_resp), true) ?: [] as $t) {
            $city_map[$t['id']] = $t['name'];
        }
    }

    /* ── 4. Create posts ── */
    foreach ($featured as $prop) {
        $meta    = $prop['property_meta'] ?? [];
        $title   = html_entity_decode($prop['title']['rendered'] ?? '', ENT_QUOTES);
        $content = wp_strip_all_tags($prop['content']['rendered'] ?? '');
        $slug    = $prop['slug'] ?? sanitize_title($title);

        // Skip if already exists
        if (get_page_by_path($slug, OBJECT, 'property')) {
            $log[] = "SKIP (exists): {$title}";
            continue;
        }

        $price     = $meta['fave_property_price'][0] ?? '';
        $bedrooms  = $meta['fave_property_bedrooms'][0] ?? '';
        $bathrooms = $meta['fave_property_bathrooms'][0] ?? '';
        $size      = str_replace(',', '.', $meta['fave_property_size'][0] ?? '');
        $lat       = $meta['houzez_geolocation_lat'][0] ?? '';
        $lng       = $meta['houzez_geolocation_long'][0] ?? '';
        $ref       = $meta['fave_property_id'][0] ?? 'EX-' . ($prop['id'] ?? '');

        // City from taxonomy
        $city_ids = (array) ($prop['property_city'] ?? []);
        $city     = implode(', ', array_filter(array_map(fn($id) => $city_map[$id] ?? '', $city_ids)));
        if (!$city) $city = 'Ciudad Quesada';

        // Strip price of spaces (e.g. "384 000" → "384000")
        $price = preg_replace('/\s+/', '', $price);

        /* Create the post */
        $post_id = wp_insert_post([
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'property',
        ]);

        if (is_wp_error($post_id)) {
            $log[] = "ERROR creating: {$title} – " . $post_id->get_error_message();
            continue;
        }

        /* Save meta */
        $metas = [
            'sb_price'      => $price,
            'sb_bedrooms'   => preg_replace('/[^0-9]/', '', explode('/', $bedrooms)[0]),
            'sb_bathrooms'  => preg_replace('/[^0-9]/', '', explode('/', $bathrooms)[0]),
            'sb_size'       => $size,
            'sb_city'       => $city,
            'sb_ref'        => $ref,
            'sb_lat'        => $lat,
            'sb_lng'        => $lng,
            'sb_status'     => 'for-sale',
            'sb_build_type' => 'resale',
            'sb_featured'   => '1',
        ];
        foreach ($metas as $key => $val) {
            update_post_meta($post_id, $key, $val);
        }

        /* Sideload images */
        $image_ids  = array_filter((array) ($meta['fave_property_images'] ?? []));
        $image_urls = [];
        $thumb_url  = '';

        // Resolve featured media first for thumb
        $thumb_media_id = $prop['featured_media'] ?? 0;
        if ($thumb_media_id) {
            $tm = wp_remote_get("{$source}/media/{$thumb_media_id}?_fields=source_url", ['timeout' => 10]);
            if (!is_wp_error($tm)) {
                $td = json_decode(wp_remote_retrieve_body($tm), true);
                $thumb_url = $td['source_url'] ?? '';
            }
        }

        // Sideload up to 8 gallery images
        foreach (array_slice($image_ids, 0, 8) as $img_id) {
            $mr = wp_remote_get("{$source}/media/{$img_id}?_fields=source_url", ['timeout' => 10]);
            if (!is_wp_error($mr)) {
                $md = json_decode(wp_remote_retrieve_body($mr), true);
                if (!empty($md['source_url'])) {
                    $image_urls[] = $md['source_url'];
                }
            }
        }

        // Use first gallery image as thumb if no featured media
        if (!$thumb_url && !empty($image_urls)) {
            $thumb_url = $image_urls[0];
        }

        // Store remote URLs (theme uses sb_thumb_url + sb_image_urls to display)
        if ($thumb_url) {
            update_post_meta($post_id, 'sb_thumb_url', $thumb_url);
        }
        if (!empty($image_urls)) {
            update_post_meta($post_id, 'sb_image_urls', $image_urls);
        }

        $log[] = "CREATED [{$post_id}]: {$title} | €{$price} | {$bedrooms}bd | {$city} | imgs:" . count($image_urls);
    }

    update_option('sb_featured_imported_v1', true);

    // Show log
    echo '<pre style="font-family:monospace;padding:20px;background:#f0f0f0;white-space:pre-wrap">';
    echo '<strong>Import complete!</strong>' . "\n\n";
    echo implode("\n", $log);
    echo '</pre>';
    exit;
});

/**
 * Fix missing images: re-fetch ALL gallery image URLs from old site for featured properties.
 * Trigger: /wp-admin/?sb_fix_images=1  (admin only, processes 2 properties per run)
 *
 * The original import was limited to 8 images per property. This fetches all of them.
 * Re-run until you see "All done!" to process all properties.
 */
add_action('admin_init', function () {
    if (!isset($_GET['sb_fix_images']) || !current_user_can('manage_options')) return;

    $source     = 'https://spaniabolig-old.holthe.com/wp-json/wp/v2';
    $batch_size = 2;
    $log        = [];

    // Find featured properties that haven't been fixed yet
    $props = get_posts([
        'post_type'      => 'property',
        'post_status'    => 'publish',
        'posts_per_page' => $batch_size,
        'meta_query'     => [
            ['key' => 'sb_featured', 'value' => '1'],
            ['key' => 'sb_ref', 'compare' => 'EXISTS'],
            ['key' => 'sb_images_fixed', 'compare' => 'NOT EXISTS'],
        ],
    ]);

    if (empty($props)) {
        wp_die('<pre style="font-family:monospace;padding:20px;background:#d4edda">All done! All featured properties have full image sets.</pre>');
    }

    foreach ($props as $prop) {
        $ref  = get_post_meta($prop->ID, 'sb_ref', true);
        $slug = $prop->post_name;

        // Try to find the property on the old site by slug
        $resp = wp_remote_get("{$source}/properties?slug={$slug}&_fields=id,featured_media,property_meta", ['timeout' => 30]);
        if (is_wp_error($resp)) {
            $log[] = "ERROR fetching {$slug}: " . $resp->get_error_message();
            update_post_meta($prop->ID, 'sb_images_fixed', '1');
            continue;
        }

        $data = json_decode(wp_remote_retrieve_body($resp), true);
        if (empty($data[0])) {
            $log[] = "SKIP (not found on old site): {$slug}";
            update_post_meta($prop->ID, 'sb_images_fixed', '1');
            continue;
        }

        $old      = $data[0];
        $meta     = $old['property_meta'] ?? [];
        $img_ids  = array_filter((array) ($meta['fave_property_images'] ?? []));
        $old_count = count($img_ids);

        // Resolve ALL image URLs (no limit)
        $image_urls = [];
        foreach ($img_ids as $img_id) {
            $mr = wp_remote_get("{$source}/media/{$img_id}?_fields=source_url", ['timeout' => 10]);
            if (!is_wp_error($mr)) {
                $md = json_decode(wp_remote_retrieve_body($mr), true);
                if (!empty($md['source_url'])) {
                    $image_urls[] = $md['source_url'];
                }
            }
        }

        // Resolve featured media for thumb
        $thumb_url = '';
        $thumb_id  = $old['featured_media'] ?? 0;
        if ($thumb_id) {
            $tm = wp_remote_get("{$source}/media/{$thumb_id}?_fields=source_url", ['timeout' => 10]);
            if (!is_wp_error($tm)) {
                $td = json_decode(wp_remote_retrieve_body($tm), true);
                $thumb_url = $td['source_url'] ?? '';
            }
        }
        if (!$thumb_url && !empty($image_urls)) {
            $thumb_url = $image_urls[0];
        }

        // Update meta
        if ($thumb_url) {
            update_post_meta($prop->ID, 'sb_thumb_url', $thumb_url);
        }
        if (!empty($image_urls)) {
            update_post_meta($prop->ID, 'sb_image_urls', $image_urls);
        }

        update_post_meta($prop->ID, 'sb_images_fixed', '1');
        $log[] = "FIXED [{$prop->ID}]: {$slug} | {$old_count} images found, " . count($image_urls) . " URLs saved";
    }

    echo '<pre style="font-family:monospace;padding:20px;background:#f0f0f0;white-space:pre-wrap">';
    echo "<strong>Batch complete!</strong> Processed " . count($props) . " properties.\n";
    echo "Reload this page to process the next batch.\n\n";
    echo implode("\n", $log);
    echo '</pre>';
    exit;
});
