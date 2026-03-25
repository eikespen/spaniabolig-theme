<?php
/*
 * Template Name: How It Works
 */
get_header();

$pid = get_the_ID();
function sb_hiw($key, $default = '') {
    global $pid;
    $v = get_post_meta($pid, $key, true);
    return $v !== '' ? $v : $default;
}
?>

<section class="page-hero">
    <div class="section-inner--narrow">
        <h1><?php echo esc_html(sb_hiw('sb_hero_title', 'How it works')); ?></h1>
        <p><?php echo esc_html(sb_hiw('sb_hero_subtitle', 'Finding your perfect property in Ciudad Quesada is easy with our step-by-step process.')); ?></p>
    </div>
</section>

<?php
$steps_icons = [
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
];
$step_defs = [
    ['Step 1', 'Search for your ideal property',             'Use our advanced property search features to find properties that match your requirements. Filter by property type, location, price range, and specific features to narrow down your options in Ciudad Quesada.'],
    ['Step 2', 'Browse detailed property listings',          'View comprehensive information about each property including detailed descriptions, high-quality photos, floor plans, and key features. Our property listings provide all the details you need to make an informed decision.'],
    ['Step 3', 'Contact us about your favourite properties', 'When you find properties that interest you, contact our team directly to learn more, arrange viewings, or get expert advice about the local market and buying process in Ciudad Quesada.'],
    ['Step 4', 'We help you through the buying process',     'Our experienced team guides you through every step of the property purchase process in Spain, from making an offer and legal checks to signing at the notary. We connect you with trusted local lawyers, NIE assistance, and mortgage specialists.'],
];
$steps = [];
for ($i = 1; $i <= 4; $i++) {
    $def = $step_defs[$i - 1];
    $steps[] = [
        'step_label' => sb_hiw("sb_step{$i}_label", $def[0]),
        'step_title' => sb_hiw("sb_step{$i}_title", $def[1]),
        'step_desc'  => sb_hiw("sb_step{$i}_desc",  $def[2]),
    ];
}
?>
<section class="how-it-works-steps">
    <div class="section-inner--narrow">
        <div class="steps-stack">
            <?php foreach ($steps as $i => $step) : ?>
            <div class="step-card">
                <div class="step-icon">
                    <?php echo $steps_icons[$i % count($steps_icons)]; ?>
                </div>
                <div>
                    <p class="step-label"><?php echo esc_html($step['step_label']); ?></p>
                    <h3><?php echo esc_html($step['step_title']); ?></h3>
                    <p><?php echo esc_html($step['step_desc']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
$included_defs = [
    ['Property search assistance', 'We help you identify properties that match your exact requirements, budget, and preferred location within Ciudad Quesada and Rojales.'],
    ['Viewing arrangements',       'We organise and accompany you on property viewings, whether in person or via video call, with full local expert commentary.'],
    ['Legal guidance',             'We connect you with trusted English-speaking Spanish lawyers who handle contracts, NIE numbers, and all legal aspects of your purchase.'],
    ['Mortgage assistance',        'Our network of mortgage brokers specialise in helping foreign buyers secure competitive Spanish mortgage rates.'],
];
$included_items = [];
for ($i = 1; $i <= 4; $i++) {
    $def = $included_defs[$i - 1];
    $included_items[] = [
        'title' => sb_hiw("sb_included{$i}_title", $def[0]),
        'desc'  => sb_hiw("sb_included{$i}_desc",  $def[1]),
    ];
}
?>
<section class="about-services">
    <div class="section-inner--narrow">
        <h2><?php echo esc_html(sb_hiw('sb_included_title', "What's included in our service")); ?></h2>
        <div class="services-grid">
            <?php foreach ($included_items as $item) : ?>
            <div class="service-card">
                <h3><?php echo esc_html($item['title']); ?></h3>
                <p><?php echo esc_html($item['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="section-inner--narrow">
        <h2><?php echo esc_html(sb_hiw('sb_cta_title', 'Ready to start your property search?')); ?></h2>
        <p><?php echo esc_html(sb_hiw('sb_cta_text', 'Browse our current listings or get in touch with our local team to discuss your requirements.')); ?></p>
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-white btn-lg"><?php echo esc_html(sb_hiw('sb_cta_btn1_text', 'Browse properties')); ?></a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-lg" style="background:transparent;color:#fff;border:2px solid rgba(255,255,255,.5);"><?php echo esc_html(sb_hiw('sb_cta_btn2_text', 'Contact us')); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
