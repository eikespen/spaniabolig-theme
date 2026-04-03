<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4E91TR6TFB"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-4E91TR6TFB');
    </script>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
    <style>.site-header{background:#001d3d!important;border-bottom:1px solid rgba(255,255,255,.1)!important}.header-inner{height:76px!important}.nav-list li a{color:rgba(255,255,255,.85)!important}.nav-list li a:hover{background:rgba(255,255,255,.1)!important;color:#fff!important}.btn-header{background:transparent!important;color:#fff!important;border:2px solid rgba(255,255,255,.6)!important;padding:9px 22px!important;border-radius:100px!important;font-size:14px!important;font-weight:600!important;text-decoration:none!important}.btn-header:hover{background:#fff!important;color:#001d3d!important}</style>
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
                    <ul class="dropdown dropdown--rich">
                        <li><a href="<?php echo esc_url(home_url('/properties/?property_type=villa')); ?>" class="dropdown-item">
                            <span class="dropdown-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
                            <span class="dropdown-text"><strong>Villas</strong><span>Explore standalone villas with private pools</span></span>
                        </a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?property_type=apartment')); ?>" class="dropdown-item">
                            <span class="dropdown-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="18" rx="2"/><path d="M9 3v18M15 3v18M2 9h20M2 15h20"/></svg></span>
                            <span class="dropdown-text"><strong>Apartments</strong><span>Browse apartments with fantastic views</span></span>
                        </a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?property_type=townhouse')); ?>" class="dropdown-item">
                            <span class="dropdown-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg></span>
                            <span class="dropdown-text"><strong>Townhouses</strong><span>Community living with exclusive amenities</span></span>
                        </a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?build_type=new_build')); ?>" class="dropdown-item">
                            <span class="dropdown-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg></span>
                            <span class="dropdown-text"><strong>New builds</strong><span>Brand new properties with modern features</span></span>
                        </a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?build_type=resale')); ?>" class="dropdown-item">
                            <span class="dropdown-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg></span>
                            <span class="dropdown-text"><strong>Resale</strong><span>Resale properties ready to move in</span></span>
                        </a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?features=pool')); ?>" class="dropdown-item">
                            <span class="dropdown-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
                            <span class="dropdown-text"><strong>Properties with pool</strong><span>Find your perfect home with a swimming pool</span></span>
                        </a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties/?featured=1')); ?>" class="dropdown-item">
                            <span class="dropdown-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></span>
                            <span class="dropdown-text"><strong>Featured properties</strong><span>Our exclusive hand-picked selection</span></span>
                        </a></li>
                        <li><a href="<?php echo esc_url(home_url('/properties')); ?>" class="dropdown-item">
                            <span class="dropdown-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></span>
                            <span class="dropdown-text"><strong>Investment properties</strong><span>High-yield rental investment opportunities</span></span>
                        </a></li>
                    </ul>
                </li>
                <li <?php if (is_page('about')) echo 'class="current"'; ?>><a href="<?php echo esc_url(home_url('/about')); ?>">About</a></li>
                <li <?php if (is_page('how-it-works')) echo 'class="current"'; ?>><a href="<?php echo esc_url(home_url('/how-it-works')); ?>">How it works</a></li>
                <li <?php if (is_page('services')) echo 'class="current"'; ?>><a href="<?php echo esc_url(home_url('/services')); ?>">Services</a></li>
                <li <?php if (is_page('contact')) echo 'class="current"'; ?>><a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a></li>
                <?php
                $fav_page = get_page_by_path('favorites') ?: get_page_by_path('favourites');
                $fav_url  = $fav_page ? get_permalink($fav_page->ID) : home_url('/favorites');
                ?>
                <li <?php if (is_page(['favorites','favourites'])) echo 'class="current"'; ?>>
                    <a href="<?php echo esc_url($fav_url); ?>" class="nav-favorites">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        Favorites
                    </a>
                </li>
            </ul>
        </nav>

        <div class="header-actions">
            <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn-header">View properties</a>
        </div>

        <button class="nav-toggle" aria-label="Open menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
