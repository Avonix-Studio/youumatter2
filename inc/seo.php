<?php
/**
 * SEO meta: Open Graph, Twitter Card, and LocalBusiness JSON-LD.
 *
 * Bails when Yoast or Rank Math is active so we never double up.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Skip our SEO output when a dedicated SEO plugin is handling things.
 */
function yum2_seo_should_bail() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' );
}

/**
 * Resolve the OG / Twitter share image URL.
 *
 * Priority: post thumbnail (yum2-og-image size) -> Customizer default -> bundled placeholder.
 *
 * @return string
 */
function yum2_seo_get_share_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_url( get_post_thumbnail_id(), 'yum2-og-image' );
		if ( $src ) {
			return $src;
		}
	}

	$default_id = (int) get_theme_mod( 'yum2_default_og_image', 0 );
	if ( $default_id ) {
		$src = wp_get_attachment_image_url( $default_id, 'yum2-og-image' );
		if ( $src ) {
			return $src;
		}
	}

	return get_template_directory_uri() . '/assets/images/og-default.jpg';
}

/**
 * Document description for the current view. Falls back to the site tagline.
 *
 * @return string
 */
function yum2_seo_get_description() {
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$excerpt = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_strip_all_tags( $post->post_content );
			$excerpt = trim( preg_replace( '/\s+/', ' ', (string) $excerpt ) );
			if ( '' !== $excerpt ) {
				return wp_trim_words( $excerpt, 30, '…' );
			}
		}
	}

	$tagline = get_bloginfo( 'description', 'display' );
	return $tagline ? $tagline : '';
}

/**
 * Output Open Graph and Twitter Card meta tags.
 */
function yum2_seo_meta_tags() {
	if ( yum2_seo_should_bail() ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = yum2_seo_get_description();
	$url         = is_singular() ? get_permalink() : home_url( add_query_arg( null, null ) );
	$site_name   = get_bloginfo( 'name' );
	$image       = yum2_seo_get_share_image();
	$type        = is_singular( 'post' ) ? 'article' : 'website';

	echo "\n<!-- youumatter2 SEO -->\n";

	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( 'en_IN' ) );
	printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $type ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( '' !== $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	}
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( $site_name ) );
	if ( '' !== $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		printf( '<meta property="og:image:width" content="%d">' . "\n", 1200 );
		printf( '<meta property="og:image:height" content="%d">' . "\n", 630 );
	}

	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( '' !== $description ) {
		printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );
	}
	if ( '' !== $image ) {
		printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
	}
}
add_action( 'wp_head', 'yum2_seo_meta_tags', 5 );

/**
 * Convert a "Mon to Sat · 10:00 AM to 7:00 PM" style string into
 * schema.org openingHoursSpecification entries. Best-effort parse;
 * returns the default Mon-Sat 10:00-19:00 spec if parsing fails.
 *
 * @param string $hours Free-form hours string from config.
 * @return array
 */
function yum2_seo_parse_hours( $hours ) {
	$default = array(
		array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ),
			'opens'     => '10:00',
			'closes'    => '19:00',
		),
	);

	if ( ! is_string( $hours ) || '' === trim( $hours ) ) {
		return $default;
	}

	$day_map = array(
		'mon' => 'Monday',
		'tue' => 'Tuesday',
		'wed' => 'Wednesday',
		'thu' => 'Thursday',
		'fri' => 'Friday',
		'sat' => 'Saturday',
		'sun' => 'Sunday',
	);
	$order   = array_values( $day_map );

	if ( ! preg_match( '/([a-z]{3})[a-z]*\s*(?:to|-)\s*([a-z]{3})[a-z]*/i', $hours, $dm ) ) {
		return $default;
	}
	$start = strtolower( substr( $dm[1], 0, 3 ) );
	$end   = strtolower( substr( $dm[2], 0, 3 ) );
	if ( ! isset( $day_map[ $start ], $day_map[ $end ] ) ) {
		return $default;
	}
	$start_i = array_search( $day_map[ $start ], $order, true );
	$end_i   = array_search( $day_map[ $end ], $order, true );
	if ( false === $start_i || false === $end_i || $end_i < $start_i ) {
		return $default;
	}
	$days = array_slice( $order, $start_i, $end_i - $start_i + 1 );

	if ( ! preg_match( '/(\d{1,2})(?::(\d{2}))?\s*(am|pm)?\s*(?:to|-)\s*(\d{1,2})(?::(\d{2}))?\s*(am|pm)?/i', $hours, $tm ) ) {
		return $default;
	}
	$to_24 = static function ( $h, $m, $ap ) {
		$h = (int) $h;
		$m = $m ? (int) $m : 0;
		$ap = strtolower( (string) $ap );
		if ( 'pm' === $ap && $h < 12 ) {
			$h += 12;
		}
		if ( 'am' === $ap && 12 === $h ) {
			$h = 0;
		}
		return sprintf( '%02d:%02d', $h, $m );
	};
	$opens  = $to_24( $tm[1], $tm[2], $tm[3] );
	$closes = $to_24( $tm[4], $tm[5], $tm[6] );

	return array(
		array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => $days,
			'opens'     => $opens,
			'closes'    => $closes,
		),
	);
}

/**
 * Output LocalBusiness JSON-LD on every page.
 */
function yum2_seo_jsonld() {
	if ( yum2_seo_should_bail() ) {
		return;
	}

	$same_as = array_filter(
		array(
			yum2_get_contact( 'instagram' ),
			yum2_get_contact( 'linkedin' ),
			yum2_get_contact( 'youtube' ),
			yum2_get_contact( 'twitter' ),
			yum2_get_contact( 'facebook' ),
		)
	);

	$data = array(
		'@context'                => 'https://schema.org',
		'@type'                   => array( 'LocalBusiness', 'MedicalBusiness' ),
		'name'                    => get_bloginfo( 'name' ),
		'url'                     => home_url( '/' ),
		'image'                   => yum2_seo_get_share_image(),
		'telephone'               => yum2_get_contact( 'phone' ),
		'email'                   => yum2_get_contact( 'email' ),
		'address'                 => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => yum2_get_contact( 'clinic_address' ),
			'addressLocality' => 'Pitampura',
			'addressRegion'   => 'Delhi',
			'addressCountry'  => 'IN',
		),
		'openingHoursSpecification' => yum2_seo_parse_hours( yum2_get_contact( 'clinic_hours' ) ),
		'priceRange'              => '₹₹',
	);

	if ( ! empty( $same_as ) ) {
		$data['sameAs'] = array_values( $same_as );
	}

	$json = wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( false === $json ) {
		return;
	}

	echo "\n" . '<script type="application/ld+json">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'yum2_seo_jsonld', 6 );
