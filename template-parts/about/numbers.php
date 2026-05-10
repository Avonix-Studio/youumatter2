<?php
/**
 * About: "A quiet track record" stats. 3 numbers, Customizer-editable.
 *
 * Each stat reads its value from get_theme_mod with a sensible default.
 * Set a value to 0 in the Customizer to hide that row.
 *
 * The number animates from 0 to its target via Alpine + IntersectionObserver
 * (yum2CountUp factory in main.js).
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stats_defaults = array(
	1 => array( 'value' => 3,   'suffix' => '+', 'label' => __( 'years walking alongside clients', 'youumatter2' ) ),
	2 => array( 'value' => 180, 'suffix' => '+', 'label' => __( 'hours of supervised clinical practice', 'youumatter2' ) ),
	3 => array( 'value' => 50,  'suffix' => '+', 'label' => __( 'individuals and couples supported', 'youumatter2' ) ),
);

$stats = array();
foreach ( $stats_defaults as $n => $d ) {
	$value = (int) get_theme_mod( 'yum2_about_stat_' . $n . '_value', $d['value'] );
	if ( $value <= 0 ) {
		continue;
	}
	$stats[] = array(
		'value'  => $value,
		'suffix' => (string) get_theme_mod( 'yum2_about_stat_' . $n . '_suffix', $d['suffix'] ),
		'label'  => (string) get_theme_mod( 'yum2_about_stat_' . $n . '_label', $d['label'] ),
	);
}

if ( empty( $stats ) ) {
	return;
}
?>
<section class="relative bg-sage-light px-5 md:px-8 py-20 md:py-40 overflow-hidden">
	<div class="relative max-w-6xl mx-auto text-center">
		<p class="text-forest tracking-[2px] uppercase mb-5" style="font-size:12px;font-weight:600;">
			<?php esc_html_e( 'A quiet track record', 'youumatter2' ); ?>
		</p>
		<h2 class="text-forest mb-12 md:mb-16" style="font-family:'Newsreader',serif;font-size:clamp(28px,4.4vw,48px);line-height:1.1;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
			<?php esc_html_e( "Numbers don't tell the whole story.", 'youumatter2' ); ?>
			<br>
			<em class="italic" style="color:#c07a5a;"><?php esc_html_e( 'But these ones are honest.', 'youumatter2' ); ?></em>
		</h2>

		<div class="grid grid-cols-1 md:grid-cols-<?php echo (int) count( $stats ); ?> gap-12 md:gap-24 text-left md:text-center">
			<?php foreach ( $stats as $stat ) : ?>
				<div x-data="yum2CountUp(<?php echo (int) $stat['value']; ?>)" x-init="init()">
					<p class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(96px,11vw,140px);line-height:0.95;letter-spacing:-0.04em;font-weight:500;">
						<span x-text="display"></span><span style="color:#c07a5a;"><?php echo esc_html( $stat['suffix'] ); ?></span>
					</p>
					<p class="text-forest/65 mt-3 max-w-[220px] mx-auto" style="font-size:14.5px;line-height:1.5;">
						<?php echo esc_html( $stat['label'] ); ?>
					</p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="h-px bg-forest/18 max-w-md mx-auto mt-14"></div>
		<p class="italic text-forest/65 mt-5" style="font-family:'Newsreader',serif;font-size:15px;">
			<?php esc_html_e( 'Updated honestly as the practice grows. Numbers will change. The care will not.', 'youumatter2' ); ?>
		</p>
	</div>
</section>
