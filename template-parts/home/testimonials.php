<?php
/**
 * Home: client testimonials carousel.
 *
 * Native CSS scroll-snap (no Swiper), Alpine drives dot/arrow controls via the
 * yum2TestimonialsCarousel component registered in main.js.
 *
 * Testimonials are managed in wp-admin under "Testimonials" (a CPT). Section
 * chrome (label/heading/privacy note) still comes from inc/content.php.
 * Google business URL + rating come from the Customizer (Contact & Socials).
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! get_theme_mod( 'yum2_home_show_testimonials', true ) ) {
	return;
}

$c            = yum2_content( 'testimonials' );
$testimonials = yum2_testimonial_items();

if ( empty( $testimonials ) ) {
	return;
}

$google_url    = (string) yum2_get_contact( 'google_business_url' );
$google_rating = (string) yum2_get_contact( 'google_rating' );
?>
<section class="relative bg-sage-light px-5 md:px-8 pt-10 md:pt-14 pb-12 md:pb-16 overflow-hidden">
	<div class="relative max-w-6xl mx-auto" x-data="yum2TestimonialsCarousel(<?php echo esc_attr( count( $testimonials ) ); ?>)" x-init="init()">

		<div class="flex flex-col md:flex-row md:items-baseline md:justify-between gap-2 md:gap-6 mb-4 md:mb-5 yum2-reveal">
			<div class="flex items-baseline gap-3 md:gap-4 flex-wrap">
				<span class="text-terracotta tracking-[2px] uppercase" style="font-size:11px;font-weight:600;">
					<?php echo esc_html( $c['label'] ); ?>
				</span>
				<span aria-hidden class="hidden md:inline-block h-px w-8 bg-forest/20"></span>
				<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(22px,2.4vw,30px);line-height:1.15;letter-spacing:-0.01em;font-weight:400;">
					<?php echo esc_html( $c['heading'] ); ?>
					<em class="italic" style="color:#c07a5a;font-weight:400;"><?php echo esc_html( $c['heading_em'] ); ?></em>
				</h2>
			</div>
			<p class="italic text-[#3d4f3e]" style="font-family:'Newsreader',serif;font-size:14.5px;">
				<?php echo esc_html( $c['privacy_note'] ); ?>
			</p>
		</div>

		<?php
		/* Aggregate Google rating pill. Hidden if no URL is set in Customizer. */
		if ( '' !== $google_url ) :
			?>
			<div class="flex items-center justify-center mb-7 md:mb-8 yum2-reveal">
				<a href="<?php echo esc_url( $google_url ); ?>" target="_blank" rel="noopener noreferrer"
					class="inline-flex items-center gap-2 md:gap-3 bg-white border border-[#e0d9ce] rounded-full px-3.5 md:px-5 py-2 hover:shadow-md hover:border-forest/30 transition-all whitespace-nowrap"
					aria-label="<?php esc_attr_e( 'See all Google reviews', 'youumatter2' ); ?>">
					<svg width="18" height="18" viewBox="0 0 18 18" aria-hidden>
						<path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z"/>
						<path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z"/>
						<path fill="#FBBC05" d="M3.964 10.707A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.707V4.961H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.039l3.007-2.332z"/>
						<path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.961L3.964 7.293C4.672 5.163 6.656 3.58 9 3.58z"/>
					</svg>
					<?php if ( '' !== $google_rating ) : ?>
						<strong style="font-size:15px;color:#1a1a1a;font-weight:700;letter-spacing:-0.01em;"><?php echo esc_html( $google_rating ); ?></strong>
					<?php endif; ?>
					<span class="inline-flex items-center gap-0.5" aria-label="<?php esc_attr_e( '5 stars', 'youumatter2' ); ?>">
						<?php for ( $i = 0; $i < 5; $i++ ) : ?>
							<svg width="13" height="13" viewBox="0 0 20 20" aria-hidden>
								<path fill="#FBBC05" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 0 0 .95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.448a1 1 0 0 0-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118L10 15.347l-3.952 2.778c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 0 0-.364-1.118L2.064 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 0 0 .95-.69l1.285-3.957z"/>
							</svg>
						<?php endfor; ?>
					</span>
					<span style="font-size:12.5px;color:#5f6368;font-weight:400;">
						· <?php esc_html_e( 'See all reviews on Google', 'youumatter2' ); ?>
					</span>
				</a>
			</div>
			<?php
		endif;
		?>

		<div
			x-ref="scroller"
			class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth -mx-1"
			style="scrollbar-width:none;"
		>
			<?php
			foreach ( $testimonials as $t ) :
				$has_attribution = '' !== $t['attribution'];
				$has_context     = '' !== $t['context'];
				$show_google     = ! empty( $t['from_google'] );
				$has_footer      = $has_attribution || $has_context || $show_google;
				$rating          = (int) $t['rating'];
				?>
				<div class="snap-start shrink-0 w-full md:w-1/2 px-1 pb-2 pt-1">
					<article class="relative bg-[#f8f3e9] border border-forest/15 rounded-[22px] p-7 md:p-8 h-full hover:border-forest/35 hover:shadow-[0_22px_44px_-18px_rgba(26,58,25,0.16)] transition-[border-color,box-shadow] duration-500 overflow-hidden">
						<span aria-hidden class="absolute -top-1 -left-1 text-terracotta/15 pointer-events-none">
							<?php echo yum2_icon( 'quote', array( 'size' => 120, 'stroke' => 1 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<div class="relative">
							<?php /* Stars row. Always rendered; rating <=0 still shows 5 empty greys. */ ?>
							<div class="flex items-center gap-0.5 mb-4" aria-label="<?php echo esc_attr( sprintf( /* translators: %d is the star rating. */ _n( '%d star', '%d stars', max( 1, $rating ), 'youumatter2' ), $rating ) ); ?>">
								<?php for ( $i = 0; $i < 5; $i++ ) : ?>
									<svg width="14" height="14" viewBox="0 0 20 20" aria-hidden>
										<path fill="<?php echo $i < $rating ? '#FBBC05' : '#d1d5db'; ?>" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 0 0 .95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.448a1 1 0 0 0-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118L10 15.347l-3.952 2.778c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 0 0-.364-1.118L2.064 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 0 0 .95-.69l1.285-3.957z"/>
									</svg>
								<?php endfor; ?>
							</div>

							<p class="italic text-forest mb-6" style="font-family:'Newsreader',serif;font-size:clamp(18px,1.8vw,21px);line-height:1.5;font-weight:400;">
								&ldquo;<?php echo esc_html( $t['quote'] ); ?>&rdquo;
							</p>

							<?php if ( $has_footer ) : ?>
								<div class="pt-4 border-t border-forest/10 flex items-end justify-between gap-3">
									<div class="flex flex-col gap-1 min-w-0">
										<?php if ( $has_attribution ) : ?>
											<span class="text-forest" style="font-family:'Newsreader',serif;font-size:16px;font-weight:500;">
												&mdash; <?php echo esc_html( $t['attribution'] ); ?>
											</span>
										<?php endif; ?>
										<?php if ( $has_context ) : ?>
											<span class="text-terracotta tracking-[0.14em] uppercase" style="font-size:10.5px;font-weight:700;">
												<?php echo esc_html( $t['context'] ); ?>
											</span>
										<?php endif; ?>
									</div>
									<?php if ( $show_google ) : ?>
										<span class="inline-flex items-center gap-1.5 shrink-0 bg-white border border-forest/15 rounded-full px-2.5 py-1"
											title="<?php esc_attr_e( 'Verified Google review', 'youumatter2' ); ?>">
											<svg width="14" height="14" viewBox="0 0 18 18" aria-hidden>
												<path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z"/>
												<path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z"/>
												<path fill="#FBBC05" d="M3.964 10.707A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.707V4.961H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.039l3.007-2.332z"/>
												<path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.961L3.964 7.293C4.672 5.163 6.656 3.58 9 3.58z"/>
											</svg>
											<span style="font-size:11px;font-weight:600;color:#5f6368;letter-spacing:.01em;">Google</span>
										</span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
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
