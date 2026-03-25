<?php
/**
 * Native WordPress meta boxes for editable page content.
 * No plugin required — uses add_meta_box() + get/update_post_meta().
 */
defined('ABSPATH') || exit;

/* ── Admin CSS ── */
add_action('admin_head', function() {
    $screen = get_current_screen();
    if (!$screen || $screen->base !== 'post' || $screen->post_type !== 'page') return;
    ?>
    <style>
    .sb-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #2271b1;
        border-radius: 6px;
        padding: 16px 18px;
        margin-bottom: 14px;
    }
    .sb-section-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #2271b1;
        margin: 0 0 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .sb-section-title span {
        display: inline-block;
        width: 20px;
        height: 20px;
        background: #2271b1;
        color: #fff;
        border-radius: 4px;
        font-size: 11px;
        line-height: 20px;
        text-align: center;
    }
    .sb-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .sb-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
    }
    .sb-field { margin: 0; }
    .sb-field label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #646970;
        margin-bottom: 5px;
    }
    .sb-field input[type="text"],
    .sb-field textarea {
        width: 100%;
        border: 1px solid #dcdcde;
        border-radius: 4px;
        padding: 7px 10px;
        font-size: 13px;
        line-height: 1.5;
        color: #1d2327;
        background: #fff;
        box-sizing: border-box;
        transition: border-color 0.15s;
    }
    .sb-field input[type="text"]:focus,
    .sb-field textarea:focus {
        border-color: #2271b1;
        outline: none;
        box-shadow: 0 0 0 1px #2271b1;
    }
    .sb-field textarea { resize: vertical; min-height: 72px; }
    .sb-divider {
        border: none;
        border-top: 1px solid #e2e8f0;
        margin: 14px 0;
    }
    </style>
    <?php
});

/* ── Register meta boxes for specific pages ── */
add_action('add_meta_boxes_page', function($post) {
    $slug     = $post->post_name;
    $front_id = (int) get_option('page_on_front');

    if ($post->ID === $front_id) {
        add_meta_box('sb_fp',       'Front Page Content',     'sb_mb_frontpage', 'page', 'normal', 'high');
    }
    if ($slug === 'about') {
        add_meta_box('sb_about',    'About Page Content',     'sb_mb_about',     'page', 'normal', 'high');
    }
    if ($slug === 'how-it-works') {
        add_meta_box('sb_hiw',      'How It Works — Content', 'sb_mb_hiw',       'page', 'normal', 'high');
    }
    if ($slug === 'services') {
        add_meta_box('sb_services', 'Services Page Content',  'sb_mb_services',  'page', 'normal', 'high');
    }
    if ($slug === 'contact') {
        add_meta_box('sb_contact',  'Contact Page Content',   'sb_mb_contact',   'page', 'normal', 'high');
    }
});

/* ── Render helpers ── */
function sb_section_start($icon, $title) {
    echo '<div class="sb-section"><p class="sb-section-title"><span>' . esc_html($icon) . '</span>' . esc_html($title) . '</p>';
}
function sb_section_end() {
    echo '</div>';
}
function sb_grid_start($cols = 2) {
    echo '<div class="' . ($cols === 3 ? 'sb-grid-3' : 'sb-grid') . '">';
}
function sb_grid_end() {
    echo '</div>';
}
function sb_divider() {
    echo '<hr class="sb-divider">';
}
function sb_field($post, $key, $label, $type = 'text', $default = '') {
    $val = get_post_meta($post->ID, $key, true);
    if ($val === '') $val = $default;
    echo '<p class="sb-field"><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label>';
    if ($type === 'textarea') {
        echo '<textarea id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" rows="3">' . esc_textarea($val) . '</textarea>';
    } else {
        echo '<input type="text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '">';
    }
    echo '</p>';
}

/* ════════════════════════════════════════════
   FRONT PAGE
════════════════════════════════════════════ */
function sb_mb_frontpage($post) {
    wp_nonce_field('sb_page_meta', 'sb_page_nonce');

    sb_section_start('✦', 'Hero');
        sb_field($post, 'sb_hero_heading',  'Heading',  'text',     'Find your dream property in Ciudad Quesada');
        sb_field($post, 'sb_hero_subtitle', 'Subtitle', 'textarea', 'Discover beautiful homes in Ciudad Quesada and the urbanizations of Rojales.');
    sb_section_end();

    sb_section_start('◈', 'Lifestyle Cards');
        sb_field($post, 'sb_lifestyle_title', 'Section title', 'text', 'The Ciudad Quesada lifestyle');
        sb_divider();
        sb_grid_start(3);
            sb_field($post, 'sb_lifestyle_card1_title', 'Card 1 title', 'text',     'Sunshine & outdoor living');
            sb_field($post, 'sb_lifestyle_card2_title', 'Card 2 title', 'text',     'International community');
            sb_field($post, 'sb_lifestyle_card3_title', 'Card 3 title', 'text',     'Beaches & nature');
        sb_grid_end();
        sb_grid_start(3);
            sb_field($post, 'sb_lifestyle_card1_desc', 'Card 1 text', 'textarea', 'Enjoy over 320 days of sunshine per year in one of Spain\'s most popular residential areas.');
            sb_field($post, 'sb_lifestyle_card2_desc', 'Card 2 text', 'textarea', 'A welcoming expat community with English-speaking services, restaurants, and amenities.');
            sb_field($post, 'sb_lifestyle_card3_desc', 'Card 3 text', 'textarea', 'Just minutes from stunning Mediterranean beaches and natural parks.');
        sb_grid_end();
    sb_section_end();

    sb_section_start('⊞', 'Why Buy — Section');
        sb_field($post, 'sb_why_title', 'Section title', 'text', 'Why buy in Ciudad Quesada');
        sb_grid_start();
            sb_field($post, 'sb_why_col1_heading', 'Column 1 heading', 'text', 'Perfect Mediterranean lifestyle');
            sb_field($post, 'sb_why_col2_heading', 'Column 2 heading', 'text', 'Excellent investment opportunity');
        sb_grid_end();
        sb_grid_start();
            sb_field($post, 'sb_why_col1_text', 'Column 1 text', 'textarea', "Ciudad Quesada is a popular residential area in Costa Blanca, offering a perfect blend of Spanish lifestyle with international amenities. With over 320 days of sunshine per year, it's an ideal location.");
            sb_field($post, 'sb_why_col2_text', 'Column 2 text', 'textarea', 'Ciudad Quesada offers exceptional value for money compared to other Mediterranean destinations, with a stable property market and strong rental potential.');
        sb_grid_end();
        sb_divider();
        sb_grid_start();
            echo '<div>';
                sb_field($post, 'sb_why_col1_item1', 'Col 1 — Checklist item 1', 'text', '320+ days of sunshine per year');
                sb_field($post, 'sb_why_col1_item2', 'Col 1 — Checklist item 2', 'text', 'Excellent golf courses and leisure facilities');
                sb_field($post, 'sb_why_col1_item3', 'Col 1 — Checklist item 3', 'text', 'International schools and medical facilities');
                sb_field($post, 'sb_why_col1_item4', 'Col 1 — Checklist item 4', 'text', 'Easy access to Alicante Airport');
            echo '</div>';
            echo '<div>';
                sb_field($post, 'sb_why_col2_item1', 'Col 2 — Checklist item 1', 'text', 'Strong rental demand from tourists');
                sb_field($post, 'sb_why_col2_item2', 'Col 2 — Checklist item 2', 'text', 'Competitive property prices vs other Spanish resorts');
                sb_field($post, 'sb_why_col2_item3', 'Col 2 — Checklist item 3', 'text', 'Established legal framework for foreign buyers');
                sb_field($post, 'sb_why_col2_item4', 'Col 2 — Checklist item 4', 'text', 'Stable Spanish property market');
                sb_field($post, 'sb_why_col2_btn_text', 'Col 2 — Button text', 'text', 'Browse Ciudad Quesada properties');
            echo '</div>';
        sb_grid_end();
    sb_section_end();

    sb_section_start('?', 'FAQs');
        sb_field($post, 'sb_faq_title', 'Section title', 'text', 'Frequently asked questions');
        sb_divider();
        for ($i = 1; $i <= 4; $i++) {
            sb_grid_start();
                sb_field($post, "sb_faq{$i}_question", "FAQ $i — Question", 'text',     '');
                sb_field($post, "sb_faq{$i}_answer",   "FAQ $i — Answer",   'textarea', '');
            sb_grid_end();
        }
    sb_section_end();

    sb_section_start('→', 'CTA Banner');
        sb_grid_start();
            sb_field($post, 'sb_cta_title',    'Title',       'text',     'Ready to find your dream property in Ciudad Quesada?');
            sb_field($post, 'sb_cta_btn_text', 'Button text', 'text',     'Schedule a property viewing');
        sb_grid_end();
        sb_field($post, 'sb_cta_text', 'Paragraph', 'textarea', 'Our local property experts can help you find the perfect home that matches your requirements and budget.');
    sb_section_end();
}

/* ════════════════════════════════════════════
   ABOUT PAGE
════════════════════════════════════════════ */
function sb_mb_about($post) {
    wp_nonce_field('sb_page_meta', 'sb_page_nonce');

    sb_section_start('✦', 'Hero');
        sb_grid_start();
            sb_field($post, 'sb_hero_title',    'Title',    'text',     'About Spaniabolig');
            sb_field($post, 'sb_hero_subtitle', 'Subtitle', 'textarea', 'We help foreign buyers find their dream properties exclusively in Ciudad Quesada and the urbanizations of Rojales.');
        sb_grid_end();
    sb_section_end();

    sb_section_start('◈', 'Mission Cards');
        sb_grid_start();
            sb_field($post, 'sb_mission_card1_title', 'Card 1 title', 'text', 'Our mission');
            sb_field($post, 'sb_mission_card2_title', 'Card 2 title', 'text', 'Your trusted partner for Spanish property');
        sb_grid_end();
        sb_grid_start();
            sb_field($post, 'sb_mission_card1_text', 'Card 1 text', 'textarea', 'At Spaniabolig, we understand that finding the right property in a foreign country can be challenging. Our mission is to simplify this process for international buyers.');
            sb_field($post, 'sb_mission_card2_text', 'Card 2 text', 'textarea', 'Spaniabolig is your professional property partner specializing exclusively in Ciudad Quesada and the surrounding urbanizations of Rojales on the Costa Blanca.');
        sb_grid_end();
    sb_section_end();

    sb_section_start('⊞', 'How We Help');
        sb_field($post, 'sb_services_title', 'Section title', 'text', 'How we help you');
        sb_divider();
        sb_grid_start();
            sb_field($post, 'sb_service1_title', 'Service 1 title', 'text',     'Property search');
            sb_field($post, 'sb_service2_title', 'Service 2 title', 'text',     'Local expertise');
        sb_grid_end();
        sb_grid_start();
            sb_field($post, 'sb_service1_desc', 'Service 1 desc', 'textarea', 'We search our database of properties to find the best match for your requirements and budget.');
            sb_field($post, 'sb_service2_desc', 'Service 2 desc', 'textarea', 'Our deep knowledge of Ciudad Quesada means we can advise on the best areas, amenities, and investment potential.');
        sb_grid_end();
        sb_grid_start();
            sb_field($post, 'sb_service3_title', 'Service 3 title', 'text',     'Viewing coordination');
            sb_field($post, 'sb_service4_title', 'Service 4 title', 'text',     'After-sales support');
        sb_grid_end();
        sb_grid_start();
            sb_field($post, 'sb_service3_desc', 'Service 3 desc', 'textarea', 'We arrange property viewings and accompany you to ensure you get all the information you need.');
            sb_field($post, 'sb_service4_desc', 'Service 4 desc', 'textarea', 'We stay with you after purchase, helping with utilities, key holding, and property management referrals.');
        sb_grid_end();
    sb_section_end();

    sb_section_start('→', 'CTA');
        sb_grid_start();
            sb_field($post, 'sb_cta_title',    'Title',       'text',     'Ready to find your dream property?');
            sb_field($post, 'sb_cta_btn_text', 'Button text', 'text',     'Browse properties');
        sb_grid_end();
        sb_field($post, 'sb_cta_text', 'Paragraph', 'textarea', 'Start your journey towards owning your ideal home in Ciudad Quesada today.');
    sb_section_end();
}

/* ════════════════════════════════════════════
   HOW IT WORKS PAGE
════════════════════════════════════════════ */
function sb_mb_hiw($post) {
    wp_nonce_field('sb_page_meta', 'sb_page_nonce');

    sb_section_start('✦', 'Hero');
        sb_grid_start();
            sb_field($post, 'sb_hero_title',    'Title',    'text',     'How it works');
            sb_field($post, 'sb_hero_subtitle', 'Subtitle', 'textarea', 'Finding your perfect property in Ciudad Quesada is easy with our step-by-step process.');
        sb_grid_end();
    sb_section_end();

    $step_defaults = [
        1 => ['Tell us what you\'re looking for', 'Contact us with your requirements — budget, property type, number of bedrooms, and any specific preferences for location or amenities.'],
        2 => ['We find matching properties',       'Our team searches our database and network to find properties that match your criteria in Ciudad Quesada and the surrounding urbanizations.'],
        3 => ['View properties in person',         'We arrange viewings at times that suit you. Our local team accompanies you to each property to answer questions and provide honest advice.'],
        4 => ['Complete your purchase',            'Once you\'ve found your dream property, we guide you through the legal process and support you all the way to completion.'],
    ];

    sb_section_start('①', 'Steps');
        for ($i = 1; $i <= 4; $i++) {
            if ($i > 1) sb_divider();
            sb_grid_start();
                sb_field($post, "sb_step{$i}_label", "Step $i — Label", 'text', "Step $i");
                sb_field($post, "sb_step{$i}_title", "Step $i — Title", 'text', $step_defaults[$i][0]);
            sb_grid_end();
            sb_field($post, "sb_step{$i}_desc", "Step $i — Description", 'textarea', $step_defaults[$i][1]);
        }
    sb_section_end();

    sb_section_start('⊞', "What's Included");
        sb_field($post, 'sb_included_title', 'Section title', 'text', "What's included in our service");
        sb_divider();
        sb_grid_start();
            sb_field($post, 'sb_included1_title', 'Item 1 title', 'text',     'Free property search');
            sb_field($post, 'sb_included2_title', 'Item 2 title', 'text',     'Local market knowledge');
        sb_grid_end();
        sb_grid_start();
            sb_field($post, 'sb_included1_desc', 'Item 1 desc', 'textarea', 'No cost to you — we are paid by the seller on completion.');
            sb_field($post, 'sb_included2_desc', 'Item 2 desc', 'textarea', 'Years of experience in the Ciudad Quesada property market.');
        sb_grid_end();
        sb_grid_start();
            sb_field($post, 'sb_included3_title', 'Item 3 title', 'text',     'Viewing assistance');
            sb_field($post, 'sb_included4_title', 'Item 4 title', 'text',     'After-sales support');
        sb_grid_end();
        sb_grid_start();
            sb_field($post, 'sb_included3_desc', 'Item 3 desc', 'textarea', 'We accompany you to all property viewings and provide unbiased advice.');
            sb_field($post, 'sb_included4_desc', 'Item 4 desc', 'textarea', 'We stay in touch after completion to help you settle in.');
        sb_grid_end();
    sb_section_end();

    sb_section_start('→', 'CTA');
        sb_grid_start();
            sb_field($post, 'sb_cta_title',     'Title',         'text',     'Ready to start your property search?');
            sb_field($post, 'sb_cta_btn1_text', 'Button 1 text', 'text',     'Browse properties');
        sb_grid_end();
        sb_grid_start();
            sb_field($post, 'sb_cta_text',      'Paragraph',     'textarea', 'Browse our current listings or get in touch with our local team.');
            sb_field($post, 'sb_cta_btn2_text', 'Button 2 text', 'text',     'Contact us');
        sb_grid_end();
    sb_section_end();
}

/* ════════════════════════════════════════════
   SERVICES PAGE
════════════════════════════════════════════ */
function sb_mb_services($post) {
    wp_nonce_field('sb_page_meta', 'sb_page_nonce');

    sb_section_start('✦', 'Hero');
        sb_grid_start();
            sb_field($post, 'sb_hero_label',    'Badge text',   'text', 'Property Management');
            sb_field($post, 'sb_hero_link_text','Link text',    'text', 'Contact us today');
        sb_grid_end();
        sb_field($post, 'sb_hero_title',    'Title',    'text',     'Services for property owners in Spain');
        sb_field($post, 'sb_hero_subtitle', 'Subtitle', 'textarea', 'Spaniabolig Real Estate offers a number of services to you who own a home in Spain.');
    sb_section_end();

    $svc_defaults = [
        1 => 'Key holding',           2 => 'Property maintenance',
        3 => 'Rental management',     4 => 'Utility management',
        5 => 'Professional photography', 6 => 'Airport transfers',
    ];

    sb_section_start('⊞', 'Services Grid');
        sb_grid_start();
            sb_field($post, 'sb_services_title',    'Section title',    'text', 'Our services');
            sb_field($post, 'sb_services_subtitle', 'Section subtitle', 'text', "From key holding to professional photography — we take care of your Spanish property so you don't have to.");
        sb_grid_end();
        sb_divider();
        for ($i = 1; $i <= 6; $i += 2) {
            sb_grid_start();
                sb_field($post, "sb_service{$i}_title",     "Service $i title",     'text',     $svc_defaults[$i]);
                sb_field($post, "sb_service{$i+1}_title",   "Service " . ($i+1) . " title", 'text', $svc_defaults[$i+1]);
            sb_grid_end();
            sb_grid_start();
                sb_field($post, "sb_service{$i}_desc",      "Service $i desc",      'textarea', '');
                sb_field($post, "sb_service{$i+1}_desc",    "Service " . ($i+1) . " desc",  'textarea', '');
            sb_grid_end();
            if ($i < 5) sb_divider();
        }
    sb_section_end();

    sb_section_start('→', 'CTA');
        sb_grid_start();
            sb_field($post, 'sb_cta_title',    'Title',       'text',     'Interested in our property services?');
            sb_field($post, 'sb_cta_btn_text', 'Button text', 'text',     'Contact us today');
        sb_grid_end();
        sb_field($post, 'sb_cta_text', 'Paragraph', 'textarea', 'Get in touch with our team to discuss how we can help look after your Spanish property.');
    sb_section_end();
}

/* ════════════════════════════════════════════
   CONTACT PAGE
════════════════════════════════════════════ */
function sb_mb_contact($post) {
    wp_nonce_field('sb_page_meta', 'sb_page_nonce');

    sb_section_start('✦', 'Hero');
        sb_grid_start();
            sb_field($post, 'sb_hero_title',    'Title',    'text',     'Contact us');
            sb_field($post, 'sb_hero_subtitle', 'Subtitle', 'textarea', 'Get in touch with Spaniabolig for any questions about properties in Ciudad Quesada.');
        sb_grid_end();
    sb_section_end();

    sb_section_start('✉', 'Form Card');
        sb_grid_start();
            sb_field($post, 'sb_form_title', 'Form title', 'text', 'Send us a message');
            sb_field($post, 'sb_form_intro', 'Form intro', 'text', 'We aim to respond to all inquiries within 24 hours during business days.');
        sb_grid_end();
    sb_section_end();
}

/* ── Save all page meta ── */
add_action('save_post_page', function($post_id) {
    if (!isset($_POST['sb_page_nonce']) || !wp_verify_nonce($_POST['sb_page_nonce'], 'sb_page_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $all_keys = [
        'sb_hero_heading','sb_hero_subtitle','sb_hero_title','sb_hero_label','sb_hero_link_text',
        'sb_lifestyle_title',
        'sb_lifestyle_card1_title','sb_lifestyle_card1_desc',
        'sb_lifestyle_card2_title','sb_lifestyle_card2_desc',
        'sb_lifestyle_card3_title','sb_lifestyle_card3_desc',
        'sb_why_title','sb_why_col1_heading','sb_why_col1_text',
        'sb_why_col1_item1','sb_why_col1_item2','sb_why_col1_item3','sb_why_col1_item4',
        'sb_why_col2_heading','sb_why_col2_text',
        'sb_why_col2_item1','sb_why_col2_item2','sb_why_col2_item3','sb_why_col2_item4',
        'sb_why_col2_btn_text',
        'sb_faq_title',
        'sb_faq1_question','sb_faq1_answer','sb_faq2_question','sb_faq2_answer',
        'sb_faq3_question','sb_faq3_answer','sb_faq4_question','sb_faq4_answer',
        'sb_cta_title','sb_cta_text','sb_cta_btn_text','sb_cta_btn1_text','sb_cta_btn2_text',
        'sb_mission_card1_title','sb_mission_card1_text',
        'sb_mission_card2_title','sb_mission_card2_text',
        'sb_services_title','sb_services_subtitle',
        'sb_service1_title','sb_service1_desc','sb_service2_title','sb_service2_desc',
        'sb_service3_title','sb_service3_desc','sb_service4_title','sb_service4_desc',
        'sb_service5_title','sb_service5_desc','sb_service6_title','sb_service6_desc',
        'sb_step1_label','sb_step1_title','sb_step1_desc',
        'sb_step2_label','sb_step2_title','sb_step2_desc',
        'sb_step3_label','sb_step3_title','sb_step3_desc',
        'sb_step4_label','sb_step4_title','sb_step4_desc',
        'sb_included_title',
        'sb_included1_title','sb_included1_desc','sb_included2_title','sb_included2_desc',
        'sb_included3_title','sb_included3_desc','sb_included4_title','sb_included4_desc',
        'sb_form_title','sb_form_intro',
    ];

    foreach ($all_keys as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_textarea_field($_POST[$key]));
        }
    }
});
