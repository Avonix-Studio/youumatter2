<?php
/**
 * Reusable Calendly book button.
 *
 * Args:
 *   label    string  Button text. Default: 'Book a Session'.
 *   class    string  Extra utility classes appended to the variant base.
 *   variant  string  'primary' (default) | 'outline'.
 *   icon     bool    Prepend a small calendar icon. Default false.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args ?? array(),
	array(
		'label'   => __( 'Book a Session', 'youumatter2' ),
		'class'   => '',
		'variant' => 'primary',
		'icon'    => false,
	)
);

$calendly = (string) yum2_get_contact( 'calendly_url' );

$variants = array(
	'primary' => 'bg-[#2b5329] hover:bg-[#1f3d1e] text-white shadow-[0_10px_24px_rgba(26,58,25,0.18)]',
	'outline' => 'bg-transparent border-2 border-[rgba(43,83,41,0.25)] hover:border-[#2b5329] text-[#2b5329]',
);
$variant_class = isset( $variants[ $args['variant'] ] ) ? $variants[ $args['variant'] ] : $variants['primary'];

$class = trim(
	'inline-flex items-center justify-center gap-2 rounded-full h-[52px] px-6 transition-colors font-body ' .
	$variant_class . ' ' . $args['class']
);

$onclick = '' !== $calendly
	? sprintf( 'return yum2OpenCalendly(%s)', wp_json_encode( $calendly ) )
	: 'return false';
?>
<button
	type="button"
	class="<?php echo esc_attr( $class ); ?>"
	style="font-size:15px;font-weight:600;"
	onclick="<?php echo esc_attr( $onclick ); ?>"
	<?php if ( '' === $calendly ) : ?>aria-disabled="true"<?php endif; ?>
>
	<?php if ( $args['icon'] ) : ?>
		<?php echo yum2_icon( 'calendar', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php endif; ?>
	<span><?php echo esc_html( $args['label'] ); ?></span>
</button>
