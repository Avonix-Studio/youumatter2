<?php
/**
 * Front page. Phase 2 will replace this with the full home composition.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<section class="bg-cream">
		<div class="mx-auto max-w-6xl px-4 md:px-6 py-20 md:py-28 grid md:grid-cols-2 gap-12 items-center">
			<div>
				<?php if ( yum2_get_contact( 'accepting_clients' ) ) : ?>
					<span class="inline-flex items-center gap-2 bg-white border border-forest/15 rounded-full px-3 py-1 text-xs text-forest mb-6">
						<span class="size-2 rounded-full bg-forest"></span>
						<?php esc_html_e( 'Accepting new clients', 'youumatter2' ); ?>
					</span>
				<?php endif; ?>

				<h1 class="font-heading text-4xl md:text-6xl text-forest leading-tight mb-6">
					<?php esc_html_e( 'Youu matter.', 'youumatter2' ); ?>
					<span class="block italic text-terracotta mt-2">
						<?php esc_html_e( 'And youu can do this.', 'youumatter2' ); ?>
					</span>
				</h1>

				<p class="text-ink/80 text-lg max-w-md mb-8">
					<?php esc_html_e( 'Counselling psychology with Sanya Oberoi. Online and in-person therapy from Pitampura, New Delhi. Take your time. I\'ll be here when you\'re ready.', 'youumatter2' ); ?>
				</p>

				<div class="flex flex-wrap gap-3">
					<a href="#" onclick="return yum2OpenCalendly(YUM2.calendlyUrl)"
						class="inline-flex items-center justify-center bg-forest text-cream rounded-full px-6 py-3 text-sm font-medium hover:bg-forest/90 transition">
						<?php esc_html_e( 'Book a free consultation', 'youumatter2' ); ?>
					</a>
					<a href="<?php echo esc_url( yum2_whatsapp_url( __( 'Hi Sanya, I would like to learn more about working together.', 'youumatter2' ) ) ); ?>"
						target="_blank" rel="noopener"
						class="inline-flex items-center justify-center bg-white border border-forest/20 text-forest rounded-full px-6 py-3 text-sm font-medium hover:bg-sage-light/40 transition">
						<?php esc_html_e( 'WhatsApp Sanya', 'youumatter2' ); ?>
					</a>
				</div>
			</div>

			<div class="bg-sage-light/40 aspect-[4/5] rounded-3xl flex items-center justify-center text-forest/40 font-heading italic text-2xl">
				<?php esc_html_e( 'portrait placeholder', 'youumatter2' ); ?>
			</div>
		</div>
	</section>

	<section class="bg-forest text-cream">
		<div class="mx-auto max-w-3xl px-4 md:px-6 py-16 text-center">
			<p class="font-heading italic text-2xl md:text-3xl mb-6">
				<?php esc_html_e( 'Stay close to what helps.', 'youumatter2' ); ?>
			</p>
			<p class="text-cream/80 text-sm">
				<?php echo esc_html( yum2_get_contact( 'clinic_address' ) ); ?>
				&middot;
				<?php echo esc_html( yum2_get_contact( 'clinic_hours' ) ); ?>
			</p>
		</div>
	</section>
</main>

<?php
get_footer();
