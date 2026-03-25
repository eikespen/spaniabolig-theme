<?php
/*
 * Template Name: Services
 */
get_header();

$pid = get_the_ID();
function sb_sv($key, $default = '') {
    global $pid;
    $v = get_post_meta($pid, $key, true);
    return $v !== '' ? $v : $default;
}
?>

<section class="page-hero page-hero--dark">
    <div class="section-inner--narrow">
        <span class="page-hero-label"><?php echo esc_html(sb_sv('sb_hero_label', 'Property Management')); ?></span>
        <h1><?php echo esc_html(sb_sv('sb_hero_title', 'Services for property owners in Spain')); ?></h1>
        <p><?php echo esc_html(sb_sv('sb_hero_subtitle', 'Spaniabolig Real Estate offers a number of services to you who own a home in Spain. See below for more information and feel free to contact us if you have any questions.')); ?></p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>"><?php echo esc_html(sb_sv('sb_hero_link_text', 'Contact us today')); ?> &rarr;</a>
    </div>
</section>

<?php
$services_icons = [
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
    '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
];
$svc_defs = [
    ['Meet & Greet',           'We offer a first-class meet and greet service, ensuring a smooth key handover upon both arrival and departure. We make sure your property is clean, stocked with essentials, and ready for your arrival.'],
    ['Key holding',             'It is always a good idea to have someone have access to the property in case something happens. We are happy to take care of your keys and look after your property year round.'],
    ['Pool services / Pool boy','We have several teams that help you keep your pool in top quality so that it can be used all year round — either by yourself or by rental guests.'],
    ['Cleaning services',       'We provide professional cleaning services for your Spanish property, whether for regular maintenance, after a rental period, or to prepare your home for your own stay.'],
    ['Property photography',    'High-quality professional photography to showcase your property at its best — perfect for rental listings, sales, or simply capturing your Spanish home in beautiful light.'],
    ['Maintenance & repairs',   'From minor repairs to larger maintenance tasks, our trusted network of local tradespeople ensures your Spanish property is always well-maintained and in excellent condition.'],
];
$services = [];
for ($i = 1; $i <= 6; $i++) {
    $def = $svc_defs[$i - 1];
    $services[] = [
        'title' => sb_sv("sb_service{$i}_title", $def[0]),
        'desc'  => sb_sv("sb_service{$i}_desc",  $def[1]),
    ];
}
?>
<section class="about-services">
    <div class="section-inner--narrow">
        <h2><?php echo esc_html(sb_sv('sb_services_title', 'Our services')); ?></h2>
        <p style="text-align:center;color:var(--grey-500);margin-top:-24px;margin-bottom:40px;font-size:15px;"><?php echo esc_html(sb_sv('sb_services_subtitle', "From key holding to professional photography — we take care of your Spanish property so you don't have to.")); ?></p>
        <div class="services-grid">
            <?php foreach ($services as $i => $s) : ?>
            <div class="service-card service-card--icon">
                <div class="service-card-icon"><?php echo $services_icons[$i % count($services_icons)]; ?></div>
                <h3><?php echo esc_html($s['title']); ?></h3>
                <p><?php echo esc_html($s['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="svc-contact-section">
    <div class="section-inner--narrow">
        <div class="svc-contact-card">

            <!-- Left: agent info -->
            <div class="svc-contact-card__left">
                <h2 class="svc-contact-card__heading">
                    <?php echo esc_html(sb_sv('sb_contact_heading', 'Do you want to order some of our services or have any questions? Let us know.')); ?>
                </h2>

                <?php
                $photo_url = sb_sv('sb_contact_photo_url', '');
                if ($photo_url): ?>
                <img src="<?php echo esc_url($photo_url); ?>"
                     alt="<?php echo esc_attr(sb_sv('sb_contact_name', 'Christer')); ?>"
                     class="svc-contact-card__photo">
                <?php else: ?>
                <div class="svc-contact-card__photo-placeholder">
                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="40" cy="32" r="16" fill="#cbd5e1"/>
                        <path d="M8 72c0-17.673 14.327-32 32-32s32 14.327 32 32" fill="#cbd5e1"/>
                    </svg>
                </div>
                <?php endif; ?>

                <div class="svc-contact-card__info">
                    <?php $phone = sb_sv('sb_contact_phone', '+47 909 17 648'); if ($phone): ?>
                    <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="svc-contact-card__info-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
                        <?php echo esc_html($phone); ?>
                    </a>
                    <?php endif;
                    $email = sb_sv('sb_contact_email', 'christer@spaniabolig.no'); if ($email): ?>
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="svc-contact-card__info-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <?php echo esc_html($email); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: enquiry form -->
            <div class="svc-contact-card__right">
                <form class="svc-form" id="svc-inquiry-form" novalidate>
                    <?php wp_nonce_field('sb_service_inquiry', 'svc_nonce'); ?>

                    <div class="svc-form__field">
                        <select name="svc_service" class="svc-form__input svc-form__select" required>
                            <option value="" disabled selected><?php echo esc_html(sb_sv('sb_form_service_label', 'What type of services do you need? Click here.')); ?></option>
                            <option value="Meet &amp; Greet">Meet &amp; Greet</option>
                            <option value="Key holding">Key holding</option>
                            <option value="Pool services">Pool services / Pool boy</option>
                            <option value="Cleaning services">Cleaning services</option>
                            <option value="Property photography">Property photography</option>
                            <option value="Maintenance &amp; repairs">Maintenance &amp; repairs</option>
                            <option value="Other">Other / General enquiry</option>
                        </select>
                    </div>

                    <div class="svc-form__field">
                        <input type="text" name="svc_name" class="svc-form__input" placeholder="Name" required>
                    </div>

                    <div class="svc-form__row">
                        <div class="svc-form__field">
                            <input type="email" name="svc_email" class="svc-form__input" placeholder="Email" required>
                        </div>
                        <div class="svc-form__field">
                            <input type="tel" name="svc_phone" class="svc-form__input" placeholder="Phone">
                        </div>
                    </div>

                    <div class="svc-form__field">
                        <input type="text" name="svc_city" class="svc-form__input" placeholder="City">
                    </div>

                    <div class="svc-form__field svc-form__field--checkbox">
                        <label class="svc-form__checkbox-label">
                            <input type="checkbox" name="svc_consent" required>
                            <span>I consent to having this website store my submitted information</span>
                        </label>
                    </div>

                    <div class="svc-form__error" id="svc-form-error" style="display:none" role="alert"></div>
                    <div class="svc-form__success" id="svc-form-success" style="display:none" role="status"></div>

                    <button type="submit" class="svc-form__submit" id="svc-submit-btn">Submit</button>
                </form>
            </div>

        </div>
    </div>
</section>

<script>
(function () {
    var form    = document.getElementById('svc-inquiry-form');
    var errEl   = document.getElementById('svc-form-error');
    var sucEl   = document.getElementById('svc-form-success');
    var submitBtn = document.getElementById('svc-submit-btn');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        errEl.style.display = 'none';
        sucEl.style.display = 'none';

        var consent = form.querySelector('[name="svc_consent"]');
        if (consent && !consent.checked) {
            errEl.textContent = 'Please give your consent to submit this form.';
            errEl.style.display = 'block';
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending…';

        var fd = new FormData(form);
        fd.append('action', 'sb_service_inquiry');

        fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', { method: 'POST', body: fd })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success) {
                    form.reset();
                    sucEl.textContent = res.data.message;
                    sucEl.style.display = 'block';
                } else {
                    errEl.textContent = (res.data && res.data.message) ? res.data.message : 'Something went wrong. Please try again.';
                    errEl.style.display = 'block';
                }
            })
            .catch(function () {
                errEl.textContent = 'Network error. Please check your connection and try again.';
                errEl.style.display = 'block';
            })
            .finally(function () {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit';
            });
    });
})();
</script>

<?php get_footer(); ?>
