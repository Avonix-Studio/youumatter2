<?php
/**
 * Brand wordmark fallback. Rendered when no Customizer logo is set.
 *
 * Args:
 *   class string  Extra utility classes for the wrapper span. Default: ''.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args  = wp_parse_args( $args ?? array(), array( 'class' => '' ) );
$class = trim( 'font-heading text-forest tracking-tight ' . $args['class'] );
?>
<span class="<?php echo esc_attr( $class ); ?>" aria-label="youumatter2">
	<span class="italic text-terracotta">youu</span><span>matter</span><span class="text-terracotta">2</span>
</span>
