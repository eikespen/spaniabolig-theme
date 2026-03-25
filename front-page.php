<?php get_header(); ?>

<!-- ── HERO ── -->
<section class="hero">
    <div class="hero-inner">
        <h1 class="hero-heading">Find your dream property in Ciudad Quesada</h1>
        <p class="hero-subtitle">Discover beautiful homes in Ciudad Quesada and the urbanizations of Rojales with properties to suit every lifestyle and budget.</p>

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
                            <option value="new">New Build</option>
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
                        <label>Urbanization</label>
                        <select name="urbanization">
                            <option value="">All Urbanizations</option>
                            <?php
                            $urbs = get_terms(['taxonomy' => 'urbanization', 'hide_empty' => false]);
                            foreach ($urbs as $u) echo '<option value="' . esc_attr($u->slug) . '">' . esc_html($u->name) . '</option>';
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
            <div class="search-cta-row">
                <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-primary btn-pill">Browse properties</a>
                <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-outline btn-pill">View all properties</a>
            </div>
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
            <a href="<?php echo esc_url(home_url('/properties/?build_type=new')); ?>" class="quick-link">
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
            <a href="<?php echo esc_url(home_url('/properties/?build_type=new')); ?>" class="btn btn-outline-sm">View All New Builds</a>
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
<section class="lifestyle">
    <div class="section-inner">
        <h2>The Ciudad Quesada lifestyle</h2>
        <div class="lifestyle-grid">
            <div class="lifestyle-card">
                <div class="lifestyle-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <h3>Prime location</h3>
                <p>Ciudad Quesada offers a perfect blend of Spanish charm and modern amenities, just 10 minutes from Mediterranean beaches.</p>
            </div>
            <div class="lifestyle-card">
                <div class="lifestyle-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3>International community</h3>
                <p>Join a thriving international community with residents from across Europe enjoying a relaxed Mediterranean lifestyle.</p>
            </div>
            <div class="lifestyle-card">
                <div class="lifestyle-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                </div>
                <h3>320+ days of sunshine</h3>
                <p>Enjoy the famous climate of Ciudad Quesada with over 320 days of sunshine per year, perfect for outdoor living and golf.</p>
            </div>
            <div class="lifestyle-card">
                <div class="lifestyle-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <h3>Expert local support</h3>
                <p>Our team of local property experts provides personalized assistance throughout your entire property buying journey.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── WHY BUY ── -->
<section class="why-buy">
    <div class="section-inner">
        <h2>Why buy in Ciudad Quesada</h2>
        <div class="why-buy-grid">
            <div class="why-buy-col">
                <h3>Perfect Mediterranean lifestyle</h3>
                <p>Ciudad Quesada is a popular residential area in Costa Blanca, offering a perfect blend of Spanish lifestyle with international amenities. With over 320 days of sunshine per year, it's an ideal location for those seeking a relaxed Mediterranean way of life.</p>
                <ul class="check-list">
                    <li>Established residential area with excellent infrastructure</li>
                    <li>Close to beaches, golf courses, and nature reserves</li>
                    <li>International community with shops, restaurants, and services</li>
                </ul>
            </div>
            <div class="why-buy-col">
                <h3>Excellent investment opportunity</h3>
                <p>Ciudad Quesada offers exceptional value for money compared to other Mediterranean destinations, with a stable property market and strong rental potential.</p>
                <ul class="check-list">
                    <li>Competitive property prices with good appreciation potential</li>
                    <li>Strong rental demand throughout the year</li>
                    <li>Lower cost of living compared to Northern European countries</li>
                </ul>
                <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-primary btn-pill" style="margin-top:24px;">Browse Ciudad Quesada properties</a>
            </div>
        </div>
    </div>
</section>

<!-- ── FAQ ── -->
<section class="faq">
    <div class="section-inner">
        <h2>Frequently asked questions</h2>
        <div class="faq-list">
            <?php
            $faqs = [
                ['q' => 'Why buy property in Ciudad Quesada?', 'a' => 'Ciudad Quesada offers the perfect blend of Spanish lifestyle with international amenities. It features year-round sunshine, beautiful beaches just minutes away, championship golf courses, extensive restaurant options, and a thriving international community. Properties here typically offer excellent value compared to other Mediterranean coastal areas.'],
                ['q' => 'What types of properties are available in Ciudad Quesada?', 'a' => 'Ciudad Quesada offers a diverse range of properties including modern villas with private pools, contemporary apartments with community facilities, traditional Spanish townhouses, new build developments with the latest features, and investment properties with strong rental potential. Properties range from affordable apartments starting around €100,000 to luxury villas exceeding €500,000.'],
                ['q' => 'What is the buying process for foreigners in Spain?', 'a' => 'The buying process in Spain typically involves: obtaining an NIE (foreigner identification number), opening a Spanish bank account, making an offer and signing a reservation agreement with a deposit (usually €3,000–€6,000), signing the private purchase contract with a larger deposit (typically 10%), and completing the final purchase at the notary. Our team guides you through each step and connects you with trusted legal advisors.'],
                ['q' => 'What additional costs should I budget for when buying property in Spain?', 'a' => 'When purchasing property in Spain, additional costs typically total 10–13% of the purchase price. These include: transfer tax (10% for resale properties) or VAT (10% for new builds), notary fees (0.5–1%), property registry fees (0.5%), legal fees (1–2%), and potentially mortgage arrangement fees if financing. Our team provides detailed cost breakdowns specific to your purchase.'],
                ['q' => 'Can I get financing to buy property in Ciudad Quesada?', 'a' => 'Yes, foreign buyers can obtain mortgages for properties in Ciudad Quesada. Spanish banks typically offer 60–70% financing for non-residents (up to 80% for residents). Our property team works with mortgage specialists who can secure competitive rates and guide you through the application process. We recommend getting pre-approved before serious property hunting to understand your budget.'],
            ];
            foreach ($faqs as $i => $faq) : ?>
            <details class="faq-item" <?php if ($i === 0) echo 'open'; ?>>
                <summary><?php echo esc_html($faq['q']); ?></summary>
                <div class="faq-answer"><p><?php echo esc_html($faq['a']); ?></p></div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── CTA BANNER ── -->
<section class="cta-banner">
    <div class="section-inner">
        <h2>Ready to find your dream property in Ciudad Quesada?</h2>
        <p>Our local property experts can help you find the perfect home that matches your requirements and budget in Ciudad Quesada and the urbanizations of Rojales.</p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-white btn-lg">Schedule a property viewing</a>
    </div>
</section>

<?php get_footer(); ?>
