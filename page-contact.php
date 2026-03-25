<?php
/*
 * Template Name: Contact
 */
get_header(); ?>

<section class="page-hero">
    <div class="section-inner">
        <h1>Contact us</h1>
        <p>Our team is based in Ciudad Quesada and ready to help. Get in touch and we'll respond within one business day.</p>
    </div>
</section>

<section class="contact-page">
    <div class="section-inner">
        <div class="contact-page-grid">

            <!-- Contact Form -->
            <div class="contact-form-wrap">
                <h2>Send us a message</h2>
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
                            <label for="contact_name">Full name <span class="required">*</span></label>
                            <input type="text" id="contact_name" name="contact_name" placeholder="Your full name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_email">Email address <span class="required">*</span></label>
                            <input type="email" id="contact_email" name="contact_email" placeholder="you@example.com" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_phone">Phone number</label>
                            <input type="tel" id="contact_phone" name="contact_phone" placeholder="+44 or +353...">
                        </div>
                        <div class="form-group">
                            <label for="contact_subject">Subject</label>
                            <select id="contact_subject" name="contact_subject">
                                <option value="">-- Select a topic --</option>
                                <option value="buying">Buying a property</option>
                                <option value="renting">Renting a property</option>
                                <option value="viewing">Arrange a viewing</option>
                                <option value="valuation">Property valuation</option>
                                <option value="other">Other enquiry</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_message">Message <span class="required">*</span></label>
                        <textarea id="contact_message" name="contact_message" rows="6" placeholder="Tell us what you're looking for..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="consent-label">
                            <input type="checkbox" name="contact_consent" value="1" required>
                            I agree to my data being processed to respond to my enquiry. See our <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">privacy policy</a>.
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Send message</button>
                </form>
            </div>

            <!-- Contact Info -->
            <aside class="contact-info">
                <h2>Get in touch</h2>

                <div class="contact-details">
                    <div class="contact-detail">
                        <div class="contact-detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <strong>Office address</strong>
                            <p>Ciudad Quesada<br>Rojales, Alicante<br>03170, Spain</p>
                        </div>
                    </div>

                    <div class="contact-detail">
                        <div class="contact-detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.1a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.37h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div>
                            <strong>Phone</strong>
                            <p><a href="tel:+34000000000">+34 000 000 000</a></p>
                            <p style="font-size:.875rem;color:var(--grey-500);">Mon–Fri 9:00–18:00 CET</p>
                        </div>
                    </div>

                    <div class="contact-detail">
                        <div class="contact-detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div>
                            <strong>Email</strong>
                            <p><a href="mailto:info@spaniabolig.no">info@spaniabolig.no</a></p>
                        </div>
                    </div>

                    <div class="contact-detail">
                        <div class="contact-detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div>
                            <strong>Office hours</strong>
                            <p>Monday – Friday: 9:00 – 18:00<br>Saturday: 10:00 – 14:00<br>Sunday: Closed</p>
                        </div>
                    </div>
                </div>

                <div class="contact-note">
                    <h3>Planning a visit?</h3>
                    <p>If you are flying out to view properties, let us know in advance and we can arrange a full itinerary of viewings during your stay.</p>
                    <a href="<?php echo esc_url(home_url('/how-it-works')); ?>" class="btn btn-outline btn-sm">How it works</a>
                </div>
            </aside>

        </div>
    </div>
</section>

<?php get_footer(); ?>
