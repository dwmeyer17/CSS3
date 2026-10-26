<?php
/**
 * Static pages (About, Contact, etc.).
 *
 * @package moms-menu
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="single-recipe-container">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="single-recipe-title"><?php the_title(); ?></h1>
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="recipe-featured-image"><?php the_post_thumbnail( 'large' ); ?></div>
                <?php endif; ?>
                <div class="recipe-intro">
                    <?php the_content(); ?>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();
