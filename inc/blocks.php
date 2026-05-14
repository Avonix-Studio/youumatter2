<?php
/**
 * Register custom blocks.
 *
 * Each block lives in blocks/{name}/. WordPress reads block.json from that
 * folder automatically - no need to list attributes or settings here.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all yum2 custom blocks.
 */
function yum2_register_blocks() {
	register_block_type( get_template_directory() . '/blocks/pullquote' );
}
add_action( 'init', 'yum2_register_blocks' );
