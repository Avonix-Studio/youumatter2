<?php
/**
 * FAQ: closing CTA section.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contact_url = '';
$contact_page = get_page_by_path( 'contact' );
if ( $contact_page ) {
	$contact_url = (string) get_permalink( $contact_page );
}
if ( '' === $contact_url ) {
	$contact_url = home_url( '/contact/' );
}
?>
<section class="bg-sage-light border-y border-forest/10">
	<div class="max-w-4xl mx-auto px-5 md:px-8 py-14 md:py-20 text-center">
		<p class="text-terracotta tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;">
			<?php esc_html_e( 'Still wondering?', 'youumatter2' ); ?>
		</p>
		<h2 class="text-forest mb-5" style="font-family:'Newsreader',serif;font-size:clamp(28px,4vw,44px);line-height:1.1;font-weight:400;letter-spacing:-0.01em;">
			<?php esc_html_e( "Didn't find what you were looking for?", 'youumatter2' ); ?>
			<em class="italic" style="color:#c07a5a;"><?php esc_html_e( 'Just ask.', 'youumatter2' ); ?></em>
		</h2>
		<p class="italic text-forest/65 max-w-xl mx-auto mb-8" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;">
			<?php esc_html_e( "There's no such thing as a silly question. If you've read this far, your curiosity is a good sign.", 'youumatter2' ); ?>
		</p>
		<div class="flex flex-wrap items-center justify-center gap-3">
			<a
				href="<?php echo esc_url( $contact_url ); ?>"
				class="inline-flex items-center gap-2 bg-forest hover:bg-forest/90 text-cream rounded-full h-[52px] px-6 transition-colors shadow-[0_14px_30px_rgba(26,58,25,0.18)]"
				style="font-size:15px;font-weight:600;"
			>
				<?php echo yum2_icon( 'mail', array( 'size' => 16, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php esc_html_e( 'Send a note', 'youumatter2' ); ?>
			</a>
			<a
				href="<?php echo esc_url( yum2_whatsapp_url( __( 'Hi Sanya, I have a quick question.', 'youumatter2' ) ) ); ?>"
				target="_blank" rel="noopener noreferrer"
				class="inline-flex items-center gap-2 bg-transparent border-2 border-forest/25 hover:border-forest text-forest rounded-full h-[52px] px-6 transition-colors"
				style="font-size:15px;font-weight:600;"
			>
				<?php echo yum2_icon( 'message-circle', array( 'size' => 16, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php esc_html_e( 'WhatsApp instead', 'youumatter2' ); ?>
			</a>
		</div>
	</div>
</section>
