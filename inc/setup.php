<?php
/**
 * Theme setup: supports, menus, image sizes.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function yum2_setup() {
	load_theme_textdomain( 'youumatter2', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 162,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'youumatter2' ),
			'mobile'  => __( 'Mobile Menu', 'youumatter2' ),
			'footer'  => __( 'Footer Menu', 'youumatter2' ),
			'legal'   => __( 'Legal Menu', 'youumatter2' ),
		)
	);

	add_image_size( 'yum2-blog-card', 800, 500, true );
	add_image_size( 'yum2-blog-featured', 1200, 700, true );
	add_image_size( 'yum2-portrait', 800, 1000, true );
	add_image_size( 'yum2-og-image', 1200, 630, true );

	add_post_type_support( 'post', 'excerpt' );

	// Block editor sees front-end rendering via the compiled editor stylesheet.
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'yum2_setup' );
