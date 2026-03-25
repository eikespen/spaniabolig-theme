<?php
/*
 * Template Name: How It Works
 */
get_header(); ?>

<section class="page-hero">
    <div class="section-inner">
        <h1>How it works</h1>
        <p>Finding your dream property in Ciudad Quesada is simple. We guide you through every step of the process.</p>
    </div>
</section>

<section class="how-it-works-steps">
    <div class="section-inner">
        <h2>Your journey to a Spanish property</h2>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Search &amp; browse</h3>
                <p>Use our search tool to filter properties by type, location, price and features. Browse hundreds of listings in Ciudad Quesada and the surrounding urbanizations.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Contact us</h3>
                <p>Found something you like? Get in touch with our local team. We'll answer your questions and arrange viewings at times that suit you — including virtual tours.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>View properties</h3>
                <p>Visit properties in person or virtually. Our local experts will accompany you and give you honest, unbiased advice about each property and the surrounding area.</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <h3>Complete your purchase</h3>
                <p>We connect you with trusted local lawyers, NIE number assistance, and mortgage specialists to ensure your purchase goes smoothly from offer to keys.</p>
            </div>
        </div>
    </div>
</section>

<section class="about-services" style="background: var(--grey-50);">
    <div class="section-inner">
        <h2>What's included in our service</h2>
        <div class="services-grid">
            <?php
            $items = [
                ['title' => 'Property search assistance', 'desc' => 'We help you identify properties that match your exact requirements, budget, and preferred location within Ciudad Quesada and Rojales.'],
                ['title' => 'Viewing arrangements', 'desc' => 'We organise and accompany you on property viewings, whether in person or via video call, with full local expert commentary.'],
                ['title' => 'Legal guidance', 'desc' => 'We connect you with trusted English-speaking Spanish lawyers who handle contracts, NIE numbers, and all legal aspects of your purchase.'],
                ['title' => 'Mortgage assistance', 'desc' => 'Our network of mortgage brokers specialise in helping foreign buyers secure competitive Spanish mortgage rates.'],
                ['title' => 'After-sales support', 'desc' => 'From utility connections to renovation recommendations, our support continues after you receive the keys to your new home.'],
                ['title' => 'Currency exchange', 'desc' => 'We work with currency specialists who can save you thousands on international money transfers when purchasing in euros.'],
            ];
            foreach ($items as $item) : ?>
            <div class="service-card">
                <h3><?php echo esc_html($item['title']); ?></h3>
                <p><?php echo esc_html($item['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="section-inner">
        <h2>Ready to start your property search?</h2>
        <p>Browse our current listings or get in touch with our local team to discuss your requirements.</p>
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-white btn-lg">Browse properties</a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-lg" style="background:transparent;color:#fff;border:2px solid rgba(255,255,255,.5);">Contact us</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
