<?php
/**
 * Mid-content CTA. Auto-injected after the second h2 by
 * yum2_inject_mid_cta() filter on the_content.
 *
 * Wrapped in `not-prose` so the typography plugin doesn't restyle it.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="not-prose my-10 md:my-12">
	<div class="relative bg-sage-light/70 border border-forest/12 rounded-[22px] p-6 md:p-7 overflow-hidden">
		<div aria-hidden class="absolute -top-16 -right-16 w-[280px] h-[280px] rounded-full pointer-events-none" style="background:radial-gradient(circle at center, rgba(248,243,233,0.7) 0%, rgba(228,239,227,0) 65%);"></div>

		<div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
			<div class="max-w-lg">
				<p class="text-terracotta tracking-[2px] uppercase mb-2" style="font-size:11px;font-weight:600;">
					<?php esc_html_e( 'Reading for yourself?', 'youumatter2' ); ?>
				</p>
				<p class="italic text-forest" style="font-family:'Newsreader',serif;font-size:clamp(19px,2vw,23px);line-height:1.3;font-weight:500;">
					<?php esc_html_e( 'Reading is a start. A short conversation is the next one.', 'youumatter2' ); ?>
				</p>
			</div>
			<div class="shrink-0">
				<?php
				get_template_part(
					'template-parts/shared/book-button',
					null,
					array(
						'label'   => __( 'Book a free intro', 'youumatter2' ),
						'variant' => 'primary',
						'icon'    => true,
					)
				);
				?>
			</div>
		</div>
	</div>
</div>
