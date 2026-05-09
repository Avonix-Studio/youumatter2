<?php
/**
 * Newsletter strip. Posts to admin-post.php?action=yum2_subscribe.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$subscribed = isset( $_GET['subscribed'] ) ? sanitize_text_field( wp_unslash( $_GET['subscribed'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$redirect   = remove_query_arg( 'subscribed' );
?>
<section id="newsletter" class="bg-[#f8f3e9] px-6 pt-6 pb-4 border-t border-forest/5">
	<div class="max-w-4xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-5">
		<div class="md:max-w-sm">
			<p class="italic text-forest mb-1.5" style="font-family:'Newsreader',serif;font-size:22px;">
				<?php esc_html_e( 'Stay close to what helps.', 'youumatter2' ); ?>
			</p>
			<p class="text-forest/60" style="font-size:13px;line-height:1.55;">
				<?php esc_html_e( 'Monthly notes on therapy, mindful habits, and quiet reflections. No spam, unsubscribe anytime.', 'youumatter2' ); ?>
			</p>
		</div>

		<?php if ( '1' === $subscribed ) : ?>
			<p class="italic text-forest" style="font-family:'Newsreader',serif;font-size:16px;">
				<?php esc_html_e( "Thank you. I'll be in touch.", 'youumatter2' ); ?>
			</p>
		<?php else : ?>
			<form
				action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
				method="post"
				class="flex items-center bg-cream border border-forest/15 rounded-full pl-5 pr-1.5 h-[52px] w-full md:w-[380px] md:shrink-0 focus-within:border-forest transition-colors"
			>
				<input type="hidden" name="action" value="yum2_subscribe">
				<input type="hidden" name="_yum2_redirect" value="<?php echo esc_url( $redirect ); ?>">
				<?php wp_nonce_field( 'yum2_subscribe', '_yum2_subscribe_nonce' ); ?>

				<label class="sr-only" for="yum2-newsletter-email"><?php esc_html_e( 'Email', 'youumatter2' ); ?></label>
				<input
					id="yum2-newsletter-email"
					type="email"
					name="email"
					required
					placeholder="<?php esc_attr_e( 'Your email', 'youumatter2' ); ?>"
					class="flex-1 min-w-0 bg-transparent outline-none text-ink placeholder:text-forest/60"
					style="font-size:14px;"
				>
				<button
					type="submit"
					class="bg-forest hover:bg-forest/90 text-cream rounded-full px-6 h-[40px] transition-colors whitespace-nowrap"
					style="font-size:14px;font-weight:600;"
				>
					<?php esc_html_e( 'Subscribe', 'youumatter2' ); ?>
				</button>
			</form>
		<?php endif; ?>
	</div>
	<?php if ( '0' === $subscribed ) : ?>
		<p class="text-terracotta text-center mt-3" style="font-size:13px;">
			<?php esc_html_e( 'That email address looked off. Please try again.', 'youumatter2' ); ?>
		</p>
	<?php endif; ?>
</section>
