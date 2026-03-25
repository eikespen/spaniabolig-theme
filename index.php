<?php get_header(); ?>
<main class="page-main">
    <div class="section-inner">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article><?php the_title('<h2>','</h2>'); the_excerpt(); ?></article>
        <?php endwhile; else: ?>
            <p><?php esc_html_e('Nothing found.', 'spaniabolig'); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
