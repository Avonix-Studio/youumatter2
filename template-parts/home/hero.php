<?php
/**
 * Home hero. Eyebrow chip + giant H1 + 2-button CTA + portrait disc.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$portrait = get_template_directory_uri() . '/assets/images/sanya-portrait.png';
$accepting = (bool) yum2_get_contact( 'accepting_clients' );
?>
<section class="relative overflow-hidden bg-[#f2ede3]">
	<div aria-hidden class="absolute -top-32 -right-40 w-[520px] h-[520px] rounded-full bg-[#e4efe3] opacity-70 blur-[2px]"></div>
	<div aria-hidden class="absolute bottom-10 -left-48 w-[420px] h-[420px] rounded-full bg-[rgba(192,122,90,0.08)]"></div>

	<div class="relative max-w-6xl mx-auto px-5 md:px-8 pt-10 md:pt-14 pb-20 md:pb-24">
		<span class="inline-flex items-center gap-2 bg-[#d1e5d0] text-[#2b5329] rounded-full pl-3 pr-4 py-1.5 mb-8 md:mb-10" style="font-size:12px;font-weight:600;letter-spacing:1.5px;">
			<span class="size-1.5 rounded-full bg-[#2b5329]"></span>
			<span class="uppercase"><?php esc_html_e( 'Counselling Psychologist', 'youumatter2' ); ?></span>
		</span>

		<h1 class="text-[#1a3a19] mb-10 md:mb-14" style="font-family:'Newsreader',serif;font-size:clamp(44px,9vw,120px);line-height:0.98;letter-spacing:-0.03em;font-weight:500;text-wrap:balance;">
			<?php
			/* translators: brand wordmark uses doubled "youu" */
			esc_html_e( 'Youu matter.', 'youumatter2' );
			?>
			<br>
			<span class="italic" style="color:#c07a5a;"><?php esc_html_e( 'And youu can do this.', 'youumatter2' ); ?></span>
		</h1>

		<div class="grid grid-cols-1 md:grid-cols-[1.1fr_1fr] gap-12 md:gap-16 items-center">
			<div class="order-2 md:order-1">
				<p class="text-[#516352] max-w-lg mb-8" style="font-size:clamp(17px,1.6vw,20px);line-height:1.55;">
					<?php esc_html_e( "A quiet space to untangle what's weighing on you. Thoughtful, evidence-based therapy, online or in person at my clinic.", 'youumatter2' ); ?>
				</p>

				<div class="flex flex-wrap gap-3 mb-4">
					<?php
					get_template_part(
						'template-parts/shared/book-button',
						null,
						array(
							'label'   => __( 'Book a Session', 'youumatter2' ),
							'variant' => 'primary',
							'class'   => 'h-[56px] px-7',
						)
					);
					?>
					<a href="<?php echo esc_url( yum2_whatsapp_url( __( 'Hi Sanya, I would like to learn more about working together.', 'youumatter2' ) ) ); ?>"
						target="_blank" rel="noopener"
						class="inline-flex items-center justify-center gap-2 bg-transparent border-2 border-[rgba(43,83,41,0.25)] hover:border-[#2b5329] text-[#2b5329] rounded-full h-[56px] px-7 transition-colors"
						style="font-size:16px;font-weight:600;">
						<?php esc_html_e( 'Message on WhatsApp', 'youumatter2' ); ?>
						<span aria-hidden>&rarr;</span>
					</a>
				</div>

				<p class="text-[rgba(81,99,82,0.75)]" style="font-size:13px;">
					<?php
					echo esc_html(
						sprintf(
							/* translators: %s: clinic location e.g. "Pitampura, New Delhi" */
							__( '%s · Online &amp; In-Person', 'youumatter2' ),
							yum2_get_contact( 'clinic_address' )
						)
					);
					?>
				</p>
			</div>

			<div class="order-1 md:order-2 relative w-full max-w-[420px] md:max-w-[460px] mx-auto aspect-square">
				<div aria-hidden class="absolute -inset-[6%] rounded-full bg-[#d1e5d0]"></div>
				<div class="relative rounded-full overflow-hidden shadow-[0_24px_48px_-12px_rgba(19,60,20,0.22)] size-full">
					<img
						src="<?php echo esc_url( $portrait ); ?>"
						alt="<?php esc_attr_e( 'Sanya Oberoi, Counselling Psychologist', 'youumatter2' ); ?>"
						class="w-full h-full object-cover"
						loading="eager"
						decoding="async"
					>
				</div>

				<svg viewBox="0 0 64 64" class="absolute -top-6 -right-4 md:-top-8 md:-right-6 w-14 h-14 md:w-20 md:h-20 text-[#c07a5a]" fill="currentColor" aria-hidden>
					<path d="M46 8C24 8 12 24 12 42c0 6 3 12 9 12 14 0 26-12 30-34 .6-2.4 1.8-6.9 3-12-3.6 0-6 0-8 0z" opacity="0.95"/>
					<path d="M22 46c4-10 12-18 22-22" stroke="#f2ede3" stroke-width="1.6" fill="none" opacity="0.7"/>
				</svg>

				<div class="hidden md:flex absolute -top-5 -left-6 bg-white rounded-2xl px-4 py-3 items-center gap-2 shadow-[0_14px_32px_rgba(19,60,20,0.12)] border border-[#e0d9ce]" style="transform:rotate(-3deg);">
					<span class="italic text-[#1a3a19]" style="font-family:'Newsreader',serif;font-size:14px;">
						<?php esc_html_e( '"A steady Tuesday."', 'youumatter2' ); ?>
					</span>
				</div>

				<?php if ( $accepting ) : ?>
					<div class="absolute -bottom-3 left-3 md:left-6 bg-white/95 backdrop-blur-sm border border-[#e0d9ce] rounded-full pl-3 pr-4 py-2 flex items-center gap-2 shadow-[0_12px_28px_rgba(19,60,20,0.12)]">
						<span class="relative flex size-2">
							<span class="absolute inset-0 rounded-full bg-[#2b5329] animate-ping opacity-60"></span>
							<span class="relative rounded-full size-2 bg-[#2b5329]"></span>
						</span>
						<span class="text-[#1a3a19]" style="font-size:12px;font-weight:600;">
							<?php esc_html_e( 'Accepting new clients', 'youumatter2' ); ?>
						</span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
