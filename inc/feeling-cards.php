<?php
/**
 * "Reasons People Reach Out" cards.
 *
 * Pieces:
 *   - ACF field group with tagline / signs / approach / fee / chip_label.
 *   - yum2_feeling_cards() : ordered cards array, used by the home carousel.
 *   - yum2_feeling_chips() : derived filter chip labels, kept in sync with cards.
 *   - One-time seed        : migrates the original 9 hardcoded cards from
 *                            inc/content.php into the CPT on first admin load.
 *
 * The `feeling_card` post type is registered in inc/post-types.php.
 * Drag-order via menu_order in wp-admin.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * 1. ACF FIELD GROUP
 *    Code-registered so there is nothing to build in ACF by hand. Guarded
 *    so the theme never errors if ACF is deactivated.
 * ====================================================================== */
function yum2_register_feeling_card_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_yum2_feeling_card',
			'title'    => __( 'Card details', 'youumatter2' ),
			'fields'   => array(
				array(
					'key'          => 'field_yum2_feeling_tagline',
					'label'        => __( 'Tagline', 'youumatter2' ),
					'name'         => 'tagline',
					'type'         => 'text',
					'instructions' => __( 'Short italic phrase under the heading (e.g. "Re-learning how to be with people you love.")', 'youumatter2' ),
				),
				array(
					'key'          => 'field_yum2_feeling_signs',
					'label'        => __( 'Signs this might be present', 'youumatter2' ),
					'name'         => 'signs',
					'type'         => 'repeater',
					'instructions' => __( 'Two or three short bullets. Each one is a sign that this card might apply.', 'youumatter2' ),
					'min'          => 1,
					'max'          => 4,
					'layout'       => 'block',
					'button_label' => __( 'Add sign', 'youumatter2' ),
					'sub_fields'   => array(
						array(
							'key'   => 'field_yum2_feeling_sign_text',
							'label' => __( 'Sign', 'youumatter2' ),
							'name'  => 'text',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'          => 'field_yum2_feeling_approach',
					'label'        => __( "How we'd work on it", 'youumatter2' ),
					'name'         => 'approach',
					'type'         => 'textarea',
					'rows'         => 4,
					'new_lines'    => '',
					'instructions' => __( 'A short paragraph describing the approach for this card.', 'youumatter2' ),
				),
				array(
					'key'          => 'field_yum2_feeling_fee',
					'label'        => __( 'Fee', 'youumatter2' ),
					'name'         => 'fee',
					'type'         => 'text',
					'instructions' => __( 'Display price for this card (e.g. ₹2,500 or ₹2,500 / person).', 'youumatter2' ),
				),
				array(
					'key'          => 'field_yum2_feeling_chip',
					'label'        => __( 'Filter chip label', 'youumatter2' ),
					'name'         => 'chip_label',
					'type'         => 'text',
					'instructions' => __( 'Optional. Short label for the chip above the carousel (e.g. "Relationships"). Defaults to the card title.', 'youumatter2' ),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'feeling_card',
					),
				),
			),
			'position' => 'normal',
			'style'    => 'seamless',
		)
	);
}
add_action( 'acf/init', 'yum2_register_feeling_card_fields' );

/* =========================================================================
 * 2. DATA HELPERS
 * ====================================================================== */

/**
 * Ordered "Reasons" cards for the homepage carousel.
 *
 * Returns CPT-backed cards when feeling_card posts exist, otherwise falls
 * back to the hardcoded array in inc/content.php so the design never breaks
 * if ACF / CPT is deactivated.
 *
 * Shape (same as yum2_content('feeling')['cards']):
 *   array{ title:string, tagline:string, signs:string[],
 *          approach:string, fee:string, chip:string }
 *
 * @return array<int, array<string, mixed>>
 */
function yum2_feeling_cards() {
	$posts = get_posts(
		array(
			'post_type'      => 'feeling_card',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'no_found_rows'  => true,
		)
	);

	if ( empty( $posts ) ) {
		$fallback = yum2_content( 'feeling' );
		$cards    = isset( $fallback['cards'] ) ? (array) $fallback['cards'] : array();
		$chips    = isset( $fallback['chips'] ) ? (array) $fallback['chips'] : array();
		$out      = array();
		foreach ( $cards as $i => $card ) {
			$card['chip'] = isset( $chips[ $i ] ) ? $chips[ $i ] : ( $card['title'] ?? '' );
			$out[]        = $card;
		}
		return $out;
	}

	$cards = array();
	foreach ( $posts as $post ) {
		$signs_raw = function_exists( 'get_field' ) ? get_field( 'signs', $post->ID ) : array();
		$signs     = array();
		if ( is_array( $signs_raw ) ) {
			foreach ( $signs_raw as $row ) {
				$text = isset( $row['text'] ) ? trim( (string) $row['text'] ) : '';
				if ( '' !== $text ) {
					$signs[] = $text;
				}
			}
		}

		$title      = get_the_title( $post );
		$chip_label = function_exists( 'get_field' ) ? (string) get_field( 'chip_label', $post->ID ) : '';

		$cards[] = array(
			'title'    => $title,
			'tagline'  => function_exists( 'get_field' ) ? (string) get_field( 'tagline', $post->ID ) : '',
			'signs'    => $signs,
			'approach' => function_exists( 'get_field' ) ? (string) get_field( 'approach', $post->ID ) : '',
			'fee'      => function_exists( 'get_field' ) ? (string) get_field( 'fee', $post->ID ) : '',
			'chip'     => '' !== $chip_label ? $chip_label : $title,
		);
	}
	return $cards;
}

/**
 * Filter chip labels for the homepage carousel header.
 *
 * Derived from yum2_feeling_cards() so chips and cards never drift apart.
 *
 * @return string[]
 */
function yum2_feeling_chips() {
	$chips = array();
	foreach ( yum2_feeling_cards() as $card ) {
		$chips[] = isset( $card['chip'] ) ? (string) $card['chip'] : ( $card['title'] ?? '' );
	}
	return $chips;
}

/* =========================================================================
 * 3. ONE-TIME SEEDING
 *    Migrates the original 9 hardcoded cards from inc/content.php into the
 *    CPT the first time an admin loads wp-admin after this feature ships, so
 *    nothing is lost. Runs once, guarded by an option and an existing check.
 * ====================================================================== */
function yum2_seed_feeling_cards() {
	if ( ! is_admin() || wp_doing_ajax() ) {
		return;
	}
	if ( get_option( 'yum2_feeling_cards_seeded' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'feeling_card',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);
	if ( ! empty( $existing ) ) {
		update_option( 'yum2_feeling_cards_seeded', 1 );
		return;
	}

	$source = yum2_content( 'feeling' );
	$cards  = isset( $source['cards'] ) ? (array) $source['cards'] : array();
	$chips  = isset( $source['chips'] ) ? (array) $source['chips'] : array();
	$menu   = 0;

	foreach ( $cards as $i => $card ) {
		$post_id = wp_insert_post(
			array(
				'post_type'   => 'feeling_card',
				'post_status' => 'publish',
				'post_title'  => isset( $card['title'] ) ? (string) $card['title'] : '',
				'menu_order'  => $menu,
			),
			true
		);
		$menu++;

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		$write = function ( $key, $value ) use ( $post_id ) {
			if ( function_exists( 'update_field' ) ) {
				update_field( $key, $value, $post_id );
			} else {
				update_post_meta( $post_id, $key, $value );
			}
		};

		$write( 'tagline', isset( $card['tagline'] ) ? (string) $card['tagline'] : '' );

		$signs = array();
		if ( ! empty( $card['signs'] ) && is_array( $card['signs'] ) ) {
			foreach ( $card['signs'] as $sign ) {
				$signs[] = array( 'text' => (string) $sign );
			}
		}
		$write( 'signs', $signs );

		$write( 'approach', isset( $card['approach'] ) ? (string) $card['approach'] : '' );
		$write( 'fee', isset( $card['fee'] ) ? (string) $card['fee'] : '' );

		$chip = isset( $chips[ $i ] ) ? (string) $chips[ $i ] : '';
		$write( 'chip_label', $chip );
	}

	update_option( 'yum2_feeling_cards_seeded', 1 );
}
add_action( 'admin_init', 'yum2_seed_feeling_cards' );
