<?php
/**
 * Home: 9-card "you might be feeling" Swiper carousel.
 *
 * Section data is a hardcoded PHP array per the Content strategy in CLAUDE.md.
 * ACF migration later swaps $cards / $chips for get_field() with no markup change.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Copy lives in inc/content.php under 'feeling'. */
$c     = yum2_content( 'feeling' );
$chips = $c['chips'];
$cards = $c['cards'];
?>
<section class="relative bg-[#f2ede3] px-5 md:px-8 pt-14 md:pt-20 pb-14 md:pb-20 overflow-hidden">
	<div aria-hidden class="absolute -top-24 left-1/2 -translate-x-1/2 w-[720px] h-[440px] rounded-full pointer-events-none" style="background:radial-gradient(ellipse at center, rgba(228,239,227,0.55) 0%, rgba(242,237,227,0) 70%);"></div>

	<div class="relative max-w-6xl mx-auto" x-data="{ active: 0 }">
		<p class="text-[#c07a5a] tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;">
			<?php echo esc_html( $c['label'] ); ?>
		</p>

		<div class="grid grid-cols-1 md:grid-cols-[1.4fr_1fr] gap-5 md:gap-14 items-end mb-8 md:mb-10 yum2-reveal">
			<h2 class="text-[#1a3a19]" style="font-family:'Newsreader',serif;font-size:clamp(30px,4.8vw,52px);line-height:1.1;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
				<?php echo esc_html( $c['heading'] ); ?>
				<em class="italic" style="font-weight:400;color:#c07a5a;"><?php echo esc_html( $c['heading_em'] ); ?></em>
			</h2>
			<p class="italic text-[#3d4f3e]" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;">
				<?php echo esc_html( $c['description'] ); ?>
			</p>
		</div>

		<div class="relative -mx-5 md:mx-0 mb-8">
			<div class="flex md:flex-wrap gap-2 overflow-x-auto md:overflow-visible scroll-smooth px-5 md:px-0 md:gap-2.5" style="scrollbar-width:none;">
				<?php foreach ( $chips as $i => $label ) : ?>
					<button
						type="button"
						data-chip="<?php echo (int) $i; ?>"
						@click="active = <?php echo (int) $i; ?>; window.yum2 && window.yum2.feelingSwiper && window.yum2.feelingSwiper.slideTo(<?php echo (int) $i; ?>)"
						:class="active === <?php echo (int) $i; ?> ? 'bg-[#2b5329] text-white border-[#2b5329]' : 'bg-[#f8f3e9] text-[#1a3a19] border-[#c8dcc7] hover:border-[#2b5329]'"
						class="shrink-0 h-9 md:h-10 px-4 rounded-full whitespace-nowrap transition-colors border"
						style="font-size:13px;font-weight:500;"
					>
						<?php echo esc_html( $label ); ?>
					</button>
				<?php endforeach; ?>
			</div>
			<div aria-hidden class="md:hidden pointer-events-none absolute right-0 top-0 bottom-0 w-12" style="background:linear-gradient(to right, rgba(242,237,227,0) 0%, #f2ede3 80%);"></div>
		</div>

		<div class="swiper yum2-feeling-swiper">
			<div class="swiper-wrapper">
				<?php foreach ( $cards as $i => $card ) : ?>
					<div class="swiper-slide pb-2 pt-1">
						<article
							:class="active === <?php echo (int) $i; ?> ? 'border-[#2b5329]' : 'border-[rgba(43,83,41,0.22)] hover:border-[rgba(43,83,41,0.45)] hover:shadow-[0_22px_44px_-16px_rgba(26,58,25,0.16)]'"
							class="group relative bg-[#f8f3e9] border rounded-[20px] p-5 md:p-7 overflow-hidden transition-[border-color,box-shadow] duration-300 h-full">
							<span aria-hidden class="pointer-events-none absolute -top-2 right-3 md:-top-4 md:right-4 select-none" style="font-family:'Newsreader',serif;font-size:clamp(86px,11vw,160px);font-weight:600;line-height:1;color:rgba(26,58,25,0.06);letter-spacing:-0.05em;">
								<?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?>
							</span>

							<div class="relative">
								<p class="text-[#c07a5a] tracking-[0.18em] mb-3" style="font-size:11px;font-weight:700;">
									<?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?>
								</p>

								<h3 class="text-[#1a3a19] mb-2" style="font-family:'Newsreader',serif;font-size:clamp(22px,2.2vw,28px);line-height:1.15;letter-spacing:-0.02em;font-weight:500;">
									<?php echo esc_html( $card['title'] ); ?>
								</h3>
								<p class="italic text-[#3d4f3e] mb-5" style="font-family:'Newsreader',serif;font-size:17px;line-height:1.5;">
									<?php echo esc_html( $card['tagline'] ); ?>
								</p>

								<p class="text-[#c07a5a] tracking-[0.14em] uppercase mb-2.5" style="font-size:11px;font-weight:700;">
									<?php esc_html_e( 'Signs this might be present', 'youumatter2' ); ?>
								</p>
								<ul class="flex flex-col gap-2 mb-5">
									<?php foreach ( $card['signs'] as $sign ) : ?>
										<li class="grid grid-cols-[12px_1fr] gap-3 text-[#3d4f3e]" style="font-size:15px;line-height:1.55;">
											<span class="mt-[8px] size-[6px] rounded-full bg-[#2b5329]" aria-hidden></span>
											<span><?php echo esc_html( $sign ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>

								<div class="pt-4 border-t border-[rgba(26,58,25,0.1)]">
									<p class="text-[#c07a5a] tracking-[0.14em] uppercase mb-1.5" style="font-size:11px;font-weight:700;">
										<?php esc_html_e( "How we'd work on it", 'youumatter2' ); ?>
									</p>
									<p class="italic text-[#3d4f3e]" style="font-family:'Newsreader',serif;font-size:15px;line-height:1.6;">
										<?php echo esc_html( $card['approach'] ); ?>
									</p>
								</div>

								<div class="mt-6 h-px bg-[rgba(26,58,25,0.12)]"></div>

								<dl class="mt-5 grid grid-cols-3 gap-3">
									<?php
									$meta = array(
										array( 'label' => __( 'Duration', 'youumatter2' ), 'value' => __( '60 min', 'youumatter2' ) ),
										array( 'label' => __( 'Format', 'youumatter2' ), 'value' => __( 'Online or in-person', 'youumatter2' ) ),
										array( 'label' => __( 'Fee', 'youumatter2' ), 'value' => $card['fee'] ),
									);
									foreach ( $meta as $m ) :
										?>
										<div class="min-w-0">
											<dt class="text-[#c07a5a] tracking-[0.14em] uppercase mb-1" style="font-size:10.5px;font-weight:700;">
												<?php echo esc_html( $m['label'] ); ?>
											</dt>
											<dd class="text-[#1a3a19]" style="font-family:'Newsreader',serif;font-size:14.5px;font-weight:500;line-height:1.3;">
												<?php echo esc_html( $m['value'] ); ?>
											</dd>
										</div>
									<?php endforeach; ?>
								</dl>

								<div class="mt-5">
									<?php
									get_template_part(
										'template-parts/shared/book-button',
										null,
										array(
											'label'   => __( 'Book this session', 'youumatter2' ),
											'variant' => 'primary',
											'icon'    => true,
											'class'   => 'w-full',
										)
									);
									?>
								</div>
							</div>
						</article>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="flex items-center justify-between mt-6 gap-4">
			<div class="flex items-center gap-2">
				<div class="yum2-feeling-pagination flex items-center gap-2"></div>
				<span class="ml-3 text-[#3d4f3e] whitespace-nowrap" style="font-size:12px;">
					<span class="yum2-feeling-current">1</span>
					<?php esc_html_e( 'of', 'youumatter2' ); ?>
					<?php echo esc_html( (string) count( $cards ) ); ?>
				</span>
			</div>

			<div class="flex items-center gap-2">
				<button type="button" class="yum2-feeling-prev size-11 rounded-full border border-[rgba(26,58,25,0.2)] text-[#1a3a19] flex items-center justify-center hover:bg-[rgba(26,58,25,0.06)] hover:border-[#2b5329] transition-colors disabled:opacity-40" aria-label="<?php esc_attr_e( 'Previous', 'youumatter2' ); ?>">
					<?php echo yum2_icon( 'arrow-left', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<button type="button" class="yum2-feeling-next size-11 rounded-full border border-[rgba(26,58,25,0.2)] text-[#1a3a19] flex items-center justify-center hover:bg-[rgba(26,58,25,0.06)] hover:border-[#2b5329] transition-colors disabled:opacity-40" aria-label="<?php esc_attr_e( 'Next', 'youumatter2' ); ?>">
					<?php echo yum2_icon( 'arrow-right', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
			</div>
		</div>

		<div class="mt-8">
			<div class="h-px bg-[rgba(26,58,25,0.12)]"></div>
			<p class="italic text-[#3d4f3e] text-center mt-6" style="font-family:'Newsreader',serif;font-size:17px;line-height:1.5;">
				<?php echo esc_html( $c['cta_note'] ); ?>
			</p>
			<div class="mt-4 flex flex-col md:flex-row md:justify-center items-stretch md:items-center gap-3">
				<?php
				get_template_part(
					'template-parts/shared/book-button',
					null,
					array(
						'label'   => $c['btn_book'],
						'variant' => 'primary',
						'icon'    => true,
						'class'   => 'w-full md:w-auto md:max-w-[320px]',
					)
				);
				?>
				<a href="<?php echo esc_url( yum2_whatsapp_url( $c['whatsapp_msg'] ) ); ?>"
					target="_blank" rel="noopener"
					class="inline-flex items-center justify-center gap-2 bg-transparent border-[1.5px] border-[#2b5329] text-[#2b5329] hover:bg-[rgba(43,83,41,0.06)] rounded-full h-[52px] px-6 transition-colors w-full md:w-auto md:max-w-[320px]"
					style="font-size:15px;font-weight:600;">
					<?php echo yum2_icon( 'whatsapp', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo esc_html( $c['btn_whatsapp'] ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
