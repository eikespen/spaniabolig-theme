<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-inner">
        <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>">
            <?php
            if (has_custom_logo()) {
                the_custom_logo();
            } else {
                echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/img/logo-white.svg') . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="logo-img" width="180" height="48">';
            }
            ?>
        </a>

        <nav class="site-nav" aria-label="Primary">
            <ul class="nav-list">
                <li <?php if (is_front_page()) echo 'class="current"'; ?>><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                <li class="has-dropdown <?php if (is_post_type_archive('property') || is_singular('property')) echo 'current'; ?>">
                    <a href="<?php echo esc_url(home_url('/properties')); ?>">Properties <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg></a>
                    <ul class="dropdown">
                        <li><a href="<?php echo esc_url(home_url('/properties')); ?>">All Properties</a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?build_type=resale')); ?>">Resale</a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?build_type=new')); ?>">New Build</a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?property_type=villa')); ?>">Villas</a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?property_type=apartment')); ?>">Apartments</a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?property_type=townhouse')); ?>">Townhouses</a></li>
                    </ul>
                </li>
                <li <?php if (is_page('about')) echo 'class="current"'; ?>><a href="<?php echo esc_url(home_url('/about')); ?>">About</a></li>
                <li <?php if (is_page('how-it-works')) echo 'class="current"'; ?>><a href="<?php echo esc_url(home_url('/how-it-works')); ?>">How it works</a></li>
                <li <?php if (is_page('services')) echo 'class="current"'; ?>><a href="<?php echo esc_url(home_url('/services')); ?>">Services</a></li>
                <li <?php if (is_page('contact')) echo 'class="current"'; ?>><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
            </ul>
        </nav>

        <div class="header-actions">
            <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-header">View properties</a>
        </div>

        <button class="nav-toggle" aria-label="Open menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
