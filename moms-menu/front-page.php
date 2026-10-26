<?php
/**
 * Home page: hero slideshow followed by a grid of recipes for each category.
 *
 * @package moms-menu
 */

get_header();
get_template_part( 'template-parts/hero' );

$categories = get_categories(
    array(
        'hide_empty' => true,
        'orderby'    => 'name',
    )
);
?>

<main id="primary" class="site-main">
    <?php foreach ( $categories as $category ) : ?>
        <?php
        $recipes = new WP_Query(
            array(
                'cat'                 => $category->term_id,
                'posts_per_page'      => 8,
                'ignore_sticky_posts' => true,
                'no_found_rows'       => true,
            )
        );

        if ( ! $recipes->have_posts() ) {
            continue;
        }
        ?>
        <section class="recipe-section">
            <h2 class="section-title">
                <a href="<?php echo esc_url( get_category_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
            </h2>
            <div class="recipe-grid">
                <?php
                while ( $recipes->have_posts() ) :
                    $recipes->the_post();
                    get_template_part( 'template-parts/recipe-card' );
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
            <?php if ( $category->count > 8 ) : ?>
                <a class="section-more" href="<?php echo esc_url( get_category_link( $category ) ); ?>">
                    <?php
                    /* translators: %s: category name */
                    printf( esc_html__( 'See all %s recipes', 'moms-menu' ), esc_html( $category->name ) );
                    ?>
                </a>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>

    <?php if ( empty( $categories ) ) : ?>
        <p class="no-results"><?php esc_html_e( 'No recipes yet. Add a post to a category to get started.', 'moms-menu' ); ?></p>
    <?php endif; ?>
</main>

<?php
get_footer();
