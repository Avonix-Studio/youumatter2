<?php
/**
 * Template Name: Contact Layout
 *
 * Contact page. Auto-applies to a Page with slug "contact", and is also
 * selectable from the Page Attributes > Template dropdown.
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
	get_template_part( 'template-parts/contact/hero' );
	get_template_part( 'template-parts/contact/methods' );
	get_template_part( 'template-parts/contact/map' );
	get_template_part( 'template-parts/contact/closing-cta' );
	?>
</main>

<?php
get_footer();
