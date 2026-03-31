<?php get_header(); the_post();
$post_id   = get_the_ID();
$agent     = sb_get_property_agent($post_id);
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
                    <?php foreach ($image_urls as $i => $thumb_url) : ?>
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

                <!-- Stats bar -->
                <?php
                $type_terms  = get_the_terms($post_id, 'property_type');
                $type_label  = (!empty($type_terms) && !is_wp_error($type_terms)) ? implode(', ', wp_list_pluck($type_terms, 'name')) : '';
                $features    = get_post_meta($post_id, 'sb_features', true) ?: [];
                if (!is_array($features)) $features = [];
                ?>
                <div class="property-stats">
                    <?php if ($type_label) : ?>
                        <div class="stat">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <span><?php echo esc_html($type_label); ?></span>
                            <label><?php esc_html_e('Property Type', 'spaniabolig'); ?></label>
                        </div>
                    <?php endif; ?>
                    <?php if ($bedrooms) : ?>
                        <div class="stat">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                            <span><?php echo esc_html($bedrooms); ?></span>
                            <label><?php esc_html_e('Bedrooms', 'spaniabolig'); ?></label>
                        </div>
                    <?php endif; ?>
                    <?php if ($bathrooms) : ?>
                        <div class="stat">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" y1="5" x2="8" y2="7"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                            <span><?php echo esc_html($bathrooms); ?></span>
                            <label><?php esc_html_e('Bathrooms', 'spaniabolig'); ?></label>
                        </div>
                    <?php endif; ?>
                    <?php if ($size) : ?>
                        <div class="stat">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11M8 10v11M12 10v11M16 10v11"/></svg>
                            <span><?php echo esc_html($size); ?> m²</span>
                            <label><?php esc_html_e('Area Size', 'spaniabolig'); ?></label>
                        </div>
                    <?php endif; ?>
                    <?php if ($ref) : ?>
                        <div class="stat">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="15" x2="12" y2="15"/></svg>
                            <span><?php echo esc_html($ref); ?></span>
                            <label><?php esc_html_e('Ref #', 'spaniabolig'); ?></label>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tabbed content -->
                <div class="prop-tabs">
                    <div class="prop-tabs__nav">
                        <button class="prop-tab-btn active" data-tab="description"><?php esc_html_e('Description', 'spaniabolig'); ?></button>
                        <button class="prop-tab-btn" data-tab="details"><?php esc_html_e('Details', 'spaniabolig'); ?></button>
                        <?php if (!empty($features)) : ?>
                        <button class="prop-tab-btn" data-tab="features"><?php esc_html_e('Features', 'spaniabolig'); ?></button>
                        <?php endif; ?>
                        <?php if ($lat && $lng) : ?>
                        <button class="prop-tab-btn" data-tab="location"><?php esc_html_e('Location', 'spaniabolig'); ?></button>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="prop-tab-panel active" id="tab-description">
                        <div class="property-description">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="prop-tab-panel" id="tab-details">
                        <table class="prop-details-table">
                            <?php if ($type_label) : ?><tr><th><?php esc_html_e('Property Type', 'spaniabolig'); ?></th><td><?php echo esc_html($type_label); ?></td></tr><?php endif; ?>
                            <?php if ($bedrooms)   : ?><tr><th><?php esc_html_e('Bedrooms', 'spaniabolig'); ?></th><td><?php echo esc_html($bedrooms); ?></td></tr><?php endif; ?>
                            <?php if ($bathrooms)  : ?><tr><th><?php esc_html_e('Bathrooms', 'spaniabolig'); ?></th><td><?php echo esc_html($bathrooms); ?></td></tr><?php endif; ?>
                            <?php if ($size)       : ?><tr><th><?php esc_html_e('Area Size', 'spaniabolig'); ?></th><td><?php echo esc_html($size); ?> m²</td></tr><?php endif; ?>
                            <?php if ($city)       : ?><tr><th><?php esc_html_e('Location', 'spaniabolig'); ?></th><td><?php echo esc_html($city); ?></td></tr><?php endif; ?>
                            <?php if ($status)     : ?><tr><th><?php esc_html_e('Status', 'spaniabolig'); ?></th><td><?php echo esc_html(ucwords(str_replace('_', ' ', $status))); ?></td></tr><?php endif; ?>
                            <?php if ($ref)        : ?><tr><th><?php esc_html_e('Reference', 'spaniabolig'); ?></th><td><?php echo esc_html($ref); ?></td></tr><?php endif; ?>
                        </table>
                    </div>

                    <!-- Features -->
                    <?php if (!empty($features)) : ?>
                    <div class="prop-tab-panel" id="tab-features">
                        <div class="prop-features-grid">
                            <?php foreach ($features as $feature) : ?>
                                <div class="prop-feature">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/></svg>
                                    <?php echo esc_html($feature); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Location -->
                    <?php if ($lat && $lng) : ?>
                    <div class="prop-tab-panel" id="tab-location">
                        <div id="sb-map" data-lat="<?php echo esc_attr($lat); ?>" data-lng="<?php echo esc_attr($lng); ?>" style="height:400px;border-radius:12px;overflow:hidden;"></div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Full-width enquiry section -->
                <div class="property-enquiry">
                    <div class="enquiry-agent">
                        <div class="enquiry-agent__avatar">
                            <?php if ($agent['photo_url']) : ?>
                                <img src="<?php echo esc_url($agent['photo_url']); ?>" alt="<?php echo esc_attr($agent['name']); ?>">
                            <?php else : ?>
                                <span class="enquiry-agent__initials"><?php echo esc_html(mb_strtoupper(mb_substr($agent['name'], 0, 2))); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="enquiry-agent__details">
                            <strong><?php echo esc_html($agent['name']); ?></strong>
                            <span><?php echo esc_html($agent['title']); ?></span>
                            <?php if ($agent['phone']) : ?>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $agent['phone'])); ?>" class="enquiry-agent__phone">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.94-.94a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
                                <?php echo esc_html($agent['phone']); ?>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="enquiry-agent__cta">
                            <?php if ($agent['phone']) : ?>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $agent['phone'])); ?>" class="btn-white">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.94-.94a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
                                Call
                            </a>
                            <?php endif; ?>
                            <?php if ($agent['whatsapp']) : ?>
                            <a href="https://wa.me/<?php echo esc_attr($agent['whatsapp']); ?>?text=<?php echo rawurlencode('Hello, I am interested in ' . get_the_title()); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                WhatsApp
                            </a>
                            <?php endif; ?>
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
                            <?php if ($agent['photo_url']) : ?>
                                <img src="<?php echo esc_url($agent['photo_url']); ?>" alt="<?php echo esc_attr($agent['name']); ?>">
                            <?php else : ?>
                                <span class="agent-card__initials"><?php echo esc_html(mb_strtoupper(mb_substr($agent['name'], 0, 2))); ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="agent-card__name"><?php echo esc_html($agent['name']); ?></div>
                            <div class="agent-card__company"><?php echo esc_html($agent['title']); ?></div>
                        </div>
                    </div>

                    <div class="agent-card__contact">
                        <?php if ($agent['phone']) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $agent['phone'])); ?>" class="agent-card__phone">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.94-.94a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
                            <?php echo esc_html($agent['phone']); ?>
                        </a>
                        <?php endif; ?>
                        <?php if ($agent['whatsapp']) : ?>
                        <a href="https://wa.me/<?php echo esc_attr($agent['whatsapp']); ?>?text=<?php echo rawurlencode('Hello, I am interested in ' . get_the_title()); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-block">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                            WhatsApp Us
                        </a>
                        <?php endif; ?>
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
