<?php
/*
 * Template Name: Contact
 */
get_header(); ?>

<section class="page-hero">
    <div class="section-inner">
        <h1><?php echo esc_html(get_field('hero_title') ?: 'Contact us'); ?></h1>
        <p><?php echo esc_html(get_field('hero_subtitle') ?: 'Get in touch with Spaniabolig for any questions about properties in Ciudad Quesada and the urbanizations of Rojales.'); ?></p>
    </div>
</section>

<section class="contact-page">
    <div class="section-inner">
        <div class="contact-form-card">
            <h2><?php echo esc_html(get_field('form_title') ?: 'Send us a message'); ?></h2>
            <p><?php echo esc_html(get_field('form_intro') ?: 'We aim to respond to all inquiries within 24 hours during business days.'); ?></p>
            <?php
            $sent = isset($_GET['sent']) && $_GET['sent'] === '1';
            $error = isset($_GET['error']) && $_GET['error'] === '1';
            if ($sent) : ?>
                <div class="form-notice form-notice--success">Thank you — we'll be in touch shortly.</div>
            <?php elseif ($error) : ?>
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
    </div>
</section>

<?php get_footer(); ?>
