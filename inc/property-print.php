<?php
/**
 * Printable property sheet — included via single-property.php?print=1
 * Clean A4 layout suitable for pinning on boards outside the office.
 */
defined('ABSPATH') || exit;

$post_id    = get_the_ID();
$agent      = sb_get_property_agent($post_id);
$price      = get_post_meta($post_id, 'sb_price', true);
$bedrooms   = get_post_meta($post_id, 'sb_bedrooms', true);
$bathrooms  = get_post_meta($post_id, 'sb_bathrooms', true);
$size       = get_post_meta($post_id, 'sb_size', true);
$status     = get_post_meta($post_id, 'sb_status', true);
$city       = get_post_meta($post_id, 'sb_city', true);
$ref        = get_post_meta($post_id, 'sb_ref', true);
$address    = get_post_meta($post_id, 'sb_address', true);
$build_type = get_post_meta($post_id, 'sb_build_type', true);

$type_terms = get_the_terms($post_id, 'property_type');
$type_label = (!empty($type_terms) && !is_wp_error($type_terms)) ? implode(', ', wp_list_pluck($type_terms, 'name')) : '';
$features   = get_post_meta($post_id, 'sb_features', true) ?: [];
if (!is_array($features)) $features = [];

$main_img   = sb_get_image_url($post_id, 'full');
$image_urls = get_post_meta($post_id, 'sb_image_urls', true) ?: [];
if (!is_array($image_urls)) $image_urls = [];

// Pick up to 4 additional images for the grid
$grid_imgs = [];
foreach ($image_urls as $u) {
    if ($u && $u !== $main_img) $grid_imgs[] = $u;
    if (count($grid_imgs) >= 4) break;
}

$status_labels = ['for-sale' => 'For Sale', 'for-rent' => 'For Rent', 'sold' => 'Sold'];
$status_key    = str_replace('_', '-', (string) $status);
$status_label  = $status_labels[$status_key] ?? ucwords(str_replace(['-','_'], ' ', (string) $status));

$qr_data = urlencode(get_permalink($post_id));
$qr_url  = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . $qr_data;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo esc_html(get_the_title() . ' — ' . get_bloginfo('name')); ?></title>
<style>
    @page { size: A4; margin: 6mm; }
    * { box-sizing: border-box; }
    html, body {
        margin: 0; padding: 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
        color: #111;
        background: #fff;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        font-size: 10px;
    }
    .sheet {
        width: 198mm;        /* A4 210mm - 2x6mm margin */
        max-width: 198mm;
        margin: 0 auto;
        padding: 0;
        display: flex;
        flex-direction: column;
    }
    @media print {
        html, body { width: 198mm; }
        .sheet { height: 285mm; overflow: hidden; page-break-after: avoid; page-break-inside: avoid; } /* A4 297mm - 2x6mm */
        .sheet > * { page-break-inside: avoid; }
    }
    .print-toolbar {
        position: sticky; top: 0;
        background: #001d3d; color: #fff;
        padding: 12px 20px;
        display: flex; justify-content: space-between; align-items: center;
        z-index: 10;
    }
    .print-toolbar button, .print-toolbar a {
        background: #fff; color: #001d3d;
        border: 0; border-radius: 6px;
        padding: 8px 16px; font-weight: 600;
        cursor: pointer; text-decoration: none; font-size: 14px;
        font-family: inherit;
    }
    .print-toolbar .close-link { background: transparent; color: #fff; text-decoration: underline; }
    @media print { .print-toolbar { display: none; } }

    /* Brand banner */
    .ph-banner {
        background: #001d3d;
        padding: 11px 18px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .ph-banner img { height: 30px; width: auto; display: block; }
    .ph-banner .tagline {
        color: #fff; font-size: 10px;
        text-transform: uppercase; letter-spacing: 2.5px;
        opacity: 0.85;
        font-weight: 500;
    }

    /* Header */
    .ph {
        border-bottom: 2px solid #001d3d;
        padding: 12px 18px 10px;
        margin-bottom: 10px;
        display: flex; justify-content: space-between; align-items: flex-start;
    }
    .ph-left { flex: 1; min-width: 0; }
    .ph-title {
        font-size: 22px; font-weight: 800;
        color: #0b0f19; margin: 0 0 5px; line-height: 1.15;
        letter-spacing: -0.2px;
    }
    .ph-address {
        font-size: 12px; color: #555;
        margin: 0;
    }
    .ph-right { text-align: right; padding-left: 18px; white-space: nowrap; }
    .ph-price {
        font-size: 26px; font-weight: 800;
        color: #001d3d; line-height: 1;
        letter-spacing: -0.3px;
    }
    .ph-status {
        display: inline-block;
        background: #001d3d; color: #fff;
        padding: 4px 11px; border-radius: 4px;
        font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.7px;
        margin-top: 6px;
    }
    .ph-ref { font-size: 10px; color: #888; margin-top: 5px; letter-spacing: 0.3px; }

    /* Main image */
    .p-main-img {
        width: calc(100% - 36px);
        margin: 0 18px 6px;
        height: 82mm;
        object-fit: cover;
        border-radius: 4px;
        display: block;
    }

    /* Grid */
    .p-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 5px;
        margin: 0 18px 11px;
    }
    .p-grid img {
        width: 100%; height: 26mm;
        object-fit: cover; border-radius: 4px;
        display: block;
    }

    /* Stats row */
    .p-stats {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0;
        margin: 0 18px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 5px;
        padding: 10px 6px;
        background: #f9fafb;
    }
    .p-stat { text-align: center; border-right: 1px solid #e5e7eb; padding: 0 6px; }
    .p-stat:last-child { border-right: 0; }
    .p-stat .v { font-size: 16px; font-weight: 800; color: #001d3d; line-height: 1.1; }
    .p-stat .l { font-size: 9px; color: #666; text-transform: uppercase; letter-spacing: 0.6px; margin-top: 4px; font-weight: 600; }

    /* Body */
    .p-body {
        display: grid;
        grid-template-columns: 1fr 58mm;
        gap: 14px;
        margin: 0 18px 10px;
        flex: 1;
    }
    .p-desc h3, .p-side h3 {
        font-size: 11px; text-transform: uppercase;
        letter-spacing: 1.2px; color: #001d3d;
        margin: 0 0 6px;
        border-bottom: 1.5px solid #001d3d;
        padding-bottom: 4px;
        font-weight: 700;
    }
    .p-desc p {
        font-size: 10.5px; line-height: 1.55; color: #333;
        margin: 0 0 6px;
        text-align: justify;
    }
    .p-features {
        columns: 2; column-gap: 16px;
        font-size: 10px; color: #333;
        margin: 8px 0 0;
        padding: 0; list-style: none;
    }
    .p-features li { margin-bottom: 3px; break-inside: avoid; line-height: 1.35; }
    .p-features li::before { content: "✓ "; color: #10b981; font-weight: 700; }

    /* Sidebar */
    .p-side { font-size: 10px; }
    .p-agent {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 5px;
        padding: 12px 10px;
        margin-bottom: 10px;
        text-align: center;
    }
    .p-agent img {
        width: 62px; height: 62px;
        border-radius: 50%; object-fit: cover;
        margin: 0 auto 6px; display: block;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px #e5e7eb;
    }
    .p-agent .name { font-size: 12px; font-weight: 700; color: #111; }
    .p-agent .title { font-size: 9px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; margin: 2px 0 6px; font-weight: 600; }
    .p-agent .phone { font-size: 12px; color: #001d3d; font-weight: 700; margin-top: 3px; }
    .p-agent .email { font-size: 9.5px; color: #555; word-break: break-all; margin-top: 2px; }

    .p-qr {
        text-align: center;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 5px;
        padding: 10px;
    }
    .p-qr img { width: 90px; height: 90px; display: block; margin: 0 auto 5px; }
    .p-qr .cap { font-size: 9px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }

    .p-footer {
        margin-top: auto;
        padding: 11px 18px;
        background: #001d3d;
        color: #fff;
        text-align: center;
        font-size: 11px;
        display: flex; justify-content: space-between; align-items: center;
        gap: 16px;
    }
    .p-footer img { height: 24px; width: auto; }
    .p-footer .contact { flex: 1; text-align: center; opacity: 0.92; letter-spacing: 0.4px; }
    .p-footer .spacer { width: 48px; }
</style>
</head>
<body>
<div class="sheet">

<div class="print-toolbar">
    <strong>Print preview — <?php echo esc_html(get_the_title()); ?></strong>
    <div>
        <button onclick="window.print()">🖨 Print</button>
        <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="close-link">← Back to listing</a>
    </div>
</div>

<div class="ph-banner">
    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo-white.svg'); ?>" alt="Spaniabolig">
    <div class="tagline">Real Estate · Costa Blanca</div>
</div>

<header class="ph">
    <div class="ph-left">
        <h1 class="ph-title"><?php echo esc_html(get_the_title()); ?></h1>
        <?php if ($address || $city) : ?>
            <p class="ph-address">📍 <?php echo esc_html($address ?: $city); ?></p>
        <?php endif; ?>
    </div>
    <div class="ph-right">
        <?php if ($price) : ?>
            <div class="ph-price"><?php echo esc_html(sb_format_price($price)); ?></div>
        <?php endif; ?>
        <?php if ($status_label) : ?>
            <div class="ph-status"><?php echo esc_html($status_label); ?></div>
        <?php endif; ?>
        <?php if ($ref) : ?>
            <div class="ph-ref">Ref: <?php echo esc_html($ref); ?></div>
        <?php endif; ?>
    </div>
</header>

<?php if ($main_img) : ?>
    <img src="<?php echo esc_url($main_img); ?>" alt="" class="p-main-img">
<?php endif; ?>

<?php if (!empty($grid_imgs)) : ?>
<div class="p-grid">
    <?php foreach ($grid_imgs as $img) : ?>
        <img src="<?php echo esc_url($img); ?>" alt="">
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="p-stats">
    <?php if ($type_label) : ?>
        <div class="p-stat"><div class="v"><?php echo esc_html($type_label); ?></div><div class="l">Type</div></div>
    <?php endif; ?>
    <?php if ($bedrooms) : ?>
        <div class="p-stat"><div class="v"><?php echo esc_html($bedrooms); ?></div><div class="l">Bedrooms</div></div>
    <?php endif; ?>
    <?php if ($bathrooms) : ?>
        <div class="p-stat"><div class="v"><?php echo esc_html($bathrooms); ?></div><div class="l">Bathrooms</div></div>
    <?php endif; ?>
    <?php if ($size) : ?>
        <div class="p-stat"><div class="v"><?php echo esc_html($size); ?> m²</div><div class="l">Size</div></div>
    <?php endif; ?>
    <?php if ($build_type) : ?>
        <div class="p-stat"><div class="v"><?php echo esc_html(ucwords(str_replace('_', ' ', $build_type))); ?></div><div class="l">Build</div></div>
    <?php endif; ?>
</div>

<div class="p-body">
    <div class="p-desc">
        <h3>Description</h3>
        <?php
        $content = apply_filters('the_content', get_the_content());
        $plain = wp_strip_all_tags($content);
        $plain = trim(preg_replace('/\s+/', ' ', $plain));
        $limit = 1200;
        if (strlen($plain) > $limit) {
            $plain = substr($plain, 0, $limit);
            $dot = strrpos($plain, '. ');
            if ($dot !== false) $plain = substr($plain, 0, $dot + 1);
            $plain .= '…';
        }
        echo '<p>' . esc_html($plain) . '</p>';
        ?>

        <?php if (!empty($features)) : ?>
            <h3 style="margin-top:10px;">Features</h3>
            <ul class="p-features">
                <?php foreach (array_slice($features, 0, 10) as $feat) : ?>
                    <li><?php echo esc_html($feat); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <aside class="p-side">
        <h3>Contact Agent</h3>
        <div class="p-agent">
            <?php if (!empty($agent['photo_url'])) : ?>
                <img src="<?php echo esc_url($agent['photo_url']); ?>" alt="">
            <?php endif; ?>
            <div class="name"><?php echo esc_html($agent['name'] ?? 'Spaniabolig'); ?></div>
            <?php if (!empty($agent['title'])) : ?>
                <div class="title"><?php echo esc_html($agent['title']); ?></div>
            <?php endif; ?>
            <?php if (!empty($agent['phone'])) : ?>
                <div class="phone">📞 <?php echo esc_html($agent['phone']); ?></div>
            <?php endif; ?>
            <?php if (!empty($agent['email'])) : ?>
                <div class="email"><?php echo esc_html($agent['email']); ?></div>
            <?php endif; ?>
        </div>

        <div class="p-qr">
            <img src="<?php echo esc_url($qr_url); ?>" alt="QR code">
            <div class="cap">Scan to view online</div>
        </div>
    </aside>
</div>

<footer class="p-footer">
    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo-white.svg'); ?>" alt="Spaniabolig">
    <div class="contact">spaniabolig.no &nbsp;·&nbsp; +34 696 039 621 &nbsp;·&nbsp; post@spaniabolig.no</div>
    <div class="spacer"></div>
</footer>

</div>
<script>
    // Auto-open print dialog on direct open
    if (!document.referrer || !document.referrer.includes(window.location.hostname)) {
        // Only auto-print if arrived directly
    }
</script>
</body>
</html>
