<?php get_header(); ?>

<section class="archive-hero">
    <div class="section-inner">
        <h1><?php esc_html_e('Properties', 'spaniabolig'); ?></h1>
        <p><?php esc_html_e('Browse all available properties', 'spaniabolig'); ?></p>
    </div>
</section>

<section class="archive-main">
    <div class="section-inner archive-layout">

        <!-- Sidebar Filter -->
        <aside class="filter-sidebar">
            <form class="filter-form" id="sb-filter-form" method="get" action="<?php echo esc_url(home_url('/properties')); ?>">
                <h3><?php esc_html_e('Filter Properties', 'spaniabolig'); ?></h3>

                <div class="filter-group">
                    <label><?php esc_html_e('Location', 'spaniabolig'); ?></label>
                    <?php $locations = get_terms(['taxonomy' => 'property_location', 'hide_empty' => false]); ?>
                    <select name="location">
                        <option value=""><?php esc_html_e('Any', 'spaniabolig'); ?></option>
                        <?php foreach ($locations as $loc) : ?>
                            <option value="<?php echo esc_attr($loc->slug); ?>" <?php selected(get_query_var('location'), $loc->slug); ?>>
                                <?php echo esc_html($loc->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label><?php esc_html_e('Property Type', 'spaniabolig'); ?></label>
                    <?php $types = get_terms(['taxonomy' => 'property_type', 'hide_empty' => false]); ?>
                    <select name="property_type">
                        <option value=""><?php esc_html_e('Any', 'spaniabolig'); ?></option>
                        <?php foreach ($types as $type) : ?>
                            <option value="<?php echo esc_attr($type->slug); ?>" <?php selected(get_query_var('property_type'), $type->slug); ?>>
                                <?php echo esc_html($type->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label><?php esc_html_e('Status', 'spaniabolig'); ?></label>
                    <select name="status">
                        <option value=""><?php esc_html_e('Any', 'spaniabolig'); ?></option>
                        <option value="for-sale" <?php selected($_GET['status'] ?? '', 'for-sale'); ?>><?php esc_html_e('For Sale', 'spaniabolig'); ?></option>
                        <option value="for-rent" <?php selected($_GET['status'] ?? '', 'for-rent'); ?>><?php esc_html_e('For Rent', 'spaniabolig'); ?></option>
                    </select>
                </div>

                <div class="filter-group">
                    <label><?php esc_html_e('Min Bedrooms', 'spaniabolig'); ?></label>
                    <select name="bedrooms">
                        <option value=""><?php esc_html_e('Any', 'spaniabolig'); ?></option>
                        <?php foreach ([1,2,3,4,5] as $n) : ?>
                            <option value="<?php echo $n; ?>" <?php selected($_GET['bedrooms'] ?? '', $n); ?>><?php echo $n; ?>+</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group filter-group--price">
                    <label><?php esc_html_e('Price Range (€)', 'spaniabolig'); ?></label>
                    <div class="price-range">
                        <input type="number" name="min_price" placeholder="<?php esc_attr_e('Min', 'spaniabolig'); ?>" value="<?php echo esc_attr($_GET['min_price'] ?? ''); ?>" min="0" step="10000">
                        <span>–</span>
                        <input type="number" name="max_price" placeholder="<?php esc_attr_e('Max', 'spaniabolig'); ?>" value="<?php echo esc_attr($_GET['max_price'] ?? ''); ?>" min="0" step="10000">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block"><?php esc_html_e('Apply Filters', 'spaniabolig'); ?></button>
                <a href="<?php echo esc_url(home_url('/properties')); ?>" class="btn btn-ghost btn-block"><?php esc_html_e('Clear All', 'spaniabolig'); ?></a>
            </form>
        </aside>

        <!-- Results -->
        <div class="archive-results">
            <?php if (have_posts()) : ?>
                <div class="results-header">
                    <span class="results-count">
                        <?php
                        global $wp_query;
                        printf(esc_html(_n('%s property found', '%s properties found', $wp_query->found_posts, 'spaniabolig')), number_format_i18n($wp_query->found_posts));
                        ?>
                    </span>
                    <select class="sort-select" id="sb-sort">
                        <option value="date"><?php esc_html_e('Newest first', 'spaniabolig'); ?></option>
                        <option value="price-asc"><?php esc_html_e('Price: Low to High', 'spaniabolig'); ?></option>
                        <option value="price-desc"><?php esc_html_e('Price: High to Low', 'spaniabolig'); ?></option>
                    </select>
                </div>
                <div class="property-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/property-card'); ?>
                    <?php endwhile; ?>
                </div>
                <div class="archive-pagination">
                    <?php the_posts_pagination(['prev_text' => '&larr; Previous', 'next_text' => 'Next &rarr;']); ?>
                </div>
            <?php else : ?>
                <div class="no-results">
                    <h2><?php esc_html_e('No properties found', 'spaniabolig'); ?></h2>
                    <p><?php esc_html_e('Try adjusting your filters or', 'spaniabolig'); ?> <a href="<?php echo esc_url(home_url('/properties')); ?>"><?php esc_html_e('view all properties', 'spaniabolig'); ?></a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
