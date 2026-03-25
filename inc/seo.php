<?php
/**
 * SEO — Yoast meta defaults for all pages and properties
 *
 *  1. Auto-generate Yoast meta when a property is saved (if not already set)
 *  2. One-time seeder: /wp-admin/?sb_seed_seo=1  — sets meta for all pages + existing properties
 *  3. Fallback <meta> tags in wp_head for any page/property still missing Yoast data
 */
defined('ABSPATH') || exit;

/* ── Helpers ── */
function sb_seo_set(int $id, string $title, string $desc, string $focuskw = ''): void {
    // Only write if the value is currently empty (don't overwrite manual Yoast edits)
    if (!get_post_meta($id, '_yoast_wpseo_title', true)) {
        update_post_meta($id, '_yoast_wpseo_title', $title);
    }
    if (!get_post_meta($id, '_yoast_wpseo_metadesc', true)) {
        update_post_meta($id, '_yoast_wpseo_metadesc', $desc);
    }
    if ($focuskw && !get_post_meta($id, '_yoast_wpseo_focuskw', true)) {
        update_post_meta($id, '_yoast_wpseo_focuskw', $focuskw);
    }
}

function sb_seo_force(int $id, string $title, string $desc, string $focuskw = ''): void {
    update_post_meta($id, '_yoast_wpseo_title', $title);
    update_post_meta($id, '_yoast_wpseo_metadesc', $desc);
    if ($focuskw) update_post_meta($id, '_yoast_wpseo_focuskw', $focuskw);
}

/* ════════════════════════════════════════════
   1. AUTO-SEO WHEN A PROPERTY IS SAVED
   Fires after the property meta is saved so all
   fields are already in the DB.
════════════════════════════════════════════ */
add_action('save_post_property', function (int $post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Give save_post_property (which writes meta) a chance to run first
    // by using a late priority — we hook at priority 20
}, 20);

add_action('save_post_property', function (int $post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;

    sb_seo_generate_property($post_id);
}, 30); // runs after the standard meta save at default priority

function sb_seo_generate_property(int $post_id): void {
    $title      = get_the_title($post_id);
    $price      = (int) get_post_meta($post_id, 'sb_price',      true);
    $beds       = (int) get_post_meta($post_id, 'sb_bedrooms',   true);
    $baths      = (int) get_post_meta($post_id, 'sb_bathrooms',  true);
    $size       = (int) get_post_meta($post_id, 'sb_size',       true);
    $city       = get_post_meta($post_id, 'sb_city',       true);
    $build_type = get_post_meta($post_id, 'sb_build_type', true);
    $status     = get_post_meta($post_id, 'sb_status',     true);
    $site_name  = get_bloginfo('name');

    // Determine property type label
    $type_terms = get_the_terms($post_id, 'property_type');
    $type_label = ($type_terms && !is_wp_error($type_terms)) ? strtolower($type_terms[0]->name) : 'property';

    // Build status phrase
    $status_phrase = 'for sale';
    if ($status === 'for-rent') $status_phrase = 'for rent';
    elseif ($status === 'sold')  $status_phrase = '— sold';

    // Build type qualifier
    $build_label = '';
    if ($build_type === 'new_build') $build_label = 'new build ';
    elseif ($build_type === 'resale') $build_label = 'resale ';

    // Location phrase
    $location = $city ?: 'Ciudad Quesada, Costa Blanca';

    // SEO title: "3-Bedroom Villa for Sale in Ciudad Quesada | Spaniabolig"
    $seo_title = '';
    if ($beds) {
        $seo_title = "{$beds}-Bedroom " . ucfirst($build_label) . ucfirst($type_label) . " " . ucfirst($status_phrase) . " in {$location} | {$site_name}";
    } else {
        $seo_title = ucfirst($build_label) . ucfirst($type_label) . " " . ucfirst($status_phrase) . " in {$location} | {$site_name}";
    }

    // SEO description (150-160 chars target)
    $desc_parts = [];
    if ($beds)  $desc_parts[] = "{$beds} bed";
    if ($baths) $desc_parts[] = "{$baths} bath";
    if ($size)  $desc_parts[] = "{$size} m²";
    $features = $desc_parts ? implode(', ', $desc_parts) . '. ' : '';

    $price_str = $price ? '€' . number_format($price, 0, ',', ' ') . '. ' : '';
    $seo_desc  = ucfirst($build_label) . ucfirst($type_label) . " {$status_phrase} in {$location}, Spain. {$features}{$price_str}Contact Spaniabolig to arrange a viewing today.";

    // Trim description to 160 chars
    if (strlen($seo_desc) > 160) $seo_desc = substr($seo_desc, 0, 157) . '…';

    // Focus keyword
    $focuskw = ($beds ? "{$beds} bedroom " : '') . $type_label . ' ' . $status_phrase . ' ' . strtolower($location);

    sb_seo_set($post_id, $seo_title, $seo_desc, $focuskw);
}

/* ════════════════════════════════════════════
   2. ONE-TIME SEO SEEDER
   Visit: /wp-admin/?sb_seed_seo=1
   Sets Yoast meta on all pages + all existing properties.
   Safe to re-run — only overwrites blank fields unless you
   add ?sb_seed_seo=1&force=1 to overwrite everything.
════════════════════════════════════════════ */
add_action('admin_init', function () {
    if (!isset($_GET['sb_seed_seo']) || !current_user_can('manage_options')) return;

    $force     = isset($_GET['force']) && $_GET['force'] === '1';
    $site_name = get_bloginfo('name');
    $fn        = $force ? 'sb_seo_force' : 'sb_seo_set';
    $log       = [];

    /* ── Page definitions ── */
    $page_seo = [
        // slug => [title, description, focus_keyword]
        'home' => [
            "Find Your Dream Property in Ciudad Quesada, Costa Blanca | {$site_name}",
            "Browse villas, apartments and townhouses for sale in Ciudad Quesada and the urbanizations of Rojales on Costa Blanca, Spain. Find your perfect Spanish home with Spaniabolig.",
            'properties for sale Ciudad Quesada',
        ],
        'about' => [
            "About Spaniabolig — Property Specialists in Ciudad Quesada | {$site_name}",
            "Meet the Spaniabolig team. Local property specialists helping international buyers find their perfect home in Ciudad Quesada and Rojales, Costa Blanca, Spain.",
            'property specialist Ciudad Quesada',
        ],
        'how-it-works' => [
            "How to Buy Property in Spain — Step by Step | {$site_name}",
            "Our step-by-step guide to buying property in Ciudad Quesada, Spain. From your first enquiry to completion — we guide you through every stage of the Spanish property purchase.",
            'buying property Spain guide',
        ],
        'services' => [
            "Property Management Services in Spain | {$site_name}",
            "Key holding, pool maintenance, cleaning, property photography and more. Professional property management services for owners in Ciudad Quesada and Rojales, Costa Blanca.",
            'property management services Spain',
        ],
        'contact' => [
            "Contact Spaniabolig — Property Enquiries | {$site_name}",
            "Get in touch with the Spaniabolig team. Questions about buying or managing a property in Ciudad Quesada, Costa Blanca? We'd love to hear from you.",
            'contact Spaniabolig property',
        ],
        'dictionary' => [
            "Spanish Property Dictionary — Key Terms Explained | {$site_name}",
            "A comprehensive guide to Spanish property buying terminology. From NIE to escritura, ITP to plusvalía — understand every legal and financial term before you buy.",
            'Spanish property glossary terms',
        ],
        'privacy-policy' => [
            "Privacy Policy | {$site_name}",
            "Read Spaniabolig's privacy policy to understand how we collect, use and protect your personal data in accordance with GDPR.",
            '',
        ],
        'terms-of-use' => [
            "Terms of Use | {$site_name}",
            "Read the Spaniabolig terms of use governing your use of our website and property services.",
            '',
        ],
        'cookie-policy' => [
            "Cookie Policy | {$site_name}",
            "Learn how Spaniabolig uses cookies, what types we set, and how you can manage your cookie preferences.",
            '',
        ],
        'property-dashboard' => [
            "Property Dashboard | {$site_name}",
            "Manage your property listings on Spaniabolig.",
            '',
        ],
        'add-property' => [
            "Add a New Property Listing | {$site_name}",
            "Publish a new property listing using our step-by-step wizard.",
            '',
        ],
        'favorites' => [
            "Your Saved Properties | {$site_name}",
            "View the properties you've saved to your favourites list.",
            '',
        ],
    ];

    // Front page
    $front_id = (int) get_option('page_on_front');
    if ($front_id) {
        $d = $page_seo['home'];
        $fn($front_id, $d[0], $d[1], $d[2]);
        $log[] = "Front page (ID {$front_id}): set";
    }

    // All other pages by slug
    foreach ($page_seo as $slug => $d) {
        if ($slug === 'home') continue;
        $page = get_page_by_path($slug);
        if (!$page) { $log[] = "Page not found: {$slug}"; continue; }
        $fn($page->ID, $d[0], $d[1], $d[2]);
        $log[] = "Page '{$slug}' (ID {$page->ID}): set";
    }

    // Properties archive (Yoast handles archives differently — set via wpseo_titles option)
    $wpseo_titles = get_option('wpseo_titles', []);
    if (empty($wpseo_titles['title-tax-property_type']) || $force) {
        $wpseo_titles['title-archive-property']       = "Properties for Sale on Costa Blanca | {$site_name}";
        $wpseo_titles['metadesc-archive-property']    = "Browse our full selection of villas, apartments and townhouses for sale on the Costa Blanca. Properties in Ciudad Quesada and Rojales, Alicante, Spain.";
        $wpseo_titles['title-tax-property_type']      = "%%term_title%% Properties for Sale in Ciudad Quesada | {$site_name}";
        $wpseo_titles['metadesc-tax-property_type']   = "Browse %%term_title%% properties for sale in Ciudad Quesada and Rojales, Costa Blanca. Find your perfect Spanish home with Spaniabolig.";
        $wpseo_titles['title-property']               = "%%title%% | {$site_name}";
        $wpseo_titles['metadesc-property']            = '';
        update_option('wpseo_titles', $wpseo_titles);
        $log[] = "Yoast archive/taxonomy title templates: set";
    }

    // All existing properties
    $properties = get_posts([
        'post_type'      => 'property',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ]);
    $prop_count = 0;
    foreach ($properties as $pid) {
        if ($force) {
            // Force: clear existing so sb_seo_set will write
            delete_post_meta($pid, '_yoast_wpseo_title');
            delete_post_meta($pid, '_yoast_wpseo_metadesc');
            delete_post_meta($pid, '_yoast_wpseo_focuskw');
        }
        sb_seo_generate_property($pid);
        $prop_count++;
    }
    $log[] = "Properties: set SEO meta on {$prop_count} listing(s)";

    wp_die(
        '<h2>SEO Seeder</h2>'
        . ($force ? '<p style="color:darkorange">⚠️ Force mode — all existing Yoast meta overwritten.</p>' : '<p>Safe mode — only blank fields were filled. Add <code>&amp;force=1</code> to overwrite everything.</p>')
        . '<ul><li>' . implode('</li><li>', array_map('esc_html', $log)) . '</li></ul>'
        . '<p><a href="' . admin_url() . '">Back to dashboard</a></p>'
    );
});

/* ════════════════════════════════════════════
   3. WP_HEAD FALLBACK META TAGS
   Outputs <meta> only when Yoast hasn't already done so
   (i.e. Yoast is not active, or the field is empty).
════════════════════════════════════════════ */
add_action('wp_head', function () {
    // Skip if Yoast is handling this page (it outputs its own meta)
    if (class_exists('WPSEO_Frontend') || class_exists('Yoast\WP\SEO\Main')) {
        // Yoast active — trust it; only add OG image fallback if missing
        sb_seo_og_image_fallback();
        return;
    }

    global $post;
    $site_name = get_bloginfo('name');
    $title     = '';
    $desc      = '';
    $img_url   = '';

    if (is_singular('property') && $post) {
        $pid   = $post->ID;
        $title = get_post_meta($pid, '_yoast_wpseo_title', true);
        $desc  = get_post_meta($pid, '_yoast_wpseo_metadesc', true);
        if (!$title) {
            $city  = get_post_meta($pid, 'sb_city', true);
            $beds  = get_post_meta($pid, 'sb_bedrooms', true);
            $types = get_the_terms($pid, 'property_type');
            $type  = ($types && !is_wp_error($types)) ? $types[0]->name : 'Property';
            $title = ($beds ? "{$beds}-Bed " : '') . "{$type} for Sale" . ($city ? " in {$city}" : '') . " | {$site_name}";
        }
        $img_url = sb_get_image_url($pid, 'large');
    } elseif (is_page() && $post) {
        $title = get_post_meta($post->ID, '_yoast_wpseo_title', true)    ?: get_the_title() . " | {$site_name}";
        $desc  = get_post_meta($post->ID, '_yoast_wpseo_metadesc', true) ?: '';
    } elseif (is_front_page()) {
        $title = "Find Your Dream Property in Ciudad Quesada | {$site_name}";
        $desc  = "Browse villas, apartments and townhouses for sale in Ciudad Quesada and the urbanizations of Rojales on Costa Blanca, Spain.";
    }

    if ($title) echo '<meta name="title" content="' . esc_attr($title) . '">' . "\n";
    if ($desc)  echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
    if ($title) echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    if ($desc)  echo '<meta property="og:description" content="' . esc_attr($desc) . '">' . "\n";
    if ($img_url) echo '<meta property="og:image" content="' . esc_url($img_url) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
    echo '<meta property="og:locale" content="en_GB">' . "\n";
}, 5); // priority 5 so it fires before Yoast (priority 1) can suppress it — actually Yoast runs at 1 so this won't conflict

function sb_seo_og_image_fallback(): void {
    // When Yoast is active, add og:image if the property has a photo and Yoast hasn't set one
    if (!is_singular('property')) return;
    global $post;
    if (!$post) return;
    $img_url = sb_get_image_url($post->ID, 'large');
    if (!$img_url) return;
    // Yoast outputs og:image — we only add a Twitter card image fallback
    echo '<meta name="twitter:image" content="' . esc_url($img_url) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}

/* ════════════════════════════════════════════
   4. YOAST GLOBAL SETTINGS — title separators,
      site-wide title format, social profiles
════════════════════════════════════════════ */
add_action('init', function () {
    // Only touch these if Yoast is installed
    if (!class_exists('WPSEO_Options') && !function_exists('wpseo_init')) return;

    // Set a clean title separator (dash) if not already configured
    $wpseo_titles = get_option('wpseo_titles', []);
    $changed = false;

    if (empty($wpseo_titles['separator'])) {
        $wpseo_titles['separator'] = 'sc-dash'; // " - "
        $changed = true;
    }
    // Set page title template if blank
    if (empty($wpseo_titles['title-page'])) {
        $wpseo_titles['title-page'] = '%%title%% | %%sitename%%';
        $changed = true;
    }
    // Homepage template
    if (empty($wpseo_titles['title-home-wpseo'])) {
        $wpseo_titles['title-home-wpseo'] = '%%sitename%% — Properties for Sale in Ciudad Quesada';
        $changed = true;
    }
    if ($changed) update_option('wpseo_titles', $wpseo_titles);
}, 20);
