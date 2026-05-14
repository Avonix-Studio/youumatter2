<?php
/**
 * Block render: yum2/pullquote
 *
 * Called by WordPress whenever this block appears in a page or post.
 * $attributes is automatically populated from block.json attribute definitions.
 *
 * @var array $attributes Block attributes: quote, attribution.
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$quote       = isset( $attributes['quote'] ) ? trim( $attributes['quote'] ) : '';
$attribution = isset( $attributes['attribution'] ) ? trim( $attributes['attribution'] ) : '';

if ( ! $quote ) {
	return;
}
?>
<figure class="my-10 px-6 py-8 bg-sage-light/30 border-y border-forest/10 text-center" style="margin-left:0;margin-right:0;">
	<blockquote class="m-0 p-0 border-0">
		<p class="italic text-forest m-0" style="font-family:'Newsreader',Georgia,serif;font-size:1.375rem;line-height:1.45;font-weight:400;">
			<?php echo wp_kses_post( $quote ); ?>
		</p>
	</blockquote>
	<?php if ( $attribution ) : ?>
		<figcaption class="mt-4 not-italic text-terracotta uppercase tracking-widest" style="font-size:0.75rem;font-weight:600;">
			<?php echo esc_html( $attribution ); ?>
		</figcaption>
	<?php endif; ?>
</figure>
