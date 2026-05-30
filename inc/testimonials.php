<?php
/**
 * Testimonials system: editable from the admin (Testimonials menu).
 *
 * Pieces:
 *   - ACF fields: initial, age, condition, duration, rating, from_google.
 *     All optional except the post body (the quote itself).
 *   - yum2_testimonial_items() : all published testimonials as a render-ready
 *     array, used by the home carousel.
 *   - One-time seeding: migrates the original hardcoded testimonials into the
 *     CPT so nothing is lost.
 *   - Drag-ordering: reorder by dragging in the admin list.
 *
 * The `testimonial` post type itself is registered in inc/post-types.php.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * 1. ACF FIELDS - all optional, registered in code (no manual ACF setup)
 * ====================================================================== */
function yum2_register_testimonial_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_yum2_testimonial',
			'title'    => __( 'Testimonial details', 'youumatter2' ),
			'fields'   => array(
				array(
					'key'          => 'field_yum2_testimonial_quote',
					'label'        => __( 'Testimonial text', 'youumatter2' ),
					'name'         => 'quote',
					'type'         => 'textarea',
					'instructions' => __( 'The review itself. Quote marks are added automatically when shown on the site.', 'youumatter2' ),
					'rows'         => 5,
					'required'     => 1,
				),
				array(
					'key'          => 'field_yum2_testimonial_initial',
					'label'        => __( 'Initial', 'youumatter2' ),
					'name'         => 'initial',
					'type'         => 'text',
					'instructions' => __( 'First-name initial only, for privacy (e.g. "A.", "S."). Optional.', 'youumatter2' ),
					'maxlength'    => 8,
				),
				array(
					'key'          => 'field_yum2_testimonial_age',
					'label'        => __( 'Age', 'youumatter2' ),
					'name'         => 'age',
					'type'         => 'number',
					'instructions' => __( 'Optional.', 'youumatter2' ),
					'min'          => 0,
					'max'          => 120,
				),
				array(
					'key'          => 'field_yum2_testimonial_condition',
					'label'        => __( 'Condition / topic', 'youumatter2' ),
					'name'         => 'condition',
					'type'         => 'text',
					'instructions' => __( 'e.g. "Anxiety", "Relationships". Optional.', 'youumatter2' ),
				),
				array(
					'key'          => 'field_yum2_testimonial_duration',
					'label'        => __( 'Duration / context', 'youumatter2' ),
					'name'         => 'duration',
					'type'         => 'text',
					'instructions' => __( 'e.g. "6 months in", "Session 5". Optional.', 'youumatter2' ),
				),
				array(
					'key'           => 'field_yum2_testimonial_rating',
					'label'         => __( 'Star rating', 'youumatter2' ),
					'name'          => 'rating',
					'type'          => 'number',
					'instructions'  => __( 'Defaults to 5.', 'youumatter2' ),
					'min'           => 1,
					'max'           => 5,
					'default_value' => 5,
				),
				array(
					'key'           => 'field_yum2_testimonial_from_google',
					'label'         => __( 'From Google review', 'youumatter2' ),
					'name'          => 'from_google',
					'type'          => 'true_false',
					'ui'            => 1,
					'instructions'  => __( 'Show the Google badge on this card.', 'youumatter2' ),
					'default_value' => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'testimonial',
					),
				),
			),
			'position' => 'normal',
		)
	);
}
add_action( 'acf/init', 'yum2_register_testimonial_fields' );

/* =========================================================================
 * 2. DATA HELPERS
 * ====================================================================== */

/**
 * Plain-text quote for a testimonial post. Prefers the ACF "quote" field;
 * falls back to the post body for legacy entries created before the field
 * was added.
 *
 * @param int|WP_Post $post Testimonial post or ID.
 * @return string
 */
function yum2_testimonial_quote( $post ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	$acf_quote = function_exists( 'get_field' )
		? (string) get_field( 'quote', $post->ID )
		: (string) get_post_meta( $post->ID, 'quote', true );
	$acf_quote = trim( $acf_quote );
	if ( '' !== $acf_quote ) {
		return $acf_quote;
	}

	return trim( wp_strip_all_tags( do_blocks( $post->post_content ), true ) );
}

/**
 * Single field reader that prefers ACF, falls back to raw post meta.
 *
 * @param int    $post_id Post ID.
 * @param string $name    Field name.
 * @return mixed
 */
function yum2_testimonial_field( $post_id, $name ) {
	if ( function_exists( 'get_field' ) ) {
		return get_field( $name, $post_id );
	}
	return get_post_meta( $post_id, $name, true );
}

/**
 * Build the visible attribution string from optional initial + age.
 *
 * @param string $initial Initial, e.g. "A.". May be empty.
 * @param mixed  $age     Age (int/string). May be empty.
 * @return string Empty when neither is set.
 */
function yum2_testimonial_attribution( $initial, $age ) {
	$initial = trim( (string) $initial );
	$age     = trim( (string) $age );
	if ( '' !== $initial && '' !== $age ) {
		return $initial . ', ' . $age;
	}
	return '' !== $initial ? $initial : $age;
}

/**
 * Build the visible context string from optional condition + duration.
 *
 * @param string $condition Condition / topic.
 * @param string $duration  Duration / context.
 * @return string Empty when neither is set.
 */
function yum2_testimonial_context( $condition, $duration ) {
	$condition = trim( (string) $condition );
	$duration  = trim( (string) $duration );
	if ( '' !== $condition && '' !== $duration ) {
		return $condition . ' · ' . $duration;
	}
	return '' !== $condition ? $condition : $duration;
}

/**
 * All published testimonials as render-ready arrays, ordered by drag/menu order.
 *
 * @return array<int, array<string, mixed>>
 */
function yum2_testimonial_items() {
	$posts = get_posts(
		array(
			'post_type'      => 'testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'no_found_rows'  => true,
		)
	);

	$items = array();
	foreach ( $posts as $post ) {
		$id        = (int) $post->ID;
		$initial   = (string) yum2_testimonial_field( $id, 'initial' );
		$age       = yum2_testimonial_field( $id, 'age' );
		$condition = (string) yum2_testimonial_field( $id, 'condition' );
		$duration  = (string) yum2_testimonial_field( $id, 'duration' );
		$rating    = (int) yum2_testimonial_field( $id, 'rating' );
		if ( $rating < 1 || $rating > 5 ) {
			$rating = 5;
		}
		$from_google = (bool) yum2_testimonial_field( $id, 'from_google' );

		$items[] = array(
			'id'          => $id,
			'quote'       => yum2_testimonial_quote( $post ),
			'attribution' => yum2_testimonial_attribution( $initial, $age ),
			'context'     => yum2_testimonial_context( $condition, $duration ),
			'rating'      => $rating,
			'from_google' => $from_google,
		);
	}

	return $items;
}

/* =========================================================================
 * 3. AUTO-TITLE - generated from the meta fields after ACF saves so the user
 *    never types it. Format: "Initial, Age - Condition", falling back to a
 *    short quote excerpt if none of those are set.
 * ====================================================================== */

/**
 * Build the title from the saved meta fields.
 *
 * @param int $post_id Testimonial post ID.
 * @return string
 */
function yum2_testimonial_build_title( $post_id ) {
	$initial   = trim( (string) yum2_testimonial_field( $post_id, 'initial' ) );
	$age       = trim( (string) yum2_testimonial_field( $post_id, 'age' ) );
	$condition = trim( (string) yum2_testimonial_field( $post_id, 'condition' ) );

	$attribution = yum2_testimonial_attribution( $initial, $age );

	$parts = array();
	if ( '' !== $attribution ) {
		$parts[] = $attribution;
	}
	if ( '' !== $condition ) {
		$parts[] = $condition;
	}
	if ( ! empty( $parts ) ) {
		return implode( ' - ', $parts );
	}

	$quote = yum2_testimonial_quote( $post_id );
	if ( '' !== $quote ) {
		return wp_trim_words( $quote, 8, '...' );
	}

	return __( 'Testimonial', 'youumatter2' );
}

/**
 * Auto-update the post_title from the meta fields after each ACF save.
 *
 * Writes directly via $wpdb (instead of wp_update_post) to avoid retriggering
 * save_post and looping. clean_post_cache() refreshes the object cache.
 *
 * @param int|string $post_id Post ID being saved.
 */
function yum2_testimonial_auto_title( $post_id ) {
	if ( ! is_numeric( $post_id ) || 'testimonial' !== get_post_type( $post_id ) ) {
		return;
	}

	$new_title = yum2_testimonial_build_title( (int) $post_id );

	global $wpdb;
	$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->posts,
		array(
			'post_title' => $new_title,
			'post_name'  => sanitize_title( $new_title ),
		),
		array( 'ID' => (int) $post_id ),
		array( '%s', '%s' ),
		array( '%d' )
	);
	clean_post_cache( (int) $post_id );
}
add_action( 'acf/save_post', 'yum2_testimonial_auto_title', 20 );

/**
 * Hide the now-redundant Title input on the testimonial edit screen, since
 * we auto-generate it. The page header still says "Edit Testimonial".
 */
function yum2_testimonial_admin_css() {
	$screen = get_current_screen();
	if ( ! $screen || 'testimonial' !== $screen->post_type ) {
		return;
	}
	echo '<style>#titlediv,#titlewrap,.editor-post-title{display:none !important;}</style>';
}
add_action( 'admin_head', 'yum2_testimonial_admin_css' );

/* =========================================================================
 * 4. ADMIN: default the testimonial list to drag/menu order
 * ====================================================================== */
function yum2_testimonial_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( 'testimonial' !== $query->get( 'post_type' ) ) {
		return;
	}
	if ( ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'menu_order date' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'yum2_testimonial_admin_order' );

/* =========================================================================
 * 4. ADMIN: drag-and-drop ordering on the testimonial list screen
 * ====================================================================== */
function yum2_testimonial_reorder_assets( $hook ) {
	if ( 'edit.php' !== $hook ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'testimonial' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_script( 'jquery-ui-sortable' );

	$inline = "(function($){
		$(function(){
			var \$rows = $('#the-list');
			if(!\$rows.length){return;}
			\$rows.sortable({
				items: 'tr',
				axis: 'y',
				cursor: 'move',
				opacity: 0.7,
				helper: function(e, tr){
					var \$o = tr.children();
					var \$h = tr.clone();
					\$h.children().each(function(i){ $(this).width(\$o.eq(i).width()); });
					return \$h;
				},
				update: function(){
					var ids = [];
					\$rows.find('tr').each(function(){
						var id = ($(this).attr('id')||'').replace('post-','');
						if(id){ ids.push(id); }
					});
					$.post(ajaxurl, {
						action: 'yum2_testimonial_reorder',
						nonce: window.yum2TestimonialReorder.nonce,
						order: ids
					});
				}
			});
		});
	})(jQuery);";

	wp_add_inline_script( 'jquery-ui-sortable', $inline );
	wp_localize_script(
		'jquery-ui-sortable',
		'yum2TestimonialReorder',
		array( 'nonce' => wp_create_nonce( 'yum2_testimonial_reorder' ) )
	);
}
add_action( 'admin_enqueue_scripts', 'yum2_testimonial_reorder_assets' );

function yum2_testimonial_reorder_save() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( 'forbidden', 403 );
	}
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'yum2_testimonial_reorder' ) ) {
		wp_send_json_error( 'bad_nonce', 400 );
	}

	$order = isset( $_POST['order'] ) ? (array) wp_unslash( $_POST['order'] ) : array();
	$order = array_map( 'absint', $order );

	$position = 0;
	foreach ( $order as $post_id ) {
		if ( $post_id && 'testimonial' === get_post_type( $post_id ) ) {
			wp_update_post(
				array(
					'ID'         => $post_id,
					'menu_order' => $position,
				)
			);
			$position++;
		}
	}

	wp_send_json_success();
}
add_action( 'wp_ajax_yum2_testimonial_reorder', 'yum2_testimonial_reorder_save' );

/* =========================================================================
 * 5. ONE-TIME SEEDING from the original hardcoded testimonials.
 *    Migrates the launch copy into the CPT the first time an admin loads
 *    wp-admin after this feature ships, so nothing is lost.
 * ====================================================================== */
function yum2_seed_testimonials() {
	if ( ! is_admin() || wp_doing_ajax() ) {
		return;
	}
	if ( get_option( 'yum2_testimonials_seeded' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'testimonial',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);
	if ( ! empty( $existing ) ) {
		update_option( 'yum2_testimonials_seeded', 1 );
		return;
	}

	$seed = yum2_testimonial_seed_data();
	$menu = 0;

	foreach ( $seed as $row ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'testimonial',
				'post_status'  => 'publish',
				'post_title'   => $row['title'],
				'menu_order'   => $menu,
			),
			true
		);
		$menu++;
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		$meta = array(
			'quote'       => $row['quote'],
			'initial'     => $row['initial'],
			'age'         => $row['age'],
			'condition'   => $row['condition'],
			'duration'    => $row['duration'],
			'rating'      => 5,
			'from_google' => 1,
		);
		foreach ( $meta as $name => $value ) {
			if ( function_exists( 'update_field' ) ) {
				update_field( $name, $value, $post_id );
			} else {
				update_post_meta( $post_id, $name, $value );
			}
		}
	}

	update_option( 'yum2_testimonials_seeded', 1 );
}
add_action( 'admin_init', 'yum2_seed_testimonials' );

/**
 * Initial testimonial content for seeding. Mirrors launch copy.
 *
 * @return array<int, array<string, mixed>>
 */
function yum2_testimonial_seed_data() {
	return array(
		array(
			'title'     => 'A. - Anxiety',
			'quote'     => __( 'I came in thinking something was wrong with me. I left understanding that I had been coping, not broken. Sanya made the difference feel huge.', 'youumatter2' ),
			'initial'   => 'A.',
			'age'       => 29,
			'condition' => __( 'Anxiety', 'youumatter2' ),
			'duration'  => __( '6 months in', 'youumatter2' ),
		),
		array(
			'title'     => 'R. - Relationships',
			'quote'     => __( 'She never rushed me. The first real moment came in session five, and she was there, ready, like she had been expecting it.', 'youumatter2' ),
			'initial'   => 'R.',
			'age'       => 34,
			'condition' => __( 'Relationships', 'youumatter2' ),
			'duration'  => '',
		),
		array(
			'title'     => 'M. - Self-esteem',
			'quote'     => __( "I had tried therapy before and walked away. This time felt different. Warm, specific, and honest in a way I didn't know therapy could be.", 'youumatter2' ),
			'initial'   => 'M.',
			'age'       => 41,
			'condition' => __( 'Self-esteem', 'youumatter2' ),
			'duration'  => '',
		),
		array(
			'title'     => 'K. - Purpose & direction',
			'quote'     => __( 'The space she holds is the actual work. I started saying things I did not know I thought, and she helped me stay with them.', 'youumatter2' ),
			'initial'   => 'K.',
			'age'       => 26,
			'condition' => __( 'Purpose & direction', 'youumatter2' ),
			'duration'  => '',
		),
		array(
			'title'     => 'S. - Depression',
			'quote'     => __( "Even our hardest sessions ended with me feeling more like myself, not less. That's rare.", 'youumatter2' ),
			'initial'   => 'S.',
			'age'       => 38,
			'condition' => __( 'Depression', 'youumatter2' ),
			'duration'  => '',
		),
	);
}
