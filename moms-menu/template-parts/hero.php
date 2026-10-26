<?php
/**
 * Hero slideshow banner.
 *
 * @package moms-menu
 */

$slides = moms_menu_slideshow_posts();
?>
<div class="hero-slideshow" data-interval="5000">
    <?php if ( $slides ) : ?>
        <?php foreach ( $slides as $index => $slide ) : ?>
            <a class="hero-slide<?php echo 0 === $index ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_permalink( $slide ) ); ?>">
                <?php echo get_the_post_thumbnail( $slide, 'moms-menu-hero', array( 'alt' => '' ) ); ?>
                <span class="hero-slide-caption"><?php echo esc_html( get_the_title( $slide ) ); ?></span>
            </a>
        <?php endforeach; ?>
    <?php else : ?>
        <?php esc_html_e( 'Images Slideshow', 'moms-menu' ); ?>
    <?php endif; ?>
</div>
