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

// Load .env at the theme root (API keys etc.). Gitignored; on production
// either upload the .env file or define the same constants in wp-config.php.
require get_template_directory() . '/inc/env.php';

require get_template_directory() . '/inc/config.php';
require get_template_directory() . '/inc/content.php';
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/faq.php';
require get_template_directory() . '/inc/testimonials.php';
if ( is_admin() ) {
	require get_template_directory() . '/inc/admin-newsletter.php';
}
require get_template_directory() . '/inc/security.php';
require get_template_directory() . '/inc/seo.php';
