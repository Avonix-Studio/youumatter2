<?php
/**
 * Desktop floating TOC pill (bottom-right). Mirrors the React FloatingTOC
 * design: forest pill with a circular progress ring + section label,
 * expandable list of sections.
 *
 * Renders only on lg+ screens with at least one h2 in the post.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$result = yum2_post_toc();
$toc    = $result['toc'];
if ( empty( $toc ) ) {
	return;
}

// Pre-encode to safely embed in Alpine x-data.
$items_json = wp_json_encode(
	array_map(
		static function ( $item ) {
			return array(
				'id'   => $item['id'],
				'text' => $item['text'],
			);
		},
		$toc
	)
);
?>
<div
	x-data='yum2FloatingToc(<?php echo esc_attr( $items_json ); ?>)'
	x-init="init()"
	@click.outside="open = false"
	@keydown.escape.window="open = false"
	class="hidden lg:block fixed bottom-8 right-8 z-30"
>
	<div
		x-show="open"
		x-cloak
		x-transition.opacity.duration.150ms
		class="absolute bottom-[64px] right-0 w-[340px] bg-white border border-forest/15 rounded-[18px] shadow-[0_24px_60px_-20px_rgba(26,58,25,0.35)] overflow-hidden"
	>
		<div class="flex items-center justify-between px-5 py-4 bg-[#f8f3e9] border-b border-forest/15">
			<div class="flex items-center gap-2">
				<span class="text-terracotta">
					<?php echo yum2_icon( 'list-ordered', array( 'size' => 14, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
				<p class="text-terracotta tracking-[0.14em] uppercase" style="font-size:11px;font-weight:700;">
					<?php esc_html_e( 'On this page', 'youumatter2' ); ?>
				</p>
			</div>
			<button
				type="button"
				@click="open = false"
				aria-label="<?php esc_attr_e( 'Close', 'youumatter2' ); ?>"
				class="size-7 rounded-full flex items-center justify-center text-forest/65 hover:bg-forest/5 hover:text-forest transition-colors"
			>
				<?php echo yum2_icon( 'x', array( 'size' => 14, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>

		<ul class="max-h-[360px] overflow-y-auto p-2 list-none m-0">
			<template x-for="(item, i) in items" :key="item.id">
				<li>
					<a
						:href="'#' + item.id"
						@click="open = false"
						:class="i === current ? 'bg-sage-light text-forest' : 'text-forest/65 hover:bg-forest/5'"
						class="flex items-start gap-3 px-3 py-2.5 rounded-xl transition-colors"
						style="font-size:13.5px;line-height:1.45;font-weight:500;"
					>
						<span
							:class="i === current ? 'bg-forest text-cream' : 'bg-cream text-forest/65'"
							class="shrink-0 size-6 rounded-full flex items-center justify-center"
							style="font-size:11px;font-weight:600;"
							x-text="i + 1"
						></span>
						<span class="min-w-0 pt-0.5" x-text="item.text"></span>
					</a>
				</li>
			</template>
		</ul>
	</div>

	<button
		type="button"
		@click="open = !open"
		:aria-expanded="open ? 'true' : 'false'"
		aria-label="<?php esc_attr_e( 'Open table of contents', 'youumatter2' ); ?>"
		class="group relative inline-flex items-center gap-3 bg-forest hover:bg-forest/90 text-cream rounded-full h-[52px] pl-4 pr-5 shadow-[0_18px_40px_-12px_rgba(26,58,25,0.45)] transition-colors"
	>
		<span class="relative size-8">
			<svg viewBox="0 0 36 36" class="size-full -rotate-90" aria-hidden>
				<circle cx="18" cy="18" r="15" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="3"></circle>
				<circle
					cx="18"
					cy="18"
					r="15"
					fill="none"
					stroke="#c07a5a"
					stroke-width="3"
					stroke-linecap="round"
					:stroke-dasharray="`${(2 * Math.PI * 15 * progress)} ${2 * Math.PI * 15}`"
				></circle>
			</svg>
			<span class="absolute inset-0 flex items-center justify-center" style="font-size:11px;font-weight:600;" x-text="current + 1"></span>
		</span>
		<span class="flex flex-col items-start leading-tight">
			<span class="tracking-[0.14em] uppercase text-cream/65" style="font-size:9.5px;font-weight:700;">
				<?php esc_html_e( 'Section', 'youumatter2' ); ?>
				<span x-text="current + 1"></span>
				/
				<span x-text="items.length"></span>
			</span>
			<span class="truncate max-w-[180px]" style="font-size:13px;font-weight:600;" x-text="items[current] ? items[current].text : ''"></span>
		</span>
	</button>
</div>
