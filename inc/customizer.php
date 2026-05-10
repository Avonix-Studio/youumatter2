<?php
/**
 * Customizer settings.
 *
 * Every setting saves as a theme_mod named yum2_<key>. The helper
 * yum2_get_contact( $key ) reads these automatically and falls back to the
 * defaults in inc/config.php when a field is empty.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function yum2_customize_register( $wp_customize ) {
	$defaults = yum2_contact_defaults();

	$wp_customize->add_section(
		'yum2_contact',
		array(
			'title'       => __( 'Contact & Social', 'youumatter2' ),
			'priority'    => 30,
			'description' => __( 'Phone, email, WhatsApp, Calendly, and social links shown across the site. Leave a field blank to use the default.', 'youumatter2' ),
		)
	);

	$fields = array(
		'phone' => array(
			'label'    => __( 'Phone (E.164)', 'youumatter2' ),
			'desc'     => __( 'Used for tel: links. Example: +919953855858', 'youumatter2' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'phone_display' => array(
			'label'    => __( 'Phone (display format)', 'youumatter2' ),
			'desc'     => __( 'Pretty version shown in copy. Example: +91 99538 55858', 'youumatter2' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'whatsapp' => array(
			'label'    => __( 'WhatsApp number (digits only)', 'youumatter2' ),
			'desc'     => __( 'Used for wa.me links. No plus sign or spaces.', 'youumatter2' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'email' => array(
			'label'    => __( 'Contact email', 'youumatter2' ),
			'type'     => 'email',
			'sanitize' => 'sanitize_email',
		),
		'calendly_url' => array(
			'label'    => __( 'Calendly event URL', 'youumatter2' ),
			'desc'     => __( 'Full URL to the Calendly event used by the Book buttons.', 'youumatter2' ),
			'type'     => 'url',
			'sanitize' => 'esc_url_raw',
		),
		'instagram' => array(
			'label'    => __( 'Instagram URL', 'youumatter2' ),
			'type'     => 'url',
			'sanitize' => 'esc_url_raw',
		),
		'instagram_handle' => array(
			'label'    => __( 'Instagram handle', 'youumatter2' ),
			'desc'     => __( 'Shown as text. Include the @.', 'youumatter2' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'linkedin' => array(
			'label'    => __( 'LinkedIn URL', 'youumatter2' ),
			'type'     => 'url',
			'sanitize' => 'esc_url_raw',
		),
		'youtube' => array(
			'label'    => __( 'YouTube URL', 'youumatter2' ),
			'type'     => 'url',
			'sanitize' => 'esc_url_raw',
		),
		'facebook' => array(
			'label'    => __( 'Facebook URL', 'youumatter2' ),
			'type'     => 'url',
			'sanitize' => 'esc_url_raw',
		),
		'twitter' => array(
			'label'    => __( 'X / Twitter URL', 'youumatter2' ),
			'type'     => 'url',
			'sanitize' => 'esc_url_raw',
		),
		'clinic_address' => array(
			'label'    => __( 'Clinic address', 'youumatter2' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'clinic_hours' => array(
			'label'    => __( 'Clinic hours', 'youumatter2' ),
			'desc'     => __( 'Example: Mon to Sat · 10:00 AM to 7:00 PM', 'youumatter2' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
	);

	foreach ( $fields as $key => $args ) {
		$default = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		$wp_customize->add_setting(
			'yum2_' . $key,
			array(
				'default'           => $default,
				'sanitize_callback' => $args['sanitize'],
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'yum2_' . $key,
			array(
				'label'       => $args['label'],
				'description' => isset( $args['desc'] ) ? $args['desc'] : '',
				'section'     => 'yum2_contact',
				'type'        => $args['type'],
			)
		);
	}

	$wp_customize->add_setting(
		'yum2_accepting_clients',
		array(
			'default'           => $defaults['accepting_clients'],
			'sanitize_callback' => 'rest_sanitize_boolean',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'yum2_accepting_clients',
		array(
			'label'       => __( 'Currently accepting new clients', 'youumatter2' ),
			'description' => __( 'Controls the "Accepting new clients" pill on the home hero.', 'youumatter2' ),
			'section'     => 'yum2_contact',
			'type'        => 'checkbox',
		)
	);

	/* ------------------------------------------------------------------
	 * Header & Footer
	 * Toggles for chrome that wraps every page. Defaults match the live
	 * site so flipping any value off is a deliberate hide, not a surprise.
	 * ----------------------------------------------------------------*/
	$wp_customize->add_section(
		'yum2_chrome',
		array(
			'title'       => __( 'Header & Footer', 'youumatter2' ),
			'priority'    => 35,
			'description' => __( 'Show/hide the Book button, status pill, gentle-invitation home section, and newsletter strip. Default: everything visible.', 'youumatter2' ),
		)
	);

	$chrome_defaults = array(
		'header_show_book_cta'        => true,
		'header_show_status_pill'     => true,
		'footer_tagline'              => __( 'A quiet space for therapy, reflection, and steady growth.', 'youumatter2' ),
		'footer_copyright'            => __( '© %year% youumatter2. Sanya Oberoi. All rights reserved.', 'youumatter2' ),
		'footer_show_newsletter'      => true,
		'home_show_gentle_invitation' => true,
	);

	$chrome_fields = array(
		'header_show_book_cta' => array(
			'label'    => __( 'Show Book a Session button in header', 'youumatter2' ),
			'type'     => 'checkbox',
			'sanitize' => 'rest_sanitize_boolean',
		),
		'header_show_status_pill' => array(
			'label'    => __( 'Show "Accepting new clients" pill in header', 'youumatter2' ),
			'desc'     => __( 'Also requires "Currently accepting new clients" above to be on.', 'youumatter2' ),
			'type'     => 'checkbox',
			'sanitize' => 'rest_sanitize_boolean',
		),
		'footer_tagline' => array(
			'label'    => __( 'Footer tagline', 'youumatter2' ),
			'desc'     => __( 'Small line under the wordmark in the footer.', 'youumatter2' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'footer_copyright' => array(
			'label'    => __( 'Footer copyright line', 'youumatter2' ),
			'desc'     => __( 'Use %year% to insert the current year automatically. HTML is stripped.', 'youumatter2' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'footer_show_newsletter' => array(
			'label'    => __( 'Show newsletter strip in footer', 'youumatter2' ),
			'type'     => 'checkbox',
			'sanitize' => 'rest_sanitize_boolean',
		),
		'home_show_gentle_invitation' => array(
			'label'    => __( 'Show gentle-invitation section on home page', 'youumatter2' ),
			'desc'     => __( 'The "Take your time" CTA + practical-details card. Home page only.', 'youumatter2' ),
			'type'     => 'checkbox',
			'sanitize' => 'rest_sanitize_boolean',
		),
	);

	foreach ( $chrome_fields as $key => $args ) {
		$wp_customize->add_setting(
			'yum2_' . $key,
			array(
				'default'           => $chrome_defaults[ $key ],
				'sanitize_callback' => $args['sanitize'],
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'yum2_' . $key,
			array(
				'label'       => $args['label'],
				'description' => isset( $args['desc'] ) ? $args['desc'] : '',
				'section'     => 'yum2_chrome',
				'type'        => $args['type'],
			)
		);
	}

	/* ------------------------------------------------------------------
	 * Homepage Sections
	 * Toggles for the lower homepage sections (everything below
	 * how-it-works). Defaults all visible.
	 * ----------------------------------------------------------------*/
	$wp_customize->add_section(
		'yum2_home_sections',
		array(
			'title'       => __( 'Homepage Sections', 'youumatter2' ),
			'priority'    => 36,
			'description' => __( 'Show or hide each section on the home page below "How it works". Default: all visible.', 'youumatter2' ),
		)
	);

	$home_defaults = array(
		'home_show_inside_session' => true,
		'home_show_testimonials'   => true,
		'home_show_from_blog'      => true,
		'home_show_faq'            => true,
		'home_show_instagram'      => true,
	);

	$home_fields = array(
		'home_show_inside_session' => __( 'Show "Inside a session" section', 'youumatter2' ),
		'home_show_testimonials'   => __( 'Show testimonials carousel', 'youumatter2' ),
		'home_show_from_blog'      => __( 'Show "From the blog" section', 'youumatter2' ),
		'home_show_faq'            => __( 'Show FAQ section', 'youumatter2' ),
		'home_show_instagram'      => __( 'Show Instagram feed section', 'youumatter2' ),
	);

	foreach ( $home_fields as $key => $label ) {
		$wp_customize->add_setting(
			'yum2_' . $key,
			array(
				'default'           => $home_defaults[ $key ],
				'sanitize_callback' => 'rest_sanitize_boolean',
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'yum2_' . $key,
			array(
				'label'   => $label,
				'section' => 'yum2_home_sections',
				'type'    => 'checkbox',
			)
		);
	}
}
add_action( 'customize_register', 'yum2_customize_register' );
