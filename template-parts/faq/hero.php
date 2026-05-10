<?php
/**
 * FAQ hero. Sage-light section with title, subtitle, and search input.
 *
 * The search input is bound (via x-model) to the parent yum2FAQ Alpine
 * scope opened in page-faq.php.
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
			<?php esc_html_e( 'FAQ', 'youumatter2' ); ?>
		</p>
		<h1 class="text-forest max-w-3xl" style="font-family:'Newsreader',serif;font-size:clamp(36px,5.6vw,64px);line-height:1.05;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
			<?php esc_html_e( 'Questions,', 'youumatter2' ); ?>
			<em class="italic" style="color:#c07a5a;font-weight:400;"><?php esc_html_e( 'answered.', 'youumatter2' ); ?></em>
		</h1>
		<p class="italic text-forest/65 mt-5 max-w-2xl" style="font-family:'Newsreader',serif;font-size:19px;line-height:1.55;">
			<?php esc_html_e( "The things people ask most often before they reach out. If something you're wondering isn't here, just send a note.", 'youumatter2' ); ?>
		</p>

		<div class="mt-8 max-w-xl relative">
			<span aria-hidden class="absolute left-5 top-1/2 -translate-y-1/2 text-forest/65">
				<?php echo yum2_icon( 'search', array( 'size' => 16, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
			<label class="sr-only" for="yum2-faq-search"><?php esc_html_e( 'Search the FAQs', 'youumatter2' ); ?></label>
			<input
				id="yum2-faq-search"
				type="text"
				x-model.debounce.150ms="query"
				placeholder="<?php esc_attr_e( 'Search the FAQs…', 'youumatter2' ); ?>"
				class="w-full bg-[#f8f3e9] border border-forest/15 rounded-full pl-12 pr-5 h-[52px] text-ink placeholder:text-forest/65 outline-none focus:border-forest transition-colors"
				style="font-size:14.5px;"
			>
		</div>
	</div>
</section>
