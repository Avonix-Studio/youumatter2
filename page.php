<?php
/**
 * Generic page template.
 *
 * Used for any WP page without a more specific page-{slug}.php template.
 * Privacy Policy, Terms of Service, and any future custom pages render
 * through here.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$crumbs = yum2_breadcrumb();
	?>

	<main id="primary" class="site-main">

		<section class="bg-cream px-5 md:px-8 pt-12 md:pt-20 pb-8 md:pb-10 border-b border-forest/10">
			<div class="max-w-3xl mx-auto">
				<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'youumatter2' ); ?>" class="flex items-center flex-wrap gap-1.5 text-forest/70 mb-5" style="font-size:12.5px;">
					<?php $count = count( $crumbs ); foreach ( $crumbs as $i => $c ) : ?>
						<?php if ( '' !== $c['url'] ) : ?>
							<a href="<?php echo esc_url( $c['url'] ); ?>" class="hover:text-forest transition-colors">
								<?php echo esc_html( $c['label'] ); ?>
							</a>
						<?php else : ?>
							<span class="text-forest truncate max-w-[260px]" title="<?php echo esc_attr( $c['label'] ); ?>">
								<?php echo esc_html( $c['label'] ); ?>
							</span>
						<?php endif; ?>
						<?php if ( $i < $count - 1 ) : ?>
							<span aria-hidden class="text-forest/40">
								<?php echo yum2_icon( 'chevron-right', array( 'size' => 12, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						<?php endif; ?>
					<?php endforeach; ?>
				</nav>

				<h1 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(34px,5vw,52px);line-height:1.05;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
					<?php the_title(); ?>
				</h1>
			</div>
		</section>

		<section class="bg-white px-5 md:px-8 py-12 md:py-16">
			<article <?php post_class( 'prose prose-yum2 prose-lg max-w-3xl mx-auto' ); ?>>
				<?php the_content(); ?>
			</article>
		</section>

	</main>

	<?php
endwhile;

get_footer();
