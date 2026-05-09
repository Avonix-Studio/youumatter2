<?php
/**
 * Social icon row. Renders only socials with non-empty URLs in config.
 *
 * Args:
 *   class      string Extra utility classes for the wrapper.
 *   item_class string Extra utility classes per icon button.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args ?? array(),
	array(
		'class'      => 'flex gap-3',
		'item_class' => 'size-10 rounded-full border border-forest/20 flex items-center justify-center text-forest/70 hover:bg-forest hover:text-cream hover:border-forest transition-colors',
	)
);

$socials = array(
	'instagram' => __( 'Instagram', 'youumatter2' ),
	'linkedin'  => __( 'LinkedIn', 'youumatter2' ),
	'youtube'   => __( 'YouTube', 'youumatter2' ),
	'facebook'  => __( 'Facebook', 'youumatter2' ),
	'twitter'   => __( 'X', 'youumatter2' ),
);

$items = array();
foreach ( $socials as $key => $label ) {
	$url = (string) yum2_get_contact( $key );
	if ( '' === $url ) {
		continue;
	}
	$items[] = array(
		'key'   => $key,
		'label' => $label,
		'url'   => $url,
	);
}

if ( empty( $items ) ) {
	return;
}
?>
<div class="<?php echo esc_attr( $args['class'] ); ?>">
	<?php foreach ( $items as $item ) : ?>
		<a
			href="<?php echo esc_url( $item['url'] ); ?>"
			aria-label="<?php echo esc_attr( $item['label'] ); ?>"
			target="_blank"
			rel="noopener noreferrer"
			class="<?php echo esc_attr( $args['item_class'] ); ?>"
		>
			<?php echo yum2_icon( $item['key'], array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	<?php endforeach; ?>
</div>
