<?php
/*
 * Template Name: How It Works
 */
get_header(); ?>

<section class="page-hero">
    <div class="section-inner">
        <h1>How it works</h1>
        <p>Finding your perfect property in Ciudad Quesada is easy with our step-by-step process.</p>
    </div>
</section>

<section class="how-it-works-steps">
    <div class="section-inner">
        <div class="steps-stack">
            <div class="step-card">
                <div class="step-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div>
                    <p class="step-label">Step 1</p>
                    <h3>Search for your ideal property</h3>
                    <p>Use our advanced property search features to find properties that match your requirements. Filter by property type, location, price range, and specific features to narrow down your options in Ciudad Quesada.</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div>
                    <p class="step-label">Step 2</p>
                    <h3>Browse detailed property listings</h3>
                    <p>View comprehensive information about each property including detailed descriptions, high-quality photos, floor plans, and key features. Our property listings provide all the details you need to make an informed decision about whether a property meets your requirements.</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <p class="step-label">Step 3</p>
                    <h3>Contact us about your favourite properties</h3>
                    <p>When you find properties that interest you, contact our team directly to learn more, arrange viewings, or get expert advice about the local market and buying process in Ciudad Quesada.</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                </div>
                <div>
                    <p class="step-label">Step 4</p>
                    <h3>We help you through the buying process</h3>
                    <p>Our experienced team guides you through every step of the property purchase process in Spain, from making an offer and legal checks to signing at the notary. We connect you with trusted local lawyers, NIE assistance, and mortgage specialists.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-services">
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
