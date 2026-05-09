<?php
/**
 * Related posts band. 3 cards in the same primary category, falling back
 * to the most recent posts when the category has fewer than 3 siblings.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id     = get_the_ID();
$cats        = get_the_category( $post_id );
$primary_cat = ! empty( $cats ) ? $cats[0] : null;

$blog_url = get_permalink( (int) get_option( 'page_for_posts' ) );
if ( ! $blog_url ) {
	$blog_url = home_url( '/' );
}

$related_q = null;
if ( $primary_cat ) {
	$related_q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'post__not_in'        => array( $post_id ),
			'category__in'        => array( $primary_cat->term_id ),
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

if ( ! $related_q || ! $related_q->have_posts() ) {
	$related_q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'post__not_in'        => array( $post_id ),
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

if ( ! $related_q->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="relative bg-cream px-5 md:px-8 pt-12 md:pt-16 pb-16 md:pb-20 border-t border-forest/10">
	<div class="max-w-6xl mx-auto">
		<div class="flex items-baseline justify-between gap-4 mb-6 md:mb-8">
			<div>
				<p class="text-terracotta tracking-[2px] uppercase mb-2" style="font-size:11px;font-weight:600;">
					<?php esc_html_e( 'Keep reading', 'youumatter2' ); ?>
				</p>
				<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(24px,3vw,36px);line-height:1.1;letter-spacing:-0.01em;font-weight:400;">
					<?php esc_html_e( 'More on', 'youumatter2' ); ?>
					<em class="italic" style="color:#c07a5a;font-weight:400;"><?php echo esc_html( $primary_cat ? strtolower( $primary_cat->name ) . '.' : __( 'the journal.', 'youumatter2' ) ); ?></em>
				</h2>
			</div>
			<a href="<?php echo esc_url( $blog_url ); ?>" class="hidden md:inline-flex items-center gap-1.5 text-forest hover:text-forest/80" style="font-size:14px;font-weight:600;">
				<?php esc_html_e( 'All posts', 'youumatter2' ); ?>
				<?php echo yum2_icon( 'arrow-up-right', array( 'size' => 15, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-6">
			<?php while ( $related_q->have_posts() ) : $related_q->the_post(); ?>
				<?php get_template_part( 'template-parts/blog/card' ); ?>
			<?php endwhile; ?>
		</div>

		<div class="mt-10 md:mt-12 flex items-center justify-between gap-3">
			<a href="<?php echo esc_url( $blog_url ); ?>" class="inline-flex items-center gap-2 text-forest hover:text-forest/80" style="font-size:14px;font-weight:600;">
				<?php echo yum2_icon( 'arrow-left', array( 'size' => 15, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php esc_html_e( 'Back to all posts', 'youumatter2' ); ?>
			</a>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();
