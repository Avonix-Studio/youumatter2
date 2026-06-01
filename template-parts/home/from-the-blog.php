<?php
/**
 * Home: 3 most recent posts. Hides cleanly when no posts published.
 *
 * Reuses template-parts/blog/card.php so styling stays in one place.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! get_theme_mod( 'yum2_home_show_from_blog', true ) ) {
	return;
}

$q = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post_status'         => 'publish',
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
	)
);

if ( ! $q->have_posts() ) {
	wp_reset_postdata();
	return;
}

/* Copy lives in inc/content.php under 'from_blog'. */
$c = yum2_content( 'from_blog' );

$blog_url = get_permalink( (int) get_option( 'page_for_posts' ) );
if ( ! $blog_url ) {
	$blog_url = home_url( '/' );
}
?>
<section class="relative bg-cream px-5 md:px-8 pt-14 md:pt-20 pb-14 md:pb-20 overflow-hidden">
	<div class="relative max-w-6xl mx-auto">
		<div class="grid grid-cols-1 md:grid-cols-[1.2fr_1fr] gap-5 md:gap-14 items-end mb-10 md:mb-12">
			<div>
				<p class="text-terracotta tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;">
					<?php echo esc_html( $c['label'] ); ?>
				</p>
				<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(30px,4.8vw,52px);line-height:1.08;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
					<?php echo esc_html( $c['heading'] ); ?>
					<em class="italic" style="color:#c07a5a;font-weight:400;"><?php echo esc_html( $c['heading_em'] ); ?></em>
				</h2>
			</div>
			<p class="italic text-[#3d4f3e]" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;">
				<?php echo esc_html( $c['description'] ); ?>
			</p>
		</div>

		<div data-yum2-h-scroll class="flex md:grid md:grid-cols-3 gap-4 md:gap-6 overflow-x-auto md:overflow-visible snap-x snap-mandatory pr-8 md:pr-0 touch-pan-x md:touch-auto" style="scrollbar-width:none;">
			<?php while ( $q->have_posts() ) : $q->the_post(); ?>
				<div class="shrink-0 snap-start w-[85%] md:w-auto yum2-reveal">
					<?php get_template_part( 'template-parts/blog/card' ); ?>
				</div>
			<?php endwhile; ?>
		</div>

		<div class="flex items-center justify-center mt-10 md:mt-12">
			<a href="<?php echo esc_url( $blog_url ); ?>" class="group inline-flex items-center gap-2 text-forest hover:text-forest/80 transition-colors" style="font-size:15px;font-weight:600;">
				<?php echo esc_html( $c['view_all'] ); ?>
				<span class="transition-transform group-hover:translate-x-1" aria-hidden>&rarr;</span>
			</a>
		</div>
	</div>
</section>
<?php wp_reset_postdata(); ?>
