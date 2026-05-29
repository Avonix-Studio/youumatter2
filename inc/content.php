<?php
/**
 * Central content file for all template sections.
 *
 * THIS IS THE ONE FILE TO OPEN WHEN YOU WANT TO EDIT:
 *   - Page headings and section labels
 *   - Body copy and descriptions
 *   - Card data (services, FAQs, testimonials, how-it-works steps, etc.)
 *   - WhatsApp pre-filled messages
 *   - Instagram placeholder tiles
 *   - Newsletter and contact strip copy
 *
 * Contact details (phone, email, Calendly URL, address) live in inc/config.php,
 * not here. Section visibility toggles live in the Customizer.
 *
 * HOW TO USE:
 *   $c = yum2_content( 'section_key' );
 *   echo esc_html( $c['heading'] );
 *
 * The data is built once per page load (static cache) - zero performance cost.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return all content for a given section.
 *
 * @param  string      $section  Section key (e.g. 'hero', 'faq').
 * @param  string|null $key      Optional sub-key to return a single value.
 * @return mixed                 Array for the section, or a single value, or null.
 */
function yum2_content( $section, $key = null ) {
	static $data = null;

	if ( null === $data ) {
		$data = yum2_build_content();
	}

	$sec = isset( $data[ $section ] ) ? $data[ $section ] : array();

	if ( null !== $key ) {
		return isset( $sec[ $key ] ) ? $sec[ $key ] : null;
	}

	return $sec;
}

/**
 * Fetch the latest Instagram posts from the Behold.so feed.
 *
 * Live data, cached in a WP transient so we don't hit Behold on every page
 * load. New posts appear within ~1 hour of Sanya posting. Returns an empty
 * array on any error - the template falls back to placeholder tiles from
 * yum2_content( 'instagram' )['tiles'].
 *
 * @param int $limit Max number of posts to return.
 * @return array
 */
function yum2_get_instagram_posts( $limit = 6 ) {
	$cache_key = 'yum2_ig_feed_v1';
	$cached    = get_transient( $cache_key );
	if ( false !== $cached ) {
		return is_array( $cached ) ? $cached : array();
	}

	$url = (string) yum2_get_contact( 'behold_feed_url' );
	if ( '' === $url ) {
		return array();
	}

	$response = wp_remote_get( $url, array( 'timeout' => 8 ) );

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		/* Short-cache empty result so a flaky network doesn't hammer Behold. */
		set_transient( $cache_key, array(), 5 * MINUTE_IN_SECONDS );
		return array();
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( ! is_array( $data ) || empty( $data['posts'] ) ) {
		set_transient( $cache_key, array(), 5 * MINUTE_IN_SECONDS );
		return array();
	}

	$posts = array();
	foreach ( array_slice( $data['posts'], 0, (int) $limit ) as $p ) {
		$caption = isset( $p['prunedCaption'] ) ? $p['prunedCaption'] : ( isset( $p['caption'] ) ? $p['caption'] : '' );
		$caption = trim( preg_replace( '/\s+/', ' ', (string) $caption ) );
		if ( function_exists( 'mb_strlen' ) && mb_strlen( $caption ) > 110 ) {
			$caption = mb_substr( $caption, 0, 107 ) . '…';
		} elseif ( strlen( $caption ) > 110 ) {
			$caption = substr( $caption, 0, 107 ) . '...';
		}

		$posts[] = array(
			'caption'   => $caption,
			'thumb'     => isset( $p['thumbnailUrl'] ) ? esc_url_raw( $p['thumbnailUrl'] ) : '',
			'permalink' => isset( $p['permalink'] ) ? esc_url_raw( $p['permalink'] ) : '',
			'is_reel'   => ! empty( $p['isReel'] ),
		);
	}

	set_transient( $cache_key, $posts, HOUR_IN_SECONDS );
	return $posts;
}

/**
 * Build and return the full content array.
 * Private to this file - always access via yum2_content().
 *
 * @return array
 */
function yum2_build_content() {
	return array(

		/* =============================================================
		   HERO SECTION
		   Edit: eyebrow chip, main heading, italic tagline, body
		         paragraph, floating quote card text, button labels,
		         WhatsApp pre-filled message.
		   ============================================================= */
		'hero' => array(
			'eyebrow'         => __( 'Counselling Psychologist', 'youumatter2' ),
			'heading'         => __( 'Youu matter.', 'youumatter2' ),
			'heading_em'      => __( 'And you can do this.', 'youumatter2' ),
			'body'            => __( "A quiet space to untangle what's weighing on you. Thoughtful, evidence-based therapy, online or in person at my clinic.", 'youumatter2' ),
			'quote_card'      => __( '"A steady Tuesday."', 'youumatter2' ),
			'btn_book'        => __( 'Book a Session', 'youumatter2' ),
			'btn_whatsapp'    => __( 'Message on WhatsApp', 'youumatter2' ),
			'badge_accepting' => __( 'Accepting new clients', 'youumatter2' ),
			'whatsapp_msg'    => __( 'Hi Sanya, I would like to learn more about working together.', 'youumatter2' ),
		),

		/* =============================================================
		   YOU MIGHT BE FEELING (9-card carousel)
		   Edit: section label, heading, description, the chip labels
		         across the top, and all 9 service cards.
		   Each card: title, tagline, signs (up to 3 bullets),
		              approach paragraph, session fee.
		   ============================================================= */
		'feeling' => array(
			'label'        => __( 'What are you carrying today?', 'youumatter2' ),
			'heading'      => __( 'If any of these sound like you,', 'youumatter2' ),
			'heading_em'   => __( 'this might help.', 'youumatter2' ),
			'description'  => __( 'Nine common reasons people reach out, and a small starting place for each, before you book anything.', 'youumatter2' ),
			'cta_note'     => __( 'Not sure which one fits? The 15-minute intro call sorts it.', 'youumatter2' ),
			'btn_book'     => __( 'Book a free 15-min call', 'youumatter2' ),
			'btn_whatsapp' => __( 'Message on WhatsApp', 'youumatter2' ),
			'whatsapp_msg' => __( 'Hi Sanya, I would like to chat about working together.', 'youumatter2' ),

			/* Filter chips above the carousel - must match card order. */
			'chips' => array(
				__( 'Relationships', 'youumatter2' ),
				__( 'Anxiety', 'youumatter2' ),
				__( 'Couples', 'youumatter2' ),
				__( 'Self-Esteem', 'youumatter2' ),
				__( 'Emotions', 'youumatter2' ),
				__( 'Intimacy', 'youumatter2' ),
				__( 'Depression', 'youumatter2' ),
				__( 'Transitions', 'youumatter2' ),
				__( 'Boundaries', 'youumatter2' ),
			),

			/* The 9 service cards. 'signs' is an array of bullet strings. */
			'cards' => array(
				array(
					'title'    => __( 'Relationship Difficulties', 'youumatter2' ),
					'tagline'  => __( 'Re-learning how to be with people you love.', 'youumatter2' ),
					'signs'    => array(
						__( 'You have the same fight on repeat.', 'youumatter2' ),
						__( "You've stopped bringing things up.", 'youumatter2' ),
						__( 'You love them and feel alone.', 'youumatter2' ),
					),
					'approach' => __( 'We look at the pattern, not the person. What gets triggered, what protects, what is underneath, so you can show up differently without losing yourself.', 'youumatter2' ),
					'fee'      => __( '₹2,500 / person', 'youumatter2' ),
				),
				array(
					'title'    => __( 'Anxiety & Overthinking', 'youumatter2' ),
					'tagline'  => __( "When the what-ifs won't stop.", 'youumatter2' ),
					'signs'    => array(
						__( "Your mind won't stop at 2am.", 'youumatter2' ),
						__( 'You rehearse conversations that never happen.', 'youumatter2' ),
						__( 'Your body is always a little braced.', 'youumatter2' ),
					),
					'approach' => __( "CBT gives us language for the loop. Somatic work gives us an exit from it. We'll find both.", 'youumatter2' ),
					'fee'      => __( '₹2,500', 'youumatter2' ),
				),
				array(
					'title'    => __( 'Breaking Unhealthy Patterns', 'youumatter2' ),
					'tagline'  => __( 'The loops that keep you stuck.', 'youumatter2' ),
					'signs'    => array(
						__( 'You keep ending up here.', 'youumatter2' ),
						__( 'You know the script by heart.', 'youumatter2' ),
						__( 'The self-sabotage feels familiar, almost comforting.', 'youumatter2' ),
					),
					'approach' => __( "Patterns form for a reason, usually protection. We'll understand the job they were doing, thank them, and learn something new.", 'youumatter2' ),
					'fee'      => __( '₹2,500 / person', 'youumatter2' ),
				),
				array(
					'title'    => __( 'Emotional Regulation', 'youumatter2' ),
					'tagline'  => __( 'Tools to feel without being flooded.', 'youumatter2' ),
					'signs'    => array(
						__( 'You go from 0 to 100 fast.', 'youumatter2' ),
						__( "Or you've gone numb.", 'youumatter2' ),
						__( "Other people's moods move you.", 'youumatter2' ),
					),
					'approach' => __( "Less 'calm down,' more 'what is this feeling actually trying to tell you.' Regulation comes from being heard, first by yourself.", 'youumatter2' ),
					'fee'      => __( '₹2,500', 'youumatter2' ),
				),
				array(
					'title'    => __( 'Self-Esteem & Confidence', 'youumatter2' ),
					'tagline'  => __( 'Who you are, before the doubt.', 'youumatter2' ),
					'signs'    => array(
						__( 'The inner voice is cruel.', 'youumatter2' ),
						__( "You can't take a compliment.", 'youumatter2' ),
						__( 'You shrink yourself without noticing.', 'youumatter2' ),
					),
					'approach' => __( 'We separate the voice from you. We ask where it was learned. And we practice, gently, sounding like someone who respects you.', 'youumatter2' ),
					'fee'      => __( '₹2,500', 'youumatter2' ),
				),
				array(
					'title'    => __( 'Finding Purpose Again', 'youumatter2' ),
					'tagline'  => __( 'When meaning feels far away.', 'youumatter2' ),
					'signs'    => array(
						__( 'Nothing feels meaningful.', 'youumatter2' ),
						__( 'You tick boxes but feel hollow.', 'youumatter2' ),
						__( "You're performing a life you don't recognize.", 'youumatter2' ),
					),
					'approach' => __( "We slow down. We listen for what still makes you lean in, even faintly. Purpose isn't found, it's noticed.", 'youumatter2' ),
					'fee'      => __( '₹2,500', 'youumatter2' ),
				),
				array(
					'title'    => __( 'Overcoming Past Trauma', 'youumatter2' ),
					'tagline'  => __( 'Gentle, paced, at your speed.', 'youumatter2' ),
					'signs'    => array(
						__( "The past isn't past.", 'youumatter2' ),
						__( 'Your body remembers things your mind tries to skip.', 'youumatter2' ),
						__( 'Certain days, smells, songs undo you.', 'youumatter2' ),
					),
					'approach' => __( 'Paced, consent-based, never rushed. We build safety before we touch the hard parts. You set the speed, always.', 'youumatter2' ),
					'fee'      => __( '₹2,500', 'youumatter2' ),
				),
				array(
					'title'    => __( 'Depression', 'youumatter2' ),
					'tagline'  => __( 'Light, patiently, back in.', 'youumatter2' ),
					'signs'    => array(
						__( 'Everything feels heavy.', 'youumatter2' ),
						__( "You're tired in a way sleep won't fix.", 'youumatter2' ),
						__( "You've lost the thread of what you used to love.", 'youumatter2' ),
					),
					'approach' => __( "We don't try to think our way out of depression. We'll build small, honest things back in: warmth, movement, being witnessed.", 'youumatter2' ),
					'fee'      => __( '₹2,500', 'youumatter2' ),
				),
				array(
					'title'    => __( 'Limiting Beliefs', 'youumatter2' ),
					'tagline'  => __( "The quiet rules you didn't choose.", 'youumatter2' ),
					'signs'    => array(
						__( "You hear yourself say 'I'm just not the kind of person who...'", 'youumatter2' ),
						__( 'You shrink your wants to fit your story.', 'youumatter2' ),
						__( 'You believed something about yourself early, and forgot you could question it.', 'youumatter2' ),
					),
					'approach' => __( 'Narrative therapy asks: whose voice is that, really? Then it makes room for a new one.', 'youumatter2' ),
					'fee'      => __( '₹2,500', 'youumatter2' ),
				),
			),
		),

		/* =============================================================
		   HOW IT WORKS (3-step section)
		   Edit: section label, heading, step cards (icon stays, but
		         title, body, and note below the step number are editable).
		   ============================================================= */
		'how_it_works' => array(
			'label'        => __( 'From here to a first session', 'youumatter2' ),
			'heading'      => __( 'Three small steps.', 'youumatter2' ),
			'heading_em'   => __( "No commitment until you're ready.", 'youumatter2' ),
			'description'  => __( "Reaching out is often the hardest part. After that, it's simple.", 'youumatter2' ),
			'footer_note'  => __( "Not ready to book? That's okay, just say hi.", 'youumatter2' ),
			'btn_book'     => __( 'Book a Session', 'youumatter2' ),
			'btn_whatsapp' => __( 'Message on WhatsApp', 'youumatter2' ),
			'whatsapp_msg' => __( 'Hi Sanya, just saying hi for now.', 'youumatter2' ),

			/* 'icon' maps to a Lucide icon name registered in inc/template-tags.php. */
			'steps' => array(
				array(
					'icon'  => 'calendar',
					'title' => __( 'Book', 'youumatter2' ),
					'body'  => __( "Tap Book, pick a time that works, and you're set. No forms, no back-and-forth.", 'youumatter2' ),
					'note'  => __( 'takes about a minute', 'youumatter2' ),
				),
				array(
					'icon'  => 'message',
					'title' => __( 'Connect', 'youumatter2' ),
					'body'  => __( "A fifteen-minute intro on WhatsApp. To see if we're a fit, no pressure either way.", 'youumatter2' ),
					'note'  => __( 'on WhatsApp, at your comfort', 'youumatter2' ),
				),
				array(
					'icon'  => 'leaf',
					'title' => __( 'Begin', 'youumatter2' ),
					'body'  => __( 'Your first therapy session. Sixty minutes, online or in person. We start where you are, gently, at your pace.', 'youumatter2' ),
					'note'  => __( 'sixty minutes, yours', 'youumatter2' ),
				),
			),
		),

		/* =============================================================
		   INSIDE A SESSION (4-card grid)
		   Edit: section label, heading, and each card label + body.
		   'icon' maps to a Lucide icon - change with care.
		   ============================================================= */
		'inside_a_session' => array(
			'label'      => __( 'Inside a session', 'youumatter2' ),
			'heading'    => __( 'What actually happens', 'youumatter2' ),
			'heading_em' => __( 'in a session.', 'youumatter2' ),

			'items' => array(
				array(
					'icon'  => 'map-pin',
					'label' => __( 'Start where you are', 'youumatter2' ),
					'body'  => __( 'No script. Bring a specific problem, or just "I feel off". Both work.', 'youumatter2' ),
				),
				array(
					'icon'  => 'ear',
					'label' => __( 'Stay with what surfaces', 'youumatter2' ),
					'body'  => __( 'I listen closely, ask gently, and give it space. No rushing past the real stuff.', 'youumatter2' ),
				),
				array(
					'icon'  => 'sprout',
					'label' => __( 'Patterns, not blame', 'youumatter2' ),
					'body'  => __( "We look at what a feeling is protecting, not whether you're right or wrong.", 'youumatter2' ),
				),
				array(
					'icon'  => 'gem',
					'label' => __( 'Leave with one thing', 'youumatter2' ),
					'body'  => __( 'One observation or small practice to sit with. Not ten takeaways to forget.', 'youumatter2' ),
				),
			),
		),

		/* =============================================================
		   TESTIMONIALS
		   Edit: section label, heading, and each quote, client initial
		         + age, and the context tag (condition + duration).
		   ============================================================= */
		'testimonials' => array(
			'label'        => __( 'Client reviews', 'youumatter2' ),
			'heading'      => __( 'What clients say,', 'youumatter2' ),
			'heading_em'   => __( 'in their own words.', 'youumatter2' ),
			'privacy_note' => __( 'Shared with consent · details changed for privacy.', 'youumatter2' ),

			'items' => array(
				array(
					'quote'       => __( 'I came in thinking something was wrong with me. I left understanding that I had been coping, not broken. Sanya made the difference feel huge.', 'youumatter2' ),
					'attribution' => __( 'A., 29', 'youumatter2' ),
					'context'     => __( 'Anxiety · 6 months in', 'youumatter2' ),
				),
				array(
					'quote'       => __( 'She never rushed me. The first real moment came in session five, and she was there, ready, like she had been expecting it.', 'youumatter2' ),
					'attribution' => __( 'R., 34', 'youumatter2' ),
					'context'     => __( 'Relationships', 'youumatter2' ),
				),
				array(
					'quote'       => __( "I had tried therapy before and walked away. This time felt different. Warm, specific, and honest in a way I didn't know therapy could be.", 'youumatter2' ),
					'attribution' => __( 'M., 41', 'youumatter2' ),
					'context'     => __( 'Self-esteem', 'youumatter2' ),
				),
				array(
					'quote'       => __( 'The space she holds is the actual work. I started saying things I did not know I thought, and she helped me stay with them.', 'youumatter2' ),
					'attribution' => __( 'K., 26', 'youumatter2' ),
					'context'     => __( 'Purpose & direction', 'youumatter2' ),
				),
				array(
					'quote'       => __( "Even our hardest sessions ended with me feeling more like myself, not less. That's rare.", 'youumatter2' ),
					'attribution' => __( 'S., 38', 'youumatter2' ),
					'context'     => __( 'Depression', 'youumatter2' ),
				),
			),
		),

		/* =============================================================
		   FAQ (accordion, home page shows 6)
		   Edit: section label, heading, description, and every Q&A pair.
		   Add or remove items freely - the accordion renders however many
		   are in this array.
		   ============================================================= */
		'faq' => array(
			'label'        => __( 'Before you reach out', 'youumatter2' ),
			'heading'      => __( 'Common things', 'youumatter2' ),
			'heading_em'   => __( 'people ask.', 'youumatter2' ),
			'description'  => __( "The questions that come up most often. If yours isn't here, message me directly. I'll answer the same day.", 'youumatter2' ),
			'footer_note'  => __( 'Still wondering about something?', 'youumatter2' ),
			'btn_all_faqs' => __( 'See all FAQs', 'youumatter2' ),
			'btn_whatsapp' => __( 'Ask on WhatsApp', 'youumatter2' ),
			'whatsapp_msg' => __( 'Hi Sanya, I have a quick question.', 'youumatter2' ),
			/* Questions come from the FAQs CPT (flagged "Show on homepage"), not from here. */
		),

		/* =============================================================
		   FROM THE BLOG (3 most recent posts - content is dynamic)
		   Edit: section label, heading, italic tagline, description.
		   The actual posts come from WordPress, not from here.
		   ============================================================= */
		'from_blog' => array(
			'label'       => __( 'From the blog', 'youumatter2' ),
			'heading'     => __( 'Thoughts to help you', 'youumatter2' ),
			'heading_em'  => __( 'along the way.', 'youumatter2' ),
			'description' => __( 'Honest writing on anxiety, relationships, and what it means to feel okay again.', 'youumatter2' ),
			'view_all'    => __( 'View all posts', 'youumatter2' ),
		),

		/* =============================================================
		   ABOUT SANYA (home page block - not the full About page)
		   Edit: section label, heading, bio paragraph, beliefs (3 items),
		         approaches (4 chips), stat rows on the floating card.
		   'k' is the number label on each belief (e.g. "01").
		   't' is the bold belief statement.
		   's' is the italic clarification.
		   ============================================================= */
		'about' => array(
			'label'            => __( 'About Sanya', 'youumatter2' ),
			'heading'          => __( "Hi, I'm", 'youumatter2' ),
			'heading_em'       => __( 'Sanya.', 'youumatter2' ),
			'bio'              => __( "I'm a Counselling Psychologist (M.A. Clinical Psychology) working with individuals and couples through relationships, anxiety, and emotional well-being. My style is warm, collaborative, and client-centered. A safe, non-judgmental space.", 'youumatter2' ),
			'beliefs_label'    => __( 'What I believe', 'youumatter2' ),
			'read_more'        => __( 'Read my full story', 'youumatter2' ),
			'credential_label' => __( 'Credential', 'youumatter2' ),
			'credential_value' => __( 'M.A. Clinical Psychology', 'youumatter2' ),

			/* The three belief statements in the green box. */
			'beliefs' => array(
				array(
					'k' => '01',
					't' => __( "Therapy isn't fixing.", 'youumatter2' ),
					's' => __( "It's meeting yourself with less armour.", 'youumatter2' ),
				),
				array(
					'k' => '02',
					't' => __( 'Your pace, always.', 'youumatter2' ),
					's' => __( "We don't rush the tender parts.", 'youumatter2' ),
				),
				array(
					'k' => '03',
					't' => __( 'Small shifts, real change.', 'youumatter2' ),
					's' => __( 'Tiny honest things, practised often.', 'youumatter2' ),
				),
			),

			/* Therapy approach chips - hover shows the gist as a tooltip. */
			'approaches' => array(
				array( 'name' => __( 'CBT', 'youumatter2' ),               'gist' => __( 'Naming the thought loops that keep us stuck.', 'youumatter2' ) ),
				array( 'name' => __( 'Narrative Therapy', 'youumatter2' ), 'gist' => __( "Rewriting the story you've been told about yourself.", 'youumatter2' ) ),
				array( 'name' => __( 'Mindfulness', 'youumatter2' ),       'gist' => __( 'Staying with what is, without flinching.', 'youumatter2' ) ),
				array( 'name' => __( 'Emotion-Focused', 'youumatter2' ),   'gist' => __( 'Feelings as information, not obstacles.', 'youumatter2' ) ),
			),

			/* Floating stats card (bottom-left of portrait). */
			'stat_rows' => array(
				array( 'label' => __( 'Based in', 'youumatter2' ),  'value' => __( 'Pitampura, Delhi', 'youumatter2' ) ),
				array( 'label' => __( 'Sessions', 'youumatter2' ),  'value' => __( 'Online · In-person', 'youumatter2' ) ),
				array( 'label' => __( 'Languages', 'youumatter2' ), 'value' => __( 'English · Hindi', 'youumatter2' ) ),
			),
		),

		/* =============================================================
		   INSTAGRAM FEED (placeholder tiles)
		   Edit: section label, heading, description, and each tile
		         caption, like/comment counts, whether it is a reel.
		   NOTE: bg is a hex color for the placeholder card background.
		   These tiles are replaced by a Behold.so embed in Phase 6+.
		   ============================================================= */
		'instagram' => array(
			'label'       => __( 'On Instagram', 'youumatter2' ),
			'heading'     => __( 'Small reminders,', 'youumatter2' ),
			'heading_em'  => __( 'in your feed.', 'youumatter2' ),
			'description' => __( 'Reflections, reels, and quiet reminders. Shared between sessions.', 'youumatter2' ),

			'tiles' => array(
				array(
					'caption'  => __( "On the days the 'what-ifs' win, try this one grounding practice.", 'youumatter2' ),
					'likes'    => '124k',
					'comments' => '842',
					'is_reel'  => true,
					'bg'       => '#c8dcc7',
				),
				array(
					'caption'  => __( 'What to say when your inner critic gets loud.', 'youumatter2' ),
					'likes'    => '96k',
					'comments' => '1.2k',
					'is_reel'  => true,
					'bg'       => '#ede0d0',
				),
				array(
					'caption'  => __( "Boundaries aren't walls. They're doors with handles on the inside.", 'youumatter2' ),
					'likes'    => '58k',
					'comments' => '412',
					'is_reel'  => true,
					'bg'       => '#d1e5d0',
				),
				array(
					'caption'  => __( 'A reminder for anyone rebuilding their self-worth this week.', 'youumatter2' ),
					'likes'    => '214k',
					'comments' => '3.1k',
					'is_reel'  => true,
					'bg'       => '#e4efe3',
				),
				array(
					'caption'  => __( "Rest isn't a reward. It's the ground the rest grows from.", 'youumatter2' ),
					'likes'    => '42k',
					'comments' => '268',
					'is_reel'  => true,
					'bg'       => '#f8f3e9',
				),
				array(
					'caption'  => __( 'A quiet Sunday practice: ask what felt honest this week.', 'youumatter2' ),
					'likes'    => '1.2k',
					'comments' => '46',
					'is_reel'  => false,
					'bg'       => '#f2ede3',
				),
			),
		),

		/* =============================================================
		   GENTLE INVITATION (closing home section)
		   Edit: label, heading, italic tagline, body paragraph.
		   Contact details (phone/email/hours/clinic) come from
		   inc/config.php via yum2_get_contact() - edit them there.
		   ============================================================= */
		'gentle_invitation' => array(
			'label'           => __( 'A gentle invitation', 'youumatter2' ),
			'heading'         => __( 'Take your time.', 'youumatter2' ),
			'heading_em'      => __( "I'll be here when you're ready.", 'youumatter2' ),
			'body'            => __( 'Reaching out is often the hardest part. No pressure, no rush. Just a conversation when it feels right for you.', 'youumatter2' ),
			'btn_book'        => __( 'Book a session', 'youumatter2' ),
			'btn_whatsapp'    => __( 'Message on WhatsApp', 'youumatter2' ),
			'accepting_text'  => __( 'Currently accepting new clients', 'youumatter2' ),
			'practical_label' => __( 'Practical details', 'youumatter2' ),
			'sessions_value'  => __( 'Online (Google Meet) · In-person', 'youumatter2' ),
			'reach_me_label'  => __( 'Reach me', 'youumatter2' ),
			'whatsapp_msg'    => __( 'Hi Sanya, I would like to start a conversation.', 'youumatter2' ),
		),

		/* =============================================================
		   NEWSLETTER STRIP (footer)
		   Edit: heading, description, input placeholder, button text,
		         success message, and error message.
		   ============================================================= */
		'newsletter' => array(
			'heading'     => __( 'Stay close to what helps.', 'youumatter2' ),
			'description' => __( 'Monthly notes on therapy, mindful habits, and quiet reflections. No spam, unsubscribe anytime.', 'youumatter2' ),
			'placeholder' => __( 'Your email', 'youumatter2' ),
			'btn_submit'  => __( 'Subscribe', 'youumatter2' ),
			'success'     => __( "Thank you. I'll be in touch.", 'youumatter2' ),
			'error'       => __( 'That email address looked off. Please try again.', 'youumatter2' ),
		),

		/* =============================================================
		   CONTACT STRIP (three clickable columns in the footer)
		   Edit: the three column labels and the WhatsApp sub-label.
		   Email and phone values come from inc/config.php.
		   ============================================================= */
		'contact_strip' => array(
			'label_email'     => __( 'Email me', 'youumatter2' ),
			'label_call'      => __( 'Call me', 'youumatter2' ),
			'label_whatsapp'  => __( 'WhatsApp me', 'youumatter2' ),
			'meta_whatsapp'   => __( 'Quick reply, usually same day', 'youumatter2' ),
		),

	);
}
