<?php
/**
 * Blog hero band. Eyebrow + H1 with italic-terracotta accent + subtitle.
 *
 * Args:
 *   eyebrow  string Small uppercase line above the title.
 *   title    string Main H1 text (rendered straight).
 *   accent   string Italic terracotta accent appended to the title.
 *   subtitle string Italic line under the title.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'  => __( 'The Journal', 'youumatter2' ),
		'title'    => __( 'Notes on therapy,', 'youumatter2' ),
		'accent'   => __( 'mind & meaning.', 'youumatter2' ),
		'subtitle' => __( 'Honest writing on anxiety, relationships, parenting, and what it actually means to feel okay again.', 'youumatter2' ),
	)
);
?>
<section class="relative bg-cream px-5 md:px-8 pt-12 md:pt-20 pb-8 md:pb-12 overflow-hidden">
	<div aria-hidden class="absolute -top-40 -left-40 w-[560px] h-[560px] rounded-full pointer-events-none" style="background:radial-gradient(circle at center, rgba(228,239,227,0.7) 0%, rgba(242,237,227,0) 65%);"></div>

	<div class="relative max-w-6xl mx-auto">
		<p class="text-terracotta tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;">
			<?php echo esc_html( $args['eyebrow'] ); ?>
		</p>
		<h1 class="text-forest max-w-4xl" style="font-family:'Newsreader',serif;font-size:clamp(36px,6vw,72px);line-height:1.02;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
			<?php echo esc_html( $args['title'] ); ?>
			<em class="italic" style="color:#c07a5a;font-weight:400;"><?php echo esc_html( $args['accent'] ); ?></em>
		</h1>
		<?php if ( ! empty( $args['subtitle'] ) ) : ?>
			<p class="italic text-forest/65 mt-5 max-w-2xl" style="font-family:'Newsreader',serif;font-size:19px;line-height:1.55;">
				<?php echo esc_html( $args['subtitle'] ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>
