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
}
add_action( 'customize_register', 'yum2_customize_register' );
