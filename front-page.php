<?php
/**
 * Front page. Composes home sections in Home.tsx order.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/you-might-be-feeling' );
	get_template_part( 'template-parts/home/about' );
	get_template_part( 'template-parts/home/how-it-works' );
	?>
</main>

<?php
get_footer();
