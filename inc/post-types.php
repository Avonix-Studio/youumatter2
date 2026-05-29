<?php
/**
 * Custom post types and taxonomies.
 *
 * Registers the editable FAQ system:
 *   - `faq`          post type  (one FAQ = title is the question, body is the answer)
 *   - `faq_category` taxonomy   (the groups shown on the FAQ page sidebar)
 *
 * The "Show on homepage" toggle and all FAQ query/seed/order logic live in
 * inc/faq.php. This file only declares the data structures.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the `faq` post type.
 *
 * Title = the question, editor = the answer, page-attributes = drag/order.
 */
function yum2_register_faq_cpt() {
	$labels = array(
		'name'                  => _x( 'FAQs', 'post type general name', 'youumatter2' ),
		'singular_name'         => _x( 'FAQ', 'post type singular name', 'youumatter2' ),
		'menu_name'             => __( 'FAQs', 'youumatter2' ),
		'add_new'               => __( 'Add New', 'youumatter2' ),
		'add_new_item'          => __( 'Add New FAQ', 'youumatter2' ),
		'edit_item'             => __( 'Edit FAQ', 'youumatter2' ),
		'new_item'              => __( 'New FAQ', 'youumatter2' ),
		'view_item'             => __( 'View FAQ', 'youumatter2' ),
		'search_items'          => __( 'Search FAQs', 'youumatter2' ),
		'not_found'             => __( 'No FAQs yet', 'youumatter2' ),
		'not_found_in_trash'    => __( 'No FAQs in Trash', 'youumatter2' ),
		'all_items'             => __( 'All FAQs', 'youumatter2' ),
		'item_published'        => __( 'FAQ published.', 'youumatter2' ),
		'item_updated'          => __( 'FAQ updated.', 'youumatter2' ),
	);

	register_post_type(
		'faq',
		array(
			'labels'             => $labels,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-editor-help',
			'menu_position'      => 26,
			'supports'           => array( 'title', 'editor', 'page-attributes' ),
			'has_archive'        => false,
			'rewrite'            => false,
			'query_var'          => false,
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
		)
	);
}
add_action( 'init', 'yum2_register_faq_cpt' );

/**
 * Register the `faq_category` taxonomy.
 *
 * Hierarchical so it shows as checkboxes (like post categories) and exposes a
 * Description field per term, which we render as the grey subtitle under each
 * group heading on the FAQ page (e.g. "What the first few steps look like.").
 */
function yum2_register_faq_taxonomy() {
	$labels = array(
		'name'              => _x( 'FAQ Categories', 'taxonomy general name', 'youumatter2' ),
		'singular_name'     => _x( 'FAQ Category', 'taxonomy singular name', 'youumatter2' ),
		'menu_name'         => __( 'Categories', 'youumatter2' ),
		'all_items'         => __( 'All Categories', 'youumatter2' ),
		'edit_item'         => __( 'Edit Category', 'youumatter2' ),
		'update_item'       => __( 'Update Category', 'youumatter2' ),
		'add_new_item'      => __( 'Add New Category', 'youumatter2' ),
		'new_item_name'     => __( 'New Category Name', 'youumatter2' ),
		'search_items'      => __( 'Search Categories', 'youumatter2' ),
	);

	register_taxonomy(
		'faq_category',
		array( 'faq' ),
		array(
			'labels'            => $labels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_in_rest'      => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => false,
			'query_var'         => false,
		)
	);
}
add_action( 'init', 'yum2_register_faq_taxonomy' );

/**
 * Use "Question" as the title-field placeholder on the FAQ editor so it's
 * obvious the title is the question.
 *
 * @param string  $text Placeholder text.
 * @param WP_Post $post Current post.
 * @return string
 */
function yum2_faq_title_placeholder( $text, $post ) {
	if ( $post instanceof WP_Post && 'faq' === $post->post_type ) {
		return __( 'Question (e.g. How long is each session?)', 'youumatter2' );
	}
	return $text;
}
add_filter( 'enter_title_here', 'yum2_faq_title_placeholder', 10, 2 );
