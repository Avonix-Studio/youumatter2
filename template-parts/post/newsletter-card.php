<?php
/**
 * Single-post newsletter card. Cosmetic variant of the inline newsletter
 * strip; same form action / nonce as Phase 3's footer newsletter.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$subscribed = isset( $_GET['subscribed'] ) ? sanitize_text_field( wp_unslash( $_GET['subscribed'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$redirect   = remove_query_arg( 'subscribed' );
?>
<aside class="not-prose bg-[#f8f3e9] border border-forest/15 rounded-[22px] p-6 md:p-7">
	<p class="text-terracotta tracking-[2px] uppercase mb-2" style="font-size:11px;font-weight:600;">
		<?php esc_html_e( 'Stay close', 'youumatter2' ); ?>
	</p>
	<h3 class="text-forest mb-2" style="font-family:'Newsreader',serif;font-size:clamp(20px,2vw,24px);line-height:1.2;font-weight:500;">
		<?php esc_html_e( 'New essays,', 'youumatter2' ); ?>
		<em class="italic" style="color:#c07a5a;font-weight:400;"><?php esc_html_e( 'in your inbox.', 'youumatter2' ); ?></em>
	</h3>
	<p class="italic text-forest/65 mb-4" style="font-family:'Newsreader',serif;font-size:15px;">
		<?php esc_html_e( 'One thoughtful note a month. Unsubscribe any time.', 'youumatter2' ); ?>
	</p>

	<?php if ( '1' === $subscribed ) : ?>
		<p class="italic text-forest" style="font-family:'Newsreader',serif;font-size:15px;">
			<?php esc_html_e( "Thank you. I'll be in touch.", 'youumatter2' ); ?>
		</p>
	<?php else : ?>
		<form
			action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
			method="post"
			class="flex items-center bg-white border border-forest/15 rounded-full pl-5 pr-1.5 h-[50px] focus-within:border-forest transition-colors"
		>
			<input type="hidden" name="action" value="yum2_subscribe">
			<input type="hidden" name="_yum2_redirect" value="<?php echo esc_url( $redirect ); ?>">
			<?php wp_nonce_field( 'yum2_subscribe', '_yum2_subscribe_nonce' ); ?>

			<label class="sr-only" for="yum2-newsletter-post-email"><?php esc_html_e( 'Email', 'youumatter2' ); ?></label>
			<input
				id="yum2-newsletter-post-email"
				type="email"
				name="email"
				required
				placeholder="<?php esc_attr_e( 'Your email', 'youumatter2' ); ?>"
				class="flex-1 min-w-0 bg-transparent outline-none text-ink placeholder:text-forest/65"
				style="font-size:14px;"
			>
			<button
				type="submit"
				class="bg-forest hover:bg-forest/90 text-cream rounded-full px-5 h-[40px] transition-colors whitespace-nowrap"
				style="font-size:13.5px;font-weight:600;"
			>
				<?php esc_html_e( 'Subscribe', 'youumatter2' ); ?>
			</button>
		</form>
	<?php endif; ?>
</aside>
