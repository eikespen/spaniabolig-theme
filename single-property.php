<?php get_header(); the_post();
$price     = get_post_meta(get_the_ID(), 'sb_price', true);
$bedrooms  = get_post_meta(get_the_ID(), 'sb_bedrooms', true);
$bathrooms = get_post_meta(get_the_ID(), 'sb_bathrooms', true);
$size      = get_post_meta(get_the_ID(), 'sb_size', true);
$status    = get_post_meta(get_the_ID(), 'sb_status', true);
$city      = get_post_meta(get_the_ID(), 'sb_city', true);
$ref       = get_post_meta(get_the_ID(), 'sb_ref', true);
$lat       = get_post_meta(get_the_ID(), 'sb_lat', true);
$lng       = get_post_meta(get_the_ID(), 'sb_lng', true);
$status_key    = str_replace('_', '-', (string) $status);
$status_labels = ['for-sale' => 'For Sale', 'for-rent' => 'For Rent', 'sold' => 'Sold'];
?>

<div class="single-property">
    <div class="section-inner">

        <!-- Gallery -->
        <div class="property-gallery">
            <?php
            $main_img   = sb_get_image_url(get_the_ID(), 'full');
            $image_urls = get_post_meta(get_the_ID(), 'sb_image_urls', true) ?: [];
            if (!is_array($image_urls)) $image_urls = [];
            if ($main_img) :
            ?>
                <div class="gallery-main">
                    <img id="gallery-main-img" src="<?php echo esc_url($main_img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="gallery-main-img">
                </div>
                <?php if (count($image_urls) > 1) : ?>
                <div class="gallery-thumbs">
                    <?php foreach (array_slice($image_urls, 0, 9) as $i => $thumb_url) : ?>
                        <img src="<?php echo esc_url($thumb_url); ?>" alt="" class="gallery-thumb<?php echo $i === 0 ? ' active' : ''; ?>" loading="lazy" data-full="<?php echo esc_url($thumb_url); ?>">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="property-layout">
            <!-- Main Content -->
            <div class="property-content">
                <div class="property-header">
                    <?php if ($status_key) : ?>
                        <span class="card-badge card-badge--<?php echo esc_attr($status_key); ?>">
                            <?php echo esc_html($status_labels[$status_key] ?? ucwords(str_replace(['-','_'], ' ', $status))); ?>
                        </span>
                    <?php endif; ?>
                    <h1 class="property-title"><?php the_title(); ?></h1>
                    <?php if ($city) : ?>
                        <p class="property-location">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?php echo esc_html($city); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($price) : ?>
                        <div class="property-price"><?php echo esc_html(sb_format_price($price)); ?></div>
                    <?php endif; ?>
                </div>

                <div class="property-stats">
                    <?php if ($bedrooms) : ?>
                        <div class="stat">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                            <span><?php echo esc_html($bedrooms); ?></span>
                            <label><?php esc_html_e('Bedrooms', 'spaniabolig'); ?></label>
                        </div>
                    <?php endif; ?>
                    <?php if ($bathrooms) : ?>
                        <div class="stat">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" y1="5" x2="8" y2="7"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                            <span><?php echo esc_html($bathrooms); ?></span>
                            <label><?php esc_html_e('Bathrooms', 'spaniabolig'); ?></label>
                        </div>
                    <?php endif; ?>
                    <?php if ($size) : ?>
                        <div class="stat">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                            <span><?php echo esc_html($size); ?> m²</span>
                            <label><?php esc_html_e('Size', 'spaniabolig'); ?></label>
                        </div>
                    <?php endif; ?>
                    <?php if ($ref) : ?>
                        <div class="stat">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="15" x2="12" y2="15"/></svg>
                            <span><?php echo esc_html($ref); ?></span>
                            <label><?php esc_html_e('Ref #', 'spaniabolig'); ?></label>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="property-description">
                    <h2><?php esc_html_e('Description', 'spaniabolig'); ?></h2>
                    <?php the_content(); ?>
                </div>

                <?php if ($lat && $lng) : ?>
                <div class="property-map">
                    <h2><?php esc_html_e('Location', 'spaniabolig'); ?></h2>
                    <div id="sb-map" data-lat="<?php echo esc_attr($lat); ?>" data-lng="<?php echo esc_attr($lng); ?>" style="height:360px;border-radius:12px;overflow:hidden;"></div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Contact Sidebar -->
            <aside class="property-sidebar">
                <div class="contact-card">
                    <h3><?php esc_html_e('Interested in this property?', 'spaniabolig'); ?></h3>
                    <p><?php esc_html_e('Contact us and we\'ll get back to you as soon as possible.', 'spaniabolig'); ?></p>
                    <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <input type="hidden" name="action" value="sb_property_inquiry">
                        <input type="hidden" name="property_id" value="<?php echo get_the_ID(); ?>">
                        <?php wp_nonce_field('sb_inquiry', 'sb_inquiry_nonce'); ?>
                        <input type="text" name="your_name" placeholder="<?php esc_attr_e('Your Name', 'spaniabolig'); ?>" required>
                        <input type="email" name="your_email" placeholder="<?php esc_attr_e('Email Address', 'spaniabolig'); ?>" required>
                        <input type="tel" name="your_phone" placeholder="<?php esc_attr_e('Phone Number', 'spaniabolig'); ?>">
                        <textarea name="your_message" rows="4" placeholder="<?php esc_attr_e('I am interested in this property...', 'spaniabolig'); ?>"></textarea>
                        <button type="submit" class="btn btn-primary btn-block"><?php esc_html_e('Send Inquiry', 'spaniabolig'); ?></button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php get_footer(); ?>
