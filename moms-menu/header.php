<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'moms-menu' ); ?></a>

<header class="site-header">
    <div class="site-branding">
        <?php if ( has_custom_logo() ) : ?>
            <?php the_custom_logo(); ?>
        <?php endif; ?>
        <?php if ( is_front_page() ) : ?>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
        <?php else : ?>
            <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
        <?php endif; ?>
        <?php if ( get_bloginfo( 'description' ) ) : ?>
            <p class="site-description"><?php bloginfo( 'description' ); ?></p>
        <?php endif; ?>
    </div>

    <?php moms_menu_navigation(); ?>
</header>
