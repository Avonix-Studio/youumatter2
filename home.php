<?php
/**
 * Blog index. Used when Settings > Reading > Posts page is set, OR as
 * the front page when no static homepage is configured.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$show_featured = is_home() && ! is_paged() && ! is_search();
$featured_id   = 0;
if ( $show_featured ) {
	$sticky = (array) get_option( 'sticky_posts', array() );
	if ( ! empty( $sticky ) ) {
		$featured_id = (int) $sticky[0];
	} else {
		$most_recent = get_posts(
			array(
				'numberposts' => 1,
				'post_status' => 'publish',
				'fields'      => 'ids',
			)
		);
		$featured_id = ! empty( $most_recent ) ? (int) $most_recent[0] : 0;
	}

}
?>

<main id="primary" class="site-main">

	<?php get_template_part( 'template-parts/blog/hero' ); ?>

	<?php if ( $show_featured && $featured_id ) : ?>
		<section class="relative bg-cream px-5 md:px-8 pb-10 md:pb-14">
			<div class="max-w-6xl mx-auto">
				<?php get_template_part( 'template-parts/blog/card-featured', null, array( 'post_id' => $featured_id ) ); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// If the featured ID is set, drop it from the current loop's posts in-memory.
	if ( $show_featured && $featured_id ) {
		global $wp_query;
		if ( ! empty( $wp_query->posts ) ) {
			$wp_query->posts = array_values(
				array_filter(
					$wp_query->posts,
					function ( $p ) use ( $featured_id ) {
						return (int) $p->ID !== $featured_id;
					}
				)
			);
			$wp_query->post_count = count( $wp_query->posts );
		}
	}

	get_template_part( 'template-parts/blog/loop' );

	get_template_part( 'template-parts/home/gentle-invitation' );
	?>

</main>

<?php
get_footer();
