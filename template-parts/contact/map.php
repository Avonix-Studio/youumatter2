<?php
/**
 * Contact: Google Maps embed centered on the clinic.
 *
 * Pitampura, New Delhi area: lat 28.6986, lng 77.1339 (approximate
 * neighbourhood centre, not the exact clinic. Sanya can replace the
 * iframe src with a precise share-link from Google Maps when she is
 * ready to publicise the address.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="bg-[#f8f3e9] px-5 md:px-8 py-12 md:py-16 border-t border-forest/10">
	<div class="max-w-6xl mx-auto">
		<div class="grid grid-cols-1 md:grid-cols-[1fr_1.6fr] gap-6 md:gap-10 items-center">
			<div>
				<p class="text-terracotta tracking-[2px] uppercase mb-3" style="font-size:11px;font-weight:600;">
					<?php esc_html_e( 'In-person clinic', 'youumatter2' ); ?>
				</p>
				<h2 class="text-forest mb-3" style="font-family:'Newsreader',serif;font-size:clamp(26px,3vw,34px);line-height:1.15;font-weight:400;">
					<?php esc_html_e( 'Pitampura,', 'youumatter2' ); ?>
					<em class="italic" style="color:#c07a5a;"><?php esc_html_e( 'New Delhi.', 'youumatter2' ); ?></em>
				</h2>
				<p class="italic text-forest/65" style="font-family:'Newsreader',serif;font-size:16px;line-height:1.55;">
					<?php esc_html_e( "The exact address is shared once we've confirmed a first session. The neighbourhood is well-connected by Metro (Pitampura station) and easy to reach by car.", 'youumatter2' ); ?>
				</p>
			</div>
			<div class="rounded-[22px] overflow-hidden border border-forest/15 shadow-[0_24px_60px_-30px_rgba(26,58,25,0.2)]">
				<iframe
					title="<?php esc_attr_e( 'Map of Pitampura, New Delhi', 'youumatter2' ); ?>"
					src="https://www.google.com/maps?q=Pitampura,New+Delhi&hl=en&z=14&output=embed"
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
