<?php
/**
 * Shared loop block for blog index, archives, and search.
 *
 * Renders the filter row, dynamic heading, posts grid (with newsletter
 * strip injected after the third card), pagination, and the empty state.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_newsletter = (bool) get_theme_mod( 'yum2_footer_show_newsletter', true );

if ( is_search() ) {
	$heading = sprintf(
		/* translators: %d: number of posts found */
		_n( '%d essay found', '%d essays found', (int) $GLOBALS['wp_query']->found_posts, 'youumatter2' ),
		(int) $GLOBALS['wp_query']->found_posts
	);
} elseif ( is_category() ) {
	$heading = sprintf(
		/* translators: %s: category name */
		__( 'Posts in %s', 'youumatter2' ),
		single_cat_title( '', false )
	);
} elseif ( is_tag() ) {
	$heading = sprintf(
		/* translators: %s: tag name */
		__( 'Tagged %s', 'youumatter2' ),
		single_tag_title( '', false )
	);
} elseif ( is_author() ) {
	$heading = sprintf(
		/* translators: %s: author display name */
		__( 'Essays by %s', 'youumatter2' ),
		get_the_author()
	);
} else {
	$heading = __( 'Recent posts', 'youumatter2' );
}
?>
<section class="relative bg-[#f8f3e9] px-5 md:px-8 pt-12 md:pt-16 pb-14 md:pb-20 border-t border-forest/10">
	<div class="max-w-6xl mx-auto">

		<div class="flex flex-col-reverse lg:flex-row lg:items-end lg:justify-between gap-4 lg:gap-8 pb-4 lg:pb-5 mb-8 md:mb-10 border-b border-forest/15">
			<?php get_template_part( 'template-parts/blog/filter-pills' ); ?>
			<?php get_template_part( 'template-parts/blog/search' ); ?>
		</div>

		<div class="flex items-baseline justify-between mb-6 md:mb-8 gap-4">
			<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(22px,2.4vw,28px);line-height:1.15;letter-spacing:-0.01em;font-weight:400;">
				<?php echo esc_html( $heading ); ?>
			</h2>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
				<?php
				$index = 0;
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/blog/card' );
					$index++;
					if ( 3 === $index && $show_newsletter ) {
						get_template_part( 'template-parts/blog/newsletter-inline' );
					}
				endwhile;
				?>
			</div>

			<?php get_template_part( 'template-parts/blog/pagination' ); ?>

		<?php else : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
				<?php get_template_part( 'template-parts/blog/empty' ); ?>
			</div>
		<?php endif; ?>

	</div>
</section>
