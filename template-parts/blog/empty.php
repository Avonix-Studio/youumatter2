<?php
/**
 * No-results state for the blog index, archives, and search.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_url = get_permalink( (int) get_option( 'page_for_posts' ) );
if ( ! $blog_url ) {
	$blog_url = home_url( '/' );
}
?>
<div class="md:col-span-2 lg:col-span-3">
	<div class="bg-white border border-forest/15 rounded-[22px] p-10 text-center">
		<p class="italic text-forest/65 mb-5" style="font-family:'Newsreader',serif;font-size:18px;">
			<?php esc_html_e( 'Nothing here yet. Try another category or a different word.', 'youumatter2' ); ?>
		</p>
		<a href="<?php echo esc_url( $blog_url ); ?>" class="inline-flex items-center gap-1.5 text-forest hover:text-forest/80" style="font-size:14px;font-weight:600;">
			<?php esc_html_e( 'See all essays', 'youumatter2' ); ?>
			<?php echo yum2_icon( 'arrow-right', array( 'size' => 14, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	</div>
</div>
