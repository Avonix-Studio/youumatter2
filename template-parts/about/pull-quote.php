<?php
/**
 * About: large pull-quote in a sage-light card.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="relative bg-cream px-5 md:px-8 py-20 md:py-32 overflow-hidden">
	<div class="relative mx-auto bg-sage-light rounded-[28px] px-6 md:px-12 py-14 md:py-24 text-center overflow-hidden" style="max-width: 1100px;">
		<p class="relative text-forest max-w-3xl mx-auto" style="font-family:'Newsreader',serif;font-size:clamp(24px,3.8vw,40px);line-height:1.25;letter-spacing:-0.01em;font-weight:400;">
			<?php esc_html_e( "Most of us aren't broken.", 'youumatter2' ); ?>
			<em class="italic" style="color:#c07a5a;">
				<?php esc_html_e( "We're just carrying patterns we didn't choose, in a body that learned to brace too early.", 'youumatter2' ); ?>
			</em>
		</p>
		<p class="relative italic text-forest/65 mt-7 flex items-center justify-center gap-2" style="font-family:'Newsreader',serif;font-size:15px;">
			<span aria-hidden class="inline-block w-6 h-px bg-terracotta"></span>
			<?php esc_html_e( "Sanya, on what she's noticed across a thousand quiet conversations", 'youumatter2' ); ?>
		</p>
	</div>
</section>
