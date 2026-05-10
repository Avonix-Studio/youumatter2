<?php
/**
 * FAQ sidebar nav. Desktop: sticky vertical list. Mobile: horizontal
 * scrolling chips at the top of content.
 *
 * Args:
 *   groups array Required. Same shape as yum2_faq_groups().
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args   = wp_parse_args( $args ?? array(), array( 'groups' => array() ) );
$groups = (array) $args['groups'];
if ( empty( $groups ) ) {
	return;
}
?>
<aside class="lg:sticky lg:top-24 lg:self-start">
	<p class="hidden lg:block text-terracotta tracking-[2px] uppercase mb-4" style="font-size:11px;font-weight:600;">
		<?php esc_html_e( 'Categories', 'youumatter2' ); ?>
	</p>

	<nav class="hidden lg:flex flex-col gap-1" aria-label="<?php esc_attr_e( 'FAQ categories', 'youumatter2' ); ?>">
		<?php foreach ( $groups as $g ) : ?>
			<button
				type="button"
				@click="selectGroup('<?php echo esc_js( $g['id'] ); ?>')"
				:class="activeGroup === '<?php echo esc_js( $g['id'] ); ?>' && !query.trim() ? 'bg-sage-light text-forest' : 'text-forest/65 hover:bg-forest/5 hover:text-forest'"
				class="text-left px-4 py-3 rounded-[12px] transition-colors"
			>
				<p :style="activeGroup === '<?php echo esc_js( $g['id'] ); ?>' && !query.trim() ? 'font-weight:600' : 'font-weight:500'" style="font-size:14.5px;">
					<?php echo esc_html( $g['label'] ); ?>
				</p>
				<p class="mt-0.5 text-forest/65" style="font-size:12.5px;line-height:1.45;">
					<?php echo esc_html( $g['blurb'] ); ?>
				</p>
			</button>
		<?php endforeach; ?>
	</nav>

	<div class="lg:hidden -mx-5 px-5 overflow-x-auto" style="scrollbar-width:none;">
		<div class="flex gap-2 pb-1">
			<?php foreach ( $groups as $g ) : ?>
				<button
					type="button"
					@click="selectGroup('<?php echo esc_js( $g['id'] ); ?>')"
					:class="activeGroup === '<?php echo esc_js( $g['id'] ); ?>' && !query.trim() ? 'bg-forest border-forest text-cream' : 'bg-[#f8f3e9] border-forest/15 text-forest'"
					class="shrink-0 px-4 py-2.5 rounded-full border whitespace-nowrap transition-colors"
					style="font-size:13.5px;font-weight:500;"
				>
					<?php echo esc_html( $g['label'] ); ?>
				</button>
			<?php endforeach; ?>
		</div>
	</div>
</aside>
