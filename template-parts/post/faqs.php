<?php
/**
 * Single post: "Quick answers on this topic" accordion.
 *
 * Reads the `post_faqs` ACF repeater on the current post. Renders nothing
 * when the repeater is empty. Schema for these Q&A is emitted separately by
 * yum2_seo_post_faq_jsonld() in inc/seo.php.
 *
 * Edit per-post FAQs in the post editor under the "Post FAQs" metabox.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rows = function_exists( 'get_field' ) ? get_field( 'post_faqs' ) : array();
if ( empty( $rows ) || ! is_array( $rows ) ) {
	return;
}

/* Filter out blank rows so the section only appears when there's content. */
$items = array();
foreach ( $rows as $row ) {
	$q = isset( $row['question'] ) ? trim( (string) $row['question'] ) : '';
	$a = isset( $row['answer'] ) ? trim( (string) $row['answer'] ) : '';
	if ( '' === $q || '' === $a ) {
		continue;
	}
	$items[] = array( 'q' => $q, 'a' => $a );
}
if ( empty( $items ) ) {
	return;
}

$intro = (string) ( function_exists( 'get_field' ) ? get_field( 'post_faqs_intro' ) : '' );
if ( '' === $intro ) {
	$intro = __( 'Quick answers on this topic.', 'youumatter2' );
}
?>
<section class="not-prose mt-12 md:mt-14 mb-4" x-data="{ open: null }">
	<p class="text-terracotta tracking-[2px] uppercase mb-3" style="font-size:11px;font-weight:600;">
		<?php esc_html_e( 'Quick answers', 'youumatter2' ); ?>
	</p>
	<h2 class="text-forest mb-6" style="font-family:'Newsreader',serif;font-size:clamp(22px,2.4vw,30px);line-height:1.2;letter-spacing:-0.01em;font-weight:500;">
		<?php echo esc_html( $intro ); ?>
	</h2>

	<div class="flex flex-col gap-3">
		<?php foreach ( $items as $i => $item ) : ?>
			<div class="border border-forest/15 rounded-[16px] overflow-hidden">
				<button
					type="button"
					@click="open = open === <?php echo (int) $i; ?> ? null : <?php echo (int) $i; ?>"
					:aria-expanded="open === <?php echo (int) $i; ?> ? 'true' : 'false'"
					aria-controls="post-faq-panel-<?php echo (int) $i; ?>"
					class="w-full flex items-center justify-between gap-4 px-5 md:px-6 py-4 md:py-5 text-left transition-colors hover:bg-forest/[0.02]"
				>
					<span class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(16px,1.6vw,18.5px);line-height:1.35;font-weight:500;">
						<?php echo esc_html( $item['q'] ); ?>
					</span>
					<span
						class="shrink-0 size-8 rounded-full border border-forest/20 flex items-center justify-center text-forest transition-transform"
						:class="open === <?php echo (int) $i; ?> ? 'rotate-180 bg-sage-light border-forest' : ''"
					>
						<?php echo yum2_icon( 'arrow-down', array( 'size' => 14, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
				</button>
				<div
					id="post-faq-panel-<?php echo (int) $i; ?>"
					x-show="open === <?php echo (int) $i; ?>"
					x-cloak
					x-transition:enter="transition ease-out duration-300"
					x-transition:enter-start="opacity-0 -translate-y-1"
					x-transition:enter-end="opacity-100 translate-y-0"
					class="px-5 md:px-6 pb-5"
				>
					<div class="pt-1 border-t border-forest/10">
						<p class="text-[#3d4f3e] pt-4" style="font-size:15px;line-height:1.65;">
							<?php echo esc_html( $item['a'] ); ?>
						</p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
