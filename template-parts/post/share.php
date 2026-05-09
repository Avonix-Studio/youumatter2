<?php
/**
 * Share row. WhatsApp + Twitter/X intent + Copy-link with Alpine toast.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title    = get_the_title();
$url      = get_permalink();
$wa_text  = sprintf(
	/* translators: 1: post title, 2: permalink */
	__( '%1$s - %2$s', 'youumatter2' ),
	$title,
	$url
);
$wa_url   = 'https://wa.me/?text=' . rawurlencode( $wa_text );
$tw_url   = 'https://twitter.com/intent/tweet?text=' . rawurlencode( $title ) . '&url=' . rawurlencode( $url );
?>
<div class="not-prose mt-10 md:mt-12 py-5 border-y border-forest/12 flex items-center justify-between gap-4 flex-wrap" x-data="{ copied: false }">
	<span class="inline-flex items-center gap-2 text-forest/65" style="font-size:13px;">
		<?php echo yum2_icon( 'share-2', array( 'size' => 14, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php esc_html_e( 'Share this essay', 'youumatter2' ); ?>
	</span>

	<div class="flex items-center gap-2">
		<a
			aria-label="<?php esc_attr_e( 'Share on WhatsApp', 'youumatter2' ); ?>"
			href="<?php echo esc_url( $wa_url ); ?>"
			target="_blank" rel="noopener noreferrer"
			class="size-10 rounded-full border border-forest/20 text-forest flex items-center justify-center hover:bg-forest/5 hover:border-forest transition-colors"
		>
			<?php echo yum2_icon( 'message-circle', array( 'size' => 15, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
		<a
			aria-label="<?php esc_attr_e( 'Share on X', 'youumatter2' ); ?>"
			href="<?php echo esc_url( $tw_url ); ?>"
			target="_blank" rel="noopener noreferrer"
			class="size-10 rounded-full border border-forest/20 text-forest flex items-center justify-center hover:bg-forest/5 hover:border-forest transition-colors"
		>
			<?php echo yum2_icon( 'twitter', array( 'size' => 14, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
		<button
			type="button"
			aria-label="<?php esc_attr_e( 'Copy link', 'youumatter2' ); ?>"
			data-yum2-copy="<?php echo esc_attr( $url ); ?>"
			@click="window.yum2 && window.yum2.copyLink && window.yum2.copyLink($event.currentTarget); copied = true; setTimeout(() => copied = false, 1800)"
			class="inline-flex items-center gap-1.5 h-10 px-3 rounded-full border border-forest/20 text-forest hover:bg-forest/5 hover:border-forest transition-colors"
			style="font-size:12.5px;font-weight:600;"
		>
			<span x-show="!copied" x-cloak class="inline-flex items-center gap-1.5">
				<?php echo yum2_icon( 'copy', array( 'size' => 13, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php esc_html_e( 'Copy link', 'youumatter2' ); ?>
			</span>
			<span x-show="copied" x-cloak class="inline-flex items-center gap-1.5 text-forest">
				<?php echo yum2_icon( 'check', array( 'size' => 14, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php esc_html_e( 'Copied', 'youumatter2' ); ?>
			</span>
		</button>
	</div>
</div>
