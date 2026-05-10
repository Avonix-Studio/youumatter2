<?php
/**
 * About: "What I believe" sticky-scroll three-pane.
 *
 * Left column is sticky on lg+ ("Three things I keep coming back to.").
 * Right column is the three beliefs, each one a tall stacked block.
 *
 * Active state on each belief is driven by IntersectionObserver via
 * yum2BeliefBlock() Alpine component in main.js.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$beliefs = array(
	array(
		'k' => '01',
		't' => __( "Therapy isn't fixing.", 'youumatter2' ),
		's' => __( "It's meeting yourself with less armour. The goal isn't a tidier you. It's a more honest one.", 'youumatter2' ),
	),
	array(
		'k' => '02',
		't' => __( 'Your pace, always.', 'youumatter2' ),
		's' => __( "We don't rush the tender parts. If a session feels too fast or too far, we slow down without explanation.", 'youumatter2' ),
	),
	array(
		'k' => '03',
		't' => __( 'Small shifts, real change.', 'youumatter2' ),
		's' => __( 'Insight is lovely, but practice is what reshapes a life. Tiny honest things, repeated often, beat one heroic week.', 'youumatter2' ),
	),
);
?>
<section class="relative bg-cream px-5 md:px-8 py-20 md:py-28">
	<div class="relative max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-[0.9fr_1.1fr] gap-10 md:gap-20">

		<div class="md:sticky md:top-[120px] md:self-start md:max-h-[calc(100vh-140px)] md:flex md:flex-col md:justify-center">
			<p class="text-terracotta tracking-[2px] uppercase mb-5" style="font-size:12px;font-weight:600;">
				<?php esc_html_e( 'What I believe', 'youumatter2' ); ?>
			</p>
			<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(32px,4.4vw,52px);line-height:1.05;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
				<?php esc_html_e( 'Three things I keep', 'youumatter2' ); ?>
				<em class="italic" style="color:#c07a5a;"><?php esc_html_e( 'coming back to.', 'youumatter2' ); ?></em>
			</h2>
			<p class="hidden md:block italic text-forest/65 mt-5 max-w-sm" style="font-family:'Newsreader',serif;font-size:17px;line-height:1.55;">
				<?php esc_html_e( 'Not a manifesto, just the quiet anchors I notice myself returning to, session after session.', 'youumatter2' ); ?>
			</p>
			<div class="hidden md:block mt-8 h-px w-16 bg-terracotta"></div>
		</div>

		<div class="flex flex-col md:gap-8">
			<?php foreach ( $beliefs as $i => $b ) : ?>
				<div
					x-data="yum2BeliefBlock()"
					x-init="init()"
					x-ref="root"
					class="md:min-h-[80vh] flex flex-col justify-center py-12 md:py-0"
				>
					<span
						class="block mb-4 transition-all duration-500"
						:style="active ? 'transform: scale(1.05); opacity: 1' : 'transform: scale(1); opacity: 0.3'"
						style="font-family:'Newsreader',serif;font-size:clamp(56px,7vw,88px);line-height:1;letter-spacing:-0.03em;font-weight:500;color:#c07a5a;transform-origin:left center;"
					>
						<?php echo esc_html( $b['k'] ); ?>
					</span>
					<h3
						class="text-forest mb-4 transition-opacity duration-500"
						:class="active ? 'opacity-100' : 'opacity-30'"
						style="font-family:'Newsreader',serif;font-size:clamp(28px,3.6vw,40px);line-height:1.15;letter-spacing:-0.015em;font-weight:500;"
					>
						<?php echo esc_html( $b['t'] ); ?>
					</h3>
					<p
						class="italic text-forest/65 max-w-md transition-opacity duration-500"
						:class="active ? 'opacity-100' : 'opacity-55'"
						style="font-family:'Newsreader',serif;font-size:19px;line-height:1.55;"
					>
						<?php echo esc_html( $b['s'] ); ?>
					</p>
					<span class="sr-only">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %d: belief number */
								__( 'Principle %d', 'youumatter2' ),
								(int) ( $i + 1 )
							)
						);
						?>
					</span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
