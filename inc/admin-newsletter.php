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

		<h2><?php esc_html_e( 'Configuration', 'youumatter2' ); ?></h2>
		<p class="description" style="max-width:680px;">
			<?php esc_html_e( 'Save your MailerLite API key and Group ID here. They live in the WordPress database (wp_options), so they survive every deploy, never touch git, and need no .env file.', 'youumatter2' ); ?>
		</p>

		<form method="post" action="<?php echo esc_url( $post_url ); ?>">
			<?php wp_nonce_field( 'yum2_nl_settings', '_yum2_nl_nonce' ); ?>
			<input type="hidden" name="action" value="yum2_nl_settings">

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="yum2-api-key"><?php esc_html_e( 'MailerLite API key', 'youumatter2' ); ?></label></th>
					<td>
						<?php
						$key_source = yum2_mailerlite_source( 'api_key' );
						$key_saved  = get_option( 'yum2_mailerlite_api_key', '' );
						if ( '' !== $key_saved ) {
							$placeholder = sprintf(
								/* translators: %s is a short prefix of the saved key. */
								esc_attr__( 'Saved: %s... (paste a new key to replace, leave blank to keep)', 'youumatter2' ),
								substr( $key_saved, 0, 10 )
							);
						} else {
							$placeholder = esc_attr__( 'Paste your MailerLite API token', 'youumatter2' );
						}
						?>
						<input type="password" id="yum2-api-key" name="api_key" class="large-text" value="" placeholder="<?php echo $placeholder; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already esc_attr__'d above ?>" autocomplete="off">
						<p class="description">
							<?php
							switch ( $key_source ) {
								case 'option':
									esc_html_e( 'Source: saved in the database via this screen.', 'youumatter2' );
									break;
								case 'env':
									esc_html_e( 'Source: environment variable (YUM2_MAILERLITE_API_KEY).', 'youumatter2' );
									break;
								case 'constant':
									esc_html_e( 'Source: PHP constant (wp-config.php or theme .env file).', 'youumatter2' );
									break;
								default:
									esc_html_e( 'Not set anywhere yet. Paste the key above and click Save.', 'youumatter2' );
							}
							?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="yum2-group-id"><?php esc_html_e( 'MailerLite Group ID', 'youumatter2' ); ?></label></th>
					<td>
						<?php
						$group_source = yum2_mailerlite_source( 'group_id' );
						$group_saved  = get_option( 'yum2_mailerlite_group_id', '' );
						?>
						<input type="text" id="yum2-group-id" name="group_id" class="regular-text" value="<?php echo esc_attr( $group_saved ); ?>" placeholder="e.g. 188833744132507220">
						<p class="description">
							<?php esc_html_e( 'The group new signups are added to in MailerLite.', 'youumatter2' ); ?>
							<?php
							switch ( $group_source ) {
								case 'option':
									esc_html_e( 'Source: saved here.', 'youumatter2' );
									break;
								case 'env':
									esc_html_e( 'Source: environment variable.', 'youumatter2' );
									break;
								case 'constant':
									esc_html_e( 'Source: PHP constant.', 'youumatter2' );
									break;
								default:
									esc_html_e( 'Not set.', 'youumatter2' );
							}
							?>
						</p>
					</td>
				</tr>
			</table>

			<?php submit_button( __( 'Save settings', 'youumatter2' ) ); ?>
		</form>

		<?php if ( '' !== $key_saved ) : ?>
			<form method="post" action="<?php echo esc_url( $post_url ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'Remove the saved API key? The theme will fall back to env / constant if any.', 'youumatter2' ) ); ?>');">
				<?php wp_nonce_field( 'yum2_nl_clear_key', '_yum2_nl_nonce' ); ?>
				<input type="hidden" name="action" value="yum2_nl_clear_key">
				<?php submit_button( __( 'Clear saved API key', 'youumatter2' ), 'delete small', 'submit', false ); ?>
			</form>
		<?php endif; ?>

		<h2 style="margin-top:2em;"><?php esc_html_e( 'MailerLite status', 'youumatter2' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'API key', 'youumatter2' ); ?></th>
				<td>
					<?php
					$theme_dir    = get_template_directory();
					$env_path     = $theme_dir . '/.env';
					$php_path     = $theme_dir . '/inc/env.local.php';
					$env_exists   = file_exists( $env_path );
					$env_readable = $env_exists && is_readable( $env_path );
					$php_exists   = file_exists( $php_path );
					$php_readable = $php_exists && is_readable( $php_path );
					?>
					<?php if ( '' !== $key ) : ?>
						<span style="color:#1f7a36;font-weight:600;"><?php esc_html_e( 'Loaded', 'youumatter2' ); ?></span>
						<code><?php echo esc_html( strlen( $key ) ); ?> chars, starts <?php echo esc_html( substr( $key, 0, 6 ) ); ?>...</code>
					<?php else : ?>
						<span style="color:#b00;font-weight:600;"><?php esc_html_e( 'NOT loaded', 'youumatter2' ); ?></span>
					<?php endif; ?>

					<details style="margin-top:.75em;" <?php echo '' === $key ? 'open' : ''; ?>>
						<summary><?php esc_html_e( 'Where the theme looked for it', 'youumatter2' ); ?></summary>
						<ul style="margin:.5em 0 0;list-style:disc;padding-left:1.5em;">
							<li><?php esc_html_e( 'Theme dir:', 'youumatter2' ); ?> <code><?php echo esc_html( $theme_dir ); ?></code></li>
							<li><code>.env</code> &nbsp; <?php esc_html_e( 'exists:', 'youumatter2' ); ?> <strong><?php echo $env_exists ? 'yes' : 'no'; ?></strong>, <?php esc_html_e( 'readable:', 'youumatter2' ); ?> <strong><?php echo $env_readable ? 'yes' : 'no'; ?></strong></li>
							<li><code>inc/env.local.php</code> &nbsp; <?php esc_html_e( 'exists:', 'youumatter2' ); ?> <strong><?php echo $php_exists ? 'yes' : 'no'; ?></strong>, <?php esc_html_e( 'readable:', 'youumatter2' ); ?> <strong><?php echo $php_readable ? 'yes' : 'no'; ?></strong></li>
							<li><code>YUM2_MAILERLITE_API_KEY</code> <?php esc_html_e( 'constant defined:', 'youumatter2' ); ?> <strong><?php echo defined( 'YUM2_MAILERLITE_API_KEY' ) ? 'yes' : 'no'; ?></strong></li>
						</ul>
					</details>
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

/**
 * Save the API key and/or Group ID from the Configuration form.
 * Empty API key field = keep the saved key (so users can re-save Group ID
 * without re-pasting the secret). Empty Group ID = clear it.
 */
function yum2_nl_handle_save_settings() {
	yum2_nl_guard( 'yum2_nl_settings' );

	$messages = array();

	if ( isset( $_POST['api_key'] ) ) {
		$key = trim( wp_unslash( $_POST['api_key'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- API token stored as-is, validated by MailerLite on use.
		if ( '' !== $key ) {
			update_option( 'yum2_mailerlite_api_key', $key, false );
			$messages[] = __( 'API key saved.', 'youumatter2' );
		}
	}

	if ( isset( $_POST['group_id'] ) ) {
		$group = trim( sanitize_text_field( wp_unslash( $_POST['group_id'] ) ) );
		if ( '' === $group ) {
			if ( get_option( 'yum2_mailerlite_group_id' ) ) {
				delete_option( 'yum2_mailerlite_group_id' );
				$messages[] = __( 'Group ID cleared.', 'youumatter2' );
			}
		} else {
			update_option( 'yum2_mailerlite_group_id', $group, false );
			$messages[] = __( 'Group ID saved.', 'youumatter2' );
		}
	}

	if ( empty( $messages ) ) {
		$messages[] = __( 'No changes saved.', 'youumatter2' );
	}

	yum2_nl_redirect( implode( ' ', $messages ) );
}
add_action( 'admin_post_yum2_nl_settings', 'yum2_nl_handle_save_settings' );

/**
 * Remove the API key saved in the DB. Falls back to env / constant if any.
 */
function yum2_nl_handle_clear_key() {
	yum2_nl_guard( 'yum2_nl_clear_key' );
	delete_option( 'yum2_mailerlite_api_key' );
	yum2_nl_redirect( __( 'Saved API key removed.', 'youumatter2' ) );
}
add_action( 'admin_post_yum2_nl_clear_key', 'yum2_nl_handle_clear_key' );
