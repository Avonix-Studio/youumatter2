<?php
/**
 * Contact: closing soft CTA strip.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="bg-sage-light border-y border-forest/10">
	<div class="max-w-6xl mx-auto px-5 md:px-8 py-10 md:py-14 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
		<div class="max-w-xl">
			<p class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(22px,3vw,30px);line-height:1.2;font-weight:400;">
				<?php esc_html_e( 'Prefer to just book a first session?', 'youumatter2' ); ?>
			</p>
			<p class="text-forest/65 mt-2" style="font-size:14.5px;">
				<?php esc_html_e( "It's a 50-minute conversation. No diagnosis, no pressure to continue.", 'youumatter2' ); ?>
			</p>
		</div>
		<?php
		get_template_part(
			'template-parts/shared/book-button',
			null,
			array(
				'label'   => __( 'Book a session', 'youumatter2' ),
				'variant' => 'primary',
				'icon'    => true,
			)
		);
		?>
	</div>
</section>
