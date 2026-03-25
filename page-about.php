<?php
/*
 * Template Name: About
 */
get_header();

$pid = get_the_ID();
function sb_ab($key, $default = '') {
    global $pid;
    $v = get_post_meta($pid, $key, true);
    return $v !== '' ? $v : $default;
}
?>

<section class="page-hero">
    <div class="section-inner">
        <h1><?php echo esc_html(sb_ab('sb_hero_title', 'About Spaniabolig')); ?></h1>
        <p><?php echo esc_html(sb_ab('sb_hero_subtitle', 'We help foreign buyers find their dream properties exclusively in Ciudad Quesada and the urbanizations of Rojales, with a focus on villas, apartments, and townhouses.')); ?></p>
    </div>
</section>

<!-- Mission cards -->
<section class="about-mission">
    <div class="section-inner">
        <div class="about-stack">
            <div class="about-card">
                <h2><?php echo esc_html(sb_ab('sb_mission_card1_title', 'Our mission')); ?></h2>
                <p><?php echo esc_html(sb_ab('sb_mission_card1_text', 'At Spaniabolig, we understand that finding the right property in a foreign country can be challenging. Our mission is to simplify this process for international buyers by providing a transparent property listing service that showcases the best properties in Ciudad Quesada and the surrounding urbanizations suited to your specific needs and budget.')); ?></p>
                <p>We believe that everyone deserves clear, unbiased information when making important property investment decisions, especially when buying abroad. That's why we've developed a platform that cuts through the complexity and presents your options in a straightforward, easy-to-understand way.</p>
            </div>
            <div class="about-card">
                <h2><?php echo esc_html(sb_ab('sb_mission_card2_title', 'Your trusted partner for Spanish property')); ?></h2>
                <p><?php echo esc_html(sb_ab('sb_mission_card2_text', 'Spaniabolig is your professional property partner specializing exclusively in Ciudad Quesada and the surrounding urbanizations of Rojales on the Costa Blanca. We are dedicated to helping international buyers find their perfect Spanish home in this beautiful Mediterranean community.')); ?></p>
                <p>Our deep connection to Ciudad Quesada goes beyond business. Living and working in this vibrant community has allowed us to build strong relationships with local real estate professionals, developers, and property owners. This insider knowledge gives us invaluable insights into the local housing market and upcoming opportunities.</p>
                <p>We have successfully helped numerous international clients from across Europe find their dream properties in Ciudad Quesada and the surrounding areas.</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact form -->
<section class="about-contact-form">
    <div class="section-inner">
        <div class="about-contact-card">
            <h2>Get in touch with us</h2>
            <p>Have questions about properties in Ciudad Quesada? Send us a message and we'll get back to you as soon as possible.</p>
            <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="sb_contact_form">
                <?php wp_nonce_field('sb_contact', 'sb_contact_nonce'); ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="ab_name">Your Name</label>
                        <input type="text" id="ab_name" name="contact_name" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="ab_email">Email Address</label>
                        <input type="email" id="ab_email" name="contact_email" placeholder="Enter your email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ab_phone">Phone Number (Optional)</label>
                    <input type="tel" id="ab_phone" name="contact_phone" placeholder="Enter your phone number">
                </div>
                <div class="form-group">
                    <label for="ab_message">Your Message</label>
                    <textarea id="ab_message" name="contact_message" rows="5" placeholder="Tell me about your property requirements or any questions you have"></textarea>
                </div>
                <label class="consent-label">
                    <input type="checkbox" required>
                    I consent to having this website store my submitted information so I can be contacted in response to my inquiry.
                </label>
                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:8px;">Send Message</button>
            </form>
        </div>
    </div>
</section>

<!-- How we help you -->
<section class="about-services">
    <div class="section-inner">
        <h2><?php echo esc_html(sb_ab('sb_services_title', 'How we help you')); ?></h2>
        <?php
        $service_defaults = [
            ['title' => 'Expert property service',       'desc' => 'Our team of local property experts has extensive knowledge of Ciudad Quesada and the urbanizations of Rojales. We personally view and evaluate every property we list to ensure it meets our quality standards and is presented accurately to potential buyers.'],
            ['title' => 'Comprehensive property listings','desc' => 'We offer an extensive range of properties exclusively in Ciudad Quesada and the urbanizations of Rojales, from villas with pools to apartments and townhouses with great locations.'],
            ['title' => 'Foreigner-friendly focus',      'desc' => 'We specifically cater to international buyers, providing guidance on the Spanish property purchase process and connecting you with English-speaking legal and financial experts.'],
            ['title' => 'Transparent information',       'desc' => 'We provide clear details on property features, locations, prices, and local amenities in Ciudad Quesada to help you make informed decisions about your property investment.'],
            ['title' => 'Personalized property search',  'desc' => 'Our advanced property search tools help you find properties that match your specific requirements, from budget and location to features like swimming pools and proximity to the beach.'],
        ];
        $services = [];
        for ($i = 1; $i <= 4; $i++) {
            $t = sb_ab("sb_service{$i}_title", '');
            $d = sb_ab("sb_service{$i}_desc",  '');
            if ($t) $services[] = ['title' => $t, 'desc' => $d];
        }
        if (empty($services)) $services = $service_defaults;
        ?>
        <div class="about-services-list">
            <?php foreach ($services as $i => $s) : ?>
            <div class="about-service-item<?php echo $i === 0 ? ' about-service-item--first' : ''; ?>">
                <h3><?php echo esc_html($s['title']); ?></h3>
                <p><?php echo esc_html($s['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-banner">
    <div class="section-inner">
        <h2><?php echo esc_html(sb_ab('sb_cta_title', 'Ready to find your dream property in Ciudad Quesada?')); ?></h2>
        <p><?php echo esc_html(sb_ab('sb_cta_text', 'Start your journey towards owning your ideal home in Ciudad Quesada and the urbanizations of Rojales today.')); ?></p>
        <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-white btn-lg"><?php echo esc_html(sb_ab('sb_cta_btn_text', 'Browse properties')); ?></a>
    </div>
</section>

<?php get_footer(); ?>
