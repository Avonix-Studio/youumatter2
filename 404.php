<?php
/**
 * 404 page. Header + centered message + three CTAs + search.
 *
 * No gentle-invitation block; keep this page light.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$blog_url = get_permalink( (int) get_option( 'page_for_posts' ) );
if ( ! $blog_url ) {
	$blog_url = home_url( '/' );
}
?>

<main id="primary" class="site-main">
	<section class="bg-cream min-h-[60vh] flex items-center justify-center px-5 py-20 md:py-28">
		<div class="max-w-xl text-center">
			<p class="text-terracotta tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;">
				<?php esc_html_e( '404', 'youumatter2' ); ?>
			</p>
			<h1 class="text-forest mb-4" style="font-family:'Newsreader',serif;font-size:clamp(36px,5vw,56px);line-height:1.1;font-weight:400;text-wrap:balance;">
				<?php esc_html_e( 'Page not found.', 'youumatter2' ); ?>
				<em class="italic block mt-2" style="color:#c07a5a;font-weight:400;">
					<?php esc_html_e( "Sometimes we lose our way. That's okay.", 'youumatter2' ); ?>
				</em>
			</h1>
			<p class="italic text-forest/65 mb-8" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;">
				<?php esc_html_e( 'Try a search, or pick one of these.', 'youumatter2' ); ?>
			</p>

			<div class="max-w-md mx-auto mb-8">
				<?php get_template_part( 'template-parts/blog/search' ); ?>
			</div>

			<div class="flex flex-wrap items-center justify-center gap-3">
				<a
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					class="inline-flex items-center gap-2 bg-forest hover:bg-forest/90 text-cream rounded-full h-[48px] px-6 transition-colors shadow-[0_10px_24px_rgba(26,58,25,0.16)]"
					style="font-size:14px;font-weight:600;"
				>
					<?php esc_html_e( 'Back to home', 'youumatter2' ); ?>
					<span aria-hidden>&rarr;</span>
				</a>
				<a
					href="<?php echo esc_url( $blog_url ); ?>"
					class="inline-flex items-center gap-2 bg-transparent border-2 border-forest/25 hover:border-forest text-forest rounded-full h-[48px] px-6 transition-colors"
					style="font-size:14px;font-weight:600;"
				>
					<?php esc_html_e( 'Browse the blog', 'youumatter2' ); ?>
				</a>
				<a
					href="<?php echo esc_url( yum2_whatsapp_url( __( 'Hi Sanya, I was looking for something on your site and ended up at a 404.', 'youumatter2' ) ) ); ?>"
					target="_blank" rel="noopener noreferrer"
					class="inline-flex items-center gap-2 bg-transparent border-2 border-forest/25 hover:border-forest text-forest rounded-full h-[48px] px-6 transition-colors"
					style="font-size:14px;font-weight:600;"
				>
					<?php echo yum2_icon( 'message-circle', array( 'size' => 14, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( 'Message on WhatsApp', 'youumatter2' ); ?>
				</a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
