<?php
/**
 * Single-post featured image, rendered INLINE at the top of the article column
 * (no longer a full-width section). Sits inside the article's max-w-[680px]
 * column and stays constrained so it never overpowers the copy.
 *
 * When no featured image is set, falls back to a sage-light "Essay illustration"
 * placeholder so the article always has a visual anchor at the top.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$has_thumb = has_post_thumbnail();
$caption   = $has_thumb ? get_the_post_thumbnail_caption() : '';
?>
<div class="w-full rounded-[16px] overflow-hidden border border-forest/15 relative flex items-center justify-center mb-8 md:mb-10 bg-sage-light"
	style="aspect-ratio:5/2;max-height:280px;">
	<?php if ( $has_thumb ) : ?>
		<?php
		the_post_thumbnail(
			'yum2-blog-featured',
			array(
				'class'   => 'w-full h-full object-cover',
				'loading' => 'eager',
			)
		);
		?>
	<?php else : ?>
		<span class="italic text-forest/50" style="font-family:'Newsreader',serif;font-size:13px;">
			<?php esc_html_e( 'Essay illustration', 'youumatter2' ); ?>
		</span>
	<?php endif; ?>
</div>
<?php if ( '' !== $caption ) : ?>
	<p class="italic text-[#3d4f3e] text-center mb-8 md:mb-10" style="font-family:'Newsreader',serif;font-size:14px;">
		<?php echo esc_html( $caption ); ?>
	</p>
<?php endif; ?>
