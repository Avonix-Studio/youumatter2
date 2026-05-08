<?php
/**
 * Custom output helpers (yum2_*).
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline-SVG icon set. Lifted from lucide.dev so we don't ship the icon
 * library at runtime. Each phase adds icons here as new sections need them.
 *
 * @param string $name  Icon key.
 * @param array  $attrs Optional attributes (class, size, stroke).
 * @return string SVG markup, or empty string for unknown icons.
 */
function yum2_icon( $name, $attrs = array() ) {
	$attrs = wp_parse_args(
		$attrs,
		array(
			'class'  => '',
			'size'   => 18,
			'stroke' => 1.8,
		)
	);

	$paths = array(
		'calendar' => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>',
		'message'  => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
		'leaf'     => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19.2 2.96c1.4 9.3-3.4 15.7-8.2 17.04Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6"/>',
		'arrow-right' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'arrow-left'  => '<path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>',
		'plus'        => '<path d="M5 12h14"/><path d="M12 5v14"/>',
		'whatsapp'    => '<path fill="currentColor" stroke="none" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zM12.04 21.785h-.004a9.87 9.87 0 0 1-5.031-1.378l-.36-.214-3.741.981.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.889-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.892 6.994c-.003 5.45-4.437 9.885-9.886 9.885z"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$class = trim( 'yum2-icon ' . $attrs['class'] );

	return sprintf(
		'<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%2$s" stroke-linecap="round" stroke-linejoin="round" class="%3$s" aria-hidden="true">%4$s</svg>',
		(int) $attrs['size'],
		esc_attr( $attrs['stroke'] ),
		esc_attr( $class ),
		$paths[ $name ] // SVG markup is trusted (hardcoded above), not user input.
	);
}
