<?php
/**
 * Template Name: FAQ Layout
 *
 * FAQ page. Auto-applies to a Page with slug "faq", and is also
 * selectable from the Page Attributes > Template dropdown.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$groups = yum2_faq_groups();
$first  = ! empty( $groups ) ? (string) $groups[0]['id'] : '';
?>

<main id="primary" class="site-main" x-data="yum2FAQ('<?php echo esc_js( $first ); ?>')">

	<?php get_template_part( 'template-parts/faq/hero' ); ?>

	<section class="bg-cream px-5 md:px-8 py-14 md:py-20">
		<div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-10 lg:gap-14">

			<?php
			get_template_part(
				'template-parts/faq/sidebar-jumplinks',
				null,
				array( 'groups' => $groups )
			);
			?>

			<div class="min-w-0">
				<?php foreach ( $groups as $group ) : ?>
					<?php get_template_part( 'template-parts/faq/group', null, array( 'group' => $group ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/faq/closing-cta' ); ?>

</main>

<?php
get_footer();
