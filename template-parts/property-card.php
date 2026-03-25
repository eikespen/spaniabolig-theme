<?php
$price     = get_post_meta(get_the_ID(), 'sb_price', true);
$bedrooms  = get_post_meta(get_the_ID(), 'sb_bedrooms', true);
$bathrooms = get_post_meta(get_the_ID(), 'sb_bathrooms', true);
$size      = get_post_meta(get_the_ID(), 'sb_size', true);
$status    = get_post_meta(get_the_ID(), 'sb_status', true);
$city      = get_post_meta(get_the_ID(), 'sb_city', true);

$status_key    = str_replace('_', '-', (string) $status);
$status_labels = ['for-sale' => 'For Sale', 'for-rent' => 'For Rent', 'sold' => 'Sold'];
?>
<article class="property-card">
    <a href="<?php the_permalink(); ?>" class="card-image-wrap">
        <?php $img_url = sb_get_image_url(get_the_ID()); ?>
        <?php if ($img_url) : ?>
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="card-image" loading="lazy">
        <?php else : ?>
            <div class="card-image card-image--placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" width="48" height="48"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
        <?php endif; ?>

        <?php if ($status) : ?>
            <span class="card-badge card-badge--<?php echo esc_attr($status_key); ?>">
                <?php echo esc_html($status_labels[$status_key] ?? ucwords(str_replace(['-','_'], ' ', $status))); ?>
            </span>
        <?php endif; ?>

        <button class="fav-btn" data-id="<?php echo esc_attr(get_the_ID()); ?>" aria-label="Save to favourites" title="Save to favourites">
            <svg class="fav-icon fav-icon--outline" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            <svg class="fav-icon fav-icon--filled" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </button>
    </a>

    <div class="card-body">
        <?php if ($price) : ?>
            <div class="card-price"><?php echo esc_html(sb_format_price($price)); ?></div>
        <?php endif; ?>

        <h3 class="card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if ($city) : ?>
            <p class="card-location">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?php echo esc_html($city); ?>
            </p>
        <?php endif; ?>

        <div class="card-meta">
            <?php if ($bedrooms) : ?>
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                    <?php echo esc_html($bedrooms); ?> <?php esc_html_e('bed', 'spaniabolig'); ?>
                </span>
            <?php endif; ?>
            <?php if ($bathrooms) : ?>
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" y1="5" x2="8" y2="7"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                    <?php echo esc_html($bathrooms); ?> <?php esc_html_e('bath', 'spaniabolig'); ?>
                </span>
            <?php endif; ?>
            <?php if ($size) : ?>
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    <?php echo esc_html($size); ?> m²
                </span>
            <?php endif; ?>
        </div>
    </div>
</article>
