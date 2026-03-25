<?php
/**
 * ACF Local Field Groups — registered in code, version-controlled.
 * Uses only ACF Free field types (text, textarea, wysiwyg).
 * Repeater fields replaced with numbered sets of individual fields.
 */
defined('ABSPATH') || exit;

add_action('init', 'sb_register_acf_fields', 20);

function sb_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) return;

    /* ═══════════════════════════════════════════════
       FRONT PAGE
    ═══════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'    => 'group_sb_frontpage',
        'title'  => 'Front Page Content',
        'fields' => [
            /* Hero */
            ['key' => 'field_sb_fp_hero_heading',  'label' => 'Hero heading',  'name' => 'hero_heading',  'type' => 'text',     'default_value' => 'Find your dream property in Ciudad Quesada'],
            ['key' => 'field_sb_fp_hero_subtitle', 'label' => 'Hero subtitle', 'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Discover beautiful homes in Ciudad Quesada and the urbanizations of Rojales.'],

            /* Lifestyle */
            ['key' => 'field_sb_fp_lifestyle_title', 'label' => 'Lifestyle section title', 'name' => 'lifestyle_title', 'type' => 'text', 'default_value' => 'The Ciudad Quesada lifestyle'],
            ['key' => 'field_sb_fp_lc1_title', 'label' => 'Lifestyle card 1 title', 'name' => 'lifestyle_card1_title', 'type' => 'text', 'default_value' => 'Sunshine & outdoor living'],
            ['key' => 'field_sb_fp_lc1_desc',  'label' => 'Lifestyle card 1 text',  'name' => 'lifestyle_card1_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'Enjoy over 320 days of sunshine per year in one of Spain\'s most popular residential areas.'],
            ['key' => 'field_sb_fp_lc2_title', 'label' => 'Lifestyle card 2 title', 'name' => 'lifestyle_card2_title', 'type' => 'text', 'default_value' => 'International community'],
            ['key' => 'field_sb_fp_lc2_desc',  'label' => 'Lifestyle card 2 text',  'name' => 'lifestyle_card2_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'A welcoming expat community with English-speaking services, restaurants, and amenities.'],
            ['key' => 'field_sb_fp_lc3_title', 'label' => 'Lifestyle card 3 title', 'name' => 'lifestyle_card3_title', 'type' => 'text', 'default_value' => 'Beaches & nature'],
            ['key' => 'field_sb_fp_lc3_desc',  'label' => 'Lifestyle card 3 text',  'name' => 'lifestyle_card3_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'Just minutes from stunning Mediterranean beaches and natural parks.'],

            /* Why Buy */
            ['key' => 'field_sb_fp_why_title',    'label' => 'Why Buy — section title',   'name' => 'why_title',        'type' => 'text',     'default_value' => 'Why buy in Ciudad Quesada'],
            ['key' => 'field_sb_fp_why_col1_h',   'label' => 'Column 1 heading',          'name' => 'why_col1_heading', 'type' => 'text',     'default_value' => 'Perfect Mediterranean lifestyle'],
            ['key' => 'field_sb_fp_why_col1_txt', 'label' => 'Column 1 paragraph',        'name' => 'why_col1_text',   'type' => 'textarea', 'rows' => 3, 'default_value' => "Ciudad Quesada is a popular residential area in Costa Blanca, offering a perfect blend of Spanish lifestyle with international amenities. With over 320 days of sunshine per year, it's an ideal location for those seeking a relaxed Mediterranean way of life."],
            ['key' => 'field_sb_fp_why_col1_i1',  'label' => 'Column 1 checklist item 1', 'name' => 'why_col1_item1',  'type' => 'text',     'default_value' => '320+ days of sunshine per year'],
            ['key' => 'field_sb_fp_why_col1_i2',  'label' => 'Column 1 checklist item 2', 'name' => 'why_col1_item2',  'type' => 'text',     'default_value' => 'Excellent golf courses and leisure facilities'],
            ['key' => 'field_sb_fp_why_col1_i3',  'label' => 'Column 1 checklist item 3', 'name' => 'why_col1_item3',  'type' => 'text',     'default_value' => 'International schools and medical facilities'],
            ['key' => 'field_sb_fp_why_col1_i4',  'label' => 'Column 1 checklist item 4', 'name' => 'why_col1_item4',  'type' => 'text',     'default_value' => 'Easy access to Alicante Airport'],
            ['key' => 'field_sb_fp_why_col2_h',   'label' => 'Column 2 heading',          'name' => 'why_col2_heading', 'type' => 'text',    'default_value' => 'Excellent investment opportunity'],
            ['key' => 'field_sb_fp_why_col2_txt', 'label' => 'Column 2 paragraph',        'name' => 'why_col2_text',   'type' => 'textarea', 'rows' => 3, 'default_value' => 'Ciudad Quesada offers exceptional value for money compared to other Mediterranean destinations, with a stable property market and strong rental potential.'],
            ['key' => 'field_sb_fp_why_col2_i1',  'label' => 'Column 2 checklist item 1', 'name' => 'why_col2_item1',  'type' => 'text',     'default_value' => 'Strong rental demand from tourists'],
            ['key' => 'field_sb_fp_why_col2_i2',  'label' => 'Column 2 checklist item 2', 'name' => 'why_col2_item2',  'type' => 'text',     'default_value' => 'Competitive property prices vs other Spanish resorts'],
            ['key' => 'field_sb_fp_why_col2_i3',  'label' => 'Column 2 checklist item 3', 'name' => 'why_col2_item3',  'type' => 'text',     'default_value' => 'Established legal framework for foreign buyers'],
            ['key' => 'field_sb_fp_why_col2_i4',  'label' => 'Column 2 checklist item 4', 'name' => 'why_col2_item4',  'type' => 'text',     'default_value' => 'Stable Spanish property market'],
            ['key' => 'field_sb_fp_why_btn_text', 'label' => 'Column 2 button text',      'name' => 'why_col2_btn_text', 'type' => 'text',   'default_value' => 'Browse Ciudad Quesada properties'],

            /* FAQ */
            ['key' => 'field_sb_fp_faq_title', 'label' => 'FAQ section title', 'name' => 'faq_title', 'type' => 'text', 'default_value' => 'Frequently asked questions'],
            ['key' => 'field_sb_fp_faq1_q', 'label' => 'FAQ 1 question', 'name' => 'faq1_question', 'type' => 'text',     'default_value' => 'Can I buy property in Spain as a foreigner?'],
            ['key' => 'field_sb_fp_faq1_a', 'label' => 'FAQ 1 answer',   'name' => 'faq1_answer',   'type' => 'textarea', 'rows' => 3, 'default_value' => 'Yes, foreigners can freely purchase property in Spain. You will need a NIE (Número de Identificación de Extranjero) number, which is a tax identification number for non-residents. Our team can guide you through this process.'],
            ['key' => 'field_sb_fp_faq2_q', 'label' => 'FAQ 2 question', 'name' => 'faq2_question', 'type' => 'text',     'default_value' => 'What are the additional costs when buying in Spain?'],
            ['key' => 'field_sb_fp_faq2_a', 'label' => 'FAQ 2 answer',   'name' => 'faq2_answer',   'type' => 'textarea', 'rows' => 3, 'default_value' => 'Typically 10–12% on top of the purchase price: 10% transfer tax (ITP) for resale properties, notary fees, land registry fees, and legal costs. New builds attract 10% VAT instead of ITP.'],
            ['key' => 'field_sb_fp_faq3_q', 'label' => 'FAQ 3 question', 'name' => 'faq3_question', 'type' => 'text',     'default_value' => 'How long does the buying process take?'],
            ['key' => 'field_sb_fp_faq3_a', 'label' => 'FAQ 3 answer',   'name' => 'faq3_answer',   'type' => 'textarea', 'rows' => 3, 'default_value' => 'Typically 4–8 weeks from agreeing a price to completion. The process involves signing a reservation contract, completing due diligence, and finalising at the notary.'],
            ['key' => 'field_sb_fp_faq4_q', 'label' => 'FAQ 4 question', 'name' => 'faq4_question', 'type' => 'text',     'default_value' => 'Do I need a lawyer?'],
            ['key' => 'field_sb_fp_faq4_a', 'label' => 'FAQ 4 answer',   'name' => 'faq4_answer',   'type' => 'textarea', 'rows' => 3, 'default_value' => 'While not legally required, we strongly recommend hiring an independent Spanish lawyer to carry out due diligence, review contracts, and protect your interests throughout the purchase.'],

            /* CTA Banner */
            ['key' => 'field_sb_fp_cta_title',    'label' => 'CTA title',       'name' => 'cta_title',    'type' => 'text',     'default_value' => 'Ready to find your dream property in Ciudad Quesada?'],
            ['key' => 'field_sb_fp_cta_text',     'label' => 'CTA paragraph',   'name' => 'cta_text',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'Our local property experts can help you find the perfect home that matches your requirements and budget.'],
            ['key' => 'field_sb_fp_cta_btn_text', 'label' => 'CTA button text', 'name' => 'cta_btn_text', 'type' => 'text',     'default_value' => 'Schedule a property viewing'],
        ],
        'location'        => [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]],
        'position'        => 'normal',
        'label_placement' => 'top',
    ]);

    /* ═══════════════════════════════════════════════
       ABOUT PAGE
    ═══════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'    => 'group_sb_about',
        'title'  => 'About Page Content',
        'fields' => [
            ['key' => 'field_sb_ab_hero_title',    'label' => 'Hero title',    'name' => 'hero_title',    'type' => 'text',     'default_value' => 'About Spaniabolig'],
            ['key' => 'field_sb_ab_hero_subtitle', 'label' => 'Hero subtitle', 'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'We help foreign buyers find their dream properties exclusively in Ciudad Quesada and the urbanizations of Rojales.'],

            ['key' => 'field_sb_ab_card1_title', 'label' => 'Mission card 1 title', 'name' => 'mission_card1_title', 'type' => 'text',   'default_value' => 'Our mission'],
            ['key' => 'field_sb_ab_card1_text',  'label' => 'Mission card 1 text',  'name' => 'mission_card1_text',  'type' => 'wysiwyg', 'default_value' => '<p>At Spaniabolig, we understand that finding the right property in a foreign country can be challenging. Our mission is to simplify this process for international buyers by providing a transparent property listing service that showcases the best properties in Ciudad Quesada.</p>'],
            ['key' => 'field_sb_ab_card2_title', 'label' => 'Mission card 2 title', 'name' => 'mission_card2_title', 'type' => 'text',   'default_value' => 'Your trusted partner for Spanish property'],
            ['key' => 'field_sb_ab_card2_text',  'label' => 'Mission card 2 text',  'name' => 'mission_card2_text',  'type' => 'wysiwyg', 'default_value' => '<p>Spaniabolig is your professional property partner specializing exclusively in Ciudad Quesada and the surrounding urbanizations of Rojales on the Costa Blanca.</p>'],

            ['key' => 'field_sb_ab_services_title', 'label' => 'Services section title', 'name' => 'services_title', 'type' => 'text', 'default_value' => 'How we help you'],
            ['key' => 'field_sb_ab_svc1_title', 'label' => 'Service 1 title', 'name' => 'service1_title', 'type' => 'text',     'default_value' => 'Property search'],
            ['key' => 'field_sb_ab_svc1_desc',  'label' => 'Service 1 desc',  'name' => 'service1_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'We search our database of properties to find the best match for your requirements and budget.'],
            ['key' => 'field_sb_ab_svc2_title', 'label' => 'Service 2 title', 'name' => 'service2_title', 'type' => 'text',     'default_value' => 'Local expertise'],
            ['key' => 'field_sb_ab_svc2_desc',  'label' => 'Service 2 desc',  'name' => 'service2_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'Our deep knowledge of Ciudad Quesada means we can advise on the best areas, amenities, and investment potential.'],
            ['key' => 'field_sb_ab_svc3_title', 'label' => 'Service 3 title', 'name' => 'service3_title', 'type' => 'text',     'default_value' => 'Viewing coordination'],
            ['key' => 'field_sb_ab_svc3_desc',  'label' => 'Service 3 desc',  'name' => 'service3_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'We arrange property viewings and accompany you to ensure you get all the information you need.'],
            ['key' => 'field_sb_ab_svc4_title', 'label' => 'Service 4 title', 'name' => 'service4_title', 'type' => 'text',     'default_value' => 'After-sales support'],
            ['key' => 'field_sb_ab_svc4_desc',  'label' => 'Service 4 desc',  'name' => 'service4_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'We stay with you after purchase, helping with utilities, key holding, and property management referrals.'],

            ['key' => 'field_sb_ab_cta_title',    'label' => 'CTA title',       'name' => 'cta_title',    'type' => 'text',     'default_value' => 'Ready to find your dream property?'],
            ['key' => 'field_sb_ab_cta_text',     'label' => 'CTA paragraph',   'name' => 'cta_text',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'Start your journey towards owning your ideal home in Ciudad Quesada today.'],
            ['key' => 'field_sb_ab_cta_btn_text', 'label' => 'CTA button text', 'name' => 'cta_btn_text', 'type' => 'text',     'default_value' => 'Browse properties'],
        ],
        'location'        => [[['param' => 'page_slug', 'operator' => '==', 'value' => 'about']]],
        'position'        => 'normal',
        'label_placement' => 'top',
    ]);

    /* ═══════════════════════════════════════════════
       HOW IT WORKS PAGE
    ═══════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'    => 'group_sb_how_it_works',
        'title'  => 'How It Works — Page Content',
        'fields' => [
            ['key' => 'field_sb_hiw_hero_title',    'label' => 'Hero title',    'name' => 'hero_title',    'type' => 'text',     'default_value' => 'How it works'],
            ['key' => 'field_sb_hiw_hero_subtitle', 'label' => 'Hero subtitle', 'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Finding your perfect property in Ciudad Quesada is easy with our step-by-step process.'],

            ['key' => 'field_sb_hiw_step1_label', 'label' => 'Step 1 label', 'name' => 'step1_label', 'type' => 'text',     'default_value' => 'Step 1'],
            ['key' => 'field_sb_hiw_step1_title', 'label' => 'Step 1 title', 'name' => 'step1_title', 'type' => 'text',     'default_value' => 'Tell us what you\'re looking for'],
            ['key' => 'field_sb_hiw_step1_desc',  'label' => 'Step 1 desc',  'name' => 'step1_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'Contact us with your requirements — budget, property type, number of bedrooms, and any specific preferences for location or amenities.'],
            ['key' => 'field_sb_hiw_step2_label', 'label' => 'Step 2 label', 'name' => 'step2_label', 'type' => 'text',     'default_value' => 'Step 2'],
            ['key' => 'field_sb_hiw_step2_title', 'label' => 'Step 2 title', 'name' => 'step2_title', 'type' => 'text',     'default_value' => 'We find matching properties'],
            ['key' => 'field_sb_hiw_step2_desc',  'label' => 'Step 2 desc',  'name' => 'step2_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'Our team searches our database and network to find properties that match your criteria in Ciudad Quesada and the surrounding urbanizations.'],
            ['key' => 'field_sb_hiw_step3_label', 'label' => 'Step 3 label', 'name' => 'step3_label', 'type' => 'text',     'default_value' => 'Step 3'],
            ['key' => 'field_sb_hiw_step3_title', 'label' => 'Step 3 title', 'name' => 'step3_title', 'type' => 'text',     'default_value' => 'View properties in person'],
            ['key' => 'field_sb_hiw_step3_desc',  'label' => 'Step 3 desc',  'name' => 'step3_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'We arrange viewings at times that suit you. Our local team accompanies you to each property to answer questions and provide honest advice.'],
            ['key' => 'field_sb_hiw_step4_label', 'label' => 'Step 4 label', 'name' => 'step4_label', 'type' => 'text',     'default_value' => 'Step 4'],
            ['key' => 'field_sb_hiw_step4_title', 'label' => 'Step 4 title', 'name' => 'step4_title', 'type' => 'text',     'default_value' => 'Complete your purchase'],
            ['key' => 'field_sb_hiw_step4_desc',  'label' => 'Step 4 desc',  'name' => 'step4_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'Once you\'ve found your dream property, we guide you through the legal process, connect you with a trusted lawyer, and support you all the way to completion.'],

            ['key' => 'field_sb_hiw_incl_title', 'label' => 'Included section title', 'name' => 'included_title', 'type' => 'text', 'default_value' => "What's included in our service"],
            ['key' => 'field_sb_hiw_inc1_title', 'label' => 'Included 1 title', 'name' => 'included1_title', 'type' => 'text',     'default_value' => 'Free property search'],
            ['key' => 'field_sb_hiw_inc1_desc',  'label' => 'Included 1 desc',  'name' => 'included1_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'No cost to you — we are paid by the seller on completion.'],
            ['key' => 'field_sb_hiw_inc2_title', 'label' => 'Included 2 title', 'name' => 'included2_title', 'type' => 'text',     'default_value' => 'Local market knowledge'],
            ['key' => 'field_sb_hiw_inc2_desc',  'label' => 'Included 2 desc',  'name' => 'included2_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'Years of experience in the Ciudad Quesada property market.'],
            ['key' => 'field_sb_hiw_inc3_title', 'label' => 'Included 3 title', 'name' => 'included3_title', 'type' => 'text',     'default_value' => 'Viewing assistance'],
            ['key' => 'field_sb_hiw_inc3_desc',  'label' => 'Included 3 desc',  'name' => 'included3_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'We accompany you to all property viewings and provide unbiased advice.'],
            ['key' => 'field_sb_hiw_inc4_title', 'label' => 'Included 4 title', 'name' => 'included4_title', 'type' => 'text',     'default_value' => 'After-sales support'],
            ['key' => 'field_sb_hiw_inc4_desc',  'label' => 'Included 4 desc',  'name' => 'included4_desc',  'type' => 'textarea', 'rows' => 2, 'default_value' => 'We stay in touch after completion to help you settle in.'],

            ['key' => 'field_sb_hiw_cta_title',     'label' => 'CTA title',         'name' => 'cta_title',     'type' => 'text',     'default_value' => 'Ready to start your property search?'],
            ['key' => 'field_sb_hiw_cta_text',      'label' => 'CTA paragraph',     'name' => 'cta_text',      'type' => 'textarea', 'rows' => 2, 'default_value' => 'Browse our current listings or get in touch with our local team to discuss your requirements.'],
            ['key' => 'field_sb_hiw_cta_btn1_text', 'label' => 'CTA button 1 text', 'name' => 'cta_btn1_text', 'type' => 'text',     'default_value' => 'Browse properties'],
            ['key' => 'field_sb_hiw_cta_btn2_text', 'label' => 'CTA button 2 text', 'name' => 'cta_btn2_text', 'type' => 'text',     'default_value' => 'Contact us'],
        ],
        'location'        => [[['param' => 'page_slug', 'operator' => '==', 'value' => 'how-it-works']]],
        'position'        => 'normal',
        'label_placement' => 'top',
    ]);

    /* ═══════════════════════════════════════════════
       SERVICES PAGE
    ═══════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'    => 'group_sb_services',
        'title'  => 'Services Page Content',
        'fields' => [
            ['key' => 'field_sb_sv_hero_label',    'label' => 'Hero badge label', 'name' => 'hero_label',    'type' => 'text',     'default_value' => 'Property Management'],
            ['key' => 'field_sb_sv_hero_title',    'label' => 'Hero title',       'name' => 'hero_title',    'type' => 'text',     'default_value' => 'Services for property owners in Spain'],
            ['key' => 'field_sb_sv_hero_subtitle', 'label' => 'Hero subtitle',    'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Spaniabolig Real Estate offers a number of services to you who own a home in Spain.'],
            ['key' => 'field_sb_sv_hero_link_text','label' => 'Hero link text',   'name' => 'hero_link_text','type' => 'text',     'default_value' => 'Contact us today'],

            ['key' => 'field_sb_sv_grid_title',    'label' => 'Services grid title',    'name' => 'services_title',    'type' => 'text', 'default_value' => 'Our services'],
            ['key' => 'field_sb_sv_grid_subtitle', 'label' => 'Services grid subtitle', 'name' => 'services_subtitle', 'type' => 'text', 'default_value' => "From key holding to professional photography — we take care of your Spanish property so you don't have to."],
            ['key' => 'field_sb_sv_svc1_title', 'label' => 'Service 1 title', 'name' => 'service1_title', 'type' => 'text',     'default_value' => 'Key holding'],
            ['key' => 'field_sb_sv_svc1_desc',  'label' => 'Service 1 desc',  'name' => 'service1_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'We hold a set of keys to your property and carry out regular inspections when you\'re away.'],
            ['key' => 'field_sb_sv_svc2_title', 'label' => 'Service 2 title', 'name' => 'service2_title', 'type' => 'text',     'default_value' => 'Property maintenance'],
            ['key' => 'field_sb_sv_svc2_desc',  'label' => 'Service 2 desc',  'name' => 'service2_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'We coordinate repairs and maintenance with trusted local tradespeople.'],
            ['key' => 'field_sb_sv_svc3_title', 'label' => 'Service 3 title', 'name' => 'service3_title', 'type' => 'text',     'default_value' => 'Rental management'],
            ['key' => 'field_sb_sv_svc3_desc',  'label' => 'Service 3 desc',  'name' => 'service3_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'We manage your property rental, including guest check-in/check-out, cleaning, and rental income reporting.'],
            ['key' => 'field_sb_sv_svc4_title', 'label' => 'Service 4 title', 'name' => 'service4_title', 'type' => 'text',     'default_value' => 'Utility management'],
            ['key' => 'field_sb_sv_svc4_desc',  'label' => 'Service 4 desc',  'name' => 'service4_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'We handle electricity, water, internet, and community fees on your behalf.'],
            ['key' => 'field_sb_sv_svc5_title', 'label' => 'Service 5 title', 'name' => 'service5_title', 'type' => 'text',     'default_value' => 'Professional photography'],
            ['key' => 'field_sb_sv_svc5_desc',  'label' => 'Service 5 desc',  'name' => 'service5_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'High-quality photos and virtual tours to showcase your property at its best.'],
            ['key' => 'field_sb_sv_svc6_title', 'label' => 'Service 6 title', 'name' => 'service6_title', 'type' => 'text',     'default_value' => 'Airport transfers'],
            ['key' => 'field_sb_sv_svc6_desc',  'label' => 'Service 6 desc',  'name' => 'service6_desc',  'type' => 'textarea', 'rows' => 3, 'default_value' => 'We arrange reliable airport transfers for you and your guests to and from Alicante Airport.'],

            ['key' => 'field_sb_sv_cta_title',    'label' => 'CTA title',       'name' => 'cta_title',    'type' => 'text',     'default_value' => 'Interested in our property services?'],
            ['key' => 'field_sb_sv_cta_text',     'label' => 'CTA paragraph',   'name' => 'cta_text',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'Get in touch with our team to discuss how we can help look after your Spanish property.'],
            ['key' => 'field_sb_sv_cta_btn_text', 'label' => 'CTA button text', 'name' => 'cta_btn_text', 'type' => 'text',     'default_value' => 'Contact us today'],
        ],
        'location'        => [[['param' => 'page_slug', 'operator' => '==', 'value' => 'services']]],
        'position'        => 'normal',
        'label_placement' => 'top',
    ]);

    /* ═══════════════════════════════════════════════
       CONTACT PAGE
    ═══════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'    => 'group_sb_contact',
        'title'  => 'Contact Page Content',
        'fields' => [
            ['key' => 'field_sb_ct_hero_title',    'label' => 'Hero title',       'name' => 'hero_title',    'type' => 'text',     'default_value' => 'Contact us'],
            ['key' => 'field_sb_ct_hero_subtitle', 'label' => 'Hero subtitle',    'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Get in touch with Spaniabolig for any questions about properties in Ciudad Quesada.'],
            ['key' => 'field_sb_ct_form_title',    'label' => 'Form card title',  'name' => 'form_title',    'type' => 'text',     'default_value' => 'Send us a message'],
            ['key' => 'field_sb_ct_form_intro',    'label' => 'Form card intro',  'name' => 'form_intro',    'type' => 'text',     'default_value' => 'We aim to respond to all inquiries within 24 hours during business days.'],
        ],
        'location'        => [[['param' => 'page_slug', 'operator' => '==', 'value' => 'contact']]],
        'position'        => 'normal',
        'label_placement' => 'top',
    ]);
}
