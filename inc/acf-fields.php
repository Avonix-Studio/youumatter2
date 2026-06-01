<?php
/**
 * ACF: Site Content options page + per-post FAQs field group.
 *
 * Edit-here-and-it-changes-everywhere admin fields for:
 *   - Home hero (eyebrow / heading / subheading)
 *   - About page (bio, beliefs repeater, "how I work" repeater, training repeater)
 *   - Per-post FAQs (sidebar metabox on blog posts)
 *
 * Every template-side read uses yum2_field() or yum2_post_field() with the
 * hardcoded inc/content.php arrays as fallback, so an empty field renders the
 * existing copy unchanged.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * 1. ACF OPTIONS PAGE - "Site Content" (under Settings)
 * ====================================================================== */
function yum2_register_site_content_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title'  => __( 'Site Content', 'youumatter2' ),
			'menu_title'  => __( 'Site Content', 'youumatter2' ),
			'menu_slug'   => 'yum2-site-content',
			'parent_slug' => 'options-general.php',
			'capability'  => 'edit_theme_options',
			'redirect'    => false,
			'icon_url'    => 'dashicons-edit-page',
		)
	);
}
add_action( 'acf/init', 'yum2_register_site_content_options_page' );

/* =========================================================================
 * 2. ACF FIELD GROUPS - Site content + per-post FAQs
 * ====================================================================== */
function yum2_register_acf_field_groups() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* ---------------------------------------------------------------- */
	/* Group: Site content (attached to the options page above)         */
	/* ---------------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_yum2_site_content',
			'title'    => __( 'Site Content', 'youumatter2' ),
			'fields'   => array(

				/* ------- Home hero ----------------------------------- */
				array(
					'key'      => 'field_yum2_tab_home_hero',
					'label'    => __( 'Home hero', 'youumatter2' ),
					'type'     => 'tab',
					'placement' => 'top',
				),
				array(
					'key'          => 'field_yum2_hero_eyebrow',
					'label'        => __( 'Eyebrow line', 'youumatter2' ),
					'name'         => 'hero_eyebrow',
					'type'         => 'text',
					'instructions' => __( 'Small uppercase line above the hero headline.', 'youumatter2' ),
				),
				array(
					'key'          => 'field_yum2_hero_heading',
					'label'        => __( 'Main heading', 'youumatter2' ),
					'name'         => 'hero_heading',
					'type'         => 'textarea',
					'rows'         => 2,
					'new_lines'    => '',
					'instructions' => __( 'The big serif heading.', 'youumatter2' ),
				),
				array(
					'key'          => 'field_yum2_hero_heading_em',
					'label'        => __( 'Heading italic phrase', 'youumatter2' ),
					'name'         => 'hero_heading_em',
					'type'         => 'text',
					'instructions' => __( 'The italic terracotta phrase that follows the main heading (e.g. "And youu can do this.").', 'youumatter2' ),
				),
				array(
					'key'          => 'field_yum2_hero_subheading',
					'label'        => __( 'Subheading / body line', 'youumatter2' ),
					'name'         => 'hero_subheading',
					'type'         => 'textarea',
					'rows'         => 3,
					'new_lines'    => '',
					'instructions' => __( 'The supporting paragraph under the headline.', 'youumatter2' ),
				),

				/* ------- About: bio + beliefs + how-I-work + training - */
				array(
					'key'       => 'field_yum2_tab_about',
					'label'     => __( 'About page', 'youumatter2' ),
					'type'      => 'tab',
					'placement' => 'top',
				),
				array(
					'key'          => 'field_yum2_about_bio',
					'label'        => __( 'About bio paragraph', 'youumatter2' ),
					'name'         => 'about_bio_paragraph',
					'type'         => 'wysiwyg',
					'tabs'         => 'visual',
					'toolbar'      => 'basic',
					'media_upload' => 0,
					'instructions' => __( 'Primary bio paragraph. Used on the About page intro.', 'youumatter2' ),
				),
				/* Beliefs / How I work / Training rows live in code (inc/content.php
				   + the template parts themselves). Edit them there. They were
				   originally ACF repeaters but Repeater is an ACF Pro field, so
				   they were silently invisible on ACF Free. */
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'yum2-site-content',
					),
				),
			),
			'position' => 'normal',
			'style'    => 'default',
		)
	);

	/* Per-post FAQs (Q&A repeater on blog posts) was also a Repeater field
	   and therefore ACF Pro-only. Removed for ACF Free. */
}
add_action( 'acf/init', 'yum2_register_acf_field_groups' );
