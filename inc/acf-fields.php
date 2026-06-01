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
				array(
					'key'          => 'field_yum2_about_beliefs',
					'label'        => __( 'Beliefs', 'youumatter2' ),
					'name'         => 'about_beliefs',
					'type'         => 'repeater',
					'instructions' => __( 'Three statements on what Sanya believes about therapy.', 'youumatter2' ),
					'min'          => 0,
					'max'          => 6,
					'layout'       => 'block',
					'button_label' => __( 'Add belief', 'youumatter2' ),
					'sub_fields'   => array(
						array(
							'key'   => 'field_yum2_belief_keyword',
							'label' => __( 'Keyword', 'youumatter2' ),
							'name'  => 'keyword',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_yum2_belief_tagline',
							'label' => __( 'Tagline', 'youumatter2' ),
							'name'  => 'tagline',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_yum2_belief_statement',
							'label' => __( 'Statement', 'youumatter2' ),
							'name'  => 'statement',
							'type'  => 'textarea',
							'rows'  => 3,
							'new_lines' => '',
						),
					),
				),
				array(
					'key'          => 'field_yum2_about_how_i_work',
					'label'        => __( 'How I work cards', 'youumatter2' ),
					'name'         => 'about_how_i_work',
					'type'         => 'repeater',
					'instructions' => __( 'Approach cards on the About page.', 'youumatter2' ),
					'min'          => 0,
					'max'          => 8,
					'layout'       => 'block',
					'button_label' => __( 'Add card', 'youumatter2' ),
					'sub_fields'   => array(
						array(
							'key'          => 'field_yum2_how_icon',
							'label'        => __( 'Icon slug', 'youumatter2' ),
							'name'         => 'icon',
							'type'         => 'text',
							'instructions' => __( 'One of: heart, sprout, compass, shield-check, ear, anchor, gem.', 'youumatter2' ),
						),
						array(
							'key'   => 'field_yum2_how_title',
							'label' => __( 'Title', 'youumatter2' ),
							'name'  => 'title',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_yum2_how_body',
							'label' => __( 'Body', 'youumatter2' ),
							'name'  => 'body',
							'type'  => 'textarea',
							'rows'  => 3,
							'new_lines' => '',
						),
					),
				),
				array(
					'key'          => 'field_yum2_about_training',
					'label'        => __( 'Training & credentials', 'youumatter2' ),
					'name'         => 'about_training',
					'type'         => 'repeater',
					'instructions' => __( 'Each row is one credential. Year, label, place.', 'youumatter2' ),
					'min'          => 0,
					'max'          => 12,
					'layout'       => 'block',
					'button_label' => __( 'Add entry', 'youumatter2' ),
					'sub_fields'   => array(
						array(
							'key'   => 'field_yum2_training_year',
							'label' => __( 'Year', 'youumatter2' ),
							'name'  => 'year',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_yum2_training_label',
							'label' => __( 'Label', 'youumatter2' ),
							'name'  => 'label',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_yum2_training_place',
							'label' => __( 'Place', 'youumatter2' ),
							'name'  => 'place',
							'type'  => 'text',
						),
					),
				),
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

	/* ---------------------------------------------------------------- */
	/* Group: Per-post FAQs (sidebar metabox on blog posts)             */
	/* ---------------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_yum2_post_faqs',
			'title'    => __( 'Post FAQs', 'youumatter2' ),
			'fields'   => array(
				array(
					'key'          => 'field_yum2_post_faqs_intro',
					'label'        => __( 'Intro line', 'youumatter2' ),
					'name'         => 'post_faqs_intro',
					'type'         => 'text',
					'instructions' => __( 'Optional. Defaults to "Quick answers on this topic." if blank.', 'youumatter2' ),
				),
				array(
					'key'          => 'field_yum2_post_faqs',
					'label'        => __( 'Q&A pairs', 'youumatter2' ),
					'name'         => 'post_faqs',
					'type'         => 'repeater',
					'instructions' => __( 'Add 2–5 question / answer pairs for this post. They render below the article and get FAQPage schema for Google rich results.', 'youumatter2' ),
					'min'          => 0,
					'max'          => 10,
					'layout'       => 'block',
					'button_label' => __( 'Add Q&A', 'youumatter2' ),
					'sub_fields'   => array(
						array(
							'key'   => 'field_yum2_post_faq_q',
							'label' => __( 'Question', 'youumatter2' ),
							'name'  => 'question',
							'type'  => 'text',
						),
						array(
							'key'      => 'field_yum2_post_faq_a',
							'label'    => __( 'Answer', 'youumatter2' ),
							'name'     => 'answer',
							'type'     => 'textarea',
							'rows'     => 4,
							'new_lines' => '',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'post',
					),
				),
			),
			'position' => 'normal',
			'style'    => 'default',
		)
	);
}
add_action( 'acf/init', 'yum2_register_acf_field_groups' );
