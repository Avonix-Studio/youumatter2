<?php
/**
 * About marquee. Horizontal CSS-animated strip of attribute words.
 *
 * Pure CSS animation defined in tailwind.src.css under @layer components
 * as `.yum2-marquee` keyframes; no JS needed.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = array(
	__( 'Warm', 'youumatter2' ),
	__( 'Honest', 'youumatter2' ),
	__( 'Slow when it matters', 'youumatter2' ),
	__( 'Evidence-based', 'youumatter2' ),
	__( 'Person-first', 'youumatter2' ),
	__( 'Online & In-person', 'youumatter2' ),
	__( 'Pitampura, New Delhi', 'youumatter2' ),
	__( 'English', 'youumatter2' ),
	__( 'Hindi', 'youumatter2' ),
);
?>
<section class="relative bg-[#f8f3e9] border-y border-forest/10 overflow-hidden">
	<div class="relative h-[80px] flex items-center" style="mask-image: linear-gradient(to right, transparent, #000 10%, #000 90%, transparent); -webkit-mask-image: linear-gradient(to right, transparent, #000 10%, #000 90%, transparent);">
		<div class="yum2-marquee flex items-center gap-8 whitespace-nowrap">
			<?php
			// Render the list twice for a seamless 50%-translate loop.
			for ( $loop = 0; $loop < 2; $loop++ ) {
				foreach ( $items as $i => $word ) {
					$is_italic = ( ( $loop * count( $items ) + $i ) % 2 === 1 );
					?>
					<span class="flex items-center gap-8">
						<span
							class="<?php echo $is_italic ? 'italic text-terracotta' : 'text-forest'; ?>"
							style="font-family:'Newsreader',serif;font-size:22px;font-weight:400;letter-spacing:-0.005em;"
						>
							<?php echo esc_html( $word ); ?>
						</span>
						<span aria-hidden class="size-1.5 rounded-full bg-terracotta"></span>
					</span>
					<?php
				}
			}
			?>
		</div>
	</div>
</section>
