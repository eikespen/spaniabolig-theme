<?php get_header(); ?>

<section class="properties-hero">
    <div class="section-inner">
        <h1><?php esc_html_e('Properties on Costa Blanca', 'spaniabolig'); ?></h1>
        <p><?php esc_html_e('Browse our selection of villas, apartments and townhouses on the Costa Blanca.', 'spaniabolig'); ?></p>
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
                global $wpdb;
                $cities = $wpdb->get_col(
                    "SELECT DISTINCT meta_value FROM {$wpdb->postmeta}
                     WHERE meta_key = 'sb_city' AND meta_value != ''
                     ORDER BY meta_value ASC"
                );
                foreach ($cities as $city) :
                    echo '<option value="' . esc_attr($city) . '">' . esc_html($city) . '</option>';
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

            <?php $active_build_type = isset($_GET['build_type']) ? sanitize_key($_GET['build_type']) : ''; ?>
            <select id="pf-build-type" class="pf-select">
                <option value=""><?php esc_html_e('All types', 'spaniabolig'); ?></option>
                <option value="resale"<?php selected($active_build_type, 'resale'); ?>><?php esc_html_e('Resale', 'spaniabolig'); ?></option>
                <option value="new_build"<?php selected($active_build_type, 'new_build'); ?>><?php esc_html_e('New Build', 'spaniabolig'); ?></option>
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

        <div id="pf-pagination" class="pf-pagination">
            <?php
            // Server-side pagination — replaced by AJAX once JS runs
            $total_pages = $wp_query->max_num_pages;
            if ($total_pages > 1) :
                $paged = max(1, get_query_var('paged'));
                echo '<div class="pf-pages pf-pages--ssr">';
                if ($paged > 1) echo '<a class="pf-page-btn" href="' . esc_url(get_pagenum_link($paged - 1)) . '">&larr; Prev</a>';
                for ($i = 1; $i <= $total_pages; $i++) :
                    $cls = $i === $paged ? 'pf-page-btn pf-page-btn--active' : 'pf-page-btn';
                    echo '<a class="' . $cls . '" href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a>';
                endfor;
                if ($paged < $total_pages) echo '<a class="pf-page-btn" href="' . esc_url(get_pagenum_link($paged + 1)) . '">Next &rarr;</a>';
                echo '</div>';
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
