<?php
/**
 * Single-post sidebar (desktop only, lg:block). TOC + author + need-to-talk + tags.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tags = get_the_tags();
?>
<aside class="hidden lg:block">
	<div class="sticky top-24 flex flex-col gap-5">
		<?php get_template_part( 'template-parts/post/toc', null, array( 'variant' => 'sidebar' ) ); ?>

		<?php get_template_part( 'template-parts/post/author-bio', null, array( 'compact' => true ) ); ?>

		<?php
		$calendly = (string) yum2_get_contact( 'calendly_url' );
		$onclick  = '' !== $calendly ? sprintf( 'return yum2OpenCalendly(%s)', wp_json_encode( $calendly ) ) : 'return false';
		?>
		<div class="relative bg-sage-light border border-forest/15 rounded-[18px] p-5 overflow-hidden">
			<div aria-hidden class="absolute -top-12 -right-12 w-[180px] h-[180px] rounded-full pointer-events-none" style="background:radial-gradient(circle at center, rgba(248,243,233,0.7) 0%, rgba(228,239,227,0) 65%);"></div>
			<div class="relative">
				<p class="text-terracotta tracking-[2px] uppercase mb-2" style="font-size:10.5px;font-weight:600;">
					<?php esc_html_e( 'Need to talk?', 'youumatter2' ); ?>
				</p>
				<p class="italic text-forest mb-4" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.35;font-weight:500;">
					<?php esc_html_e( 'A short conversation is often the next step.', 'youumatter2' ); ?>
				</p>
				<button
					type="button"
					class="inline-flex items-center gap-1.5 bg-forest hover:bg-forest/90 text-cream rounded-full h-[40px] px-4 transition-colors"
					style="font-size:13px;font-weight:600;"
					onclick="<?php echo esc_attr( $onclick ); ?>"
				>
					<?php esc_html_e( 'Book a free intro', 'youumatter2' ); ?>
					<?php echo yum2_icon( 'arrow-right', array( 'size' => 13, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
			</div>
		</div>

		<?php if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
			<div class="bg-white border border-forest/15 rounded-[18px] p-5">
				<div class="flex items-center gap-2 mb-3">
					<span class="text-terracotta">
						<?php echo yum2_icon( 'hash', array( 'size' => 13, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<p class="text-terracotta tracking-[0.14em] uppercase" style="font-size:11px;font-weight:700;">
						<?php esc_html_e( 'Topics', 'youumatter2' ); ?>
					</p>
				</div>
				<div class="h-px bg-forest/10 -mx-5 mb-3"></div>
				<div class="flex flex-wrap gap-2">
					<?php foreach ( $tags as $t ) : $hashtag = '#' . preg_replace( '/\s+/', '', $t->name ); ?>
						<a
							href="<?php echo esc_url( get_tag_link( $t ) ); ?>"
							class="inline-flex items-center bg-cream hover:bg-sage-light/60 text-forest/65 hover:text-forest border border-forest/15 rounded-full px-3 py-1 transition-colors"
							style="font-size:12.5px;font-weight:500;"
						>
							<?php echo esc_html( $hashtag ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</aside>
