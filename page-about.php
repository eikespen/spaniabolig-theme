<?php
/*
 * Template Name: About
 */
get_header(); ?>

<section class="page-hero">
    <div class="section-inner">
        <h1>About Spaniabolig</h1>
        <p>We help foreign buyers find their dream properties exclusively in Ciudad Quesada and the urbanizations of Rojales, with a focus on villas, apartments, and townhouses.</p>
    </div>
</section>

<section class="about-mission">
    <div class="section-inner about-grid">
        <div>
            <h2>Our mission</h2>
            <p>At Spaniabolig, we understand that finding the right property in a foreign country can be challenging. Our mission is to simplify this process for international buyers by providing a transparent property listing service that showcases the best properties in Ciudad Quesada and the surrounding urbanizations suited to your specific needs and budget.</p>
            <p>We believe that everyone deserves clear, unbiased information when making important property investment decisions, especially when buying abroad. That's why we've developed a platform that cuts through the complexity and presents your options in a straightforward, easy-to-understand way.</p>
        </div>
        <div>
            <h2>Your trusted partner for Spanish property</h2>
            <p>Spaniabolig is your professional property partner specializing exclusively in Ciudad Quesada and the surrounding urbanizations of Rojales on the Costa Blanca. We are dedicated to helping international buyers find their perfect Spanish home in this beautiful Mediterranean community.</p>
            <p>Our deep connection to Ciudad Quesada goes beyond business. Living and working in this vibrant community has allowed us to build strong relationships with local real estate professionals, developers, and property owners. This insider knowledge gives us invaluable insights into the local housing market and upcoming opportunities.</p>
            <p>We have successfully helped numerous international clients from across Europe find their dream properties in Ciudad Quesada and the surrounding areas.</p>
        </div>
    </div>
</section>

<section class="about-contact-form">
    <div class="section-inner about-contact-grid">
        <div>
            <h2>Get in touch with us</h2>
            <p>Have questions about properties in Ciudad Quesada? Send us a message and we'll get back to you as soon as possible.</p>
            <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="sb_contact">
                <?php wp_nonce_field('sb_contact', 'sb_contact_nonce'); ?>
                <input type="text" name="your_name" placeholder="Your Name" required>
                <input type="email" name="your_email" placeholder="Email Address" required>
                <input type="tel" name="your_phone" placeholder="Phone Number (Optional)">
                <textarea name="your_message" rows="5" placeholder="Your Message"></textarea>
                <label class="consent-label">
                    <input type="checkbox" required> I consent to having this website store my submitted information so I can be contacted in response to my inquiry.
                </label>
                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
            </form>
        </div>
    </div>
</section>

<section class="about-services">
    <div class="section-inner">
        <h2>How we help you</h2>
        <div class="services-grid">
            <?php
            $services = [
                ['title' => 'Expert property service', 'desc' => 'Our team of local property experts has extensive knowledge of Ciudad Quesada and the urbanizations of Rojales. We personally view and evaluate every property we list to ensure it meets our quality standards.'],
                ['title' => 'Comprehensive property listings', 'desc' => 'We offer an extensive range of properties exclusively in Ciudad Quesada and the urbanizations of Rojales, from villas with pools to apartments and townhouses with great locations.'],
                ['title' => 'Foreigner-friendly focus', 'desc' => 'We specifically cater to international buyers, providing guidance on the Spanish property purchase process and connecting you with English-speaking legal and financial experts.'],
                ['title' => 'Transparent information', 'desc' => 'We provide clear details on property features, locations, prices, and local amenities in Ciudad Quesada to help you make informed decisions about your property investment.'],
                ['title' => 'Personalized property search', 'desc' => 'Our advanced property search tools help you find properties that match your specific requirements, from budget and location to features like swimming pools and proximity to the beach.'],
            ];
            foreach ($services as $s) : ?>
            <div class="service-card">
                <h3><?php echo esc_html($s['title']); ?></h3>
                <p><?php echo esc_html($s['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="section-inner">
        <h2>Ready to find your dream property in Ciudad Quesada?</h2>
        <p>Start your journey towards owning your ideal home in Ciudad Quesada and the urbanizations of Rojales today.</p>
        <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-white btn-lg">Browse properties</a>
    </div>
</section>

<?php get_footer(); ?>
