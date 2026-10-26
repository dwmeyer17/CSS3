<?php
/**
 * Mom's Menu theme functions.
 *
 * @package moms-menu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup: supports, menus and image sizes.
 */
function moms_menu_setup() {
	load_theme_textdomain( 'moms-menu', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo' );

	add_image_size( 'moms-menu-card', 600, 450, true );
	add_image_size( 'moms-menu-hero', 1800, 560, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'moms-menu' ),
		)
	);
}
add_action( 'after_setup_theme', 'moms_menu_setup' );

/**
 * Enqueue the stylesheet and slideshow script.
 */
function moms_menu_scripts() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'moms-menu-style', get_stylesheet_uri(), array(), $version );
	wp_enqueue_script(
		'moms-menu-slideshow',
		get_template_directory_uri() . '/assets/js/slideshow.js',
		array(),
		$version,
		array( 'in_footer' => true )
	);
}
add_action( 'wp_enqueue_scripts', 'moms_menu_scripts' );

/**
 * Print the primary menu as a flat list of links, which is what the
 * "a | a | a" separator styling in style.css expects.
 */
function moms_menu_navigation() {
	$locations = get_nav_menu_locations();
	$links     = array();

	if ( ! empty( $locations['primary'] ) ) {
		$items = wp_get_nav_menu_items( $locations['primary'] );

		foreach ( (array) $items as $item ) {
			if ( (int) $item->menu_item_parent ) {
				continue;
			}
			$links[] = array(
				'url'     => $item->url,
				'title'   => $item->title,
				'current' => 'custom' === $item->type
					? is_front_page() && untrailingslashit( $item->url ) === untrailingslashit( home_url() )
					: (int) $item->object_id === get_queried_object_id(),
			);
		}
	} else {
		$links[] = array(
			'url'     => home_url( '/' ),
			'title'   => __( 'Home', 'moms-menu' ),
			'current' => is_front_page(),
		);
		foreach ( get_categories( array( 'number' => 5, 'orderby' => 'count', 'order' => 'DESC' ) ) as $category ) {
			$links[] = array(
				'url'     => get_category_link( $category ),
				'title'   => $category->name,
				'current' => is_category( $category->term_id ),
			);
		}
	}

	echo '<nav class="main-navigation" aria-label="' . esc_attr__( 'Primary', 'moms-menu' ) . '">';
	foreach ( $links as $link ) {
		printf(
			'<a href="%1$s"%2$s>%3$s</a>',
			esc_url( $link['url'] ),
			$link['current'] ? ' class="current" aria-current="page"' : '',
			esc_html( $link['title'] )
		);
	}
	echo '</nav>';
}

/**
 * Split a textarea value into trimmed, non-empty lines.
 *
 * @param string $value Raw text.
 * @return string[]
 */
function moms_menu_lines( $value ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $value );
	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}

/**
 * Register the Ingredients / Directions meta box on posts.
 */
function moms_menu_add_recipe_meta_box() {
	add_meta_box(
		'moms-menu-recipe',
		__( 'Recipe Details', 'moms-menu' ),
		'moms_menu_render_recipe_meta_box',
		'post',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'moms_menu_add_recipe_meta_box' );

/**
 * Render the recipe meta box.
 *
 * @param WP_Post $post Current post.
 */
function moms_menu_render_recipe_meta_box( $post ) {
	wp_nonce_field( 'moms_menu_save_recipe', 'moms_menu_recipe_nonce' );
	$ingredients = get_post_meta( $post->ID, '_moms_menu_ingredients', true );
	$directions  = get_post_meta( $post->ID, '_moms_menu_directions', true );
	?>
	<p><?php esc_html_e( 'Enter one item per line. The post content is shown as an introduction above the recipe.', 'moms-menu' ); ?></p>
	<p>
		<label for="moms-menu-ingredients"><strong><?php esc_html_e( 'Ingredients', 'moms-menu' ); ?></strong></label>
		<textarea id="moms-menu-ingredients" name="moms_menu_ingredients" rows="8" style="width:100%;"><?php echo esc_textarea( $ingredients ); ?></textarea>
	</p>
	<p>
		<label for="moms-menu-directions"><strong><?php esc_html_e( 'Directions', 'moms-menu' ); ?></strong></label>
		<textarea id="moms-menu-directions" name="moms_menu_directions" rows="10" style="width:100%;"><?php echo esc_textarea( $directions ); ?></textarea>
	</p>
	<?php
}

/**
 * Save the recipe meta box.
 *
 * @param int $post_id Post ID.
 */
function moms_menu_save_recipe_meta( $post_id ) {
	if ( ! isset( $_POST['moms_menu_recipe_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['moms_menu_recipe_nonce'] ) ), 'moms_menu_save_recipe' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'moms_menu_ingredients' => '_moms_menu_ingredients',
		'moms_menu_directions'  => '_moms_menu_directions',
	);

	foreach ( $fields as $field => $meta_key ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $meta_key, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
}
add_action( 'save_post', 'moms_menu_save_recipe_meta' );

/**
 * Posts for the hero slideshow: sticky posts first, otherwise the latest
 * posts that have a featured image.
 *
 * @return WP_Post[]
 */
function moms_menu_slideshow_posts() {
	$sticky = get_option( 'sticky_posts' );
	$args   = array(
		'posts_per_page'      => 5,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'meta_key'            => '_thumbnail_id', // phpcs:ignore WordPress.DB.SlowDBQuery
	);

	if ( ! empty( $sticky ) ) {
		$args['post__in'] = $sticky;
	}

	return get_posts( $args );
}
