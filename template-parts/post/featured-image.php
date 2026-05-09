<?php
/**
 * Single-post featured image card. Renders only when a thumbnail exists.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! has_post_thumbnail() ) {
	return;
}

$caption = get_the_post_thumbnail_caption();
?>
<section class="relative px-5 md:px-8 pt-6 md:pt-8 pb-8 md:pb-10 bg-white">
	<div class="max-w-6xl mx-auto">
		<figure class="rounded-[22px] overflow-hidden border border-forest/15 bg-sage-light">
			<?php
			the_post_thumbnail(
				'yum2-blog-featured',
				array(
					'class'   => 'w-full h-auto block',
					'loading' => 'eager',
				)
			);
			?>
		</figure>
		<?php if ( '' !== $caption ) : ?>
			<p class="italic text-forest/65 text-center mt-3" style="font-family:'Newsreader',serif;font-size:14px;">
				<?php echo esc_html( $caption ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>
