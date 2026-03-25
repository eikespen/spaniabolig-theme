<?php get_header(); the_post();
$post_id   = get_the_ID();
$price     = get_post_meta($post_id, 'sb_price', true);
$bedrooms  = get_post_meta($post_id, 'sb_bedrooms', true);
$bathrooms = get_post_meta($post_id, 'sb_bathrooms', true);
$size      = get_post_meta($post_id, 'sb_size', true);
$status    = get_post_meta($post_id, 'sb_status', true);
$city      = get_post_meta($post_id, 'sb_city', true);
$ref       = get_post_meta($post_id, 'sb_ref', true);
$lat       = get_post_meta($post_id, 'sb_lat', true);
$lng       = get_post_meta($post_id, 'sb_lng', true);
$status_key    = str_replace('_', '-', (string) $status);
$status_labels = ['for-sale' => 'For Sale', 'for-rent' => 'For Rent', 'sold' => 'Sold'];
?>

<div class="single-property">
    <div class="section-inner">

        <!-- Gallery -->
        <div class="property-gallery">
            <?php
            $main_img   = sb_get_image_url($post_id, 'full');
            $image_urls = get_post_meta($post_id, 'sb_image_urls', true) ?: [];
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

                <!-- Full-width enquiry section -->
                <div class="property-enquiry">
                    <div class="enquiry-agent">
                        <div class="enquiry-agent__avatar">
                            <?php
                            $logo = get_template_directory_uri() . '/assets/images/logo.png';
                            ?>
                            <img src="<?php echo esc_url($logo); ?>" alt="Spaniabolig" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <span class="enquiry-agent__initials" style="display:none">SB</span>
                        </div>
                        <div class="enquiry-agent__details">
                            <strong>May-Lise Gundersen</strong>
                            <span>Spaniabolig Real Estate</span>
                            <a href="tel:+4747202414" class="enquiry-agent__phone">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.94-.94a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
                                +47 47 20 24 14
                            </a>
                        </div>
                        <div class="enquiry-agent__cta">
                            <a href="tel:+4747202414" class="btn btn-outline">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.94-.94a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
                                Call
                            </a>
                            <a href="https://wa.me/4747202414?text=<?php echo rawurlencode('Hello, I am interested in ' . get_the_title()); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="enquiry-form-wrap">
                        <h3><?php esc_html_e('Enquire About This Property', 'spaniabolig'); ?></h3>
                        <form class="enquiry-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <input type="hidden" name="action" value="sb_property_inquiry">
                            <input type="hidden" name="property_id" value="<?php echo $post_id; ?>">
                            <?php wp_nonce_field('sb_inquiry', 'sb_inquiry_nonce'); ?>
                            <div class="enquiry-form__row">
                                <input type="text" name="your_name" placeholder="<?php esc_attr_e('Your Name', 'spaniabolig'); ?>" required>
                                <input type="tel" name="your_phone" placeholder="<?php esc_attr_e('Phone Number', 'spaniabolig'); ?>">
                            </div>
                            <input type="email" name="your_email" placeholder="<?php esc_attr_e('Email Address', 'spaniabolig'); ?>" required>
                            <textarea name="your_message" rows="4"><?php echo esc_textarea('Hello, I am interested in ' . get_the_title() . '. Please send me more information.'); ?></textarea>
                            <button type="submit" class="btn btn-primary btn-block"><?php esc_html_e('Request Information', 'spaniabolig'); ?></button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <aside class="property-sidebar">
                <div class="agent-card">
                    <div class="agent-card__header">
                        <div class="agent-card__avatar">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="Spaniabolig" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <span class="agent-card__initials" style="display:none">SB</span>
                        </div>
                        <div>
                            <div class="agent-card__name">May-Lise Gundersen</div>
                            <div class="agent-card__company">Spaniabolig Real Estate</div>
                        </div>
                    </div>

                    <div class="agent-card__contact">
                        <a href="tel:+4747202414" class="agent-card__phone">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.94-.94a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
                            +47 47 20 24 14
                        </a>
                        <a href="https://wa.me/4747202414?text=<?php echo rawurlencode('Hello, I am interested in ' . get_the_title()); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-block">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                            WhatsApp Us
                        </a>
                    </div>

                    <hr class="agent-card__divider">

                    <h3 class="agent-card__form-title"><?php esc_html_e('Interested in this property?', 'spaniabolig'); ?></h3>
                    <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <input type="hidden" name="action" value="sb_property_inquiry">
                        <input type="hidden" name="property_id" value="<?php echo $post_id; ?>">
                        <?php wp_nonce_field('sb_inquiry', 'sb_inquiry_nonce'); ?>
                        <input type="text" name="your_name" placeholder="<?php esc_attr_e('Your Name', 'spaniabolig'); ?>" required>
                        <input type="email" name="your_email" placeholder="<?php esc_attr_e('Email Address', 'spaniabolig'); ?>" required>
                        <input type="tel" name="your_phone" placeholder="<?php esc_attr_e('Phone Number', 'spaniabolig'); ?>">
                        <textarea name="your_message" rows="3"><?php echo esc_textarea('Hello, I am interested in ' . get_the_title() . '.'); ?></textarea>
                        <button type="submit" class="btn btn-primary btn-block"><?php esc_html_e('Send Inquiry', 'spaniabolig'); ?></button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php get_footer(); ?>
