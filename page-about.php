<?php
/**
 * Template Name: About Layout
 *
 * About page. Auto-applies to a Page with slug "about", and is also
 * selectable from the Page Attributes > Template dropdown for any other
 * page Sanya wants to lay out the same way.
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
	get_template_part( 'template-parts/about/hero' );
	get_template_part( 'template-parts/about/marquee' );
	get_template_part( 'template-parts/about/why' );
	get_template_part( 'template-parts/about/numbers' );
	get_template_part( 'template-parts/about/beliefs' );
	get_template_part( 'template-parts/about/how-i-work' );
	get_template_part( 'template-parts/about/training' );
	get_template_part( 'template-parts/about/pull-quote' );
	get_template_part( 'template-parts/about/closing-cta' );
	?>

</main>

<?php
get_footer();
