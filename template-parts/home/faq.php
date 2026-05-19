<?php
/**
 * Home: 6-question FAQ accordion.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! get_theme_mod( 'yum2_home_show_faq', true ) ) {
	return;
}

/* Copy lives in inc/content.php under 'faq'. */
$c         = yum2_content( 'faq' );
$questions = $c['items'];
$faq_url   = home_url( '/faq/' );
?>
<section class="relative bg-[#f8f3e9] px-5 md:px-8 pt-14 md:pt-20 pb-14 md:pb-20 overflow-hidden">
	<div class="relative max-w-5xl mx-auto" x-data="{ open: null }">

		<div class="grid grid-cols-1 md:grid-cols-[1fr_1.2fr] gap-5 md:gap-14 items-end mb-10 md:mb-12">
			<div>
				<p class="text-terracotta tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;">
					<?php echo esc_html( $c['label'] ); ?>
				</p>
				<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(30px,4.6vw,50px);line-height:1.08;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
					<?php echo esc_html( $c['heading'] ); ?>
					<em class="italic" style="color:#c07a5a;font-weight:400;"><?php echo esc_html( $c['heading_em'] ); ?></em>
				</h2>
			</div>
			<p class="italic text-forest/65" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;">
				<?php echo esc_html( $c['description'] ); ?>
			</p>
		</div>

		<div class="border-t border-forest/12">
			<?php foreach ( $questions as $i => $item ) : ?>
				<div class="border-b border-forest/12">
					<button
						type="button"
						@click="open = open === <?php echo (int) $i; ?> ? null : <?php echo (int) $i; ?>"
						:aria-expanded="open === <?php echo (int) $i; ?> ? 'true' : 'false'"
						class="w-full flex items-center justify-between gap-6 py-5 md:py-6 text-left group"
					>
						<span class="text-forest flex-1" style="font-family:'Newsreader',serif;font-size:clamp(18px,1.7vw,22px);line-height:1.25;letter-spacing:-0.01em;font-weight:500;">
							<?php echo esc_html( $item['q'] ); ?>
						</span>
						<span
							class="shrink-0 size-9 rounded-full border border-forest/20 flex items-center justify-center text-forest transition-all duration-300 group-hover:border-forest group-hover:bg-forest/5"
							:style="open === <?php echo (int) $i; ?> ? 'transform: rotate(45deg)' : 'transform: rotate(0deg)'"
						>
							<?php echo yum2_icon( 'plus', array( 'size' => 16, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					</button>
					<div
						x-show="open === <?php echo (int) $i; ?>"
						x-cloak
						x-transition:enter="transition ease-out duration-300"
						x-transition:enter-start="opacity-0 -translate-y-1"
						x-transition:enter-end="opacity-100 translate-y-0"
						class="overflow-hidden"
					>
						<p class="text-forest/65 pb-6 pr-12" style="font-size:16px;line-height:1.65;">
							<?php echo esc_html( $item['a'] ); ?>
						</p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mt-10 md:mt-12">
			<p class="italic text-forest/65" style="font-family:'Newsreader',serif;font-size:17px;">
				<?php echo esc_html( $c['footer_note'] ); ?>
			</p>
			<div class="flex flex-wrap gap-3">
				<a
					href="<?php echo esc_url( $faq_url ); ?>"
					class="inline-flex items-center justify-center bg-transparent border-2 border-forest/25 hover:border-forest text-forest rounded-full h-[48px] px-6 transition-colors"
					style="font-size:14px;font-weight:600;"
				>
					<?php echo esc_html( $c['btn_all_faqs'] ); ?>
					<span aria-hidden class="ml-1.5">&rarr;</span>
				</a>
				<a
					href="<?php echo esc_url( yum2_whatsapp_url( $c['whatsapp_msg'] ) ); ?>"
					target="_blank" rel="noopener noreferrer"
					class="inline-flex items-center justify-center bg-forest hover:bg-forest/90 text-cream rounded-full h-[48px] px-6 transition-colors shadow-[0_10px_24px_rgba(26,58,25,0.16)]"
					style="font-size:14px;font-weight:600;"
				>
					<?php echo esc_html( $c['btn_whatsapp'] ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
