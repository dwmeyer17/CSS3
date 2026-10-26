<?php
/**
 * Single recipe: title, intro, and two-column Ingredients / Directions.
 *
 * @package moms-menu
 */

get_header();
get_template_part( 'template-parts/hero' );
?>

<main id="primary" class="site-main">
    <div class="single-recipe-container">
        <?php
        while ( have_posts() ) :
            the_post();
            $ingredients = moms_menu_lines( get_post_meta( get_the_ID(), '_moms_menu_ingredients', true ) );
            $directions  = moms_menu_lines( get_post_meta( get_the_ID(), '_moms_menu_directions', true ) );
            $has_recipe  = $ingredients || $directions;
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="single-recipe-title"><?php the_title(); ?></h1>

                <?php if ( $has_recipe && get_the_content() ) : ?>
                    <div class="recipe-intro">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

                <div class="recipe-details-grid">
                    <div class="recipe-ingredients">
                        <h2><?php esc_html_e( 'Ingredients', 'moms-menu' ); ?></h2>
                        <?php if ( $ingredients ) : ?>
                            <ul>
                                <?php foreach ( $ingredients as $ingredient ) : ?>
                                    <li><?php echo esc_html( $ingredient ); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php elseif ( ! $has_recipe && get_the_content() ) : ?>
                            <?php the_content(); ?>
                        <?php else : ?>
                            <p><?php esc_html_e( 'No ingredients listed yet.', 'moms-menu' ); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="recipe-directions">
                        <h2><?php esc_html_e( 'Directions', 'moms-menu' ); ?></h2>
                        <?php if ( $directions ) : ?>
                            <ol>
                                <?php foreach ( $directions as $direction ) : ?>
                                    <li><?php echo esc_html( $direction ); ?></li>
                                <?php endforeach; ?>
                            </ol>
                        <?php else : ?>
                            <p><?php esc_html_e( 'No directions listed yet.', 'moms-menu' ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();
