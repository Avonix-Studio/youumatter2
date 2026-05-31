<?php
/**
 * About hero. Sage-light section with three columns: "Hi," + portrait
 * (organic blob clip) + "I'm Sanya!" headline.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$portrait = get_template_directory_uri() . '/assets/images/sanya-portrait.jpg';
?>
<section class="relative bg-sage-light overflow-hidden">
	<div aria-hidden class="absolute -top-40 -left-40 w-[640px] h-[640px] rounded-full pointer-events-none yum2-blob-drift" style="background:radial-gradient(circle at center, rgba(228,239,227,0.95) 0%, rgba(200,220,199,0) 65%);"></div>

	<div class="relative max-w-6xl mx-auto px-5 md:px-8 pt-12 md:pt-20 pb-16 md:pb-24 min-h-[90vh] md:min-h-[100vh] flex flex-col justify-center">
		<div class="grid grid-cols-1 md:grid-cols-[1fr_1.05fr_1fr] md:items-center gap-6 md:gap-4">

			<div class="order-1 md:order-1">
				<h1 class="yum2-reveal text-forest" style="font-family:'Newsreader',serif;font-size:clamp(72px,13vw,144px);line-height:0.9;letter-spacing:-0.04em;font-weight:400;transition-delay:0s;">
					<?php esc_html_e( 'Hi,', 'youumatter2' ); ?>
				</h1>

				<div class="yum2-reveal mt-6" style="transition-delay:0.6s;">
					<span class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-forest/12 rounded-full px-3.5 py-1.5">
						<span class="size-2 rounded-full bg-forest" aria-hidden></span>
						<span class="text-forest" style="font-size:12px;font-weight:600;letter-spacing:0.04em;">
							<?php esc_html_e( 'Counselling Psychologist', 'youumatter2' ); ?>
						</span>
					</span>
				</div>

				<p class="yum2-reveal italic text-[#3d4f3e] mt-4 max-w-xs" style="font-family:'Newsreader',serif;font-size:16px;line-height:1.5;transition-delay:0.7s;">
					<?php esc_html_e( 'Specialising in relationships, intimacy, and the emotional weight of being a person right now.', 'youumatter2' ); ?>
				</p>
			</div>

			<div class="order-2 md:order-2 relative mx-auto w-full max-w-[420px] md:max-w-[480px]">
				<div class="yum2-portrait-scale-in relative" style="aspect-ratio: 1 / 1.05;">
					<div class="absolute inset-0 overflow-hidden" style="border-radius: 62% 38% 54% 46% / 48% 60% 40% 52%; box-shadow: 0 30px 60px -20px rgba(19,60,20,0.28);">
						<img src="<?php echo esc_url( $portrait ); ?>" alt="<?php esc_attr_e( 'Sanya Oberoi, Counselling Psychologist', 'youumatter2' ); ?>" class="w-full h-full object-cover" loading="eager" decoding="async">
					</div>
					<svg aria-hidden viewBox="0 0 60 80" class="absolute -top-4 -right-2 md:-top-6 md:-right-6 yum2-leaf-rock-slow" width="56" height="74">
						<path d="M30 4 C 50 16, 56 44, 30 76 C 4 44, 10 16, 30 4 Z" fill="#c07a5a" opacity="0.9"/>
						<path d="M30 12 L30 68" stroke="#fff" stroke-width="0.8" opacity="0.55"/>
					</svg>
				</div>

				<?php
				/* Scroll badge: circular "SCROLL · DOWN" textPath that rotates around a
				   stationary centre arrow. Hidden on small phones where it crowds the
				   portrait edge. */
				?>
				<div aria-hidden class="hidden sm:block pointer-events-none absolute -right-6 -bottom-6 md:-right-10 md:-bottom-8 size-[88px] md:size-[120px]">
					<svg viewBox="0 0 120 120" class="yum2-scroll-badge w-full h-full" width="120" height="120">
						<defs>
							<path id="yum2-scroll-circle" d="M 60,60 m -48,0 a 48,48 0 1,1 96,0 a 48,48 0 1,1 -96,0"/>
						</defs>
						<text fill="#1a3a19" style="font-family:'Plus Jakarta Sans',sans-serif;font-size:10.5px;font-weight:600;letter-spacing:0.18em;">
							<textPath href="#yum2-scroll-circle" startOffset="0">SCROLL · DOWN · SCROLL · DOWN · </textPath>
						</text>
					</svg>
					<span class="absolute inset-0 flex items-center justify-center">
						<span class="size-9 rounded-full bg-forest text-white flex items-center justify-center">
							<?php echo yum2_icon( 'arrow-down', array( 'size' => 14, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					</span>
				</div>
			</div>

			<div class="order-3 md:order-3 md:text-right">
				<h2 class="yum2-reveal text-forest" style="font-family:'Newsreader',serif;font-size:clamp(56px,11vw,120px);line-height:0.92;letter-spacing:-0.03em;font-weight:400;transition-delay:0.4s;">
					<?php esc_html_e( "I'm", 'youumatter2' ); ?>
					<br>
					<em class="italic" style="color:#c07a5a;"><?php esc_html_e( 'Sanya', 'youumatter2' ); ?></em>
					<span aria-hidden>!</span>
				</h2>
			</div>
		</div>
	</div>
</section>
