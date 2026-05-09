<?php
/**
 * Blog post card. Caller has already called the_post(); this part reads
 * from the loop context.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cats        = get_the_category();
$primary_cat = ! empty( $cats ) ? $cats[0] : null;
?>
<article <?php post_class( 'group' ); ?>>
	<a
		href="<?php the_permalink(); ?>"
		class="flex flex-col bg-white border border-forest/15 rounded-[18px] overflow-hidden hover:border-forest/35 hover:shadow-[0_22px_44px_-18px_rgba(26,58,25,0.16)] transition-[border-color,box-shadow,transform] duration-500 hover:-translate-y-1 h-full"
	>
		<div class="relative aspect-[16/10] flex items-center justify-center overflow-hidden bg-sage-light">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php
				the_post_thumbnail(
					'yum2-blog-card',
					array(
						'class'   => 'absolute inset-0 w-full h-full object-cover',
						'loading' => 'lazy',
					)
				);
				?>
			<?php else : ?>
				<div aria-hidden class="absolute inset-0" style="background:radial-gradient(circle at 30% 20%, rgba(248,243,233,0.7) 0%, rgba(209,229,208,0) 65%), radial-gradient(circle at 70% 80%, rgba(192,122,90,0.12) 0%, rgba(209,229,208,0) 60%);"></div>
				<svg viewBox="0 0 64 64" class="relative size-12 text-forest/35" fill="currentColor" aria-hidden>
					<path d="M46 8C24 8 12 24 12 42c0 6 3 12 9 12 14 0 26-12 30-34 .6-2.4 1.8-6.9 3-12-3.6 0-6 0-8 0z" opacity="0.95"/>
					<path d="M22 46c4-10 12-18 22-22" stroke="#f2ede3" stroke-width="1.6" fill="none" opacity="0.7"/>
				</svg>
			<?php endif; ?>
			<?php if ( $primary_cat ) : ?>
				<span class="yum2-card-pill relative">
					<?php echo esc_html( $primary_cat->name ); ?>
				</span>
			<?php endif; ?>
		</div>
		<div class="flex-1 flex flex-col p-5 md:p-6">
			<h3 class="text-forest mb-2" style="font-family:'Newsreader',serif;font-size:clamp(19px,1.6vw,22px);line-height:1.2;letter-spacing:-0.01em;font-weight:500;">
				<?php the_title(); ?>
			</h3>
			<p class="text-forest/65 mb-5 flex-1" style="font-size:14.5px;line-height:1.55;">
				<?php echo esc_html( get_the_excerpt() ); ?>
			</p>
			<div class="flex items-center justify-between pt-4 border-t border-forest/10">
				<span class="text-forest/65" style="font-size:12px;">
					<?php
					echo esc_html(
						sprintf(
							/* translators: 1: post date, 2: reading time */
							__( '%1$s · %2$s', 'youumatter2' ),
							get_the_date( get_option( 'date_format' ) ),
							yum2_reading_time()
						)
					);
					?>
				</span>
				<span class="text-terracotta transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5">
					<?php echo yum2_icon( 'arrow-up-right', array( 'size' => 18, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			</div>
		</div>
	</a>
</article>
