<?php
/**
 * Brand wordmark fallback. Rendered when no Customizer logo is set.
 *
 * Args:
 *   class string  Extra utility classes for the wrapper span. Default size
 *                 is text-xl on mobile / text-2xl on desktop; pass an
 *                 explicit text-* class to override.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = isset( $args ) && is_array( $args ) ? $args : array();
$args = wp_parse_args(
	$args,
	array(
		'class' => 'text-xl md:text-2xl',
	)
);

$class = trim( 'font-heading text-forest tracking-tight inline-block whitespace-nowrap leading-none ' . $args['class'] );
?>
<span class="<?php echo esc_attr( $class ); ?>" aria-label="youumatter2">
	<span class="italic text-terracotta">youu</span><span class="text-forest">matter</span><span class="text-terracotta">2</span>
</span>
