<?php
/**
 * ACF Local Field Groups — registered in code so fields are version-controlled.
 * Requires Advanced Custom Fields (free or Pro) to be installed and active.
 */
defined('ABSPATH') || exit;

add_action('init', 'sb_register_acf_fields', 20);

function sb_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) return;

    /* ═══════════════════════════════════════════════
       FRONT PAGE
    ═══════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'            => 'group_sb_frontpage',
        'title'          => 'Front Page Content',
        'fields'         => [

            /* ── Hero ── */
            ['key' => 'field_sb_fp_hero_heading',  'label' => 'Hero heading',  'name' => 'hero_heading',  'type' => 'text',  'default_value' => 'Find your dream property in Ciudad Quesada'],
            ['key' => 'field_sb_fp_hero_subtitle', 'label' => 'Hero subtitle', 'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Discover beautiful homes in Ciudad Quesada and the urbanizations of Rojales with properties to suit every lifestyle and budget.'],

            /* ── Lifestyle section ── */
            ['key' => 'field_sb_fp_lifestyle_title', 'label' => 'Lifestyle section title', 'name' => 'lifestyle_title', 'type' => 'text', 'default_value' => 'The Ciudad Quesada lifestyle'],
            [
                'key'        => 'field_sb_fp_lifestyle_cards',
                'label'      => 'Lifestyle cards',
                'name'       => 'lifestyle_cards',
                'type'       => 'repeater',
                'min'        => 1,
                'max'        => 6,
                'layout'     => 'block',
                'button_label' => 'Add card',
                'sub_fields' => [
                    ['key' => 'field_sb_fp_lc_title', 'label' => 'Card title', 'name' => 'card_title', 'type' => 'text'],
                    ['key' => 'field_sb_fp_lc_desc',  'label' => 'Card description', 'name' => 'card_desc', 'type' => 'textarea', 'rows' => 2],
                ],
            ],

            /* ── Why Buy ── */
            ['key' => 'field_sb_fp_why_title',    'label' => 'Why Buy — section title', 'name' => 'why_title',    'type' => 'text',  'default_value' => 'Why buy in Ciudad Quesada'],
            ['key' => 'field_sb_fp_why_col1_h',   'label' => 'Column 1 heading', 'name' => 'why_col1_heading',   'type' => 'text',  'default_value' => 'Perfect Mediterranean lifestyle'],
            ['key' => 'field_sb_fp_why_col1_txt',  'label' => 'Column 1 paragraph', 'name' => 'why_col1_text',   'type' => 'textarea', 'rows' => 3, 'default_value' => "Ciudad Quesada is a popular residential area in Costa Blanca, offering a perfect blend of Spanish lifestyle with international amenities. With over 320 days of sunshine per year, it's an ideal location for those seeking a relaxed Mediterranean way of life."],
            [
                'key'        => 'field_sb_fp_why_col1_items',
                'label'      => 'Column 1 checklist',
                'name'       => 'why_col1_items',
                'type'       => 'repeater',
                'layout'     => 'table',
                'button_label' => 'Add item',
                'sub_fields' => [
                    ['key' => 'field_sb_fp_wc1_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                ],
            ],
            ['key' => 'field_sb_fp_why_col2_h',   'label' => 'Column 2 heading', 'name' => 'why_col2_heading',   'type' => 'text',  'default_value' => 'Excellent investment opportunity'],
            ['key' => 'field_sb_fp_why_col2_txt',  'label' => 'Column 2 paragraph', 'name' => 'why_col2_text',   'type' => 'textarea', 'rows' => 3, 'default_value' => 'Ciudad Quesada offers exceptional value for money compared to other Mediterranean destinations, with a stable property market and strong rental potential.'],
            [
                'key'        => 'field_sb_fp_why_col2_items',
                'label'      => 'Column 2 checklist',
                'name'       => 'why_col2_items',
                'type'       => 'repeater',
                'layout'     => 'table',
                'button_label' => 'Add item',
                'sub_fields' => [
                    ['key' => 'field_sb_fp_wc2_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                ],
            ],
            ['key' => 'field_sb_fp_why_btn_text', 'label' => 'Column 2 button text', 'name' => 'why_col2_btn_text', 'type' => 'text', 'default_value' => 'Browse Ciudad Quesada properties'],

            /* ── FAQ ── */
            ['key' => 'field_sb_fp_faq_title', 'label' => 'FAQ section title', 'name' => 'faq_title', 'type' => 'text', 'default_value' => 'Frequently asked questions'],
            [
                'key'        => 'field_sb_fp_faqs',
                'label'      => 'FAQ items',
                'name'       => 'faqs',
                'type'       => 'repeater',
                'min'        => 1,
                'layout'     => 'block',
                'button_label' => 'Add FAQ',
                'sub_fields' => [
                    ['key' => 'field_sb_fp_faq_q', 'label' => 'Question', 'name' => 'question', 'type' => 'text'],
                    ['key' => 'field_sb_fp_faq_a', 'label' => 'Answer',   'name' => 'answer',   'type' => 'textarea', 'rows' => 4],
                ],
            ],

            /* ── CTA Banner ── */
            ['key' => 'field_sb_fp_cta_title',    'label' => 'CTA title',       'name' => 'cta_title',    'type' => 'text',  'default_value' => 'Ready to find your dream property in Ciudad Quesada?'],
            ['key' => 'field_sb_fp_cta_text',     'label' => 'CTA paragraph',   'name' => 'cta_text',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'Our local property experts can help you find the perfect home that matches your requirements and budget in Ciudad Quesada and the urbanizations of Rojales.'],
            ['key' => 'field_sb_fp_cta_btn_text', 'label' => 'CTA button text', 'name' => 'cta_btn_text', 'type' => 'text',  'default_value' => 'Schedule a property viewing'],
        ],
        'location' => [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]],
        'position'        => 'normal',
        'label_placement' => 'top',
    ]);

    /* ═══════════════════════════════════════════════
       ABOUT PAGE
    ═══════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'   => 'group_sb_about',
        'title' => 'About Page Content',
        'fields' => [
            ['key' => 'field_sb_ab_hero_title',    'label' => 'Hero title',    'name' => 'hero_title',    'type' => 'text',  'default_value' => 'About Spaniabolig'],
            ['key' => 'field_sb_ab_hero_subtitle', 'label' => 'Hero subtitle', 'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'We help foreign buyers find their dream properties exclusively in Ciudad Quesada and the urbanizations of Rojales, with a focus on villas, apartments, and townhouses.'],

            ['key' => 'field_sb_ab_card1_title', 'label' => 'Mission card 1 title', 'name' => 'mission_card1_title', 'type' => 'text', 'default_value' => 'Our mission'],
            ['key' => 'field_sb_ab_card1_text',  'label' => 'Mission card 1 text',  'name' => 'mission_card1_text',  'type' => 'wysiwyg', 'default_value' => '<p>At Spaniabolig, we understand that finding the right property in a foreign country can be challenging. Our mission is to simplify this process for international buyers by providing a transparent property listing service that showcases the best properties in Ciudad Quesada and the surrounding urbanizations suited to your specific needs and budget.</p><p>We believe that everyone deserves clear, unbiased information when making important property investment decisions, especially when buying abroad.</p>'],
            ['key' => 'field_sb_ab_card2_title', 'label' => 'Mission card 2 title', 'name' => 'mission_card2_title', 'type' => 'text', 'default_value' => 'Your trusted partner for Spanish property'],
            ['key' => 'field_sb_ab_card2_text',  'label' => 'Mission card 2 text',  'name' => 'mission_card2_text',  'type' => 'wysiwyg', 'default_value' => '<p>Spaniabolig is your professional property partner specializing exclusively in Ciudad Quesada and the surrounding urbanizations of Rojales on the Costa Blanca.</p><p>Our deep connection to Ciudad Quesada goes beyond business. Living and working in this vibrant community has allowed us to build strong relationships with local real estate professionals, developers, and property owners.</p><p>We have successfully helped numerous international clients from across Europe find their dream properties in Ciudad Quesada and the surrounding areas.</p>'],

            ['key' => 'field_sb_ab_services_title', 'label' => 'Services section title', 'name' => 'services_title', 'type' => 'text', 'default_value' => 'How we help you'],
            [
                'key'        => 'field_sb_ab_services',
                'label'      => 'Service cards',
                'name'       => 'services',
                'type'       => 'repeater',
                'min'        => 1,
                'layout'     => 'block',
                'button_label' => 'Add service',
                'sub_fields' => [
                    ['key' => 'field_sb_ab_svc_title', 'label' => 'Title',       'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_sb_ab_svc_desc',  'label' => 'Description', 'name' => 'desc',  'type' => 'textarea', 'rows' => 3],
                ],
            ],

            ['key' => 'field_sb_ab_cta_title',    'label' => 'CTA title',       'name' => 'cta_title',    'type' => 'text',  'default_value' => 'Ready to find your dream property in Ciudad Quesada?'],
            ['key' => 'field_sb_ab_cta_text',     'label' => 'CTA paragraph',   'name' => 'cta_text',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'Start your journey towards owning your ideal home in Ciudad Quesada and the urbanizations of Rojales today.'],
            ['key' => 'field_sb_ab_cta_btn_text', 'label' => 'CTA button text', 'name' => 'cta_btn_text', 'type' => 'text',  'default_value' => 'Browse properties'],
        ],
        'location' => [[['param' => 'page_slug', 'operator' => '==', 'value' => 'about']]],
        'position'        => 'normal',
        'label_placement' => 'top',
    ]);

    /* ═══════════════════════════════════════════════
       HOW IT WORKS PAGE
    ═══════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'   => 'group_sb_how_it_works',
        'title' => 'How It Works — Page Content',
        'fields' => [
            ['key' => 'field_sb_hiw_hero_title',    'label' => 'Hero title',    'name' => 'hero_title',    'type' => 'text',  'default_value' => 'How it works'],
            ['key' => 'field_sb_hiw_hero_subtitle', 'label' => 'Hero subtitle', 'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Finding your perfect property in Ciudad Quesada is easy with our step-by-step process.'],

            [
                'key'        => 'field_sb_hiw_steps',
                'label'      => 'Steps',
                'name'       => 'steps',
                'type'       => 'repeater',
                'min'        => 1,
                'max'        => 8,
                'layout'     => 'block',
                'button_label' => 'Add step',
                'sub_fields' => [
                    ['key' => 'field_sb_hiw_step_label', 'label' => 'Step label (e.g. Step 1)', 'name' => 'step_label', 'type' => 'text'],
                    ['key' => 'field_sb_hiw_step_title', 'label' => 'Step heading',             'name' => 'step_title', 'type' => 'text'],
                    ['key' => 'field_sb_hiw_step_desc',  'label' => 'Step description',         'name' => 'step_desc',  'type' => 'textarea', 'rows' => 3],
                ],
            ],

            ['key' => 'field_sb_hiw_incl_title', 'label' => 'Included section title', 'name' => 'included_title', 'type' => 'text', 'default_value' => "What's included in our service"],
            [
                'key'        => 'field_sb_hiw_included',
                'label'      => 'Included items',
                'name'       => 'included_items',
                'type'       => 'repeater',
                'min'        => 1,
                'layout'     => 'block',
                'button_label' => 'Add item',
                'sub_fields' => [
                    ['key' => 'field_sb_hiw_inc_title', 'label' => 'Title',       'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_sb_hiw_inc_desc',  'label' => 'Description', 'name' => 'desc',  'type' => 'textarea', 'rows' => 3],
                ],
            ],

            ['key' => 'field_sb_hiw_cta_title',     'label' => 'CTA title',        'name' => 'cta_title',     'type' => 'text',  'default_value' => 'Ready to start your property search?'],
            ['key' => 'field_sb_hiw_cta_text',      'label' => 'CTA paragraph',    'name' => 'cta_text',      'type' => 'textarea', 'rows' => 2, 'default_value' => 'Browse our current listings or get in touch with our local team to discuss your requirements.'],
            ['key' => 'field_sb_hiw_cta_btn1_text', 'label' => 'CTA button 1 text','name' => 'cta_btn1_text', 'type' => 'text',  'default_value' => 'Browse properties'],
            ['key' => 'field_sb_hiw_cta_btn2_text', 'label' => 'CTA button 2 text','name' => 'cta_btn2_text', 'type' => 'text',  'default_value' => 'Contact us'],
        ],
        'location' => [[['param' => 'page_slug', 'operator' => '==', 'value' => 'how-it-works']]],
        'position'        => 'normal',
        'label_placement' => 'top',
    ]);

    /* ═══════════════════════════════════════════════
       SERVICES PAGE
    ═══════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'   => 'group_sb_services',
        'title' => 'Services Page Content',
        'fields' => [
            ['key' => 'field_sb_sv_hero_label',    'label' => 'Hero badge label', 'name' => 'hero_label',    'type' => 'text',  'default_value' => 'Property Management'],
            ['key' => 'field_sb_sv_hero_title',    'label' => 'Hero title',       'name' => 'hero_title',    'type' => 'text',  'default_value' => 'Services for property owners in Spain'],
            ['key' => 'field_sb_sv_hero_subtitle', 'label' => 'Hero subtitle',    'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Spaniabolig Real Estate offers a number of services to you who own a home in Spain. See below for more information and feel free to contact us if you have any questions.'],
            ['key' => 'field_sb_sv_hero_link_text','label' => 'Hero link text',   'name' => 'hero_link_text','type' => 'text',  'default_value' => 'Contact us today'],

            ['key' => 'field_sb_sv_grid_title',    'label' => 'Services grid title',    'name' => 'services_title',    'type' => 'text',  'default_value' => 'Our services'],
            ['key' => 'field_sb_sv_grid_subtitle', 'label' => 'Services grid subtitle', 'name' => 'services_subtitle', 'type' => 'text',  'default_value' => 'From key holding to professional photography — we take care of your Spanish property so you don\'t have to.'],
            [
                'key'        => 'field_sb_sv_services',
                'label'      => 'Service cards',
                'name'       => 'services',
                'type'       => 'repeater',
                'min'        => 1,
                'layout'     => 'block',
                'button_label' => 'Add service',
                'sub_fields' => [
                    ['key' => 'field_sb_sv_svc_title', 'label' => 'Title',       'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_sb_sv_svc_desc',  'label' => 'Description', 'name' => 'desc',  'type' => 'textarea', 'rows' => 3],
                ],
            ],

            ['key' => 'field_sb_sv_cta_title',    'label' => 'CTA title',       'name' => 'cta_title',    'type' => 'text',  'default_value' => 'Interested in our property services?'],
            ['key' => 'field_sb_sv_cta_text',     'label' => 'CTA paragraph',   'name' => 'cta_text',     'type' => 'textarea', 'rows' => 2, 'default_value' => 'Get in touch with our team to discuss how we can help look after your Spanish property.'],
            ['key' => 'field_sb_sv_cta_btn_text', 'label' => 'CTA button text', 'name' => 'cta_btn_text', 'type' => 'text',  'default_value' => 'Contact us today'],
        ],
        'location' => [[['param' => 'page_slug', 'operator' => '==', 'value' => 'services']]],
        'position'        => 'normal',
        'label_placement' => 'top',
    ]);

    /* ═══════════════════════════════════════════════
       CONTACT PAGE
    ═══════════════════════════════════════════════ */
    acf_add_local_field_group([
        'key'   => 'group_sb_contact',
        'title' => 'Contact Page Content',
        'fields' => [
            ['key' => 'field_sb_ct_hero_title',    'label' => 'Hero title',    'name' => 'hero_title',    'type' => 'text',  'default_value' => 'Contact us'],
            ['key' => 'field_sb_ct_hero_subtitle', 'label' => 'Hero subtitle', 'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Get in touch with Spaniabolig for any questions about properties in Ciudad Quesada and the urbanizations of Rojales.'],
            ['key' => 'field_sb_ct_form_title', 'label' => 'Form card title', 'name' => 'form_title', 'type' => 'text',  'default_value' => 'Send us a message'],
            ['key' => 'field_sb_ct_form_intro', 'label' => 'Form card intro', 'name' => 'form_intro', 'type' => 'text',  'default_value' => 'We aim to respond to all inquiries within 24 hours during business days.'],
        ],
        'location' => [[['param' => 'page_slug', 'operator' => '==', 'value' => 'contact']]],
        'position'        => 'normal',
        'label_placement' => 'top',
    ]);
}
