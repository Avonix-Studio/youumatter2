<?php
/**
 * Table of contents. Used in two contexts:
 *   - 'sidebar' (default): desktop sticky sidebar card
 *   - 'mobile':            collapsible bar at the top of mobile content
 *
 * Args:
 *   variant string 'sidebar' | 'mobile'
 *   toc     array  output of yum2_post_toc()['toc'] (or auto-fetched)
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args ?? array(),
	array(
		'variant' => 'sidebar',
		'toc'     => null,
	)
);

if ( null === $args['toc'] ) {
	$result      = yum2_post_toc();
	$args['toc'] = $result['toc'];
}

$toc = (array) $args['toc'];
if ( empty( $toc ) ) {
	return;
}

if ( 'mobile' === $args['variant'] ) :
	?>
	<div
		class="lg:hidden bg-white border border-forest/15 rounded-[20px] overflow-hidden shadow-[0_14px_30px_-18px_rgba(26,58,25,0.18)]"
		x-data="{ open: false }"
	>
		<button
			type="button"
			@click="open = !open"
			class="w-full flex items-center justify-between px-5 py-4 text-left"
			style="background:linear-gradient(135deg, #f8f3e9 0%, #ffffff 100%);"
			:aria-expanded="open ? 'true' : 'false'"
			aria-controls="yum2-mobile-toc-list"
		>
			<div class="flex items-center gap-3 min-w-0">
				<span aria-hidden class="shrink-0 size-10 rounded-full bg-sage-light flex items-center justify-center text-forest">
					<?php echo yum2_icon( 'list-ordered', array( 'size' => 16, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
				<div class="min-w-0">
					<p class="text-terracotta tracking-[0.14em] uppercase" style="font-size:10.5px;font-weight:700;">
						<?php esc_html_e( 'On this page', 'youumatter2' ); ?>
					</p>
					<p class="text-forest truncate" style="font-family:'Newsreader',serif;font-size:15px;font-weight:500;">
						<?php echo esc_html( $toc[0]['text'] ); ?>
					</p>
				</div>
			</div>
			<span class="shrink-0 inline-flex items-center gap-1 text-forest/65 ml-3" style="font-size:12px;">
				<?php echo esc_html( count( $toc ) ); ?>
				<span class="text-forest" :style="open ? 'transform:rotate(90deg)' : 'transform:rotate(0deg)'">
					<?php echo yum2_icon( 'chevron-right', array( 'size' => 14, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			</span>
		</button>

		<ul id="yum2-mobile-toc-list" x-show="open" x-cloak class="border-t border-forest/15 px-3 py-2 space-y-0.5 list-none m-0">
			<?php foreach ( $toc as $i => $item ) : ?>
				<li>
					<a
						href="#<?php echo esc_attr( $item['id'] ); ?>"
						@click="open = false"
						class="flex items-start gap-3 px-3 py-2.5 rounded-xl text-forest/65 hover:bg-forest/5 transition-colors"
						style="font-size:14px;line-height:1.45;font-weight:500;"
					>
						<span aria-hidden class="shrink-0 size-6 rounded-full bg-cream text-forest/65 flex items-center justify-center" style="font-size:11px;font-weight:600;">
							<?php echo (int) ( $i + 1 ); ?>
						</span>
						<span class="min-w-0 pt-0.5"><?php echo esc_html( $item['text'] ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
else :
	// Sidebar variant.
	?>
	<div class="bg-white border border-forest/15 rounded-[18px] p-5">
		<div class="flex items-center gap-2 mb-3">
			<span class="text-terracotta">
				<?php echo yum2_icon( 'list-ordered', array( 'size' => 13, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
			<p class="text-terracotta tracking-[0.14em] uppercase" style="font-size:11px;font-weight:700;">
				<?php esc_html_e( 'On this page', 'youumatter2' ); ?>
			</p>
		</div>
		<div class="h-px bg-forest/10 -mx-5 mb-3"></div>
		<ul class="space-y-1 list-none m-0 p-0">
			<?php foreach ( $toc as $item ) : ?>
				<li>
					<a
						href="#<?php echo esc_attr( $item['id'] ); ?>"
						class="group flex items-start gap-2 px-2 py-1.5 -mx-2 rounded-lg text-forest/65 hover:bg-forest/5 hover:text-forest transition-colors"
						style="font-size:13.5px;line-height:1.5;font-weight:500;"
					>
						<span aria-hidden class="mt-1 shrink-0 text-forest/40 group-hover:translate-x-0.5 transition-transform">
							<?php echo yum2_icon( 'chevron-right', array( 'size' => 12, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<span class="min-w-0"><?php echo esc_html( $item['text'] ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
