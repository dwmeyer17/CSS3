<?php
/**
 * Fallback template: category, tag, search and blog archives as a recipe grid.
 *
 * @package moms-menu
 */

get_header();
?>

<main id="primary" class="site-main">
    <section class="recipe-section">
        <h1 class="section-title">
            <?php
            if ( is_search() ) {
                /* translators: %s: search query */
                printf( esc_html__( 'Search results for "%s"', 'moms-menu' ), esc_html( get_search_query() ) );
            } elseif ( is_archive() ) {
                echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
            } else {
                esc_html_e( 'Recipes', 'moms-menu' );
            }
            ?>
        </h1>

        <?php if ( have_posts() ) : ?>
            <div class="recipe-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/recipe-card' );
                endwhile;
                ?>
            </div>
        <?php else : ?>
            <p><?php esc_html_e( 'No recipes found.', 'moms-menu' ); ?></p>
        <?php endif; ?>
    </section>

    <div class="pagination">
        <?php echo wp_kses_post( paginate_links() ); ?>
    </div>
</main>

<?php
get_footer();
