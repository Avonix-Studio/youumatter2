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

require get_template_directory() . '/inc/config.php';
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/security.php';
require get_template_directory() . '/inc/seo.php';
require get_template_directory() . '/inc/blocks.php';
