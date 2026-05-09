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
	return $classes;
}
add_filter( 'body_class', 'yum2_body_classes' );

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
