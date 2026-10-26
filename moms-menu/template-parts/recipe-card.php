<?php
/**
 * Recipe card used in the category grids.
 *
 * @package moms-menu
 */

?>
<a class="recipe-card" href="<?php the_permalink(); ?>">
    <div class="recipe-image-placeholder">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'moms-menu-card', array( 'alt' => '' ) ); ?>
        <?php else : ?>
            <?php esc_html_e( 'Image', 'moms-menu' ); ?>
        <?php endif; ?>
    </div>
    <h3 class="recipe-name"><?php the_title(); ?></h3>
</a>
