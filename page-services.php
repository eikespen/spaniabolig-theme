<?php
/*
 * Template Name: Services
 */
get_header(); ?>

<section class="page-hero page-hero--dark">
    <div class="section-inner">
        <span class="page-hero-label"><?php echo esc_html(get_field('hero_label') ?: 'Property Management'); ?></span>
        <h1><?php echo esc_html(get_field('hero_title') ?: 'Services for property owners in Spain'); ?></h1>
        <p><?php echo esc_html(get_field('hero_subtitle') ?: 'Spaniabolig Real Estate offers a number of services to you who own a home in Spain. See below for more information and feel free to contact us if you have any questions.'); ?></p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>"><?php echo esc_html(get_field('hero_link_text') ?: 'Contact us today'); ?> &rarr;</a>
    </div>
</section>

<?php
$services_icons = [
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
];
$services_defaults = [
    ['title' => 'Meet &amp; Greet', 'desc' => 'We offer a first-class meet and greet service, ensuring a smooth key handover upon both arrival and departure. We make sure your property is clean, stocked with essentials, and ready for your arrival.'],
    ['title' => 'Key holding', 'desc' => 'It is always a good idea to have someone have access to the property in case something happens. We are happy to take care of your keys and look after your property year round.'],
    ['title' => 'Pool services / Pool boy', 'desc' => 'We have several teams that help you keep your pool in top quality so that it can be used all year round — either by yourself or by rental guests.'],
    ['title' => 'Cleaning services', 'desc' => 'We provide professional cleaning services for your Spanish property, whether for regular maintenance, after a rental period, or to prepare your home for your own stay.'],
    ['title' => 'Property photography', 'desc' => 'High-quality professional photography to showcase your property at its best — perfect for rental listings, sales, or simply capturing your Spanish home in beautiful light.'],
    ['title' => 'Maintenance &amp; repairs', 'desc' => 'From minor repairs to larger maintenance tasks, our trusted network of local tradespeople ensures your Spanish property is always well-maintained and in excellent condition.'],
];
$services = get_field('services') ?: $services_defaults;
?>
<section class="about-services">
    <div class="section-inner">
        <h2><?php echo esc_html(get_field('services_title') ?: 'Our services'); ?></h2>
        <p style="text-align:center;color:var(--grey-500);margin-top:-24px;margin-bottom:40px;font-size:15px;"><?php echo esc_html(get_field('services_subtitle') ?: "From key holding to professional photography — we take care of your Spanish property so you don't have to."); ?></p>
        <div class="services-grid">
            <?php foreach ($services as $i => $s) : ?>
            <div class="service-card service-card--icon">
                <div class="service-card-icon"><?php echo $services_icons[$i % count($services_icons)]; ?></div>
                <h3><?php echo $s['title']; ?></h3>
                <p><?php echo esc_html($s['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="section-inner">
        <h2><?php echo esc_html(get_field('cta_title') ?: 'Interested in our property services?'); ?></h2>
        <p><?php echo esc_html(get_field('cta_text') ?: 'Get in touch with our team to discuss how we can help look after your Spanish property.'); ?></p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-white btn-lg"><?php echo esc_html(get_field('cta_btn_text') ?: 'Contact us today'); ?></a>
    </div>
</section>

<?php get_footer(); ?>
