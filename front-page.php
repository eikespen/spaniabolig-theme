<?php
get_header();

$pid = get_the_ID();
function sb_fp($key, $default = '') {
    global $pid;
    $v = get_post_meta($pid, $key, true);
    return $v !== '' ? $v : $default;
}
?>

<!-- ── HERO ── -->
<section class="hero">
    <div class="hero-inner">
        <h1 class="hero-heading"><?php echo esc_html(sb_fp('sb_hero_heading', 'Find your dream property in Ciudad Quesada')); ?></h1>
        <p class="hero-subtitle"><?php echo esc_html(sb_fp('sb_hero_subtitle', 'Discover beautiful homes in Ciudad Quesada and the urbanizations of Rojales with properties to suit every lifestyle and budget.')); ?></p>

        <!-- Search Card -->
        <div class="search-card">
            <h2 class="search-card-title">Find Properties</h2>
            <form class="search-form" method="get" action="<?php echo esc_url(home_url('/properties')); ?>">
                <div class="search-grid">
                    <div class="search-field">
                        <label>Build Type</label>
                        <select name="build_type">
                            <option value="">All Properties</option>
                            <option value="resale">Resale</option>
                            <option value="new_build">New Build</option>
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Property Type</label>
                        <select name="property_type">
                            <option value="">All Types</option>
                            <?php
                            $types = get_terms(['taxonomy' => 'property_type', 'hide_empty' => false]);
                            foreach ($types as $t) echo '<option value="' . esc_attr($t->slug) . '">' . esc_html($t->name) . '</option>';
                            ?>
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Location</label>
                        <select name="location">
                            <option value="">All Locations</option>
                            <?php
                            global $wpdb;
                            $cities = $wpdb->get_col(
                                "SELECT DISTINCT meta_value FROM {$wpdb->postmeta}
                                 WHERE meta_key = 'sb_city' AND meta_value != ''
                                 ORDER BY meta_value ASC"
                            );
                            foreach ($cities as $city) echo '<option value="' . esc_attr($city) . '">' . esc_html($city) . '</option>';
                            ?>
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Min Price</label>
                        <select name="min_price">
                            <option value="">No Min</option>
                            <option value="50000">€50,000</option>
                            <option value="100000">€100,000</option>
                            <option value="150000">€150,000</option>
                            <option value="200000">€200,000</option>
                            <option value="300000">€300,000</option>
                            <option value="400000">€400,000</option>
                            <option value="500000">€500,000</option>
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Max Price</label>
                        <select name="max_price">
                            <option value="">No Max</option>
                            <option value="100000">€100,000</option>
                            <option value="150000">€150,000</option>
                            <option value="200000">€200,000</option>
                            <option value="300000">€300,000</option>
                            <option value="400000">€400,000</option>
                            <option value="500000">€500,000</option>
                            <option value="750000">€750,000</option>
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Bedrooms</label>
                        <select name="bedrooms">
                            <option value="">Any</option>
                            <option value="1">1+</option>
                            <option value="2">2+</option>
                            <option value="3">3+</option>
                            <option value="4">4+</option>
                            <option value="5">5+</option>
                        </select>
                    </div>
                    <div class="search-field">
                        <label>Bathrooms</label>
                        <select name="bathrooms">
                            <option value="">Any</option>
                            <option value="1">1+</option>
                            <option value="2">2+</option>
                            <option value="3">3+</option>
                        </select>
                    </div>
                </div>
                <div class="search-actions">
                    <button type="reset" class="btn btn-ghost btn-search-reset">Reset Filters</button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        Search Properties
                    </button>
                </div>
            </form>
        </div>
        <div class="search-cta-row">
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary btn-lg btn-pill">Contact an agent</a>
            <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-outline btn-lg btn-pill">View all properties</a>
        </div>
    </div>
</section>

<!-- ── PROPERTY TYPE QUICK LINKS ── -->
<section class="quick-links">
    <div class="section-inner">
        <div class="quick-links-grid">
            <a href="<?php echo esc_url(home_url('/properties/?property_type=villa')); ?>" class="quick-link">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Villas
            </a>
            <a href="<?php echo esc_url(home_url('/properties/?property_type=apartment')); ?>" class="quick-link">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="18" rx="2"/><path d="M9 3v18M15 3v18M2 9h20M2 15h20"/></svg>
                Apartments
            </a>
            <a href="<?php echo esc_url(home_url('/properties/?property_type=townhouse')); ?>" class="quick-link">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                Townhouses
            </a>
            <a href="<?php echo esc_url(home_url('/properties/?features=pool')); ?>" class="quick-link">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 12h20M2 12c1.333-1 2.667-1 4 0s2.667 1 4 0 2.667-1 4 0 2.667 1 4 0M2 18c1.333-1 2.667-1 4 0s2.667 1 4 0 2.667-1 4 0 2.667 1 4 0"/><path d="M6 12V7a2 2 0 0 1 2-2h3"/><circle cx="14" cy="4" r="2"/></svg>
                Properties with pool
            </a>
            <a href="<?php echo esc_url(home_url('/properties/?near_beach=true')); ?>" class="quick-link">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><path d="M2 21h20M12 3v4M19.07 7.93l-2.83 2.83M4.93 7.93l2.83 2.83"/></svg>
                Near beach
            </a>
            <a href="<?php echo esc_url(home_url('/urbanizations')); ?>" class="quick-link">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Urbanizations
            </a>
            <a href="<?php echo esc_url(home_url('/properties/?build_type=new_build')); ?>" class="quick-link">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                New build
            </a>
            <a href="<?php echo esc_url(home_url('/properties/?build_type=resale')); ?>" class="quick-link">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                Second hand
            </a>
            <a href="<?php echo esc_url(home_url('/properties')); ?>" class="quick-link quick-link--all">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                All properties
            </a>
        </div>
    </div>
</section>

<!-- ── EXCLUSIVE / FEATURED PROPERTIES ── -->
<?php
$featured_q = new WP_Query([
    'post_type'      => 'property',
    'posts_per_page' => 6,
    'meta_query'     => [
        ['key' => 'sb_featured', 'value' => '1'],
        [
            'relation' => 'OR',
            ['key' => 'sb_status', 'value' => 'sold', 'compare' => '!='],
            ['key' => 'sb_status', 'compare' => 'NOT EXISTS'],
        ],
    ],
]);
?>
<?php if ($featured_q->have_posts()) : ?>
<section class="featured-properties">
    <div class="section-inner">
        <div class="section-header">
            <h2>Exclusive Properties</h2>
            <a href="<?php echo esc_url(home_url('/properties/?featured=1')); ?>" class="view-all">
                View all
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
        <div class="property-grid">
            <?php while ($featured_q->have_posts()) : $featured_q->the_post(); ?>
                <?php get_template_part('template-parts/property-card'); ?>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── RESALE PROPERTIES ── -->
<?php
$resale_all = new WP_Query(['post_type' => 'property', 'posts_per_page' => 1, 'meta_query' => [['key' => 'sb_build_type', 'value' => 'resale']]]);
$resale_budget = new WP_Query(['post_type' => 'property', 'posts_per_page' => 1, 'meta_query' => [['key' => 'sb_build_type', 'value' => 'resale'], ['key' => 'sb_price', 'value' => 150000, 'compare' => '<', 'type' => 'NUMERIC']]]);
$resale_mid = new WP_Query(['post_type' => 'property', 'posts_per_page' => 1, 'meta_query' => [['key' => 'sb_build_type', 'value' => 'resale'], ['key' => 'sb_price', 'value' => [150000, 300000], 'compare' => 'BETWEEN', 'type' => 'NUMERIC']]]);
$resale_high = new WP_Query(['post_type' => 'property', 'posts_per_page' => 1, 'meta_query' => [['key' => 'sb_build_type', 'value' => 'resale'], ['key' => 'sb_price', 'value' => 300000, 'compare' => '>', 'type' => 'NUMERIC']]]);
?>

<?php if ($resale_all->found_posts > 0) : ?>
<section class="price-showcase">
    <div class="section-inner">
        <div class="showcase-header">
            <h2>Resale Properties</h2>
            <a href="<?php echo esc_url(home_url('/properties/?build_type=resale')); ?>" class="btn btn-outline-sm">View All Resales</a>
        </div>
        <div class="showcase-grid">
            <?php foreach ([
                ['query' => $resale_budget, 'label' => 'Budget-Friendly', 'badge' => 'Budget Pick', 'cta' => 'See more under €150,000', 'link' => '/properties/?build_type=resale&max_price=150000'],
                ['query' => $resale_mid,    'label' => 'Mid-Range',        'badge' => 'Popular Range', 'cta' => 'See more €150k-€300k', 'link' => '/properties/?build_type=resale&min_price=150000&max_price=300000'],
                ['query' => $resale_high,   'label' => 'High-Range',       'badge' => 'Premium',       'cta' => 'See more above €300k', 'link' => '/properties/?build_type=resale&min_price=300000'],
            ] as $tier) :
                if (!$tier['query']->have_posts()) continue;
                $tier['query']->the_post();
                $price = get_post_meta(get_the_ID(), 'sb_price', true);
                $beds  = get_post_meta(get_the_ID(), 'sb_bedrooms', true);
                $baths = get_post_meta(get_the_ID(), 'sb_bathrooms', true);
                $city  = get_post_meta(get_the_ID(), 'sb_city', true);
            ?>
            <div class="showcase-tier">
                <div class="tier-label"><?php echo esc_html($tier['label']); ?></div>
                <div class="tier-badge"><?php echo esc_html($tier['badge']); ?></div>
                <a href="<?php the_permalink(); ?>" class="tier-card">
                    <?php $timg = sb_get_image_url(get_the_ID()); ?>
                    <?php if ($timg) : ?>
                        <img src="<?php echo esc_url($timg); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="tier-img" loading="lazy">
                    <?php else : ?>
                        <div class="tier-img tier-img--placeholder"></div>
                    <?php endif; ?>
                    <div class="tier-info">
                        <h3><?php the_title(); ?></h3>
                        <?php if ($city) echo '<p class="tier-city">' . esc_html($city) . '</p>'; ?>
                        <?php if ($price) echo '<p class="tier-price">€' . number_format($price, 0, ',', ' ') . '</p>'; ?>
                        <div class="tier-meta">
                            <?php if ($beds) echo '<span>' . esc_html($beds) . ' bed</span>'; ?>
                            <?php if ($baths) echo '<span>' . esc_html($baths) . ' bath</span>'; ?>
                        </div>
                    </div>
                </a>
                <a href="<?php echo esc_url(home_url($tier['link'])); ?>" class="btn btn-outline-sm btn-block"><?php echo esc_html($tier['cta']); ?></a>
            </div>
            <?php wp_reset_postdata(); endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── NEW BUILD PROPERTIES ── -->
<?php
$nb_budget = new WP_Query(['post_type' => 'property', 'posts_per_page' => 1, 'meta_query' => [['key' => 'sb_build_type', 'value' => 'new_build'], ['key' => 'sb_price', 'value' => 300000, 'compare' => '<', 'type' => 'NUMERIC']]]);
$nb_mid    = new WP_Query(['post_type' => 'property', 'posts_per_page' => 1, 'meta_query' => [['key' => 'sb_build_type', 'value' => 'new_build'], ['key' => 'sb_price', 'value' => [300000, 500000], 'compare' => 'BETWEEN', 'type' => 'NUMERIC']]]);
$nb_high   = new WP_Query(['post_type' => 'property', 'posts_per_page' => 1, 'meta_query' => [['key' => 'sb_build_type', 'value' => 'new_build'], ['key' => 'sb_price', 'value' => 500000, 'compare' => '>', 'type' => 'NUMERIC']]]);
$nb_any    = new WP_Query(['post_type' => 'property', 'posts_per_page' => 1, 'meta_query' => [['key' => 'sb_build_type', 'value' => 'new_build']]]);
?>

<?php if ($nb_any->found_posts > 0) : ?>
<section class="price-showcase price-showcase--newbuild">
    <div class="section-inner">
        <div class="showcase-header">
            <h2>New Build Properties</h2>
            <a href="<?php echo esc_url(home_url('/properties/?build_type=new_build')); ?>" class="btn btn-outline-sm">View All New Builds</a>
        </div>
        <div class="showcase-grid">
            <?php foreach ([
                ['query' => $nb_budget, 'label' => 'Budget-Friendly', 'badge' => 'Budget New Build', 'cta' => 'See more under €300,000', 'link' => '/properties/?build_type=new&max_price=300000'],
                ['query' => $nb_mid,    'label' => 'Mid-Range',        'badge' => 'Popular New Build', 'cta' => 'See more €300k-€500k',  'link' => '/properties/?build_type=new&min_price=300000&max_price=500000'],
                ['query' => $nb_high,   'label' => 'High-Range',       'badge' => 'Premium New Build', 'cta' => 'See more above €500k',   'link' => '/properties/?build_type=new&min_price=500000'],
            ] as $tier) :
                if (!$tier['query']->have_posts()) continue;
                $tier['query']->the_post();
                $price = get_post_meta(get_the_ID(), 'sb_price', true);
                $beds  = get_post_meta(get_the_ID(), 'sb_bedrooms', true);
                $baths = get_post_meta(get_the_ID(), 'sb_bathrooms', true);
                $city  = get_post_meta(get_the_ID(), 'sb_city', true);
            ?>
            <div class="showcase-tier">
                <div class="tier-label"><?php echo esc_html($tier['label']); ?></div>
                <div class="tier-badge"><?php echo esc_html($tier['badge']); ?></div>
                <a href="<?php the_permalink(); ?>" class="tier-card">
                    <?php $timg = sb_get_image_url(get_the_ID()); ?>
                    <?php if ($timg) : ?>
                        <img src="<?php echo esc_url($timg); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="tier-img" loading="lazy">
                    <?php else : ?>
                        <div class="tier-img tier-img--placeholder"></div>
                    <?php endif; ?>
                    <div class="tier-info">
                        <h3><?php the_title(); ?></h3>
                        <?php if ($city) echo '<p class="tier-city">' . esc_html($city) . '</p>'; ?>
                        <?php if ($price) echo '<p class="tier-price">€' . number_format($price, 0, ',', ' ') . '</p>'; ?>
                        <div class="tier-meta">
                            <?php if ($beds) echo '<span>' . esc_html($beds) . ' bed</span>'; ?>
                            <?php if ($baths) echo '<span>' . esc_html($baths) . ' bath</span>'; ?>
                        </div>
                    </div>
                </a>
                <a href="<?php echo esc_url(home_url($tier['link'])); ?>" class="btn btn-outline-sm btn-block"><?php echo esc_html($tier['cta']); ?></a>
            </div>
            <?php wp_reset_postdata(); endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── LIFESTYLE ── -->
<?php
$lifestyle_defaults = [
    ['Prime location',          'Ciudad Quesada offers a perfect blend of Spanish charm and modern amenities, just 10 minutes from Mediterranean beaches.'],
    ['International community', 'Join a thriving international community with residents from across Europe enjoying a relaxed Mediterranean lifestyle.'],
    ['320+ days of sunshine',   'Enjoy the famous climate of Ciudad Quesada with over 320 days of sunshine per year, perfect for outdoor living and golf.'],
    ['Expert local support',    'Our team of local property experts provides personalized assistance throughout your entire property buying journey.'],
];
$lifestyle_icons = [
    '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
    '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>',
    '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
];
$lifestyle_cards = [];
for ($i = 1; $i <= 4; $i++) {
    $def = $lifestyle_defaults[$i - 1];
    $lifestyle_cards[] = [
        'card_title' => sb_fp("sb_lifestyle_card{$i}_title", $def[0]),
        'card_desc'  => sb_fp("sb_lifestyle_card{$i}_desc",  $def[1]),
    ];
}
?>
<section class="lifestyle">
    <div class="section-inner">
        <h2><?php echo esc_html(sb_fp('sb_lifestyle_title', 'The Ciudad Quesada lifestyle')); ?></h2>
        <div class="lifestyle-grid">
            <?php foreach ($lifestyle_cards as $i => $card) : ?>
            <div class="lifestyle-card">
                <div class="lifestyle-icon">
                    <?php echo $lifestyle_icons[$i % count($lifestyle_icons)]; ?>
                </div>
                <h3><?php echo esc_html($card['card_title']); ?></h3>
                <p><?php echo esc_html($card['card_desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── WHY BUY ── -->
<?php
$why_col1_defs = [
    'Established residential area with excellent infrastructure',
    'Close to beaches, golf courses, and nature reserves',
    'International community with shops, restaurants, and services',
    '',
];
$why_col2_defs = [
    'Competitive property prices with good appreciation potential',
    'Strong rental demand throughout the year',
    'Lower cost of living compared to Northern European countries',
    '',
];
$why_col1_items = [];
$why_col2_items = [];
for ($i = 1; $i <= 4; $i++) {
    $v1 = sb_fp("sb_why_col1_item{$i}", $why_col1_defs[$i - 1]);
    $v2 = sb_fp("sb_why_col2_item{$i}", $why_col2_defs[$i - 1]);
    if ($v1) $why_col1_items[] = $v1;
    if ($v2) $why_col2_items[] = $v2;
}
?>
<section class="why-buy">
    <div class="section-inner">
        <h2><?php echo esc_html(sb_fp('sb_why_title', 'Why buy in Ciudad Quesada')); ?></h2>
        <div class="why-buy-grid">
            <div class="why-buy-col">
                <h3><?php echo esc_html(sb_fp('sb_why_col1_heading', 'Perfect Mediterranean lifestyle')); ?></h3>
                <p><?php echo esc_html(sb_fp('sb_why_col1_text', "Ciudad Quesada is a popular residential area in Costa Blanca, offering a perfect blend of Spanish lifestyle with international amenities. With over 320 days of sunshine per year, it's an ideal location for those seeking a relaxed Mediterranean way of life.")); ?></p>
                <ul class="check-list">
                    <?php foreach ($why_col1_items as $item) : ?>
                    <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="why-buy-col">
                <h3><?php echo esc_html(sb_fp('sb_why_col2_heading', 'Excellent investment opportunity')); ?></h3>
                <p><?php echo esc_html(sb_fp('sb_why_col2_text', 'Ciudad Quesada offers exceptional value for money compared to other Mediterranean destinations, with a stable property market and strong rental potential.')); ?></p>
                <ul class="check-list">
                    <?php foreach ($why_col2_items as $item) : ?>
                    <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-primary btn-pill" style="margin-top:24px;"><?php echo esc_html(sb_fp('sb_why_col2_btn_text', 'Browse Ciudad Quesada properties')); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ── FAQ ── -->
<?php
$faq_defaults = [
    ['Why buy property in Ciudad Quesada?', 'Ciudad Quesada offers the perfect blend of Spanish lifestyle with international amenities. It features year-round sunshine, beautiful beaches just minutes away, championship golf courses, extensive restaurant options, and a thriving international community. Properties here typically offer excellent value compared to other Mediterranean coastal areas.'],
    ['What types of properties are available in Ciudad Quesada?', 'Ciudad Quesada offers a diverse range of properties including modern villas with private pools, contemporary apartments with community facilities, traditional Spanish townhouses, new build developments with the latest features, and investment properties with strong rental potential.'],
    ['What is the buying process for foreigners in Spain?', 'The buying process in Spain typically involves: obtaining an NIE (foreigner identification number), opening a Spanish bank account, making an offer and signing a reservation agreement with a deposit (usually €3,000–€6,000), signing the private purchase contract with a larger deposit (typically 10%), and completing the final purchase at the notary.'],
    ['What additional costs should I budget for when buying property in Spain?', 'When purchasing property in Spain, additional costs typically total 10–13% of the purchase price. These include: transfer tax (10% for resale properties) or VAT (10% for new builds), notary fees (0.5–1%), property registry fees (0.5%), legal fees (1–2%), and potentially mortgage arrangement fees if financing.'],
];
$faqs = [];
for ($i = 1; $i <= 4; $i++) {
    $def = $faq_defaults[$i - 1];
    $q = sb_fp("sb_faq{$i}_question", $def[0]);
    $a = sb_fp("sb_faq{$i}_answer",   $def[1]);
    if ($q && $a) $faqs[] = ['question' => $q, 'answer' => $a];
}
?>
<section class="faq">
    <div class="section-inner">
        <h2><?php echo esc_html(sb_fp('sb_faq_title', 'Frequently asked questions')); ?></h2>
        <div class="faq-list">
            <?php foreach ($faqs as $i => $faq) : ?>
            <details class="faq-item" <?php if ($i === 0) echo 'open'; ?>>
                <summary><?php echo esc_html($faq['question']); ?></summary>
                <div class="faq-answer"><p><?php echo esc_html($faq['answer']); ?></p></div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── CTA BANNER ── -->
<section class="cta-banner">
    <div class="section-inner">
        <h2><?php echo esc_html(sb_fp('sb_cta_title', 'Ready to find your dream property in Ciudad Quesada?')); ?></h2>
        <p><?php echo esc_html(sb_fp('sb_cta_text', 'Our local property experts can help you find the perfect home that matches your requirements and budget in Ciudad Quesada and the urbanizations of Rojales.')); ?></p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-white btn-lg"><?php echo esc_html(sb_fp('sb_cta_btn_text', 'Schedule a property viewing')); ?></a>
    </div>
</section>

<?php get_footer(); ?>
