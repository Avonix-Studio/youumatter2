<?php
/**
 * youumatter2 functions and definitions.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'YUM2_VERSION' ) ) {
	define( 'YUM2_VERSION', '1.0.1' );
}

// Local secrets (API keys). Gitignored; may be absent on a fresh checkout or
// when secrets are defined in wp-config.php instead. Load before everything.
$yum2_secrets = get_template_directory() . '/inc/secrets.php';
if ( file_exists( $yum2_secrets ) ) {
	require $yum2_secrets;
}

require get_template_directory() . '/inc/config.php';
require get_template_directory() . '/inc/content.php';
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/faq.php';
require get_template_directory() . '/inc/security.php';
require get_template_directory() . '/inc/seo.php';
