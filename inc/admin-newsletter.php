<?php
/**
 * Tools -> Newsletter admin screen.
 *
 * Lets the maintainer:
 *   - see at a glance whether the MailerLite key + group are loaded
 *   - send a real test signup to MailerLite from inside wp-admin
 *   - view the local backup queue (signups MailerLite did NOT accept)
 *   - push that queue to MailerLite or clear it
 *
 * Doubles as a deploy sanity check: if this screen does not appear at
 * Tools -> Newsletter, the theme directory wp-admin is loading is not
 * this folder.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * 1. Menu registration
 * ====================================================================== */
function yum2_newsletter_admin_menu() {
	add_management_page(
		__( 'Newsletter', 'youumatter2' ),
		__( 'Newsletter', 'youumatter2' ),
		'manage_options',
		'yum2-newsletter',
		'yum2_newsletter_admin_page'
	);
}
add_action( 'admin_menu', 'yum2_newsletter_admin_menu' );

/* =========================================================================
 * 2. Page render
 * ====================================================================== */
function yum2_newsletter_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$key       = yum2_mailerlite_api_key();
	$group     = yum2_mailerlite_group_id();
	$queue     = (array) get_option( 'yum2_pending_subscribers', array() );
	$me        = wp_get_current_user();
	$test_to   = $me ? (string) $me->user_email : '';
	$notice    = get_transient( 'yum2_nl_notice' );
	$post_url  = admin_url( 'admin-post.php' );

	if ( $notice ) {
		delete_transient( 'yum2_nl_notice' );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Newsletter', 'youumatter2' ); ?></h1>

		<?php if ( $notice ) : ?>
			<div class="notice notice-info is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div>
		<?php endif; ?>

		<h2><?php esc_html_e( 'MailerLite status', 'youumatter2' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'API key', 'youumatter2' ); ?></th>
				<td>
					<?php if ( '' !== $key ) : ?>
						<span style="color:#1f7a36;font-weight:600;"><?php esc_html_e( 'Loaded', 'youumatter2' ); ?></span>
						<code><?php echo esc_html( strlen( $key ) ); ?> chars, starts <?php echo esc_html( substr( $key, 0, 6 ) ); ?>...</code>
					<?php else : ?>
						<span style="color:#b00;font-weight:600;"><?php esc_html_e( 'NOT loaded', 'youumatter2' ); ?></span>
						<?php esc_html_e( 'Add YUM2_MAILERLITE_API_KEY to the .env file at the theme root.', 'youumatter2' ); ?>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Group ID', 'youumatter2' ); ?></th>
				<td>
					<?php if ( '' !== $group ) : ?>
						<code><?php echo esc_html( $group ); ?></code>
					<?php else : ?>
						<em><?php esc_html_e( 'not set; subscribers will be added without a group', 'youumatter2' ); ?></em>
					<?php endif; ?>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Send a test signup', 'youumatter2' ); ?></h2>
		<p>
			<?php
			printf(
				/* translators: %s is the admin email. */
				esc_html__( 'Pushes %s to MailerLite right now using the same handler the public forms use, and reports the result.', 'youumatter2' ),
				'<code>' . esc_html( $test_to ) . '</code>'
			);
			?>
		</p>
		<form method="post" action="<?php echo esc_url( $post_url ); ?>">
			<?php wp_nonce_field( 'yum2_nl_test', '_yum2_nl_nonce' ); ?>
			<input type="hidden" name="action" value="yum2_nl_test">
			<?php submit_button( __( 'Run test signup', 'youumatter2' ), 'primary', 'submit', false ); ?>
		</form>

		<h2 style="margin-top:2em;">
			<?php
			printf(
				/* translators: %d is the backup queue count. */
				esc_html__( 'Backup queue (%d)', 'youumatter2' ),
				count( $queue )
			);
			?>
		</h2>

		<?php if ( empty( $queue ) ) : ?>
			<p><?php esc_html_e( 'Empty. Every signup reached MailerLite directly. Good.', 'youumatter2' ); ?></p>
		<?php else : ?>
			<p>
				<?php esc_html_e( 'These signups did NOT reach MailerLite (the API was unreachable, the key was missing, or MailerLite rejected the request at that moment). They are safe here; use the buttons to push them now.', 'youumatter2' ); ?>
			</p>
			<table class="widefat striped" style="max-width:560px;">
				<thead><tr><th><?php esc_html_e( 'Email', 'youumatter2' ); ?></th></tr></thead>
				<tbody>
					<?php foreach ( $queue as $email ) : ?>
						<tr><td><?php echo esc_html( $email ); ?></td></tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<p style="margin-top:1em;display:flex;gap:.5em;flex-wrap:wrap;">
				<form method="post" action="<?php echo esc_url( $post_url ); ?>" style="margin:0;">
					<?php wp_nonce_field( 'yum2_nl_sync', '_yum2_nl_nonce' ); ?>
					<input type="hidden" name="action" value="yum2_nl_sync">
					<?php submit_button( __( 'Push queue to MailerLite now', 'youumatter2' ), 'primary', 'submit', false ); ?>
				</form>
				<form method="post" action="<?php echo esc_url( $post_url ); ?>" style="margin:0;"
					onsubmit="return confirm('<?php echo esc_js( __( 'Clear the backup queue? Local copies are removed.', 'youumatter2' ) ); ?>');">
					<?php wp_nonce_field( 'yum2_nl_clear', '_yum2_nl_nonce' ); ?>
					<input type="hidden" name="action" value="yum2_nl_clear">
					<?php submit_button( __( 'Clear queue', 'youumatter2' ), 'secondary', 'submit', false ); ?>
				</form>
			</p>
		<?php endif; ?>
	</div>
	<?php
}

/* =========================================================================
 * 3. Action handlers (admin-post.php targets)
 * ====================================================================== */
function yum2_nl_redirect( $msg = '' ) {
	if ( '' !== $msg ) {
		set_transient( 'yum2_nl_notice', $msg, 60 );
	}
	wp_safe_redirect( admin_url( 'tools.php?page=yum2-newsletter' ) );
	exit;
}

function yum2_nl_guard( $action ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Forbidden', 'youumatter2' ), '', array( 'response' => 403 ) );
	}
	$nonce = isset( $_POST['_yum2_nl_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_yum2_nl_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, $action ) ) {
		wp_die( esc_html__( 'Invalid request', 'youumatter2' ), '', array( 'response' => 400 ) );
	}
}

function yum2_nl_handle_test() {
	yum2_nl_guard( 'yum2_nl_test' );
	$user  = wp_get_current_user();
	$email = $user ? sanitize_email( $user->user_email ) : '';
	if ( ! is_email( $email ) ) {
		yum2_nl_redirect( __( 'No valid admin email to test with.', 'youumatter2' ) );
	}
	$ok = yum2_mailerlite_subscribe( $email );
	yum2_nl_redirect(
		$ok
			? sprintf( /* translators: %s is the email. */ __( 'OK. Sent %s to MailerLite.', 'youumatter2' ), $email )
			: sprintf( /* translators: %s is the email. */ __( 'FAILED. MailerLite did not accept %s. Check the API key and group ID, then try again.', 'youumatter2' ), $email )
	);
}
add_action( 'admin_post_yum2_nl_test', 'yum2_nl_handle_test' );

function yum2_nl_handle_sync() {
	yum2_nl_guard( 'yum2_nl_sync' );
	$queue     = (array) get_option( 'yum2_pending_subscribers', array() );
	$remaining = array();
	$ok        = 0;
	$fail      = 0;
	foreach ( $queue as $email ) {
		if ( yum2_mailerlite_subscribe( (string) $email ) ) {
			$ok++;
		} else {
			$remaining[] = $email;
			$fail++;
		}
	}
	update_option( 'yum2_pending_subscribers', $remaining, false );
	yum2_nl_redirect(
		sprintf(
			/* translators: 1: pushed count, 2: still-pending count. */
			__( 'Pushed %1$d to MailerLite, %2$d still pending.', 'youumatter2' ),
			$ok,
			$fail
		)
	);
}
add_action( 'admin_post_yum2_nl_sync', 'yum2_nl_handle_sync' );

function yum2_nl_handle_clear() {
	yum2_nl_guard( 'yum2_nl_clear' );
	update_option( 'yum2_pending_subscribers', array(), false );
	yum2_nl_redirect( __( 'Backup queue cleared.', 'youumatter2' ) );
}
add_action( 'admin_post_yum2_nl_clear', 'yum2_nl_handle_clear' );
