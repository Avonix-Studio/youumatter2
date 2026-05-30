<?php
/**
 * Tiny .env loader.
 *
 * Reads a .env file at the theme root (KEY=VALUE per line, # for comments)
 * and exposes each line as a PHP constant AND a process env var, so both
 * defined()/CONST_NAME and getenv() work. Idempotent: never overwrites an
 * existing constant or env var, so wp-config.php definitions still win on
 * production.
 *
 * The .env file itself is gitignored (see .gitignore).
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function yum2_load_env() {
	$path = get_template_directory() . '/.env';
	if ( ! is_readable( $path ) ) {
		return;
	}

	$lines = file( $path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
	if ( ! is_array( $lines ) ) {
		return;
	}

	foreach ( $lines as $raw ) {
		$line = trim( $raw );
		if ( '' === $line || '#' === $line[0] ) {
			continue;
		}
		$eq = strpos( $line, '=' );
		if ( false === $eq ) {
			continue;
		}

		$key   = trim( substr( $line, 0, $eq ) );
		$value = trim( substr( $line, $eq + 1 ) );
		if ( '' === $key || ! preg_match( '/^[A-Z_][A-Z0-9_]*$/i', $key ) ) {
			continue;
		}

		// Strip optional surrounding single or double quotes.
		$len = strlen( $value );
		if ( $len >= 2 ) {
			$first = $value[0];
			$last  = $value[ $len - 1 ];
			if ( ( '"' === $first && '"' === $last ) || ( "'" === $first && "'" === $last ) ) {
				$value = substr( $value, 1, -1 );
			}
		}

		if ( ! defined( $key ) ) {
			define( $key, $value );
		}
		if ( false === getenv( $key ) ) {
			putenv( $key . '=' . $value );
		}
	}
}
yum2_load_env();
