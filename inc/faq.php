<?php
/**
 * FAQ system: editable from the admin (FAQs menu).
 *
 * Pieces:
 *   - ACF "Show on homepage" toggle (registered in code, no manual ACF setup).
 *   - yum2_faq_groups()        : all FAQs grouped by category, for the FAQ page + schema.
 *   - yum2_faq_homepage_items(): the FAQs flagged for the homepage section.
 *   - One-time seeding         : migrates the original hardcoded FAQs into the CPT.
 *   - Drag-ordering            : reorder FAQs by dragging in the admin list.
 *
 * The `faq` post type + `faq_category` taxonomy are declared in inc/post-types.php.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * 1. ACF FIELD - "Show on homepage" toggle
 *    Registered in code so there is nothing to build inside ACF by hand.
 *    Guarded so the theme never errors if ACF is deactivated.
 * ====================================================================== */
function yum2_register_faq_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_yum2_faq',
			'title'    => __( 'FAQ options', 'youumatter2' ),
			'fields'   => array(
				array(
					'key'          => 'field_yum2_faq_homepage',
					'label'        => __( 'Show on homepage', 'youumatter2' ),
					'name'         => 'faq_homepage',
					'type'         => 'true_false',
					'ui'           => 1,
					'instructions' => __( 'Turn on to feature this FAQ in the homepage FAQ section (about 6 are shown there). It still appears on the full FAQ page under its category.', 'youumatter2' ),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'faq',
					),
				),
			),
			'position' => 'side',
		)
	);
}
add_action( 'acf/init', 'yum2_register_faq_fields' );

/* =========================================================================
 * 2. DATA HELPERS
 * ====================================================================== */

/**
 * Plain-text answer for a FAQ post. Renders blocks, strips markup so it is
 * safe to echo with esc_html() in the existing templates.
 *
 * @param int|WP_Post $post FAQ post or ID.
 * @return string
 */
function yum2_faq_answer( $post ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	return trim( wp_strip_all_tags( do_blocks( $post->post_content ), true ) );
}

/**
 * Whether a FAQ is flagged to show on the homepage.
 *
 * @param int $post_id FAQ post ID.
 * @return bool
 */
function yum2_faq_is_homepage( $post_id ) {
	if ( function_exists( 'get_field' ) ) {
		return (bool) get_field( 'faq_homepage', $post_id );
	}
	return (bool) get_post_meta( $post_id, 'faq_homepage', true );
}

/**
 * All FAQs grouped by category, for the FAQ page and FAQPage JSON-LD.
 *
 * Shape is kept identical to the previous hardcoded version so the FAQ page
 * templates and inc/seo.php need no changes:
 *   array{ id:string, label:string, blurb:string, items:array{q,a}[] }[]
 *
 * Empty categories are skipped. Categories order by creation (term id);
 * FAQs order within a category by the drag/menu order.
 *
 * @return array<int, array<string, mixed>>
 */
function yum2_faq_groups() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'faq_category',
			'hide_empty' => true,
			'orderby'    => 'id',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}

	$groups = array();

	foreach ( $terms as $term ) {
		$posts = get_posts(
			array(
				'post_type'      => 'faq',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'no_found_rows'  => true,
				'tax_query'      => array(
					array(
						'taxonomy' => 'faq_category',
						'field'    => 'term_id',
						'terms'    => $term->term_id,
					),
				),
			)
		);

		if ( empty( $posts ) ) {
			continue;
		}

		$items = array();
		foreach ( $posts as $post ) {
			$items[] = array(
				'q' => get_the_title( $post ),
				'a' => yum2_faq_answer( $post ),
			);
		}

		$groups[] = array(
			'id'    => $term->slug,
			'label' => $term->name,
			'blurb' => $term->description,
			'items' => $items,
		);
	}

	return $groups;
}

/**
 * FAQs flagged "Show on homepage", for the homepage FAQ section.
 *
 * Falls back to the most recent FAQs if none are flagged yet, so the section is
 * never accidentally empty. Returns the same {q, a} shape the section expects.
 *
 * @param int $limit Max items.
 * @return array<int, array{q:string, a:string}>
 */
function yum2_faq_homepage_items( $limit = 6 ) {
	$limit = max( 1, (int) $limit );

	$query_args = array(
		'post_type'      => 'faq',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
		'no_found_rows'  => true,
		'meta_query'     => array(
			array(
				'key'   => 'faq_homepage',
				'value' => '1',
			),
		),
	);

	$posts = get_posts( $query_args );

	/* Fallback: nothing flagged yet -> show the most recent FAQs. */
	if ( empty( $posts ) ) {
		unset( $query_args['meta_query'] );
		$posts = get_posts( $query_args );
	}

	$items = array();
	foreach ( $posts as $post ) {
		$items[] = array(
			'q' => get_the_title( $post ),
			'a' => yum2_faq_answer( $post ),
		);
	}

	return $items;
}

/* =========================================================================
 * 3. ADMIN: default the FAQ list to the drag/menu order
 * ====================================================================== */
function yum2_faq_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( 'faq' !== $query->get( 'post_type' ) ) {
		return;
	}
	if ( ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'menu_order title' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'yum2_faq_admin_order' );

/* =========================================================================
 * 4. ADMIN: drag-and-drop ordering on the FAQ list screen
 *    jQuery UI sortable (bundled with WP) + a secured ajax save. The
 *    page-attributes "Order" field still works as a manual fallback; both
 *    write menu_order.
 * ====================================================================== */
function yum2_faq_reorder_assets( $hook ) {
	if ( 'edit.php' !== $hook ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'faq' !== $screen->post_type ) {
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
						action: 'yum2_faq_reorder',
						nonce: window.yum2FaqReorder.nonce,
						order: ids
					});
				}
			});
		});
	})(jQuery);";

	wp_add_inline_script( 'jquery-ui-sortable', $inline );
	wp_localize_script(
		'jquery-ui-sortable',
		'yum2FaqReorder',
		array( 'nonce' => wp_create_nonce( 'yum2_faq_reorder' ) )
	);
}
add_action( 'admin_enqueue_scripts', 'yum2_faq_reorder_assets' );

/**
 * Persist a new FAQ order from the drag handler.
 */
function yum2_faq_reorder_save() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( 'forbidden', 403 );
	}
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'yum2_faq_reorder' ) ) {
		wp_send_json_error( 'bad_nonce', 400 );
	}

	$order = isset( $_POST['order'] ) ? (array) wp_unslash( $_POST['order'] ) : array();
	$order = array_map( 'absint', $order );

	$position = 0;
	foreach ( $order as $post_id ) {
		if ( $post_id && 'faq' === get_post_type( $post_id ) ) {
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
add_action( 'wp_ajax_yum2_faq_reorder', 'yum2_faq_reorder_save' );

/* =========================================================================
 * 5. ONE-TIME SEEDING
 *    Migrates the original hardcoded FAQs (categories + questions) into the
 *    CPT the first time an admin loads wp-admin after this feature ships, so
 *    nothing is lost. Runs once, guarded by an option and an existing-FAQ check.
 * ====================================================================== */
function yum2_seed_faqs() {
	if ( ! is_admin() || wp_doing_ajax() ) {
		return;
	}
	if ( get_option( 'yum2_faqs_seeded' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	/* Don't seed if FAQs already exist (e.g. created by hand). */
	$existing = get_posts(
		array(
			'post_type'      => 'faq',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);
	if ( ! empty( $existing ) ) {
		update_option( 'yum2_faqs_seeded', 1 );
		return;
	}

	$seed = yum2_faq_seed_data();
	$menu = 0;

	foreach ( $seed as $group ) {
		$term = term_exists( $group['slug'], 'faq_category' );
		if ( ! $term ) {
			$term = wp_insert_term(
				$group['label'],
				'faq_category',
				array(
					'slug'        => $group['slug'],
					'description' => $group['blurb'],
				)
			);
		}
		if ( is_wp_error( $term ) ) {
			continue;
		}
		$term_id = (int) ( is_array( $term ) ? $term['term_id'] : $term );

		foreach ( $group['items'] as $item ) {
			$post_id = wp_insert_post(
				array(
					'post_type'    => 'faq',
					'post_status'  => 'publish',
					'post_title'   => $item['q'],
					'post_content' => $item['a'],
					'menu_order'   => $menu,
				),
				true
			);
			$menu++;

			if ( is_wp_error( $post_id ) || ! $post_id ) {
				continue;
			}

			wp_set_object_terms( $post_id, array( $term_id ), 'faq_category' );

			if ( ! empty( $item['home'] ) ) {
				if ( function_exists( 'update_field' ) ) {
					update_field( 'faq_homepage', 1, $post_id );
				} else {
					update_post_meta( $post_id, 'faq_homepage', '1' );
				}
			}
		}
	}

	update_option( 'yum2_faqs_seeded', 1 );
}
add_action( 'admin_init', 'yum2_seed_faqs' );

/**
 * Initial FAQ content for seeding. Mirrors the launch copy. After seeding, the
 * source of truth is the admin, not this array. `home => true` flags an item
 * for the homepage section.
 *
 * @return array<int, array<string, mixed>>
 */
function yum2_faq_seed_data() {
	return array(
		array(
			'slug'  => 'getting-started',
			'label' => __( 'Getting started', 'youumatter2' ),
			'blurb' => __( 'What the first few steps look like.', 'youumatter2' ),
			'items' => array(
				array(
					'q'    => __( 'How do I book my first session?', 'youumatter2' ),
					'a'    => __( "The simplest way is to send a short note via the contact form or WhatsApp. I'll reply with a few time options and a soft first step. We don't commit to long-term work on day one.", 'youumatter2' ),
					'home' => true,
				),
				array(
					'q'    => __( 'What happens in the first session?', 'youumatter2' ),
					'a'    => __( "It's a 50-minute conversation. I'll ask what brought you, what you've tried, and what you'd like to feel different. You're not expected to have tidy answers. Unclear is a valid starting point.", 'youumatter2' ),
					'home' => true,
				),
				array(
					'q' => __( 'Will you diagnose me on the first day?', 'youumatter2' ),
					'a' => __( "No. Diagnosis, if it's relevant at all, comes much later and only with your involvement. The early sessions are about understanding, not labels.", 'youumatter2' ),
				),
				array(
					'q'    => __( 'How long does therapy usually take?', 'youumatter2' ),
					'a'    => __( "It depends on what you're working on. Some clients feel steadier in 6 to 8 sessions; others stay for longer work. We review together every few weeks so you're always in the driver's seat.", 'youumatter2' ),
					'home' => true,
				),
			),
		),
		array(
			'slug'  => 'sessions-fees',
			'label' => __( 'Sessions & fees', 'youumatter2' ),
			'blurb' => __( 'Format, frequency, cost.', 'youumatter2' ),
			'items' => array(
				array(
					'q'    => __( 'How long is a session and how often do we meet?', 'youumatter2' ),
					'a'    => __( 'Sessions are 50 minutes. Most clients start weekly or fortnightly, and we adjust as things settle.', 'youumatter2' ),
					'home' => true,
				),
				array(
					'q'    => __( 'What do you charge?', 'youumatter2' ),
					'a'    => __( 'Fees are shared on enquiry so I can factor in your situation and format (online vs in-person). I keep a small number of concessional slots each month.', 'youumatter2' ),
					'home' => true,
				),
				array(
					'q' => __( 'Do you offer sliding scale or concessional spots?', 'youumatter2' ),
					'a' => __( 'Yes, a few each month. If cost is a barrier, please mention it in your message. No awkwardness either way.', 'youumatter2' ),
				),
				array(
					'q' => __( "What's your cancellation policy?", 'youumatter2' ),
					'a' => __( "I ask for 24 hours' notice for cancellations. Late cancellations and no-shows are charged at the full fee, with reasonable exceptions for emergencies.", 'youumatter2' ),
				),
			),
		),
		array(
			'slug'  => 'online-in-person',
			'label' => __( 'Online vs In-person', 'youumatter2' ),
			'blurb' => __( 'Choosing what works for you.', 'youumatter2' ),
			'items' => array(
				array(
					'q'    => __( 'Is online therapy as effective as in-person?', 'youumatter2' ),
					'a'    => __( "For most concerns, yes. Research consistently finds comparable outcomes. The best format is the one you'll actually show up to, week after week.", 'youumatter2' ),
					'home' => true,
				),
				array(
					'q' => __( 'What platform do you use for online sessions?', 'youumatter2' ),
					'a' => __( "Google Meet. You'll get a link before the session. No downloads beyond a browser, and end-to-end encryption by default.", 'youumatter2' ),
				),
				array(
					'q' => __( 'Where is the in-person clinic?', 'youumatter2' ),
					'a' => __( "Mahendru Enclave, New Delhi. The exact address is shared once we've confirmed a first session.", 'youumatter2' ),
				),
				array(
					'q' => __( 'Can I switch between online and in-person?', 'youumatter2' ),
					'a' => __( 'Absolutely. Some clients start online and shift as they get more comfortable, or vice versa when travel gets tricky.', 'youumatter2' ),
				),
			),
		),
		array(
			'slug'  => 'confidentiality',
			'label' => __( 'Confidentiality', 'youumatter2' ),
			'blurb' => __( 'What stays private, and the few exceptions.', 'youumatter2' ),
			'items' => array(
				array(
					'q'    => __( 'Is what I share confidential?', 'youumatter2' ),
					'a'    => __( "Yes. Everything in session stays between us, with three narrow exceptions required by professional ethics: imminent risk to life, harm to a child or vulnerable adult, or a court order. I'll walk you through these in session one.", 'youumatter2' ),
					'home' => true,
				),
				array(
					'q' => __( 'Do you share notes with anyone?', 'youumatter2' ),
					'a' => __( "No. I keep minimal, secured notes for my own continuity and they're not shared with third parties, family members, or employers.", 'youumatter2' ),
				),
				array(
					'q' => __( 'What about insurance or HR claims?', 'youumatter2' ),
					'a' => __( "I can provide a simple invoice on request. I don't fill out detailed insurance forms or speak to employers without your explicit, written consent.", 'youumatter2' ),
				),
			),
		),
		array(
			'slug'  => 'couples',
			'label' => __( 'For couples', 'youumatter2' ),
			'blurb' => __( "When you're coming in together.", 'youumatter2' ),
			'items' => array(
				array(
					'q' => __( 'Do you see couples?', 'youumatter2' ),
					'a' => __( 'Yes. Couple sessions are 60 minutes and usually fortnightly. We work on communication patterns, ruptures, and the quiet things that build up.', 'youumatter2' ),
				),
				array(
					'q' => __( 'What if only one of us wants therapy?', 'youumatter2' ),
					'a' => __( "That's common. Start with an individual session. The work often softens the dynamic at home even when only one partner is in the room.", 'youumatter2' ),
				),
			),
		),
		array(
			'slug'  => 'emergency',
			'label' => __( 'In an emergency', 'youumatter2' ),
			'blurb' => __( 'When you need support right now.', 'youumatter2' ),
			'items' => array(
				array(
					'q' => __( "What do I do if I'm in crisis?", 'youumatter2' ),
					'a' => __( "Please reach out to iCall India (9152987821) or Vandrevala Foundation (1860 266 2345). Both are free and staffed by trained counsellors. If you're in immediate danger, call emergency services. I'll follow up as soon as I see your message, but these services respond faster in acute moments.", 'youumatter2' ),
				),
			),
		),
	);
}
