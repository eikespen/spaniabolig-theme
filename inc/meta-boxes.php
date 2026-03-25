<?php
/**
 * Native WordPress meta boxes for editable page content.
 * No plugin required — uses add_meta_box() + get/update_post_meta().
 */
defined('ABSPATH') || exit;

/* ── Register meta boxes for specific pages ── */
add_action('add_meta_boxes_page', function($post) {
    $slug       = $post->post_name;
    $front_id   = (int) get_option('page_on_front');
    $is_front   = ($post->ID === $front_id);

    if ($is_front) {
        add_meta_box('sb_fp',       'Front Page Content',       'sb_mb_frontpage', 'page', 'normal', 'high');
    }
    if ($slug === 'about') {
        add_meta_box('sb_about',    'About Page Content',       'sb_mb_about',     'page', 'normal', 'high');
    }
    if ($slug === 'how-it-works') {
        add_meta_box('sb_hiw',      'How It Works — Content',   'sb_mb_hiw',       'page', 'normal', 'high');
    }
    if ($slug === 'services') {
        add_meta_box('sb_services', 'Services Page Content',    'sb_mb_services',  'page', 'normal', 'high');
    }
    if ($slug === 'contact') {
        add_meta_box('sb_contact',  'Contact Page Content',     'sb_mb_contact',   'page', 'normal', 'high');
    }
});

/* ── Render helpers ── */
function sb_mb_field($post, $key, $label, $type = 'text', $default = '') {
    $val = get_post_meta($post->ID, $key, true);
    if ($val === '') $val = $default;
    echo '<p><label style="font-weight:600;display:block;margin-bottom:3px">' . esc_html($label) . '</label>';
    if ($type === 'textarea') {
        echo '<textarea name="' . esc_attr($key) . '" rows="3" style="width:100%">' . esc_textarea($val) . '</textarea>';
    } else {
        echo '<input type="text" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '" style="width:100%">';
    }
    echo '</p>';
}
function sb_mb_heading($text) {
    echo '<h4 style="margin:16px 0 4px;border-bottom:1px solid #ddd;padding-bottom:4px">' . esc_html($text) . '</h4>';
}

/* ── Front Page ── */
function sb_mb_frontpage($post) {
    wp_nonce_field('sb_page_meta', 'sb_page_nonce');
    sb_mb_heading('Hero');
    sb_mb_field($post, 'sb_hero_heading',  'Hero heading',  'text',     'Find your dream property in Ciudad Quesada');
    sb_mb_field($post, 'sb_hero_subtitle', 'Hero subtitle', 'textarea', 'Discover beautiful homes in Ciudad Quesada and the urbanizations of Rojales.');

    sb_mb_heading('Lifestyle section');
    sb_mb_field($post, 'sb_lifestyle_title',        'Section title',         'text',     'The Ciudad Quesada lifestyle');
    sb_mb_field($post, 'sb_lifestyle_card1_title',  'Card 1 title',          'text',     'Sunshine & outdoor living');
    sb_mb_field($post, 'sb_lifestyle_card1_desc',   'Card 1 text',           'textarea', 'Enjoy over 320 days of sunshine per year in one of Spain\'s most popular residential areas.');
    sb_mb_field($post, 'sb_lifestyle_card2_title',  'Card 2 title',          'text',     'International community');
    sb_mb_field($post, 'sb_lifestyle_card2_desc',   'Card 2 text',           'textarea', 'A welcoming expat community with English-speaking services, restaurants, and amenities.');
    sb_mb_field($post, 'sb_lifestyle_card3_title',  'Card 3 title',          'text',     'Beaches & nature');
    sb_mb_field($post, 'sb_lifestyle_card3_desc',   'Card 3 text',           'textarea', 'Just minutes from stunning Mediterranean beaches and natural parks.');

    sb_mb_heading('Why Buy — Column 1');
    sb_mb_field($post, 'sb_why_title',        'Section title',    'text',     'Why buy in Ciudad Quesada');
    sb_mb_field($post, 'sb_why_col1_heading', 'Column 1 heading', 'text',     'Perfect Mediterranean lifestyle');
    sb_mb_field($post, 'sb_why_col1_text',    'Column 1 text',    'textarea', "Ciudad Quesada is a popular residential area in Costa Blanca, offering a perfect blend of Spanish lifestyle with international amenities. With over 320 days of sunshine per year, it's an ideal location.");
    sb_mb_field($post, 'sb_why_col1_item1',   'Checklist item 1', 'text',     '320+ days of sunshine per year');
    sb_mb_field($post, 'sb_why_col1_item2',   'Checklist item 2', 'text',     'Excellent golf courses and leisure facilities');
    sb_mb_field($post, 'sb_why_col1_item3',   'Checklist item 3', 'text',     'International schools and medical facilities');
    sb_mb_field($post, 'sb_why_col1_item4',   'Checklist item 4', 'text',     'Easy access to Alicante Airport');

    sb_mb_heading('Why Buy — Column 2');
    sb_mb_field($post, 'sb_why_col2_heading',  'Column 2 heading', 'text',     'Excellent investment opportunity');
    sb_mb_field($post, 'sb_why_col2_text',     'Column 2 text',    'textarea', 'Ciudad Quesada offers exceptional value for money compared to other Mediterranean destinations, with a stable property market and strong rental potential.');
    sb_mb_field($post, 'sb_why_col2_item1',    'Checklist item 1', 'text',     'Strong rental demand from tourists');
    sb_mb_field($post, 'sb_why_col2_item2',    'Checklist item 2', 'text',     'Competitive property prices vs other Spanish resorts');
    sb_mb_field($post, 'sb_why_col2_item3',    'Checklist item 3', 'text',     'Established legal framework for foreign buyers');
    sb_mb_field($post, 'sb_why_col2_item4',    'Checklist item 4', 'text',     'Stable Spanish property market');
    sb_mb_field($post, 'sb_why_col2_btn_text', 'Button text',      'text',     'Browse Ciudad Quesada properties');

    sb_mb_heading('FAQs');
    sb_mb_field($post, 'sb_faq_title',     'Section title', 'text', 'Frequently asked questions');
    sb_mb_field($post, 'sb_faq1_question', 'FAQ 1 question', 'text',     'Can I buy property in Spain as a foreigner?');
    sb_mb_field($post, 'sb_faq1_answer',   'FAQ 1 answer',   'textarea', 'Yes, foreigners can freely purchase property in Spain. You will need a NIE number, which is a tax identification number for non-residents.');
    sb_mb_field($post, 'sb_faq2_question', 'FAQ 2 question', 'text',     'What are the additional costs when buying in Spain?');
    sb_mb_field($post, 'sb_faq2_answer',   'FAQ 2 answer',   'textarea', 'Typically 10–12% on top of the purchase price: 10% transfer tax, notary fees, land registry fees, and legal costs.');
    sb_mb_field($post, 'sb_faq3_question', 'FAQ 3 question', 'text',     'How long does the buying process take?');
    sb_mb_field($post, 'sb_faq3_answer',   'FAQ 3 answer',   'textarea', 'Typically 4–8 weeks from agreeing a price to completion.');
    sb_mb_field($post, 'sb_faq4_question', 'FAQ 4 question', 'text',     'Do I need a lawyer?');
    sb_mb_field($post, 'sb_faq4_answer',   'FAQ 4 answer',   'textarea', 'While not legally required, we strongly recommend hiring an independent Spanish lawyer to carry out due diligence and protect your interests.');

    sb_mb_heading('CTA Banner');
    sb_mb_field($post, 'sb_cta_title',    'CTA title',       'text',     'Ready to find your dream property in Ciudad Quesada?');
    sb_mb_field($post, 'sb_cta_text',     'CTA paragraph',   'textarea', 'Our local property experts can help you find the perfect home that matches your requirements and budget.');
    sb_mb_field($post, 'sb_cta_btn_text', 'CTA button text', 'text',     'Schedule a property viewing');
}

/* ── About Page ── */
function sb_mb_about($post) {
    wp_nonce_field('sb_page_meta', 'sb_page_nonce');
    sb_mb_heading('Hero');
    sb_mb_field($post, 'sb_hero_title',    'Hero title',    'text',     'About Spaniabolig');
    sb_mb_field($post, 'sb_hero_subtitle', 'Hero subtitle', 'textarea', 'We help foreign buyers find their dream properties exclusively in Ciudad Quesada and the urbanizations of Rojales.');

    sb_mb_heading('Mission Cards');
    sb_mb_field($post, 'sb_mission_card1_title', 'Card 1 title', 'text',     'Our mission');
    sb_mb_field($post, 'sb_mission_card1_text',  'Card 1 text',  'textarea', 'At Spaniabolig, we understand that finding the right property in a foreign country can be challenging. Our mission is to simplify this process for international buyers.');
    sb_mb_field($post, 'sb_mission_card2_title', 'Card 2 title', 'text',     'Your trusted partner for Spanish property');
    sb_mb_field($post, 'sb_mission_card2_text',  'Card 2 text',  'textarea', 'Spaniabolig is your professional property partner specializing exclusively in Ciudad Quesada and the surrounding urbanizations of Rojales on the Costa Blanca.');

    sb_mb_heading('How We Help');
    sb_mb_field($post, 'sb_services_title', 'Section title', 'text', 'How we help you');
    sb_mb_field($post, 'sb_service1_title', 'Service 1 title', 'text',     'Property search');
    sb_mb_field($post, 'sb_service1_desc',  'Service 1 desc',  'textarea', 'We search our database of properties to find the best match for your requirements and budget.');
    sb_mb_field($post, 'sb_service2_title', 'Service 2 title', 'text',     'Local expertise');
    sb_mb_field($post, 'sb_service2_desc',  'Service 2 desc',  'textarea', 'Our deep knowledge of Ciudad Quesada means we can advise on the best areas, amenities, and investment potential.');
    sb_mb_field($post, 'sb_service3_title', 'Service 3 title', 'text',     'Viewing coordination');
    sb_mb_field($post, 'sb_service3_desc',  'Service 3 desc',  'textarea', 'We arrange property viewings and accompany you to ensure you get all the information you need.');
    sb_mb_field($post, 'sb_service4_title', 'Service 4 title', 'text',     'After-sales support');
    sb_mb_field($post, 'sb_service4_desc',  'Service 4 desc',  'textarea', 'We stay with you after purchase, helping with utilities, key holding, and property management referrals.');

    sb_mb_heading('CTA');
    sb_mb_field($post, 'sb_cta_title',    'CTA title',       'text',     'Ready to find your dream property?');
    sb_mb_field($post, 'sb_cta_text',     'CTA paragraph',   'textarea', 'Start your journey towards owning your ideal home in Ciudad Quesada today.');
    sb_mb_field($post, 'sb_cta_btn_text', 'CTA button text', 'text',     'Browse properties');
}

/* ── How It Works Page ── */
function sb_mb_hiw($post) {
    wp_nonce_field('sb_page_meta', 'sb_page_nonce');
    sb_mb_heading('Hero');
    sb_mb_field($post, 'sb_hero_title',    'Hero title',    'text',     'How it works');
    sb_mb_field($post, 'sb_hero_subtitle', 'Hero subtitle', 'textarea', 'Finding your perfect property in Ciudad Quesada is easy with our step-by-step process.');

    sb_mb_heading('Steps');
    foreach (range(1, 4) as $i) {
        sb_mb_heading("Step $i");
        sb_mb_field($post, "sb_step{$i}_label", "Step $i label", 'text',     "Step $i");
        sb_mb_field($post, "sb_step{$i}_title", "Step $i title", 'text',     ['Tell us what you\'re looking for', 'We find matching properties', 'View properties in person', 'Complete your purchase'][$i - 1]);
        sb_mb_field($post, "sb_step{$i}_desc",  "Step $i desc",  'textarea', '');
    }

    sb_mb_heading("What's Included");
    sb_mb_field($post, 'sb_included_title',   'Section title',    'text', "What's included in our service");
    sb_mb_field($post, 'sb_included1_title',  'Item 1 title',     'text',     'Free property search');
    sb_mb_field($post, 'sb_included1_desc',   'Item 1 desc',      'textarea', 'No cost to you — we are paid by the seller on completion.');
    sb_mb_field($post, 'sb_included2_title',  'Item 2 title',     'text',     'Local market knowledge');
    sb_mb_field($post, 'sb_included2_desc',   'Item 2 desc',      'textarea', 'Years of experience in the Ciudad Quesada property market.');
    sb_mb_field($post, 'sb_included3_title',  'Item 3 title',     'text',     'Viewing assistance');
    sb_mb_field($post, 'sb_included3_desc',   'Item 3 desc',      'textarea', 'We accompany you to all property viewings and provide unbiased advice.');
    sb_mb_field($post, 'sb_included4_title',  'Item 4 title',     'text',     'After-sales support');
    sb_mb_field($post, 'sb_included4_desc',   'Item 4 desc',      'textarea', 'We stay in touch after completion to help you settle in.');

    sb_mb_heading('CTA');
    sb_mb_field($post, 'sb_cta_title',     'CTA title',         'text',     'Ready to start your property search?');
    sb_mb_field($post, 'sb_cta_text',      'CTA paragraph',     'textarea', 'Browse our current listings or get in touch with our local team to discuss your requirements.');
    sb_mb_field($post, 'sb_cta_btn1_text', 'CTA button 1 text', 'text',     'Browse properties');
    sb_mb_field($post, 'sb_cta_btn2_text', 'CTA button 2 text', 'text',     'Contact us');
}

/* ── Services Page ── */
function sb_mb_services($post) {
    wp_nonce_field('sb_page_meta', 'sb_page_nonce');
    sb_mb_heading('Hero');
    sb_mb_field($post, 'sb_hero_label',    'Hero badge',    'text',     'Property Management');
    sb_mb_field($post, 'sb_hero_title',    'Hero title',    'text',     'Services for property owners in Spain');
    sb_mb_field($post, 'sb_hero_subtitle', 'Hero subtitle', 'textarea', 'Spaniabolig Real Estate offers a number of services to you who own a home in Spain.');
    sb_mb_field($post, 'sb_hero_link_text','Hero link text','text',     'Contact us today');

    sb_mb_heading('Services Grid');
    sb_mb_field($post, 'sb_services_title',    'Grid title',    'text', 'Our services');
    sb_mb_field($post, 'sb_services_subtitle', 'Grid subtitle', 'text', "From key holding to professional photography — we take care of your Spanish property so you don't have to.");
    foreach (range(1, 6) as $i) {
        $defaults = ['Key holding', 'Property maintenance', 'Rental management', 'Utility management', 'Professional photography', 'Airport transfers'];
        sb_mb_field($post, "sb_service{$i}_title", "Service $i title", 'text',     $defaults[$i - 1]);
        sb_mb_field($post, "sb_service{$i}_desc",  "Service $i desc",  'textarea', '');
    }

    sb_mb_heading('CTA');
    sb_mb_field($post, 'sb_cta_title',    'CTA title',       'text',     'Interested in our property services?');
    sb_mb_field($post, 'sb_cta_text',     'CTA paragraph',   'textarea', 'Get in touch with our team to discuss how we can help look after your Spanish property.');
    sb_mb_field($post, 'sb_cta_btn_text', 'CTA button text', 'text',     'Contact us today');
}

/* ── Contact Page ── */
function sb_mb_contact($post) {
    wp_nonce_field('sb_page_meta', 'sb_page_nonce');
    sb_mb_heading('Hero');
    sb_mb_field($post, 'sb_hero_title',    'Hero title',    'text',     'Contact us');
    sb_mb_field($post, 'sb_hero_subtitle', 'Hero subtitle', 'textarea', 'Get in touch with Spaniabolig for any questions about properties in Ciudad Quesada.');

    sb_mb_heading('Form Card');
    sb_mb_field($post, 'sb_form_title', 'Form title', 'text', 'Send us a message');
    sb_mb_field($post, 'sb_form_intro', 'Form intro', 'text', 'We aim to respond to all inquiries within 24 hours during business days.');
}

/* ── Save all page meta ── */
add_action('save_post_page', function($post_id) {
    if (!isset($_POST['sb_page_nonce']) || !wp_verify_nonce($_POST['sb_page_nonce'], 'sb_page_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $all_keys = [
        // Front page
        'sb_hero_heading','sb_hero_subtitle','sb_lifestyle_title',
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
        'sb_cta_title','sb_cta_text','sb_cta_btn_text',
        // About
        'sb_hero_title','sb_mission_card1_title','sb_mission_card1_text',
        'sb_mission_card2_title','sb_mission_card2_text','sb_services_title',
        'sb_service1_title','sb_service1_desc','sb_service2_title','sb_service2_desc',
        'sb_service3_title','sb_service3_desc','sb_service4_title','sb_service4_desc',
        // How it works
        'sb_step1_label','sb_step1_title','sb_step1_desc',
        'sb_step2_label','sb_step2_title','sb_step2_desc',
        'sb_step3_label','sb_step3_title','sb_step3_desc',
        'sb_step4_label','sb_step4_title','sb_step4_desc',
        'sb_included_title',
        'sb_included1_title','sb_included1_desc','sb_included2_title','sb_included2_desc',
        'sb_included3_title','sb_included3_desc','sb_included4_title','sb_included4_desc',
        'sb_cta_btn1_text','sb_cta_btn2_text',
        // Services
        'sb_hero_label','sb_hero_link_text','sb_services_subtitle',
        'sb_service5_title','sb_service5_desc','sb_service6_title','sb_service6_desc',
        // Contact
        'sb_form_title','sb_form_intro',
    ];

    foreach ($all_keys as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_textarea_field($_POST[$key]));
        }
    }
});
