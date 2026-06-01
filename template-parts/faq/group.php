<?php
/**
 * FAQ group renderer. One section per call; rendered inside a yum2FAQ
 * Alpine wrapper that exposes activeGroup, query, openKey, and helpers.
 *
 * Args:
 *   group array Required. Single group from yum2_faq_groups().
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args  = wp_parse_args( $args ?? array(), array( 'group' => array() ) );
$group = (array) $args['group'];
if ( empty( $group['id'] ) || empty( $group['items'] ) ) {
	return;
}

$gid = (string) $group['id'];
?>
<section
	id="faq-<?php echo esc_attr( $gid ); ?>"
	x-show="!query.trim() ? activeGroup === '<?php echo esc_js( $gid ); ?>' : true"
	class="mb-10 scroll-mt-24"
>
	<p class="text-terracotta tracking-[2px] uppercase mb-2" style="font-size:11px;font-weight:600;">
		<?php echo esc_html( $group['label'] ); ?>
	</p>
	<h2 class="text-forest mb-6" style="font-family:'Newsreader',serif;font-size:clamp(26px,3.2vw,36px);line-height:1.15;font-weight:400;letter-spacing:-0.01em;">
		<?php echo esc_html( $group['blurb'] ); ?>
	</h2>

	<div class="flex flex-col gap-3">
		<?php foreach ( $group['items'] as $i => $item ) : ?>
			<?php
			$key   = $gid . ':' . (int) $i;
			$match = $item['q'] . ' ' . $item['a'];
			?>
			<div
				x-show="matches('<?php echo esc_js( $match ); ?>')"
				x-cloak
				class="border border-forest/15 rounded-[16px] overflow-hidden"
			>
				<button
					type="button"
					@click="toggle('<?php echo esc_js( $key ); ?>')"
					:aria-expanded="isOpen('<?php echo esc_js( $key ); ?>') ? 'true' : 'false'"
					class="w-full flex items-center justify-between gap-4 px-5 md:px-6 py-4 md:py-5 text-left transition-colors hover:bg-forest/[0.02]"
				>
					<span class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(17px,1.8vw,20px);line-height:1.3;font-weight:500;">
						<?php echo esc_html( $item['q'] ); ?>
					</span>
					<span
						class="shrink-0 size-8 rounded-full border border-forest/20 flex items-center justify-center text-forest transition-transform"
						:class="isOpen('<?php echo esc_js( $key ); ?>') ? 'rotate-180 bg-sage-light border-forest' : ''"
					>
						<?php echo yum2_icon( 'arrow-down', array( 'size' => 14, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
				</button>
				<div
					x-show="isOpen('<?php echo esc_js( $key ); ?>')"
					x-cloak
					x-transition:enter="transition ease-out duration-300"
					x-transition:enter-start="opacity-0 -translate-y-1"
					x-transition:enter-end="opacity-100 translate-y-0"
					class="px-5 md:px-6 pb-5"
				>
					<div class="pt-1 border-t border-forest/10">
						<p class="text-[#3d4f3e] pt-4" style="font-size:15px;line-height:1.7;">
							<?php echo esc_html( $item['a'] ); ?>
						</p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
