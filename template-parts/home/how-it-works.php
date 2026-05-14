<?php
/**
 * Home: how it works. Three-step path from booking to first session.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$steps = array(
	array(
		'icon'  => 'calendar',
		'title' => __( 'Book', 'youumatter2' ),
		'body'  => __( "Tap Book, pick a time that works, and you're set. No forms, no back-and-forth.", 'youumatter2' ),
		'note'  => __( 'takes about a minute', 'youumatter2' ),
	),
	array(
		'icon'  => 'message',
		'title' => __( 'Connect', 'youumatter2' ),
		'body'  => __( "A fifteen-minute intro on WhatsApp. To see if we're a fit, no pressure either way.", 'youumatter2' ),
		'note'  => __( 'on WhatsApp, at your comfort', 'youumatter2' ),
	),
	array(
		'icon'  => 'leaf',
		'title' => __( 'Begin', 'youumatter2' ),
		'body'  => __( 'Your first therapy session. Sixty minutes, online or in person. We start where you are, gently, at your pace.', 'youumatter2' ),
		'note'  => __( 'sixty minutes, yours', 'youumatter2' ),
	),
);
?>
<section class="relative bg-[#e4efe3] px-5 md:px-8 pt-14 md:pt-20 pb-14 md:pb-20 overflow-hidden">
	<div aria-hidden class="absolute -top-20 right-0 w-[460px] h-[460px] rounded-full pointer-events-none" style="background:radial-gradient(circle at center, rgba(248,243,233,0.6) 0%, rgba(228,239,227,0) 70%);"></div>
	<div aria-hidden class="absolute bottom-0 -left-24 w-[380px] h-[380px] rounded-full pointer-events-none" style="background:radial-gradient(circle at center, rgba(192,122,90,0.07) 0%, rgba(228,239,227,0) 70%);"></div>

	<div class="relative max-w-6xl mx-auto">
		<div class="grid grid-cols-1 md:grid-cols-[1.2fr_1fr] gap-5 md:gap-14 items-end mb-10 md:mb-12">
			<div>
				<p class="text-[#c07a5a] tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;">
					<?php esc_html_e( 'From here to a first session', 'youumatter2' ); ?>
				</p>
				<h2 class="text-[#1a3a19]" style="font-family:'Newsreader',serif;font-size:clamp(30px,4.8vw,54px);line-height:1.08;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
					<?php esc_html_e( 'Three small steps.', 'youumatter2' ); ?>
					<em class="italic" style="color:#c07a5a;font-weight:400;"><?php esc_html_e( "No commitment until you're ready.", 'youumatter2' ); ?></em>
				</h2>
			</div>
			<p class="italic text-[#516352]" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;">
				<?php esc_html_e( "Reaching out is often the hardest part. After that, it's simple.", 'youumatter2' ); ?>
			</p>
		</div>

		<div class="flex flex-col md:flex-row items-stretch gap-4 md:gap-0">
			<?php foreach ( $steps as $i => $step ) : ?>
				<div class="flex md:flex-1 items-stretch yum2-reveal">
					<div class="relative flex-1 min-w-0">
						<div class="group relative bg-[#f8f3e9] border border-[#e0d9ce] rounded-[22px] p-6 md:p-7 h-full hover:border-[rgba(43,83,41,0.35)] hover:shadow-[0_22px_44px_-18px_rgba(26,58,25,0.16)] hover:-translate-y-1.5 transition-[border-color,box-shadow,transform] duration-500">
							<div class="flex items-center gap-3 mb-5">
								<div class="relative shrink-0">
									<span aria-hidden class="absolute inset-0 rounded-full bg-[#d1e5d0] animate-ping opacity-50"></span>
									<div class="relative size-11 rounded-full bg-[#2b5329] text-white flex items-center justify-center shadow-[0_8px_18px_rgba(26,58,25,0.22)] yum2-icon-bob<?php echo $i > 0 ? ' yum2-icon-bob--d' . $i : ''; ?>">
										<?php echo yum2_icon( $step['icon'], array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								</div>
								<div class="flex flex-col leading-tight">
									<span class="text-[#c07a5a] tracking-[0.14em] uppercase" style="font-size:10.5px;font-weight:700;">
										<?php
										echo esc_html(
											sprintf(
												/* translators: %s: zero-padded step number e.g. "01" */
												__( 'Step %s', 'youumatter2' ),
												str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT )
											)
										);
										?>
									</span>
									<span class="italic text-[#516352] mt-0.5" style="font-family:'Newsreader',serif;font-size:13px;">
										<?php echo esc_html( $step['note'] ); ?>
									</span>
								</div>
							</div>

							<h3 class="text-[#1a3a19] mb-2" style="font-family:'Newsreader',serif;font-size:clamp(22px,2.2vw,28px);line-height:1.1;letter-spacing:-0.02em;font-weight:500;">
								<?php echo esc_html( $step['title'] ); ?>
							</h3>
							<p class="text-[#516352]" style="font-size:15px;line-height:1.55;">
								<?php echo esc_html( $step['body'] ); ?>
							</p>
						</div>
					</div>

					<?php if ( $i < count( $steps ) - 1 ) : ?>
						<div aria-hidden class="hidden md:flex items-center shrink-0 px-2 self-center">
							<svg width="44" height="18" viewBox="0 0 44 18" fill="none">
								<path d="M2 9 H 34" stroke="#c07a5a" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="3 4" opacity="0.7"/>
								<path d="M32 3 L 40 9 L 32 15" stroke="#c07a5a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
							</svg>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mt-10 md:mt-12 pt-7 border-t border-[rgba(26,58,25,0.12)]">
			<p class="italic text-[#516352]" style="font-family:'Newsreader',serif;font-size:17px;">
				<?php esc_html_e( "Not ready to book? That's okay, just say hi.", 'youumatter2' ); ?>
			</p>
			<div class="flex flex-wrap gap-3">
				<?php
				get_template_part(
					'template-parts/shared/book-button',
					null,
					array(
						'label'   => __( 'Book a Session', 'youumatter2' ),
						'variant' => 'primary',
					)
				);
				?>
				<a href="<?php echo esc_url( yum2_whatsapp_url( __( 'Hi Sanya, just saying hi for now.', 'youumatter2' ) ) ); ?>"
					target="_blank" rel="noopener"
					class="inline-flex items-center justify-center gap-2 bg-transparent border-2 border-[rgba(43,83,41,0.25)] hover:border-[#2b5329] text-[#2b5329] rounded-full h-[52px] px-7 transition-colors"
					style="font-size:15px;font-weight:600;">
					<?php esc_html_e( 'Message on WhatsApp', 'youumatter2' ); ?>
					<span aria-hidden>&rarr;</span>
				</a>
			</div>
		</div>
	</div>
</section>
