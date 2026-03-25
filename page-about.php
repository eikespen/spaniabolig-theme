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
    <div class="section-inner--narrow">
        <h1><?php echo esc_html(sb_ab('sb_hero_title', 'About Spaniabolig')); ?></h1>
        <p><?php echo esc_html(sb_ab('sb_hero_subtitle', 'We help foreign buyers find their dream properties exclusively in Ciudad Quesada and the urbanizations of Rojales, with a focus on villas, apartments, and townhouses.')); ?></p>
    </div>
</section>

<!-- Mission cards -->
<section class="about-mission">
    <div class="section-inner--narrow">
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

<!-- Meet the team -->
<?php
$team = get_posts([
    'post_type'      => 'sb_agent',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
if (!empty($team)) : ?>
<section class="about-team">
    <div class="section-inner--narrow">
        <div class="about-team__header">
            <h2>Meet the team</h2>
            <p>Our dedicated team of property specialists — based in Spain and Norway — are here to guide you every step of the way.</p>
        </div>
        <div class="about-team__grid">
            <?php foreach ($team as $agent) :
                $phone    = get_post_meta($agent->ID, 'sb_agent_phone',    true);
                $email    = get_post_meta($agent->ID, 'sb_agent_email',    true);
                $title    = get_post_meta($agent->ID, 'sb_agent_title',    true) ?: 'Property Consultant';
                $wa       = get_post_meta($agent->ID, 'sb_agent_whatsapp', true);
                $photo    = get_the_post_thumbnail_url($agent->ID, 'large');
                $wa_clean = preg_replace('/[^0-9]/', '', $wa ?: $phone);
            ?>
            <div class="agent-card">
                <div class="agent-card__photo-wrap">
                    <?php if ($photo) : ?>
                    <img src="<?php echo esc_url($photo); ?>"
                         alt="<?php echo esc_attr($agent->post_title); ?>"
                         class="agent-card__photo" loading="lazy">
                    <?php else : ?>
                    <div class="agent-card__photo-placeholder">
                        <svg viewBox="0 0 80 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="34" r="18" fill="#cbd5e1"/>
                            <path d="M4 90c0-19.882 16.118-36 36-36s36 16.118 36 36" fill="#cbd5e1"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="agent-card__body">
                    <h3 class="agent-card__name"><?php echo esc_html($agent->post_title); ?></h3>
                    <span class="agent-card__title"><?php echo esc_html($title); ?></span>
                    <div class="agent-card__contacts">
                        <?php if ($phone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="agent-card__contact">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
                            <?php echo esc_html($phone); ?>
                        </a>
                        <?php endif; ?>
                        <?php if ($email) : ?>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="agent-card__contact">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <?php echo esc_html($email); ?>
                        </a>
                        <?php endif; ?>
                        <?php if ($wa_clean) : ?>
                        <a href="https://wa.me/<?php echo esc_attr($wa_clean); ?>" class="agent-card__contact agent-card__contact--whatsapp" target="_blank" rel="noopener">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.374 0 0 5.373 0 12c0 2.127.556 4.126 1.526 5.857L.057 23.882a.5.5 0 0 0 .613.613l6.102-1.458A11.944 11.944 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.656-.51-5.178-1.4l-.371-.22-3.853.921.938-3.76-.243-.386A9.956 9.956 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                            WhatsApp
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contact form -->
<section class="about-contact-form">
    <div class="section-inner--narrow">
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
                    <span>I consent to having this website store my submitted information so I can be contacted in response to my inquiry.</span>
                </label>
                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:8px;">Send Message</button>
            </form>
        </div>
    </div>
</section>

<!-- How we help you -->
<section class="about-services">
    <div class="section-inner--narrow">
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
    <div class="section-inner--narrow">
        <h2><?php echo esc_html(sb_ab('sb_cta_title', 'Ready to find your dream property in Ciudad Quesada?')); ?></h2>
        <p><?php echo esc_html(sb_ab('sb_cta_text', 'Start your journey towards owning your ideal home in Ciudad Quesada and the urbanizations of Rojales today.')); ?></p>
        <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-white btn-lg"><?php echo esc_html(sb_ab('sb_cta_btn_text', 'Browse properties')); ?></a>
    </div>
</section>

<?php get_footer(); ?>
