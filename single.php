<?php
/**
 * Single post template. Composes hero, featured image, content + sidebar,
 * tags, mid-CTA (auto-injected via the_content filter), share row,
 * mobile author bio, newsletter card, related posts, comments, gentle
 * invitation, and the standard chrome.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<main id="primary" class="site-main">

		<?php get_template_part( 'template-parts/post/hero' ); ?>
		<?php get_template_part( 'template-parts/post/featured-image' ); ?>

		<section class="relative px-5 md:px-8 pt-10 md:pt-14 pb-12 md:pb-16 border-t border-forest/10 bg-white">
			<div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-[1fr_300px] xl:grid-cols-[1fr_320px] gap-10 lg:gap-14">

				<article <?php post_class( 'min-w-0 max-w-[680px] mx-auto lg:mx-0 w-full' ); ?>>
					<div class="lg:hidden mb-7">
						<?php get_template_part( 'template-parts/post/toc', null, array( 'variant' => 'mobile' ) ); ?>
					</div>

					<div class="prose prose-yum2 prose-lg max-w-none">
						<?php the_content(); ?>
					</div>

					<?php get_template_part( 'template-parts/post/tags' ); ?>
					<?php get_template_part( 'template-parts/post/share' ); ?>

					<div class="lg:hidden mt-10">
						<?php get_template_part( 'template-parts/post/author-bio' ); ?>
					</div>

					<div class="mt-8">
						<?php get_template_part( 'template-parts/post/newsletter-card' ); ?>
					</div>
				</article>

				<?php get_template_part( 'template-parts/post/sidebar' ); ?>

			</div>
		</section>

		<?php get_template_part( 'template-parts/post/related' ); ?>

		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>

		<?php get_template_part( 'template-parts/home/gentle-invitation' ); ?>

	</main>

	<?php
endwhile;

get_footer();
