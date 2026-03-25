<?php
/**
 * User onboarding:
 *  1. Welcome email on new user registration
 *  2. WP Admin dashboard widget with quick-start guide
 *  3. First-login dismissible admin notice
 *  4. "Built by" credit widget on WP dashboard
 */
defined('ABSPATH') || exit;

/* ════════════════════════════════════════════
   HELPER — resolve front-end URLs reliably
════════════════════════════════════════════ */
function sb_front_url(string $slug, string $fallback_path): string {
    $page = get_page_by_path($slug);
    return $page ? get_permalink($page->ID) : home_url($fallback_path);
}


/* ════════════════════════════════════════════
   1. WELCOME EMAIL — fires when a user is created
════════════════════════════════════════════ */
add_action('user_register', function (int $user_id) {
    $user          = get_userdata($user_id);
    $site_name     = get_bloginfo('name');
    $site_url      = home_url('/');
    $admin_url     = admin_url();
    $login_url     = wp_login_url();
    $dashboard_url = sb_front_url('property-dashboard', '/property-dashboard');
    $add_prop_url  = sb_front_url('add-property', '/add-property');

    $subject = "Welcome to {$site_name} — Your quick-start guide";

    $body = "
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width,initial-scale=1'>
<style>
  body{margin:0;padding:0;background:#f1f5f9;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;color:#374151;}
  .wrap{max-width:600px;margin:40px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);}
  .header{background:#001d3d;padding:32px 40px;text-align:center;}
  .header h1{color:#fff;margin:0;font-size:22px;font-weight:700;letter-spacing:-.02em;}
  .header p{color:rgba(255,255,255,.65);margin:8px 0 0;font-size:14px;}
  .body{padding:36px 40px;}
  .greeting{font-size:17px;font-weight:600;color:#111827;margin-bottom:8px;}
  .intro{font-size:15px;color:#6b7280;line-height:1.7;margin-bottom:32px;}
  .section{margin-bottom:28px;}
  .section-title{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af;margin-bottom:14px;}
  .card{background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;padding:18px 20px;margin-bottom:10px;display:block;text-decoration:none;color:inherit;}
  .card-title{font-size:15px;font-weight:700;color:#001d3d;margin-bottom:4px;}
  .card-desc{font-size:13px;color:#6b7280;line-height:1.55;}
  .card-url{font-size:12px;color:#9ca3af;margin-top:6px;font-family:monospace;}
  .btn{display:inline-block;background:#001d3d;color:#fff;padding:13px 28px;border-radius:10px;font-size:15px;font-weight:600;text-decoration:none;margin:4px 0;}
  .tip{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:14px 18px;font-size:13px;color:#92400e;line-height:1.6;}
  .footer{background:#f8fafc;border-top:1px solid #e5e7eb;padding:20px 40px;text-align:center;font-size:12px;color:#9ca3af;}
  .footer a{color:#6b7280;text-decoration:none;}
</style>
</head>
<body>
<div class='wrap'>
  <div class='header'>
    <h1>Welcome to {$site_name}</h1>
    <p>Your quick-start guide to managing properties</p>
  </div>
  <div class='body'>
    <p class='greeting'>Hi {$user->display_name},</p>
    <p class='intro'>Your account on {$site_name} is ready. Here's everything you need to know to get started — bookmark this email!</p>

    <div class='section'>
      <p class='section-title'>🔑 Your Login Details</p>
      <div class='card'>
        <p class='card-title'>WordPress Admin Panel</p>
        <p class='card-desc'>This is where you manage everything behind the scenes — pages, properties, agents, settings and more.</p>
        <p class='card-url'>{$admin_url}</p>
      </div>
      <div style='margin-top:8px;'>
        <a href='{$login_url}' class='btn'>Go to login page &rarr;</a>
      </div>
    </div>

    <div class='section'>
      <p class='section-title'>🏠 Managing Properties</p>
      <div class='card'>
        <p class='card-title'>Property Dashboard (Front-end)</p>
        <p class='card-desc'>Your main hub — view all published listings, click <strong>Edit</strong> on any property to update it with the step-by-step wizard, or click <strong>Add New Property</strong> to publish a new one. No coding needed.</p>
        <p class='card-url'>{$dashboard_url}</p>
      </div>
      <div class='card'>
        <p class='card-title'>Add a New Property</p>
        <p class='card-desc'>Use the step-by-step wizard to publish a new listing. Fill in details, upload photos, set the location on the map, and hit <strong>Publish</strong>.</p>
        <p class='card-url'>{$add_prop_url}</p>
      </div>
      <div class='card'>
        <p class='card-title'>Properties (WP Admin)</p>
        <p class='card-desc'>Alternatively, manage properties directly from the WP Admin panel under the <strong>Properties</strong> menu item.</p>
        <p class='card-url'>{$admin_url}edit.php?post_type=property</p>
      </div>
    </div>

    <div class='section'>
      <p class='section-title'>✏️ Editing Page Content</p>
      <div class='card'>
        <p class='card-title'>Edit Pages (About, Services, How It Works, etc.)</p>
        <p class='card-desc'>Go to <strong>WP Admin → Pages</strong>, click any page, and look for the coloured content boxes below the main editor. All text on the About, Services, Contact, and other pages can be edited there — no coding required.</p>
        <p class='card-url'>{$admin_url}edit.php?post_type=page</p>
      </div>
    </div>

    <div class='section'>
      <p class='section-title'>👥 Agents</p>
      <div class='card'>
        <p class='card-title'>Manage Agents</p>
        <p class='card-desc'>Add or update team members under <strong>WP Admin → Agents</strong>. Each agent has a photo (set as Featured Image), name, job title, phone, WhatsApp and email. These appear automatically on the About page and Services contact card.</p>
        <p class='card-url'>{$admin_url}edit.php?post_type=sb_agent</p>
      </div>
    </div>

    <div class='section'>
      <p class='section-title'>🔍 SEO (Yoast)</p>
      <div class='card'>
        <p class='card-title'>Set SEO Titles &amp; Descriptions</p>
        <p class='card-desc'>Yoast SEO is installed. On every page and property listing, scroll down to the <strong>Yoast SEO</strong> box to set a focus keyword, meta title and meta description. This improves Google rankings significantly.</p>
        <p class='card-url'>{$admin_url}admin.php?page=wpseo_dashboard</p>
      </div>
    </div>

    <div class='tip'>
      💡 <strong>First time tip:</strong> Visit <strong>{$admin_url}?sb_seed_pages=1</strong> to auto-create all required pages (About, Services, Dictionary, Privacy Policy, etc.) with the correct templates assigned — only needed once on a fresh install.
    </div>
  </div>
  <div class='footer'>
    <p>{$site_name} &mdash; <a href='{$site_url}'>{$site_url}</a></p>
    <p>You received this because a new account was created for you.</p>
  </div>
</div>
</body>
</html>
";

    wp_mail(
        $user->user_email,
        $subject,
        $body,
        [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . get_option('admin_email') . '>',
        ]
    );
});


/* ════════════════════════════════════════════
   2. WP ADMIN DASHBOARD WIDGET — Quick Start
════════════════════════════════════════════ */
add_action('wp_dashboard_setup', function () {
    wp_add_dashboard_widget(
        'sb_quick_start',
        '🏠 Spaniabolig — Quick Start',
        'sb_dashboard_widget_render'
    );
    wp_add_dashboard_widget(
        'sb_built_by',
        '🛠 Theme Information',
        'sb_built_by_widget_render'
    );
});

function sb_dashboard_widget_render() {
    $dashboard_url = sb_front_url('property-dashboard', '/property-dashboard');
    $add_prop_url  = sb_front_url('add-property', '/add-property');
    $admin_url     = admin_url();
    ?>
    <style>
    #sb_quick_start .qs-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;}
    #sb_quick_start .qs-card{background:#f8fafc;border:1px solid #e2e8f0;border-left:3px solid #001d3d;border-radius:6px;padding:10px 12px;text-decoration:none;display:block;transition:border-color .15s;}
    #sb_quick_start .qs-card:hover{border-left-color:#0057b7;background:#f0f9ff;}
    #sb_quick_start .qs-card strong{display:block;font-size:13px;color:#001d3d;margin-bottom:2px;}
    #sb_quick_start .qs-card span{font-size:12px;color:#6b7280;line-height:1.4;}
    #sb_quick_start .qs-tip{background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:10px 12px;font-size:12px;color:#92400e;line-height:1.55;margin-top:4px;}
    </style>
    <div class="qs-grid">
        <a href="<?php echo esc_url($dashboard_url); ?>" class="qs-card" target="_blank">
            <strong>📋 Property Dashboard</strong>
            <span>View, edit &amp; manage all listings on the front-end</span>
        </a>
        <a href="<?php echo esc_url($add_prop_url); ?>" class="qs-card" target="_blank">
            <strong>➕ Add New Property</strong>
            <span>Step-by-step wizard to publish a listing</span>
        </a>
        <a href="<?php echo esc_url($admin_url . 'edit.php?post_type=property'); ?>" class="qs-card">
            <strong>🏠 All Properties (Admin)</strong>
            <span>Manage properties from WP Admin</span>
        </a>
        <a href="<?php echo esc_url($admin_url . 'edit.php?post_type=sb_agent'); ?>" class="qs-card">
            <strong>👥 Agents</strong>
            <span>Add/edit team members &amp; contact details</span>
        </a>
        <a href="<?php echo esc_url($dashboard_url); ?>" class="qs-card" target="_blank">
            <strong>✏️ Edit Pages &amp; Listings</strong>
            <span>Go to front-end dashboard to edit properties &amp; page content</span>
        </a>
        <a href="<?php echo esc_url($admin_url . 'admin.php?page=wpseo_dashboard'); ?>" class="qs-card">
            <strong>🔍 Yoast SEO</strong>
            <span>Set meta titles &amp; descriptions for Google</span>
        </a>
    </div>
    <div class="qs-tip">
        💡 <strong>Tip:</strong> Use the <strong>Property Dashboard</strong> link above to manage listings on the front-end. To edit page text (About, Services, etc.), open the page in <strong>WP Admin → Pages</strong> and look for the <span style="background:#2271b1;color:#fff;padding:1px 6px;border-radius:3px;font-size:11px;">coloured admin boxes</span> below the editor.
    </div>
    <?php
}

function sb_built_by_widget_render() {
    $year = date('Y');
    ?>
    <style>
    #sb_built_by .bb-wrap{display:flex;align-items:center;gap:14px;margin-bottom:12px;}
    #sb_built_by .bb-avatar{width:48px;height:48px;border-radius:50%;background:#001d3d;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
    #sb_built_by .bb-name{font-size:14px;font-weight:700;color:#001d3d;margin:0 0 2px;}
    #sb_built_by .bb-role{font-size:12px;color:#6b7280;margin:0;}
    #sb_built_by .bb-meta{font-size:12px;color:#6b7280;line-height:1.7;border-top:1px solid #e5e7eb;padding-top:10px;margin-top:2px;}
    #sb_built_by .bb-meta strong{color:#374151;}
    #sb_built_by .bb-badge{display:inline-block;background:#f0f9ff;border:1px solid #bae6fd;color:#0369a1;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;margin:2px 2px 2px 0;}
    </style>
    <div class="bb-wrap">
        <div class="bb-avatar">🧑‍💻</div>
        <div>
            <p class="bb-name">Espen T. Eik</p>
            <p class="bb-role">Designer &amp; Developer</p>
        </div>
    </div>
    <div class="bb-meta">
        <strong>Custom WordPress theme</strong> — designed, coded and built from scratch.<br>
        <span class="bb-badge">Custom Theme</span>
        <span class="bb-badge">PHP</span>
        <span class="bb-badge">CSS</span>
        <span class="bb-badge">JavaScript</span>
        <span class="bb-badge">WordPress</span><br><br>
        &copy; <?php echo esc_html($year); ?> Espen T. Eik. All rights reserved.<br>
        Unauthorised copying or redistribution of this theme is prohibited.
    </div>
    <?php
}


/* ════════════════════════════════════════════
   3. FIRST-LOGIN DISMISSIBLE NOTICE
════════════════════════════════════════════ */
add_action('admin_notices', function () {
    $user_id = get_current_user_id();
    if (!$user_id) return;
    if (get_user_meta($user_id, 'sb_welcome_dismissed', true)) return;

    $dashboard_url = sb_front_url('property-dashboard', '/property-dashboard');
    $add_prop_url  = sb_front_url('add-property', '/add-property');
    ?>
    <div class="notice notice-info is-dismissible sb-welcome-notice" style="border-left-color:#001d3d;padding:16px 20px;">
        <p style="font-size:15px;font-weight:700;color:#001d3d;margin:0 0 6px;">
            👋 Welcome to <?php bloginfo('name'); ?>!
        </p>
        <p style="margin:0 0 10px;color:#374151;">Here are the most important things to know:</p>
        <ul style="margin:0 0 12px;padding-left:0;list-style:none;display:flex;flex-wrap:wrap;gap:8px;">
            <li><a href="<?php echo esc_url($dashboard_url); ?>" target="_blank" style="background:#001d3d;color:#fff;padding:5px 14px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;">📋 Property Dashboard</a></li>
            <li><a href="<?php echo esc_url($add_prop_url); ?>" target="_blank" style="background:#001d3d;color:#fff;padding:5px 14px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;">➕ Add Property</a></li>
            <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=sb_agent')); ?>" style="background:#001d3d;color:#fff;padding:5px 14px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;">👥 Agents</a></li>
            <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=page')); ?>" style="background:#001d3d;color:#fff;padding:5px 14px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;">✏️ Edit Pages (Admin)</a></li>
        </ul>
        <p style="margin:0;font-size:12px;color:#6b7280;">Tip: the <strong>Property Dashboard</strong> is your main hub — edit existing listings and add new ones using the step-by-step wizard. To edit page text (About, Services, etc.) open the page in WP Admin → Pages and look for the coloured content boxes below the editor.</p>
    </div>
    <script>
    (function(){
        document.addEventListener('DOMContentLoaded', function(){
            var notice = document.querySelector('.sb-welcome-notice');
            if (!notice) return;
            notice.addEventListener('click', function(e){
                if (!e.target.closest('.notice-dismiss')) return;
                fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=sb_dismiss_welcome&nonce=<?php echo wp_create_nonce('sb_dismiss_welcome'); ?>'
                });
            });
        });
    })();
    </script>
    <?php
});

// AJAX handler to save the dismissal
add_action('wp_ajax_sb_dismiss_welcome', function () {
    check_ajax_referer('sb_dismiss_welcome', 'nonce');
    update_user_meta(get_current_user_id(), 'sb_welcome_dismissed', true);
    wp_send_json_success();
});
