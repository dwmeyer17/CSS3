<?php
/**
 * Not found.
 *
 * @package moms-menu
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="single-recipe-container">
        <h1 class="single-recipe-title"><?php esc_html_e( 'Recipe not found', 'moms-menu' ); ?></h1>
        <p class="recipe-intro">
            <?php esc_html_e( 'That page seems to have left the kitchen. Try searching instead:', 'moms-menu' ); ?>
        </p>
        <?php get_search_form(); ?>
    </div>
</main>

<?php
get_footer();
