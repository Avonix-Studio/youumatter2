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
 * Document description for the current view.
 *
 * Order:
 *   1. Template-specific overrides (About / Contact / FAQ).
 *   2. Single-post excerpt or first 30 words of content.
 *   3. Site tagline.
 *
 * @return string
 */
function yum2_seo_get_description() {
	$override = yum2_seo_template_description();
	if ( '' !== $override ) {
		return $override;
	}

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
 * Template-specific meta description override.
 *
 * Returns '' when the current view doesn't match a known template, so
 * yum2_seo_get_description() can fall through to the post excerpt /
 * tagline path.
 *
 * Slug-matched: about, contact, faq.
 * Also matches Page Templates (About Layout / Contact Layout / FAQ Layout).
 *
 * @return string
 */
function yum2_seo_template_description() {
	if ( is_singular( 'page' ) ) {
		$post = get_queried_object();
		if ( ! $post instanceof WP_Post ) {
			return '';
		}
		$slug     = $post->post_name;
		$template = (string) get_page_template_slug( $post );

		if ( 'about' === $slug || 'page-about.php' === $template ) {
			return __( 'Sanya Oberoi is a Counselling Psychologist (M.A. Clinical Psychology) in Pitampura, New Delhi. Warm, integrative therapy for relationships, anxiety, and emotional well-being. Online and in-person.', 'youumatter2' );
		}
		if ( 'contact' === $slug || 'page-contact.php' === $template ) {
			return __( 'Get in touch to book a therapy session with Sanya Oberoi. Online and in-person at the Pitampura clinic in New Delhi. Reply within 24 hours on weekdays.', 'youumatter2' );
		}
		if ( 'faq' === $slug || 'page-faq.php' === $template ) {
			return __( 'Answers to the questions people most often ask before starting therapy with Sanya Oberoi. Sessions, fees, confidentiality, and more.', 'youumatter2' );
		}
	}

	if ( is_home() ) {
		$tagline = get_bloginfo( 'description', 'display' );
		if ( $tagline ) {
			return (string) $tagline;
		}
		return __( 'The Journal: honest writing on therapy, anxiety, relationships, and what it actually means to feel okay again.', 'youumatter2' );
	}

	return '';
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

/**
 * Helper: emit a single JSON-LD `<script>` block from an array, using
 * the same encode flags as the LocalBusiness output.
 *
 * @param array $data Schema.org-shaped array.
 */
function yum2_seo_emit_jsonld( $data ) {
	$json = wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( false === $json ) {
		return;
	}
	echo "\n" . '<script type="application/ld+json">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Match the current view to one of our page templates (slug or
 * Template Name). Returns 'about' | 'contact' | 'faq' | ''.
 *
 * @return string
 */
function yum2_seo_current_template() {
	if ( ! is_singular( 'page' ) ) {
		return '';
	}
	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return '';
	}
	$slug     = $post->post_name;
	$template = (string) get_page_template_slug( $post );

	if ( 'about' === $slug || 'page-about.php' === $template ) {
		return 'about';
	}
	if ( 'contact' === $slug || 'page-contact.php' === $template ) {
		return 'contact';
	}
	if ( 'faq' === $slug || 'page-faq.php' === $template ) {
		return 'faq';
	}
	return '';
}

/**
 * Person schema for Sanya, emitted on the About page only.
 *
 * Static values mirror template-parts/about/training.php so search
 * engines see the same credentials shown on the page.
 */
function yum2_seo_person_jsonld() {
	if ( yum2_seo_should_bail() || 'about' !== yum2_seo_current_template() ) {
		return;
	}

	$site_url = home_url( '/' );
	$same_as  = array_filter(
		array(
			yum2_get_contact( 'instagram' ),
			yum2_get_contact( 'linkedin' ),
			yum2_get_contact( 'youtube' ),
			yum2_get_contact( 'twitter' ),
			yum2_get_contact( 'facebook' ),
		)
	);

	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Person',
		'name'        => __( 'Sanya Oberoi', 'youumatter2' ),
		'jobTitle'    => __( 'Counselling Psychologist', 'youumatter2' ),
		'description' => yum2_seo_template_description(),
		'image'       => yum2_seo_get_share_image(),
		'url'         => get_permalink(),
		'telephone'   => yum2_get_contact( 'phone' ),
		'email'       => yum2_get_contact( 'email' ),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'addressLocality' => 'Pitampura',
			'addressRegion'   => 'Delhi',
			'addressCountry'  => 'IN',
		),
		'alumniOf'    => array(
			array(
				'@type' => 'CollegeOrUniversity',
				'name'  => 'Amity University',
			),
			array(
				'@type' => 'CollegeOrUniversity',
				'name'  => 'University of Delhi',
			),
		),
		'knowsAbout'  => array(
			'Counselling Psychology',
			'Cognitive Behavioural Therapy',
			'Narrative Therapy',
			'Mindfulness-Based Therapy',
			'Emotion-Focused Therapy',
			'Anxiety',
			'Relationships',
		),
		'worksFor'    => array(
			'@type' => 'LocalBusiness',
			'name'  => get_bloginfo( 'name' ),
			'url'   => $site_url,
		),
	);

	if ( ! empty( $same_as ) ) {
		$data['sameAs'] = array_values( $same_as );
	}

	yum2_seo_emit_jsonld( $data );
}
add_action( 'wp_head', 'yum2_seo_person_jsonld', 7 );

/**
 * BlogPosting schema on single posts.
 */
function yum2_seo_article_jsonld() {
	if ( yum2_seo_should_bail() || ! is_singular( 'post' ) ) {
		return;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return;
	}

	$cats     = get_the_category( $post->ID );
	$primary  = ! empty( $cats ) ? $cats[0]->name : '';
	$tags     = get_the_tags( $post->ID );
	$keywords = array();
	if ( $tags && ! is_wp_error( $tags ) ) {
		foreach ( $tags as $t ) {
			$keywords[] = $t->name;
		}
	}

	$author_id   = (int) $post->post_author;
	$author_name = $author_id ? get_the_author_meta( 'display_name', $author_id ) : __( 'Sanya Oberoi', 'youumatter2' );

	$data = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'BlogPosting',
		'headline'         => get_the_title( $post ),
		'description'      => yum2_seo_get_description(),
		'image'            => yum2_seo_get_share_image(),
		'datePublished'    => mysql2date( 'c', $post->post_date_gmt, false ),
		'dateModified'     => mysql2date( 'c', $post->post_modified_gmt, false ),
		'author'           => array(
			'@type' => 'Person',
			'name'  => $author_name,
			'url'   => home_url( '/about/' ),
		),
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url( '/' ),
		),
		'mainEntityOfPage' => array(
			'@type' => 'WebPage',
			'@id'   => get_permalink( $post ),
		),
		'inLanguage'       => 'en-IN',
	);

	if ( '' !== $primary ) {
		$data['articleSection'] = $primary;
	}
	if ( ! empty( $keywords ) ) {
		$data['keywords'] = implode( ', ', $keywords );
	}

	yum2_seo_emit_jsonld( $data );
}
add_action( 'wp_head', 'yum2_seo_article_jsonld', 7 );

/**
 * FAQPage schema on the FAQ page. Reuses yum2_faq_groups() from
 * inc/template-functions.php so the rendered DOM and the schema
 * never drift apart.
 */
function yum2_seo_faq_jsonld() {
	if ( yum2_seo_should_bail() || 'faq' !== yum2_seo_current_template() ) {
		return;
	}
	if ( ! function_exists( 'yum2_faq_groups' ) ) {
		return;
	}

	$entities = array();
	foreach ( yum2_faq_groups() as $group ) {
		if ( empty( $group['items'] ) ) {
			continue;
		}
		foreach ( $group['items'] as $item ) {
			if ( empty( $item['q'] ) || empty( $item['a'] ) ) {
				continue;
			}
			$entities[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $item['q'] ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $item['a'] ),
				),
			);
		}
	}

	if ( empty( $entities ) ) {
		return;
	}

	yum2_seo_emit_jsonld(
		array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => $entities,
		)
	);
}
add_action( 'wp_head', 'yum2_seo_faq_jsonld', 7 );
