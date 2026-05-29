<?php
/**
 * Home: "Inside a session" 4-card grid.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! get_theme_mod( 'yum2_home_show_inside_session', true ) ) {
	return;
}

/* Copy lives in inc/content.php under 'inside_a_session'. */
$c     = yum2_content( 'inside_a_session' );
$items = $c['items'];
?>
<section class="relative bg-cream px-5 md:px-8 py-10 md:py-12 overflow-hidden">
	<div class="relative max-w-6xl mx-auto">
		<div class="flex items-baseline gap-3 md:gap-4 flex-wrap mb-6 md:mb-8">
			<span class="text-terracotta tracking-[2px] uppercase" style="font-size:11px;font-weight:600;">
				<?php echo esc_html( $c['label'] ); ?>
			</span>
			<span aria-hidden class="hidden md:inline-block h-px w-8 bg-forest/20"></span>
			<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(22px,2.4vw,30px);line-height:1.15;letter-spacing:-0.01em;font-weight:400;">
				<?php echo esc_html( $c['heading'] ); ?>
				<em class="italic" style="color:#c07a5a;font-weight:400;"><?php echo esc_html( $c['heading_em'] ); ?></em>
			</h2>
		</div>

		<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
			<?php foreach ( $items as $item ) : ?>
				<div class="group flex flex-col gap-3 bg-[#f8f3e9] border border-forest/15 rounded-[16px] p-5 md:p-5 hover:border-forest/35 transition-colors duration-300 yum2-reveal">
					<span class="shrink-0 size-10 rounded-full bg-sage-light flex items-center justify-center text-forest group-hover:bg-forest group-hover:text-cream transition-colors duration-300">
						<?php echo yum2_icon( $item['icon'], array( 'size' => 17, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<h3 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(16px,1.35vw,18px);line-height:1.2;letter-spacing:-0.005em;font-weight:500;">
						<?php echo esc_html( $item['label'] ); ?>
					</h3>
					<p class="text-[#3d4f3e]" style="font-size:13.5px;line-height:1.55;">
						<?php echo esc_html( $item['body'] ); ?>
					</p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
