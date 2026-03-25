<?php get_header(); ?>

<section class="properties-hero">
    <div class="section-inner">
        <h1><?php esc_html_e('Properties in Ciudad Quesada', 'spaniabolig'); ?></h1>
        <p><?php esc_html_e('Browse our selection of villas, apartments and townhouses in Ciudad Quesada and the urbanizations of Rojales.', 'spaniabolig'); ?></p>
    </div>
</section>

<section class="properties-filter">
    <div class="section-inner">
        <div class="pf-bar">
            <div class="pf-search">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" id="pf-keyword" placeholder="<?php esc_attr_e('Search properties…', 'spaniabolig'); ?>">
            </div>

            <select id="pf-type" class="pf-select">
                <option value=""><?php esc_html_e('All types', 'spaniabolig'); ?></option>
                <?php
                $types = get_terms(['taxonomy' => 'property_type', 'hide_empty' => false]);
                if (!is_wp_error($types)) foreach ($types as $t) :
                    echo '<option value="' . esc_attr($t->slug) . '">' . esc_html($t->name) . '</option>';
                endforeach;
                ?>
            </select>

            <select id="pf-location" class="pf-select">
                <option value=""><?php esc_html_e('Any location', 'spaniabolig'); ?></option>
                <?php
                $locs = get_terms(['taxonomy' => 'property_location', 'hide_empty' => false]);
                if (!is_wp_error($locs)) foreach ($locs as $l) :
                    echo '<option value="' . esc_attr($l->slug) . '">' . esc_html($l->name) . '</option>';
                endforeach;
                ?>
            </select>

            <select id="pf-price" class="pf-select">
                <option value=""><?php esc_html_e('Any price', 'spaniabolig'); ?></option>
                <option value="0-150000"><?php esc_html_e('Under €150,000', 'spaniabolig'); ?></option>
                <option value="150000-300000"><?php esc_html_e('€150k – €300k', 'spaniabolig'); ?></option>
                <option value="300000-500000"><?php esc_html_e('€300k – €500k', 'spaniabolig'); ?></option>
                <option value="500000-"><?php esc_html_e('€500,000+', 'spaniabolig'); ?></option>
            </select>

            <select id="pf-beds" class="pf-select">
                <option value=""><?php esc_html_e('Any beds', 'spaniabolig'); ?></option>
                <option value="1">1+</option>
                <option value="2">2+</option>
                <option value="3">3+</option>
                <option value="4">4+</option>
            </select>

            <select id="pf-status" class="pf-select">
                <option value=""><?php esc_html_e('Buy or rent', 'spaniabolig'); ?></option>
                <option value="for-sale"><?php esc_html_e('For sale', 'spaniabolig'); ?></option>
                <option value="for-rent"><?php esc_html_e('For rent', 'spaniabolig'); ?></option>
            </select>

            <button class="pf-clear" id="pf-clear">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                <?php esc_html_e('Clear', 'spaniabolig'); ?>
            </button>
        </div>
    </div>
</section>

<section class="properties-results">
    <div class="section-inner">
        <div class="results-header">
            <span id="pf-count" class="results-count">
                <?php
                global $wp_query;
                $n = $wp_query->found_posts;
                echo esc_html(sprintf(_n('%s property found', '%s properties found', $n, 'spaniabolig'), number_format_i18n($n)));
                ?>
            </span>
            <select id="pf-sort" class="sort-select">
                <option value="date"><?php esc_html_e('Newest first', 'spaniabolig'); ?></option>
                <option value="price-asc"><?php esc_html_e('Price: Low → High', 'spaniabolig'); ?></option>
                <option value="price-desc"><?php esc_html_e('Price: High → Low', 'spaniabolig'); ?></option>
            </select>
        </div>

        <div id="pf-grid" class="property-grid">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php get_template_part('template-parts/property-card'); ?>
            <?php endwhile; endif; ?>
        </div>

        <div id="pf-no-results" class="no-results" style="display:none;">
            <h2><?php esc_html_e('No properties found', 'spaniabolig'); ?></h2>
            <p><?php esc_html_e('Try adjusting your filters to see more results.', 'spaniabolig'); ?></p>
        </div>

        <div id="pf-pagination" class="pf-pagination"></div>
    </div>
</section>

<?php get_footer(); ?>
