<?php
/*
 * Template Name: Contact
 */
get_header();

$pid = get_the_ID();
function sb_ct($key, $default = '') {
    global $pid;
    $v = get_post_meta($pid, $key, true);
    return $v !== '' ? $v : $default;
}
?>

<section class="page-hero">
    <div class="section-inner--narrow">
        <h1><?php echo esc_html(sb_ct('sb_hero_title', 'Contact us')); ?></h1>
        <p><?php echo esc_html(sb_ct('sb_hero_subtitle', 'Get in touch with Spaniabolig for any questions about properties in Ciudad Quesada and the urbanizations of Rojales.')); ?></p>
    </div>
</section>

<section class="contact-page">
    <div class="section-inner--narrow">

        <div class="contact-form-card">
            <h2><?php echo esc_html(sb_ct('sb_form_title', 'Send us a message')); ?></h2>
            <p><?php echo esc_html(sb_ct('sb_form_intro', 'We aim to respond to all inquiries within 24 hours during business days.')); ?></p>

            <?php if (isset($_GET['sent']) && $_GET['sent'] === '1') : ?>
                <div class="form-notice form-notice--success">Thank you — we'll be in touch shortly.</div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] === '1') : ?>
                <div class="form-notice form-notice--error">Something went wrong. Please try again or email us directly.</div>
            <?php endif; ?>

            <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="sb_contact_form">
                <?php wp_nonce_field('sb_contact', 'sb_contact_nonce'); ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_name">Your name</label>
                        <input type="text" id="contact_name" name="contact_name" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_email">Email address</label>
                        <input type="email" id="contact_email" name="contact_email" placeholder="Enter your email address" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact_subject">Subject</label>
                    <input type="text" id="contact_subject" name="contact_subject" placeholder="What is your inquiry about?">
                </div>
                <div class="form-group">
                    <label for="contact_message">Message</label>
                    <textarea id="contact_message" name="contact_message" rows="7" placeholder="Please describe your question or request" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;">Send message</button>
                <p class="form-privacy-note">Your privacy is important to us. We never share your details without permission.</p>
            </form>
        </div>

        <!-- Contact info cards -->
        <div class="contact-info-cards">
            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <div>
                    <strong><?php echo esc_html(sb_ct('sb_info_email_label', 'Email us')); ?></strong>
                    <span><?php echo esc_html(sb_ct('sb_info_email', 'post@spaniabolig.no')); ?></span>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8 19.79 19.79 0 01.22 1.18 2 2 0 012.2 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/></svg>
                </div>
                <div>
                    <strong><?php echo esc_html(sb_ct('sb_info_phone_label', 'Call us')); ?></strong>
                    <span><?php echo esc_html(sb_ct('sb_info_phone', '+34 696 039 621')); ?></span>
                </div>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    <strong><?php echo esc_html(sb_ct('sb_info_location_label', 'Find us')); ?></strong>
                    <span><?php echo esc_html(sb_ct('sb_info_location', 'Ciudad Quesada, Rojales, Alicante, Spain')); ?></span>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <?php
        $faq_title = sb_ct('sb_faq_title', 'Frequently asked questions');
        $faqs = [];
        for ($i = 1; $i <= 6; $i++) {
            $q = sb_ct("sb_faq{$i}_question", '');
            $a = sb_ct("sb_faq{$i}_answer", '');
            if ($q && $a) $faqs[] = ['q' => $q, 'a' => $a];
        }
        // Default FAQs if none saved
        if (empty($faqs)) {
            $faqs = [
                ['q' => 'What areas do you cover?',                   'a' => 'We exclusively sell properties in Ciudad Quesada and the urbanizations of Rojales including Ciudad Quesada Centro, Doña Pepa, La Marquesa Golf, Lo Marabú, El Raso, Pueblo Lucero, Pueblo Bravo, Benimar, La Herrada, and Montebello. We do not offer services in other regions.'],
                ['q' => 'Do you offer property management services?',  'a' => 'Yes, we offer comprehensive property management services for non-resident owners, including rental management, maintenance, cleaning, and key-holding services.'],
                ['q' => 'Can you help with the buying process in Spain?', 'a' => 'Absolutely. Our team guides you through the entire purchasing process, from property viewing to obtaining your NIE, opening bank accounts, and connecting you with trusted lawyers and notaries.'],
                ['q' => 'What types of properties do you offer?',     'a' => 'We offer a wide range of properties including apartments, townhouses, villas, new developments, and plots of land. Whether you\'re looking for a holiday home, investment property, or permanent residence, we have options to suit all budgets and preferences.'],
            ];
        }
        if (!empty($faqs)) : ?>
        <div class="contact-faq">
            <h2><?php echo esc_html($faq_title); ?></h2>
            <div class="contact-faq-list">
                <?php foreach ($faqs as $faq) : ?>
                <div class="contact-faq-item">
                    <h3><?php echo esc_html($faq['q']); ?></h3>
                    <p><?php echo esc_html($faq['a']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
