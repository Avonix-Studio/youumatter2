<?php
/**
 * About: "How I work" 4-card grid.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cards = array(
	array(
		'icon'  => 'heart',
		'title' => __( 'Warm, not casual', 'youumatter2' ),
		'body'  => __( "I'm friendly, but the work is real. We won't only talk about your week, we'll look at the patterns underneath.", 'youumatter2' ),
	),
	array(
		'icon'  => 'compass',
		'title' => __( 'Integrative, not dogmatic', 'youumatter2' ),
		'body'  => __( "I draw from CBT, narrative therapy, mindfulness, and emotion-focused work. Whatever you respond to, that's what we use more of.", 'youumatter2' ),
	),
	array(
		'icon'  => 'anchor',
		'title' => __( 'Slow when it counts', 'youumatter2' ),
		'body'  => __( 'We move at your pace, especially around the harder parts. Safety first, every session, without making a big deal of it.', 'youumatter2' ),
	),
	array(
		'icon'  => 'sprout',
		'title' => __( 'Practice over insight', 'youumatter2' ),
		'body'  => __( 'Talk is the start. We translate what you notice into small, doable shifts you can carry into the rest of your week.', 'youumatter2' ),
	),
);
?>
<section class="relative bg-[#f8f3e9] px-5 md:px-8 py-20 md:py-28 overflow-hidden">
	<div class="relative max-w-5xl mx-auto">

		<div class="text-center max-w-2xl mx-auto">
			<p class="text-terracotta tracking-[2px] uppercase mb-5" style="font-size:12px;font-weight:600;">
				<?php esc_html_e( 'How I work', 'youumatter2' ); ?>
			</p>
			<h2 class="text-forest mb-5" style="font-family:'Newsreader',serif;font-size:clamp(30px,4.4vw,48px);line-height:1.1;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
				<?php esc_html_e( 'Less couch and clipboard,', 'youumatter2' ); ?>
				<em class="italic" style="color:#c07a5a;"><?php esc_html_e( 'more conversation.', 'youumatter2' ); ?></em>
			</h2>
			<p class="italic text-forest/65 mb-12" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;">
				<?php esc_html_e( "I don't lead with method. I lead with you. These are the four threads I weave together based on what you bring.", 'youumatter2' ); ?>
			</p>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
			<?php foreach ( $cards as $card ) : ?>
				<div class="group bg-cream border border-forest/15 rounded-[20px] p-6 md:p-7 transition-[transform,box-shadow,border-color] duration-300 hover:-translate-y-1.5 hover:shadow-[0_22px_44px_-22px_rgba(26,58,25,0.28)] hover:border-forest/25">
					<span class="relative inline-flex size-12 items-center justify-center mb-5">
						<span class="absolute inset-0 rounded-full bg-sage-light transition-transform duration-500 group-hover:rotate-[15deg]"></span>
						<span class="relative text-terracotta">
							<?php echo yum2_icon( $card['icon'], array( 'size' => 20, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					</span>
					<h3 class="text-forest mb-2" style="font-family:'Newsreader',serif;font-size:22px;line-height:1.25;letter-spacing:-0.01em;font-weight:500;">
						<?php echo esc_html( $card['title'] ); ?>
					</h3>
					<p class="text-forest/65" style="font-size:15px;line-height:1.6;">
						<?php echo esc_html( $card['body'] ); ?>
					</p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
