<?php
/**
 * Asset enqueueing and front-end performance cleanups.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function yum2_assets() {
	$theme_dir = get_template_directory();
	$theme_uri = get_template_directory_uri();

	$css_path = $theme_dir . '/assets/css/main.css';
	$js_path  = $theme_dir . '/assets/js/main.js';

	wp_enqueue_style(
		'yum2-main',
		$theme_uri . '/assets/css/main.css',
		array(),
		file_exists( $css_path ) ? filemtime( $css_path ) : YUM2_VERSION
	);

	wp_enqueue_script(
		'alpine',
		$theme_uri . '/assets/js/vendor/alpine.min.js',
		array(),
		'3.13.0',
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	if ( is_front_page() || is_singular( 'post' ) ) {
		wp_enqueue_style(
			'swiper',
			$theme_uri . '/assets/js/vendor/swiper.min.css',
			array(),
			'11.0.0'
		);
		wp_enqueue_script(
			'swiper',
			$theme_uri . '/assets/js/vendor/swiper.min.js',
			array(),
			'11.0.0',
			array( 'in_footer' => true )
		);
	}

	wp_enqueue_style( 'calendly', 'https://assets.calendly.com/assets/external/widget.css', array(), null );
	wp_enqueue_script(
		'calendly',
		'https://assets.calendly.com/assets/external/widget.js',
		array(),
		null,
		array( 'in_footer' => true )
	);

	$main_deps = array( 'alpine' );
	if ( wp_script_is( 'swiper', 'enqueued' ) ) {
		$main_deps[] = 'swiper';
	}

	wp_enqueue_script(
		'yum2-main',
		$theme_uri . '/assets/js/main.js',
		$main_deps,
		file_exists( $js_path ) ? filemtime( $js_path ) : YUM2_VERSION,
		array( 'in_footer' => true )
	);

	wp_localize_script(
		'yum2-main',
		'YUM2',
		array(
			'themeUrl'      => $theme_uri,
			'calendlyUrl'   => yum2_get_contact( 'calendly_url' ),
			'calendlyColor' => yum2_get_contact( 'calendly_color' ),
			'whatsapp'      => yum2_get_contact( 'whatsapp' ),
		)
	);

	wp_add_inline_script( 'yum2-main', yum2_calendly_tracking_js(), 'after' );
}
add_action( 'wp_enqueue_scripts', 'yum2_assets' );

/**
 * Inline listener that watches for Calendly booking completions and pushes a
 * generate_lead event into the dataLayer. GTM (container loaded inline in
 * header.php) forwards it to GA4, where it can be marked as a Key Event.
 *
 * Calendly posts a window message with e.data.event === 'calendly.event_scheduled'
 * when a booking is confirmed. The origin guard makes sure we only react to
 * messages from Calendly itself.
 *
 * @return string JS to be appended after the yum2-main script.
 */
function yum2_calendly_tracking_js() {
	return <<<JS
(function () {
	window.addEventListener('message', function (e) {
		if (!e || e.origin !== 'https://calendly.com') { return; }
		if (!e.data || e.data.event !== 'calendly.event_scheduled') { return; }
		window.dataLayer = window.dataLayer || [];
		window.dataLayer.push({
			event: 'generate_lead',
			method: 'calendly'
		});
	}, false);
})();
JS;
}

/**
 * Strip Gutenberg block-library CSS on the front-end. Classic theme; no core
 * block styles are used.
 */
function yum2_dequeue_block_library() {
	if ( is_admin() ) {
		return;
	}
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'yum2_dequeue_block_library', 100 );

/**
 * Disable the WP emoji pipeline in HTML, feeds, and emails.
 */
function yum2_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'yum2_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'yum2_disable_emojis_dns_prefetch', 10, 2 );
}
add_action( 'init', 'yum2_disable_emojis' );

function yum2_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	return array();
}

function yum2_disable_emojis_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/' );
		$urls          = array_diff( $urls, array( $emoji_svg_url ) );
	}
	return $urls;
}

/**
 * Warm the connection to Calendly so the popup widget opens fast.
 */
function yum2_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://assets.calendly.com',
			'crossorigin' => 'anonymous',
		);
		$urls[] = array(
			'href'        => 'https://calendly.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'yum2_resource_hints', 10, 2 );
