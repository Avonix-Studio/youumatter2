<?php
/**
 * Comments template. Loaded by comments_template() in single.php.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}

$comment_count = (int) get_comments_number();
$error_kind    = isset( $_GET['comment_error'] ) ? sanitize_key( wp_unslash( $_GET['comment_error'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

// Pre-fill the author name if it came back through ?ca=...
if ( ! empty( $_GET['ca'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$_COOKIE[ 'comment_author_' . COOKIEHASH ] = sanitize_text_field( wp_unslash( $_GET['ca'] ) );
}
?>

<section id="comments" class="relative px-5 md:px-8 pt-12 md:pt-16 pb-14 md:pb-20 border-t border-forest/10 bg-white">
	<div class="max-w-3xl mx-auto">

		<div class="flex items-baseline justify-between gap-4 mb-6 md:mb-8">
			<div>
				<p class="text-terracotta tracking-[2px] uppercase mb-2" style="font-size:11px;font-weight:600;">
					<?php esc_html_e( 'Conversation', 'youumatter2' ); ?>
				</p>
				<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(26px,3.4vw,40px);line-height:1.1;letter-spacing:-0.01em;font-weight:400;">
					<?php esc_html_e( 'Thoughts &amp;', 'youumatter2' ); ?>
					<em class="italic" style="color:#c07a5a;font-weight:400;"><?php esc_html_e( 'reflections.', 'youumatter2' ); ?></em>
				</h2>
			</div>
			<span class="shrink-0 inline-flex items-center gap-1.5 text-forest/65 bg-white border border-forest/15 rounded-full px-3 py-1.5" style="font-size:12.5px;font-weight:600;">
				<?php echo yum2_icon( 'message-circle', array( 'size' => 13, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo esc_html( (string) $comment_count ); ?>
			</span>
		</div>

		<?php if ( 'email' === $error_kind ) : ?>
			<div role="alert" class="mb-5 px-5 py-3 rounded-[14px] border border-terracotta/40 bg-terracotta/10 text-terracotta" style="font-size:13.5px;line-height:1.55;">
				<strong class="text-terracotta"><?php esc_html_e( 'Almost there.', 'youumatter2' ); ?></strong>
				<?php esc_html_e( 'Please add a valid email so we can let you know if your comment is replied to. Your address stays private.', 'youumatter2' ); ?>
			</div>
		<?php endif; ?>

		<?php
		if ( comments_open() ) {
			comment_form(
				array(
					'class_form'         => 'bg-white border border-forest/15 rounded-[22px] p-5 md:p-6 mb-8',
					'title_reply'        => '',
					'title_reply_before' => '<p class="text-forest mb-3" style="font-family:\'Newsreader\',serif;font-size:18px;font-weight:500;">' . esc_html__( 'Join the conversation', 'youumatter2' ) . '</p><p class="text-forest/65 mb-4" style="font-size:13.5px;line-height:1.55;">' . esc_html__( 'Comments are moderated with care. Be kind, this is a quiet space.', 'youumatter2' ) . '</p>',
					'title_reply_after'  => '',
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'submit_button'      => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" style="font-size:13.5px;font-weight:600;">%4$s <span aria-hidden>&rarr;</span></button>',
					'class_submit'       => 'inline-flex items-center justify-center gap-2 bg-forest hover:bg-forest/90 disabled:opacity-50 text-cream rounded-[14px] h-[48px] px-6 transition-colors shrink-0 mt-3',
					'label_submit'       => __( 'Post comment', 'youumatter2' ),
					'submit_field'       => '<p class="form-submit mt-3 flex flex-col sm:flex-row sm:items-center gap-3">%1$s %2$s</p>',
					'logged_in_as'       => '',
				)
			);
		} else {
			?>
			<p class="bg-cream border border-forest/15 rounded-[18px] p-5 italic text-forest/65 text-center" style="font-family:'Newsreader',serif;font-size:15px;">
				<?php esc_html_e( 'Comments are closed for this essay.', 'youumatter2' ); ?>
			</p>
			<?php
		}
		?>

		<?php if ( have_comments() ) : ?>
			<ol class="flex flex-col gap-4 list-none m-0 p-0">
				<?php
				wp_list_comments(
					array(
						'callback'    => 'yum2_comment_callback',
						'style'       => 'ol',
						'short_ping'  => true,
						'avatar_size' => 0,
					)
				);
				?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
				<nav class="mt-6 flex items-center justify-center" aria-label="<?php esc_attr_e( 'Comments', 'youumatter2' ); ?>">
					<?php paginate_comments_links( array( 'mid_size' => 1, 'end_size' => 1 ) ); ?>
				</nav>
			<?php endif; ?>
		<?php endif; ?>

	</div>
</section>
