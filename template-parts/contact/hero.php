<?php
/**
 * Contact hero. Sage-light section with title + subtitle.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="bg-sage-light">
	<div class="max-w-6xl mx-auto px-5 md:px-8 pt-14 md:pt-20 pb-14 md:pb-20">
		<p class="text-forest tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;">
			<?php esc_html_e( 'Contact', 'youumatter2' ); ?>
		</p>
		<h1 class="text-forest max-w-3xl" style="font-family:'Newsreader',serif;font-size:clamp(36px,5.6vw,64px);line-height:1.05;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
			<?php esc_html_e( "Let's start with", 'youumatter2' ); ?>
			<em class="italic" style="color:#c07a5a;font-weight:400;"><?php esc_html_e( 'a conversation.', 'youumatter2' ); ?></em>
		</h1>
		<p class="italic text-forest/65 mt-5 max-w-2xl" style="font-family:'Newsreader',serif;font-size:19px;line-height:1.55;">
			<?php esc_html_e( "Reach out the way that feels easiest. WhatsApp for something quick, email for something longer, or just book a first session. I reply personally within 24 hours on weekdays.", 'youumatter2' ); ?>
		</p>
	</div>
</section>
