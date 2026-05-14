<?php
/**
 * Home: client testimonials carousel.
 *
 * Native CSS scroll-snap (no Swiper), Alpine drives dot/arrow controls
 * via the yum2TestimonialsCarousel component registered in main.js.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! get_theme_mod( 'yum2_home_show_testimonials', true ) ) {
	return;
}

$testimonials = array(
	array(
		'quote'       => __( 'I came in thinking something was wrong with me. I left understanding that I had been coping, not broken. Sanya made the difference feel huge.', 'youumatter2' ),
		'attribution' => __( 'A., 29', 'youumatter2' ),
		'context'     => __( 'Anxiety · 6 months in', 'youumatter2' ),
	),
	array(
		'quote'       => __( 'She never rushed me. The first real moment came in session five, and she was there, ready, like she had been expecting it.', 'youumatter2' ),
		'attribution' => __( 'R., 34', 'youumatter2' ),
		'context'     => __( 'Relationships', 'youumatter2' ),
	),
	array(
		'quote'       => __( "I had tried therapy before and walked away. This time felt different. Warm, specific, and honest in a way I didn't know therapy could be.", 'youumatter2' ),
		'attribution' => __( 'M., 41', 'youumatter2' ),
		'context'     => __( 'Self-esteem', 'youumatter2' ),
	),
	array(
		'quote'       => __( 'The space she holds is the actual work. I started saying things I did not know I thought, and she helped me stay with them.', 'youumatter2' ),
		'attribution' => __( 'K., 26', 'youumatter2' ),
		'context'     => __( 'Purpose & direction', 'youumatter2' ),
	),
	array(
		'quote'       => __( "Even our hardest sessions ended with me feeling more like myself, not less. That's rare.", 'youumatter2' ),
		'attribution' => __( 'S., 38', 'youumatter2' ),
		'context'     => __( 'Depression', 'youumatter2' ),
	),
);
?>
<section class="relative bg-sage-light px-5 md:px-8 pt-10 md:pt-14 pb-12 md:pb-16 overflow-hidden">
	<div class="relative max-w-6xl mx-auto" x-data="yum2TestimonialsCarousel(<?php echo esc_attr( count( $testimonials ) ); ?>)" x-init="init()">

		<div class="flex flex-col md:flex-row md:items-baseline md:justify-between gap-2 md:gap-6 mb-6 md:mb-8 yum2-reveal">
			<div class="flex items-baseline gap-3 md:gap-4 flex-wrap">
				<span class="text-terracotta tracking-[2px] uppercase" style="font-size:11px;font-weight:600;">
					<?php esc_html_e( 'Client reviews', 'youumatter2' ); ?>
				</span>
				<span aria-hidden class="hidden md:inline-block h-px w-8 bg-forest/20"></span>
				<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(22px,2.4vw,30px);line-height:1.15;letter-spacing:-0.01em;font-weight:400;">
					<?php esc_html_e( 'What clients say,', 'youumatter2' ); ?>
					<em class="italic" style="color:#c07a5a;font-weight:400;"><?php esc_html_e( 'in their own words.', 'youumatter2' ); ?></em>
				</h2>
			</div>
			<p class="italic text-forest/65" style="font-family:'Newsreader',serif;font-size:14.5px;">
				<?php esc_html_e( 'Shared with consent · details changed for privacy.', 'youumatter2' ); ?>
			</p>
		</div>

		<div
			x-ref="scroller"
			class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth -mx-1"
			style="scrollbar-width:none;"
		>
			<?php foreach ( $testimonials as $t ) : ?>
				<div class="snap-start shrink-0 w-full md:w-1/2 px-1 pb-2 pt-1">
					<article class="relative bg-[#f8f3e9] border border-forest/15 rounded-[22px] p-7 md:p-8 h-full hover:border-forest/35 hover:shadow-[0_22px_44px_-18px_rgba(26,58,25,0.16)] transition-[border-color,box-shadow] duration-500 overflow-hidden">
						<span aria-hidden class="absolute -top-1 -left-1 text-terracotta/15 pointer-events-none">
							<?php echo yum2_icon( 'quote', array( 'size' => 120, 'stroke' => 1 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<div class="relative">
							<p class="italic text-forest mb-6" style="font-family:'Newsreader',serif;font-size:clamp(18px,1.8vw,21px);line-height:1.5;font-weight:400;">
								&ldquo;<?php echo esc_html( $t['quote'] ); ?>&rdquo;
							</p>
							<div class="pt-4 border-t border-forest/10 flex items-center justify-between gap-3">
								<span class="text-forest" style="font-family:'Newsreader',serif;font-size:16px;font-weight:500;">
									&mdash; <?php echo esc_html( $t['attribution'] ); ?>
								</span>
								<span class="text-terracotta tracking-[0.14em] uppercase text-right" style="font-size:10.5px;font-weight:700;">
									<?php echo esc_html( $t['context'] ); ?>
								</span>
							</div>
						</div>
					</article>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="flex items-center justify-between mt-6 gap-4">
			<div class="flex items-center gap-2">
				<template x-for="i in pages" :key="i">
					<button
						type="button"
						@click="goTo(i - 1)"
						:aria-label="`Page ${i}`"
						class="h-1.5 rounded-full transition-all"
						:class="i - 1 === page ? 'bg-forest' : 'bg-forest/20'"
						:style="`width: ${i - 1 === page ? '28px' : '10px'}`"
					></button>
				</template>
			</div>
			<div class="flex items-center gap-2">
				<button type="button" @click="goTo(Math.max(0, page - 1))" :disabled="page === 0"
					aria-label="<?php esc_attr_e( 'Previous', 'youumatter2' ); ?>"
					class="size-11 rounded-full border border-forest/20 text-forest flex items-center justify-center hover:bg-forest/5 hover:border-forest transition-colors disabled:opacity-40">
					<?php echo yum2_icon( 'arrow-left', array( 'size' => 18, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<button type="button" @click="goTo(Math.min(pages - 1, page + 1))" :disabled="page === pages - 1"
					aria-label="<?php esc_attr_e( 'Next', 'youumatter2' ); ?>"
					class="size-11 rounded-full border border-forest/20 text-forest flex items-center justify-center hover:bg-forest/5 hover:border-forest transition-colors disabled:opacity-40">
					<?php echo yum2_icon( 'arrow-right', array( 'size' => 18, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
			</div>
		</div>
	</div>
</section>
