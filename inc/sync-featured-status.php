<?php
/**
 * Sync sale status for featured/exclusive properties from spaniabolig.no.
 * Status is stored as a taxonomy (property_status) on the old Houzez site — not meta.
 * Trigger: /wp-admin/?sb_sync_status=1  (admin only, safe to re-run)
 */

add_action('admin_init', function () {
    if (!isset($_GET['sb_sync_status']) || !current_user_can('manage_options')) return;

    $source = 'https://spaniabolig.no/wp-json/wp/v2';
    $log    = [];

    /* ── 1. Build property_status term map  id → slug ── */
    $status_map = [];
    $sr = wp_remote_get("{$source}/property_status?per_page=100&_fields=id,slug", ['timeout' => 15]);
    if (!is_wp_error($sr)) {
        foreach (json_decode(wp_remote_retrieve_body($sr), true) ?: [] as $t) {
            $status_map[$t['id']] = $t['slug']; // e.g. 139 → 'sold', 28 → 'for-sale'
        }
    }
    $log[] = 'Status terms: ' . json_encode($status_map);
    $log[] = str_repeat('─', 60);

    /* ── 2. Fetch all featured properties (paginated) ── */
    $all   = [];
    $page  = 1;
    $pages = 1;
    while ($page <= $pages) {
        $resp = wp_remote_get(
            "{$source}/properties?per_page=100&page={$page}&_fields=slug,title,property_meta,property_status",
            ['timeout' => 30]
        );
        if (is_wp_error($resp)) break;
        $pages = (int) wp_remote_retrieve_header($resp, 'x-wp-totalpages') ?: 1;
        $data  = json_decode(wp_remote_retrieve_body($resp), true);
        if (!is_array($data) || empty($data)) break;
        $all = array_merge($all, $data);
        $page++;
    }

    $featured = array_filter($all, fn($p) => ($p['property_meta']['fave_featured'][0] ?? '0') === '1');
    $log[] = 'Fetched ' . count($all) . ' total, ' . count($featured) . ' featured';
    $log[] = str_repeat('─', 60);

    /* ── 3. Determine status from taxonomy terms ── */
    foreach ($featured as $prop) {
        $slug       = $prop['slug'] ?? '';
        $title      = html_entity_decode($prop['title']['rendered'] ?? '', ENT_QUOTES);
        $term_ids   = (array) ($prop['property_status'] ?? []);

        // Priority: sold > for-rent > for-sale
        $new_status = 'for-sale';
        foreach ($term_ids as $tid) {
            $term_slug = $status_map[$tid] ?? '';
            if ($term_slug === 'sold')     { $new_status = 'sold';     break; }
            if ($term_slug === 'for-rent') { $new_status = 'for-rent'; }
        }

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
            $log[] = "OK: {$title} → {$new_status}";
            continue;
        }

        update_post_meta($local->ID, 'sb_status', $new_status);
        $log[] = "UPDATED [{$local->ID}]: {$title}  \"{$current}\" → \"{$new_status}\"";
    }

    echo '<pre style="font-family:monospace;padding:20px;background:#f0f0f0;white-space:pre-wrap">';
    echo '<strong>Status sync complete!</strong>' . "\n\n";
    echo implode("\n", $log);
    echo '</pre>';
    exit;
});
