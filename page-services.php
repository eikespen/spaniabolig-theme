<?php
/*
 * Template Name: Services
 */
get_header(); ?>

<section class="page-hero">
    <div class="section-inner">
        <h1>Our services</h1>
        <p>Everything you need to buy, rent or invest in property on the Costa Blanca — from first search to final handover.</p>
    </div>
</section>

<section class="about-services" style="background: var(--white);">
    <div class="section-inner">
        <h2>What we offer</h2>
        <div class="services-grid services-grid--large">
            <?php
            $services = [
                [
                    'icon' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
                    'title' => 'Property search & matching',
                    'desc'  => 'Tell us what you are looking for and we will shortlist properties that match your requirements, budget and preferred location — saving you hours of searching.',
                ],
                [
                    'icon' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
                    'title' => 'Property viewings',
                    'desc'  => 'We arrange and accompany you on viewings — in person or by video call. Our local team provides honest commentary on every property and its surroundings.',
                ],
                [
                    'icon' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
                    'title' => 'Legal guidance',
                    'desc'  => 'We connect you with trusted, English-speaking Spanish lawyers who handle purchase contracts, NIE numbers, due diligence and all legal aspects of your transaction.',
                ],
                [
                    'icon' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
                    'title' => 'Mortgage assistance',
                    'desc'  => 'Our network of specialist mortgage brokers helps foreign buyers secure competitive Spanish mortgage rates and navigate the application process smoothly.',
                ],
                [
                    'icon' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
                    'title' => 'Currency exchange',
                    'desc'  => 'We work with specialist currency providers who offer better rates than high-street banks, potentially saving you thousands on international money transfers.',
                ],
                [
                    'icon' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.1a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.37h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
                    'title' => 'After-sales support',
                    'desc'  => 'Our service does not end at the notary. We help with utility connections, internet setup, furniture recommendations and trusted tradespeople for any renovations.',
                ],
                [
                    'icon' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                    'title' => 'Rental management',
                    'desc'  => 'If you plan to rent out your property when not in use, we can connect you with reputable local management companies to handle bookings, guests and maintenance.',
                ],
                [
                    'icon' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
                    'title' => 'New build & off-plan',
                    'desc'  => 'Access exclusive new build developments in Ciudad Quesada and Rojales. We guide you through payment plans, developer contracts and construction timelines.',
                ],
                [
                    'icon' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
                    'title' => 'Investment advice',
                    'desc'  => 'Looking for buy-to-let or capital growth opportunities? Our team has deep local market knowledge and can identify properties with strong rental yields and appreciation potential.',
                ],
            ];
            foreach ($services as $svc) : ?>
            <div class="service-card service-card--icon">
                <div class="service-icon"><?php echo $svc['icon']; ?></div>
                <h3><?php echo esc_html($svc['title']); ?></h3>
                <p><?php echo esc_html($svc['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="why-buy" style="background: var(--grey-50);">
    <div class="section-inner">
        <h2>Why use a local agent?</h2>
        <div class="why-buy-grid">
            <div class="why-buy-col">
                <ul class="check-list">
                    <li>Local knowledge you cannot get from a website</li>
                    <li>Genuine understanding of price levels in each urbanization</li>
                    <li>We know which developments have community fee issues</li>
                    <li>Access to off-market properties before they are listed</li>
                </ul>
            </div>
            <div class="why-buy-col">
                <ul class="check-list">
                    <li>English-speaking team — no language barrier</li>
                    <li>We work for you, not the developer or seller</li>
                    <li>No hidden fees — transparent pricing from the start</li>
                    <li>Years of experience helping British and Irish buyers</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="section-inner">
        <h2>Ready to find your Spanish home?</h2>
        <p>Whether you are buying, renting or investing — our team is here to help every step of the way.</p>
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-white btn-lg">Browse properties</a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-lg" style="background:transparent;color:#fff;border:2px solid rgba(255,255,255,.5);">Get in touch</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
