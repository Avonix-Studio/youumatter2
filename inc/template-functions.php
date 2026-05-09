<?php
/**
 * Filters, body classes, and small helper functions.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add `has-bottom-nav` to the body so the matching CSS rule pads the
 * page enough that the fixed mobile bottom-nav doesn't cover content.
 *
 * @param string[] $classes
 * @return string[]
 */
function yum2_body_classes( $classes ) {
	if ( ! is_admin() ) {
		$classes[] = 'has-bottom-nav';
	}
	if ( is_home() || ( is_archive() && in_array( get_post_type(), array( '', 'post' ), true ) ) || is_search() ) {
		$classes[] = 'blog-index';
	}
	if ( is_singular( 'post' ) ) {
		$classes[] = 'single-post-yum2';
	}
	return $classes;
}
add_filter( 'body_class', 'yum2_body_classes' );

/**
 * Reading time, in minutes, for the given post.
 *
 * Word count / 200 (the lazy adult-reading-rate average) rounded up,
 * floor of 1. Returns a translated "X min read" string.
 *
 * @param int|WP_Post|null $post
 * @return string
 */
function yum2_reading_time( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}
	$words   = str_word_count( wp_strip_all_tags( (string) $post->post_content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	return sprintf(
		/* translators: %d: minutes */
		_n( '%d min read', '%d min read', $minutes, 'youumatter2' ),
		$minutes
	);
}

/**
 * Fallback excerpt: when the post has no manual excerpt, return the
 * first sentence of the content (capped at ~30 words) instead of WP's
 * default 55-word truncation that often cuts mid-thought.
 *
 * @param string  $excerpt
 * @param WP_Post $post
 * @return string
 */
function yum2_excerpt_fallback( $excerpt, $post = null ) {
	if ( '' !== trim( (string) $excerpt ) ) {
		return $excerpt;
	}
	$post = get_post( $post );
	if ( ! $post ) {
		return $excerpt;
	}
	$plain = wp_strip_all_tags( strip_shortcodes( (string) $post->post_content ) );
	$plain = trim( preg_replace( '/\s+/', ' ', $plain ) );
	if ( '' === $plain ) {
		return $excerpt;
	}
	if ( preg_match( '/^(.{20,}?[.!?])(\s|$)/u', $plain, $m ) ) {
		$first = trim( $m[1] );
	} else {
		$first = $plain;
	}
	return wp_trim_words( $first, 30, '…' );
}
add_filter( 'get_the_excerpt', 'yum2_excerpt_fallback', 10, 2 );

/**
 * Newsletter form handler. POST target for template-parts/footer/newsletter.php.
 *
 * Phase 3 stores the email in a capped option array and redirects back
 * with ?subscribed=1. MailerLite wiring lands in a later phase; the
 * markup contract (action name + nonce field name) stays the same.
 */
function yum2_handle_subscribe() {
	$nonce = isset( $_POST['_yum2_subscribe_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_yum2_subscribe_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'yum2_subscribe' ) ) {
		wp_die( esc_html__( 'Invalid request.', 'youumatter2' ), '', array( 'response' => 400 ) );
	}

	$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$redirect = isset( $_POST['_yum2_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['_yum2_redirect'] ) ) : home_url( '/' );

	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'subscribed', '0', $redirect ) );
		exit;
	}

	$list = get_option( 'yum2_pending_subscribers', array() );
	if ( ! is_array( $list ) ) {
		$list = array();
	}
	if ( ! in_array( $email, $list, true ) ) {
		$list[] = $email;
		if ( count( $list ) > 500 ) {
			$list = array_slice( $list, -500 );
		}
		update_option( 'yum2_pending_subscribers', $list, false );
	}

	wp_safe_redirect( add_query_arg( 'subscribed', '1', $redirect ) . '#newsletter' );
	exit;
}
add_action( 'admin_post_yum2_subscribe', 'yum2_handle_subscribe' );
add_action( 'admin_post_nopriv_yum2_subscribe', 'yum2_handle_subscribe' );

/**
 * Repair the_custom_logo() output for SVG attachments.
 *
 * WordPress core uses wp_get_attachment_image_src() for the custom logo,
 * which returns false for SVGs (no raster dimensions), so the markup
 * comes back empty. We rebuild it with a clean <img> the .custom-logo
 * CSS rules can size.
 *
 * @param string $html Original markup from get_custom_logo().
 * @return string
 */
function yum2_fix_svg_custom_logo( $html ) {
	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( ! $logo_id ) {
		return $html;
	}

	$mime = (string) get_post_mime_type( $logo_id );
	if ( 'image/svg+xml' !== $mime && 'image/svg' !== $mime ) {
		return $html;
	}

	$src = wp_get_attachment_url( $logo_id );
	if ( ! $src ) {
		return $html;
	}

	$alt = (string) get_post_meta( $logo_id, '_wp_attachment_image_alt', true );
	if ( '' === $alt ) {
		$alt = get_bloginfo( 'name' );
	}

	return sprintf(
		'<a href="%1$s" class="custom-logo-link" rel="home"><img class="custom-logo" src="%2$s" alt="%3$s" loading="eager" decoding="async" /></a>',
		esc_url( home_url( '/' ) ),
		esc_url( $src ),
		esc_attr( $alt )
	);
}
add_filter( 'get_custom_logo', 'yum2_fix_svg_custom_logo' );
