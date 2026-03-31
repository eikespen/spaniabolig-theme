<?php
/**
 * Property XML Importer
 * Imports from two feeds: resale (tppcm/Kyero) and new build (redsp)
 */

defined('ABSPATH') || exit;

/* ── Feed configuration ── */
define('SB_FEED_RESALE',    'https://tppcm.com/xml/mls.php?u=QjRGZHJnRjlxeWF6UWpkdmtQYVJHZz09');
define('SB_FEED_NEWBUILD',  'https://xml.redsp.net/file/694/29012amh70w/general-zone-1.xml');

/* ── Register admin page ── */
add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=property',
        'Import Properties',
        'Import Properties',
        'manage_options',
        'sb-import',
        'sb_import_admin_page'
    );
});

/* ── Register cron schedule ── */
add_filter('cron_schedules', function ($schedules) {
    $schedules['sb_daily'] = [
        'interval' => DAY_IN_SECONDS,
        'display'  => 'Once Daily',
    ];
    return $schedules;
});

/* ── Schedule on activation / theme switch ── */
add_action('after_switch_theme', 'sb_schedule_import');
function sb_schedule_import() {
    if (!wp_next_scheduled('sb_auto_import')) {
        wp_schedule_event(time(), 'sb_daily', 'sb_auto_import');
    }
}

/* ── Clear schedule on theme deactivation ── */
add_action('switch_theme', function () {
    wp_clear_scheduled_hook('sb_auto_import');
});

/* ── Auto-import cron hook ── */
add_action('sb_auto_import', function () {
    sb_run_import('resale');
    sb_run_import('newbuild');
});

/* ── Handle manual import via POST ── */
add_action('admin_post_sb_run_import', function () {
    if (!current_user_can('manage_options') || !check_admin_referer('sb_import_nonce')) {
        wp_die('Unauthorized');
    }
    $feed = sanitize_text_field($_POST['feed'] ?? 'resale');
    $result = sb_run_import($feed);
    $msg = urlencode("Imported {$result['created']} new, updated {$result['updated']} existing, {$result['skipped']} skipped. Feed: {$feed}");
    wp_redirect(admin_url('edit.php?post_type=property&page=sb-import&msg=' . $msg));
    exit;
});

/* ── Admin page UI ── */
function sb_import_admin_page() {
    $log = get_option('sb_import_log', []);
    $next = wp_next_scheduled('sb_auto_import');
    ?>
    <div class="wrap">
        <h1>Import Properties</h1>

        <?php if (!empty($_GET['msg'])): ?>
            <div class="notice notice-success is-dismissible"><p><?php echo esc_html(urldecode($_GET['msg'])); ?></p></div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px;max-width:900px;">

            <?php foreach ([
                'resale'   => ['label' => 'Resale Properties', 'feed' => SB_FEED_RESALE,   'color' => '#0073aa'],
                'newbuild' => ['label' => 'New Build Properties', 'feed' => SB_FEED_NEWBUILD, 'color' => '#00a32a'],
            ] as $key => $info):
                $last = $log[$key] ?? null;
            ?>
            <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;">
                <h3 style="margin-top:0;color:<?php echo $info['color']; ?>"><?php echo $info['label']; ?></h3>
                <p style="font-size:12px;color:#666;word-break:break-all;"><?php echo esc_html($info['feed']); ?></p>
                <?php if ($last): ?>
                    <p><strong>Last run:</strong> <?php echo esc_html(date('d M Y H:i', $last['time'])); ?><br>
                    <strong>Result:</strong> <?php echo esc_html("{$last['created']} created, {$last['updated']} updated, {$last['skipped']} skipped"); ?></p>
                <?php else: ?>
                    <p><em>Never imported</em></p>
                <?php endif; ?>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <?php wp_nonce_field('sb_import_nonce'); ?>
                    <input type="hidden" name="action" value="sb_run_import">
                    <input type="hidden" name="feed" value="<?php echo esc_attr($key); ?>">
                    <button type="submit" class="button button-primary">Run Import Now</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:24px;max-width:900px;background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;">
            <h3 style="margin-top:0;">Auto-Import Schedule</h3>
            <p>Both feeds are imported automatically once per day via WP-Cron.</p>
            <p><strong>Next scheduled run:</strong> <?php echo $next ? esc_html(date('d M Y H:i', $next)) : 'Not scheduled'; ?></p>
            <?php if (!$next): ?>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <?php wp_nonce_field('sb_schedule_nonce'); ?>
                    <input type="hidden" name="action" value="sb_reschedule_import">
                    <button type="submit" class="button">Re-enable Schedule</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/* ── Re-enable schedule ── */
add_action('admin_post_sb_reschedule_import', function () {
    if (!current_user_can('manage_options') || !check_admin_referer('sb_schedule_nonce')) wp_die('Unauthorized');
    sb_schedule_import();
    wp_redirect(admin_url('edit.php?post_type=property&page=sb-import'));
    exit;
});

/* ────────────────────────────────────────────
   Core importer function
───────────────────────────────────────────── */
function sb_run_import(string $feed_key): array {
    @ini_set('memory_limit', '256M');
    @set_time_limit(300);
    $result = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => []];

    $url       = ($feed_key === 'resale') ? SB_FEED_RESALE : SB_FEED_NEWBUILD;
    $build_type = ($feed_key === 'resale') ? 'resale' : 'new_build';

    $response = wp_remote_get($url, ['timeout' => 60]);
    if (is_wp_error($response)) {
        $result['errors'][] = $response->get_error_message();
        return $result;
    }

    $xml_string = wp_remote_retrieve_body($response);
    if (empty($xml_string)) {
        $result['errors'][] = 'Empty response from feed';
        return $result;
    }

    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($xml_string);
    if (!$xml) {
        $result['errors'][] = 'Failed to parse XML';
        return $result;
    }

    foreach ($xml->property as $prop) {
        $ref = trim((string) $prop->ref);
        if (empty($ref)) { $result['skipped']++; continue; }

        // Map fields
        $price    = (int) $prop->price;
        $beds     = (int) $prop->beds;
        $baths    = (int) $prop->baths;
        $city     = trim((string) ($prop->town ?: $prop->location_detail));
        $lat      = (float) $prop->location->latitude;
        $lng      = (float) $prop->location->longitude;
        $status   = ((string) $prop->price_freq === 'rent') ? 'for-rent' : 'for-sale';
        $type_raw = trim((string) $prop->type);

        // Size: prefer built, fall back to plot
        $size = (int) ($prop->surface_area->built ?: $prop->surface_area->plot);

        // Title: multilingual title or clean fallback (no ref number)
        if (!empty($prop->title->en)) {
            $title = trim((string) $prop->title->en);
        } elseif ($beds && $type_raw && $city) {
            $title = $beds . '-Bedroom ' . ucfirst(strtolower($type_raw)) . ' in ' . $city;
        } elseif ($type_raw && $city) {
            $title = ucfirst(strtolower($type_raw)) . ' in ' . $city;
        } else {
            $title = 'Property in ' . ($city ?: 'Spain');
        }

        // Description
        $desc = trim((string) ($prop->desc->en ?: $prop->desc));

        // Image URLs (up to 20)
        $image_urls = [];
        if ($prop->images) {
            foreach ($prop->images->image as $img) {
                $url_val = (string) ($img->url ?: $img);
                if ($url_val) $image_urls[] = $url_val;
                if (count($image_urls) >= 20) break;
            }
        }

        // Features / amenities — try common XML patterns
        $features = [];
        if (!empty($prop->features)) {
            foreach ($prop->features->feature as $f) {
                $val = trim((string) $f);
                if ($val) $features[] = $val;
            }
        }
        if (empty($features) && !empty($prop->options)) {
            // Some feeds use comma-separated options string
            $features = array_filter(array_map('trim', explode(',', (string) $prop->options)));
        }

        // Check if property already exists by ref
        $existing = get_posts([
            'post_type'      => 'property',
            'meta_key'       => 'sb_ref',
            'meta_value'     => $ref,
            'posts_per_page' => 1,
            'post_status'    => 'any',
            'fields'         => 'ids',
        ]);

        $post_data = [
            'post_type'    => 'property',
            'post_status'  => 'publish',
            'post_title'   => $title,
            'post_content' => $desc,
        ];

        if ($existing) {
            $post_id = $existing[0];
            $post_data['ID'] = $post_id;
            wp_update_post($post_data);
            $result['updated']++;
        } else {
            $post_id = wp_insert_post($post_data);
            if (is_wp_error($post_id)) { $result['skipped']++; continue; }
            $result['created']++;
        }

        // Save meta
        update_post_meta($post_id, 'sb_ref',        $ref);
        update_post_meta($post_id, 'sb_price',       $price);
        update_post_meta($post_id, 'sb_bedrooms',    $beds);
        update_post_meta($post_id, 'sb_bathrooms',   $baths);
        update_post_meta($post_id, 'sb_size',        $size);
        update_post_meta($post_id, 'sb_city',        $city);
        update_post_meta($post_id, 'sb_lat',         $lat);
        update_post_meta($post_id, 'sb_lng',         $lng);
        update_post_meta($post_id, 'sb_status',      $status);
        update_post_meta($post_id, 'sb_build_type',  $build_type);
        update_post_meta($post_id, 'sb_image_urls',  $image_urls); // Store all image URLs
        if (!empty($features)) {
            update_post_meta($post_id, 'sb_features', $features);
        }

        // Store first image URL as external thumbnail (no download during import)
        if (!empty($image_urls)) {
            update_post_meta($post_id, 'sb_thumb_url', $image_urls[0]);
        }

        // Property type taxonomy
        if ($type_raw) {
            $slug = sb_map_type($type_raw);
            $term = get_term_by('slug', $slug, 'property_type');
            if (!$term) $term = wp_insert_term(ucfirst($slug), 'property_type', ['slug' => $slug]);
            if (!is_wp_error($term)) {
                $term_id = is_array($term) ? $term['term_id'] : $term->term_id;
                wp_set_post_terms($post_id, [$term_id], 'property_type');
            }
        }

        // Location detail stored as meta (urbanization/area detail)
        $location_detail = trim((string) $prop->location_detail);
        if ($location_detail) {
            update_post_meta($post_id, 'sb_urbanization', $location_detail);
        }
    }

    // Save log
    $log               = get_option('sb_import_log', []);
    $log[$feed_key]    = array_merge($result, ['time' => time()]);
    update_option('sb_import_log', $log);

    return $result;
}

/* ── Map type string to slug ── */
function sb_map_type(string $type): string {
    $type  = strtolower($type);
    $map   = [
        'villa'      => 'villa',
        'apartment'  => 'apartment',
        'flat'       => 'apartment',
        'townhouse'  => 'townhouse',
        'town house' => 'townhouse',
        'bungalow'   => 'bungalow',
        'penthouse'  => 'penthouse',
        'detached'   => 'villa',
        'semi-detached' => 'townhouse',
    ];
    foreach ($map as $key => $slug) {
        if (str_contains($type, $key)) return $slug;
    }
    return sanitize_title($type) ?: 'other';
}

/* ── Download & attach featured image ── */
function sb_set_featured_image(int $post_id, string $image_url, string $ref): void {
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $tmp = download_url($image_url, 30);
    if (is_wp_error($tmp)) return;

    $ext  = pathinfo(parse_url($image_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
    $ext  = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']) ? $ext : 'jpg';

    $file = [
        'name'     => 'property-' . sanitize_title($ref) . '.' . $ext,
        'type'     => 'image/' . ($ext === 'jpg' ? 'jpeg' : $ext),
        'tmp_name' => $tmp,
        'error'    => 0,
        'size'     => filesize($tmp),
    ];

    $attachment_id = media_handle_sideload($file, $post_id, $ref . ' property image');
    @unlink($tmp);

    if (!is_wp_error($attachment_id)) {
        set_post_thumbnail($post_id, $attachment_id);
    }
}
