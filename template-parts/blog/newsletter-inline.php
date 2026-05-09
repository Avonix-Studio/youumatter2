<?php
/**
 * Inline newsletter strip placed inside the blog grid (after card #3).
 * Same form action / nonce as the footer newsletter; cosmetically distinct.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$subscribed = isset( $_GET['subscribed'] ) ? sanitize_text_field( wp_unslash( $_GET['subscribed'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$redirect   = remove_query_arg( 'subscribed' );
?>
<div class="md:col-span-2 lg:col-span-3">
	<div class="relative bg-sage-light/60 border border-forest/10 rounded-[22px] p-6 md:p-9 overflow-hidden">
		<div aria-hidden class="absolute -top-20 -right-20 w-[320px] h-[320px] rounded-full pointer-events-none" style="background:radial-gradient(circle at center, rgba(248,243,233,0.65) 0%, rgba(228,239,227,0) 65%);"></div>

		<div class="relative grid grid-cols-1 md:grid-cols-[1.1fr_1fr] gap-6 md:gap-10 items-center">
			<div>
				<p class="text-terracotta tracking-[2px] uppercase mb-3" style="font-size:11px;font-weight:600;">
					<?php esc_html_e( 'A quiet newsletter', 'youumatter2' ); ?>
				</p>
				<h3 class="text-forest mb-2" style="font-family:'Newsreader',serif;font-size:clamp(24px,2.4vw,30px);line-height:1.15;letter-spacing:-0.01em;font-weight:500;">
					<?php esc_html_e( 'New essays,', 'youumatter2' ); ?>
					<em class="italic" style="color:#c07a5a;font-weight:400;"><?php esc_html_e( 'in your inbox.', 'youumatter2' ); ?></em>
				</h3>
				<p class="italic text-forest/65" style="font-family:'Newsreader',serif;font-size:16px;line-height:1.5;">
					<?php esc_html_e( 'One reflection a month. No spam. Unsubscribe any time.', 'youumatter2' ); ?>
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
					class="flex items-center bg-white border border-forest/15 rounded-full pl-5 pr-1.5 h-[56px] w-full focus-within:border-forest transition-colors"
				>
					<input type="hidden" name="action" value="yum2_subscribe">
					<input type="hidden" name="_yum2_redirect" value="<?php echo esc_url( $redirect ); ?>">
					<?php wp_nonce_field( 'yum2_subscribe', '_yum2_subscribe_nonce' ); ?>

					<label class="sr-only" for="yum2-newsletter-inline-email"><?php esc_html_e( 'Email', 'youumatter2' ); ?></label>
					<input
						id="yum2-newsletter-inline-email"
						type="email"
						name="email"
						required
						placeholder="<?php esc_attr_e( 'Your email', 'youumatter2' ); ?>"
						class="flex-1 min-w-0 bg-transparent outline-none text-ink placeholder:text-forest/65"
						style="font-size:14px;"
					>
					<button
						type="submit"
						class="inline-flex items-center gap-1.5 bg-forest hover:bg-forest/90 text-cream rounded-full px-5 h-[44px] transition-colors whitespace-nowrap"
						style="font-size:14px;font-weight:600;"
					>
						<?php esc_html_e( 'Subscribe', 'youumatter2' ); ?>
						<?php echo yum2_icon( 'arrow-right', array( 'size' => 14, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				</form>
			<?php endif; ?>
		</div>
	</div>
</div>
