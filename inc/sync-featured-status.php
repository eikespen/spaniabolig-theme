<?php
/**
 * Sync sale status for featured/exclusive properties from spaniabolig.no.
 * Trigger: /wp-admin/?sb_sync_status=1  (admin only, can be re-run safely)
 */

add_action('admin_init', function () {
    if (!isset($_GET['sb_sync_status']) || !current_user_can('manage_options')) return;

    $source = 'https://spaniabolig.no/wp-json/wp/v2';
    $log    = [];

    /* ── 1. Fetch all properties from old site (paginated) ── */
    $all  = [];
    $page = 1;
    $pages = 1;
    while ($page <= $pages) {
        $resp = wp_remote_get("{$source}/properties?per_page=100&page={$page}", ['timeout' => 30]);
        if (is_wp_error($resp)) {
            $log[] = 'ERROR fetching page ' . $page . ': ' . $resp->get_error_message();
            break;
        }
        $pages = (int) wp_remote_retrieve_header($resp, 'x-wp-totalpages') ?: 1;
        $data  = json_decode(wp_remote_retrieve_body($resp), true);
        if (!is_array($data) || empty($data)) break;
        $all = array_merge($all, $data);
        $page++;
    }

    $log[] = 'Fetched ' . count($all) . ' properties from spaniabolig.no';

    /* ── 2. Filter to featured only ── */
    $featured = array_filter($all, fn($p) => ($p['property_meta']['fave_featured'][0] ?? '0') === '1');
    $log[] = 'Found ' . count($featured) . ' featured properties';
    $log[] = str_repeat('─', 60);

    /* ── 3. Map old status → new status ── */
    $status_map = [
        'for_sale'  => 'for-sale',
        'for-sale'  => 'for-sale',
        'for_rent'  => 'for-rent',
        'for-rent'  => 'for-rent',
        'sold'      => 'sold',
        'on_hold'   => 'for-sale',
    ];

    /* ── 4. Match to local posts by slug and update status ── */
    foreach ($featured as $prop) {
        $slug       = $prop['slug'] ?? '';
        $old_status = $prop['property_meta']['fave_property_status'][0] ?? 'for_sale';
        $new_status = $status_map[$old_status] ?? 'for-sale';
        $title      = html_entity_decode($prop['title']['rendered'] ?? '', ENT_QUOTES);

        if (!$slug) {
            $log[] = "SKIP (no slug): {$title}";
            continue;
        }

        $local = get_page_by_path($slug, OBJECT, 'property');
        if (!$local) {
            $log[] = "NOT FOUND locally: {$title} ({$slug})";
            continue;
        }

        $current = get_post_meta($local->ID, 'sb_status', true);
        if ($current === $new_status) {
            $log[] = "OK (unchanged): {$title} → {$new_status}";
            continue;
        }

        update_post_meta($local->ID, 'sb_status', $new_status);
        $log[] = "UPDATED [{$local->ID}]: {$title}  {$current} → {$new_status}";
    }

    echo '<pre style="font-family:monospace;padding:20px;background:#f0f0f0;white-space:pre-wrap">';
    echo '<strong>Status sync complete!</strong>' . "\n\n";
    echo implode("\n", $log);
    echo '</pre>';
    exit;
});
