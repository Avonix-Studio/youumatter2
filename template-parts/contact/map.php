<?php
/**
 * Contact: Google Maps embed centered on the clinic.
 *
 * Matches the approved youumatter2 Google Business profile:
 * Mahendru Enclave (Gujranwala Town), off GT Karnal Road, Delhi 110033,
 * near Chhatrasal Stadium. Nearest Metro is Adarsh Nagar (Yellow Line).
 *
 * To change the heading or intro copy, edit the text below. To move the
 * map pin, go to Appearance > Customize > Contact & Social > "Contact page
 * map URL" (no code needed). The default below is used when that is blank.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$yum2_map_src = yum2_get_contact( 'clinic_map_embed' );
?>
<section class="bg-[#f8f3e9] px-5 md:px-8 py-12 md:py-16 border-t border-forest/10">
	<div class="max-w-6xl mx-auto">
		<div class="grid grid-cols-1 md:grid-cols-[1fr_1.6fr] gap-6 md:gap-10 items-center">
			<div>
				<p class="text-terracotta tracking-[2px] uppercase mb-3" style="font-size:11px;font-weight:600;">
					<?php esc_html_e( 'In-person clinic', 'youumatter2' ); ?>
				</p>
				<h2 class="text-forest mb-3" style="font-family:'Newsreader',serif;font-size:clamp(26px,3vw,34px);line-height:1.15;font-weight:400;">
					<?php esc_html_e( 'Mahendru Enclave,', 'youumatter2' ); ?>
					<em class="italic" style="color:#c07a5a;"><?php esc_html_e( 'New Delhi.', 'youumatter2' ); ?></em>
				</h2>
				<p class="italic text-[#3d4f3e]" style="font-family:'Newsreader',serif;font-size:16px;line-height:1.55;">
					<?php esc_html_e( "The exact address is shared once we've confirmed a first session. The neighbourhood sits just off GT Karnal Road near Chhatrasal Stadium, well-connected by Metro (Adarsh Nagar station) and easy to reach by car.", 'youumatter2' ); ?>
				</p>
			</div>
			<div class="rounded-[22px] overflow-hidden border border-forest/15 shadow-[0_24px_60px_-30px_rgba(26,58,25,0.2)]">
				<iframe
					title="<?php echo esc_attr( sprintf( /* translators: %s: clinic location */ __( 'Map of %s', 'youumatter2' ), yum2_get_contact( 'clinic_address' ) ) ); ?>"
					src="<?php echo esc_url( $yum2_map_src ); ?>"
					width="100%"
					height="320"
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					style="border:0;display:block;"
				></iframe>
			</div>
		</div>
	</div>
</section>
