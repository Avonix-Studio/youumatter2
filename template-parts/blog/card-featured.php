<?php
/**
 * Featured blog card. Two-column layout with sage-light gradient on the
 * left and post meta + title + excerpt + CTA on the right.
 *
 * Args:
 *   post_id int Required. Featured post ID (typically the most recent
 *               sticky post; falls back to the most recent post).
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args    = wp_parse_args( $args ?? array(), array( 'post_id' => 0 ) );
$post_id = (int) $args['post_id'];
if ( ! $post_id ) {
	return;
}

$post_obj = get_post( $post_id );
if ( ! $post_obj ) {
	return;
}

$cats        = get_the_category( $post_id );
$primary_cat = ! empty( $cats ) ? $cats[0] : null;
$thumb_url   = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'yum2-blog-featured' ) : '';
?>
<a
	href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"
	class="group relative grid grid-cols-1 md:grid-cols-[1.1fr_1fr] gap-0 bg-white border border-forest/15 rounded-[22px] overflow-hidden hover:border-forest/35 hover:shadow-[0_30px_60px_-28px_rgba(26,58,25,0.22)] transition-[border-color,box-shadow] duration-500"
>
	<div class="relative aspect-[16/10] md:aspect-auto md:min-h-[320px] flex items-end justify-start p-6 md:p-8 bg-sage-light overflow-hidden">
		<?php if ( $thumb_url ) : ?>
			<img src="<?php echo esc_url( $thumb_url ); ?>" alt="" class="absolute inset-0 w-full h-full object-cover" loading="eager" decoding="async">
		<?php else : ?>
			<div aria-hidden class="absolute inset-0" style="background:radial-gradient(circle at 30% 30%, rgba(248,243,233,0.6) 0%, rgba(209,229,208,0) 65%), radial-gradient(circle at 70% 80%, rgba(192,122,90,0.12) 0%, rgba(209,229,208,0) 60%);"></div>
		<?php endif; ?>
		<div aria-hidden class="absolute inset-0 pointer-events-none" style="background:radial-gradient(circle at 75% 20%, rgba(255,255,255,0.45) 0%, rgba(255,255,255,0) 60%);"></div>

		<span class="relative inline-flex items-center gap-1.5 bg-white/90 backdrop-blur border border-forest/15 text-terracotta rounded-full px-3 py-1.5 tracking-[0.12em] uppercase" style="font-size:11px;font-weight:700;">
			<?php echo yum2_icon( 'sparkles', array( 'size' => 12, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php esc_html_e( 'Featured', 'youumatter2' ); ?>
		</span>
	</div>
	<div class="p-6 md:p-8 flex flex-col justify-center">
		<p class="text-terracotta tracking-[0.14em] uppercase mb-3" style="font-size:11px;font-weight:700;">
			<?php
			echo esc_html(
				sprintf(
					/* translators: 1: category name, 2: read time */
					__( '%1$s · %2$s', 'youumatter2' ),
					$primary_cat ? $primary_cat->name : __( 'Essay', 'youumatter2' ),
					yum2_reading_time( $post_id )
				)
			);
			?>
		</p>
		<h3 class="text-forest mb-3" style="font-family:'Newsreader',serif;font-size:clamp(24px,2.6vw,34px);line-height:1.12;letter-spacing:-0.015em;font-weight:500;">
			<?php echo esc_html( get_the_title( $post_id ) ); ?>
		</h3>
		<p class="text-forest/65 mb-6" style="font-size:15.5px;line-height:1.6;">
			<?php
			$excerpt = get_the_excerpt( $post_id );
			echo esc_html( $excerpt );
			?>
		</p>
		<span class="inline-flex items-center gap-1.5 text-forest group-hover:text-forest/80 transition-colors" style="font-size:14px;font-weight:600;">
			<?php esc_html_e( 'Read the essay', 'youumatter2' ); ?>
			<span class="transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5">
				<?php echo yum2_icon( 'arrow-up-right', array( 'size' => 16, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
		</span>
	</div>
</a>
