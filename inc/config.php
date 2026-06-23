<?php
/**
 * Centralised contact, social, and brand config.
 *
 * Single source of truth. Every read goes through yum2_get_contact().
 * Customizer values (saved as theme mods named yum2_<key>) win over defaults
 * when set; defaults win when the Customizer value is null or empty.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default contact, social, and brand values.
 *
 * @return array
 */
function yum2_contact_defaults() {
	return array(
		'phone'             => '+919953855858',
		'phone_display'     => '+91 99538 55858',
		'whatsapp'          => '919953855858',
		'email'             => 'youumatter2@gmail.com',
		'clinic_address'    => 'Mahendru Enclave, New Delhi',
		'clinic_hours'      => 'Mon to Sat · 10:00 AM to 7:00 PM',
		'clinic_map_embed'  => 'https://www.google.com/maps?q=Mahendru+Enclave,+GT+Karnal+Road,+Delhi+110033&hl=en&z=15&output=embed',
		'instagram'         => 'https://www.instagram.com/youumatter2withsanya/',
		'instagram_handle'  => '@youumatter2withsanya',
		'behold_feed_url'   => 'https://feeds.behold.so/GN2nUABNN8oVhsf6RPwl',
		'linkedin'          => 'https://www.linkedin.com/in/sanya-oberoi-a5a747175/',
		'youtube'           => '',
		'twitter'           => '',
		'facebook'          => '',
		'calendly_url'        => '',
		'calendly_color'      => '1a4d2e',
		'accepting_clients'   => true,
		'google_business_url' => 'https://share.google/Vt121dQVDJIrrNBcd',
		'google_rating'       => '5.0',
	);
}

/**
 * Read a contact / brand value.
 *
 * Customizer (theme_mod 'yum2_<key>') wins when set to a non-empty value.
 * Falls back to yum2_contact_defaults() then to $fallback for unknown keys.
 *
 * @param string $key      Contact key.
 * @param mixed  $fallback Returned when the key is unknown.
 * @return mixed
 */
function yum2_get_contact( $key, $fallback = null ) {
	$defaults = yum2_contact_defaults();
	$default  = array_key_exists( $key, $defaults ) ? $defaults[ $key ] : $fallback;

	$mod = get_theme_mod( 'yum2_' . $key, null );

	if ( null === $mod ) {
		return $default;
	}

	if ( is_bool( $default ) ) {
		return (bool) $mod;
	}

	if ( is_string( $mod ) && '' === trim( $mod ) ) {
		return $default;
	}

	return $mod;
}

/**
 * Build a wa.me link, optionally with pre-filled text.
 *
 * @param string $message Optional pre-filled message.
 * @return string Empty string when no WhatsApp number is set.
 */
function yum2_whatsapp_url( $message = '' ) {
	$number = preg_replace( '/[^0-9]/', '', (string) yum2_get_contact( 'whatsapp' ) );
	if ( '' === $number ) {
		return '';
	}
	$url = 'https://wa.me/' . $number;
	if ( '' !== $message ) {
		$url .= '?text=' . rawurlencode( $message );
	}
	return $url;
}

/**
 * tel: link to the raw E.164 phone number.
 *
 * @return string
 */
function yum2_phone_url() {
	$phone = (string) yum2_get_contact( 'phone' );
	return '' === $phone ? '' : 'tel:' . preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * mailto: link to the contact email.
 *
 * @return string
 */
function yum2_email_url() {
	$email = (string) yum2_get_contact( 'email' );
	return '' === $email ? '' : 'mailto:' . sanitize_email( $email );
}

/**
 * MailerLite API key. Read in priority order so it is never hardcoded in
 * committed code:
 *   1. WordPress option `yum2_mailerlite_api_key` (saved from Tools -> Newsletter)
 *   2. a real environment variable (YUM2_MAILERLITE_API_KEY)
 *   3. a constant from wp-config.php / .env / inc/env.local.php
 * Returns '' when not configured, so callers can degrade gracefully.
 *
 * @return string
 */
function yum2_mailerlite_api_key() {
	$opt = get_option( 'yum2_mailerlite_api_key', '' );
	if ( is_string( $opt ) && '' !== $opt ) {
		return $opt;
	}
	$env = getenv( 'YUM2_MAILERLITE_API_KEY' );
	if ( is_string( $env ) && '' !== $env ) {
		return $env;
	}
	if ( defined( 'YUM2_MAILERLITE_API_KEY' ) ) {
		return (string) YUM2_MAILERLITE_API_KEY;
	}
	return '';
}

/**
 * MailerLite group ID that new website subscribers are added to. Same lookup
 * order as the API key. Returns '' when unset (subscriber still gets added to
 * the account, just without a group).
 *
 * @return string
 */
function yum2_mailerlite_group_id() {
	$opt = get_option( 'yum2_mailerlite_group_id', '' );
	if ( is_string( $opt ) && '' !== $opt ) {
		return $opt;
	}
	$env = getenv( 'YUM2_MAILERLITE_GROUP_ID' );
	if ( is_string( $env ) && '' !== $env ) {
		return $env;
	}
	if ( defined( 'YUM2_MAILERLITE_GROUP_ID' ) ) {
		return (string) YUM2_MAILERLITE_GROUP_ID;
	}
	return '';
}

/**
 * Where the currently-active MailerLite value is coming from. Returns one of:
 * 'option' (saved in DB), 'env' (real env var), 'constant' (wp-config / .env /
 * env.local.php), or 'none'. Used by Tools -> Newsletter for display.
 *
 * @param string $which 'api_key' or 'group_id'
 * @return string
 */
function yum2_mailerlite_source( $which ) {
	$option_key   = 'api_key' === $which ? 'yum2_mailerlite_api_key' : 'yum2_mailerlite_group_id';
	$constant_key = 'api_key' === $which ? 'YUM2_MAILERLITE_API_KEY' : 'YUM2_MAILERLITE_GROUP_ID';

	$opt = get_option( $option_key, '' );
	if ( is_string( $opt ) && '' !== $opt ) {
		return 'option';
	}
	$env = getenv( $constant_key );
	if ( is_string( $env ) && '' !== $env ) {
		return 'env';
	}
	if ( defined( $constant_key ) ) {
		return 'constant';
	}
	return 'none';
}
