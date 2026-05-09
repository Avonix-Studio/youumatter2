<?php
/**
 * Standard WP pagination for the blog index, archives, search.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;
if ( $wp_query->max_num_pages < 2 ) {
	return;
}

$links = paginate_links(
	array(
		'mid_size'  => 1,
		'end_size'  => 1,
		'prev_text' => __( '&larr; Previous', 'youumatter2' ),
		'next_text' => __( 'Next &rarr;', 'youumatter2' ),
		'type'      => 'array',
	)
);

if ( empty( $links ) ) {
	return;
}
?>
<nav class="yum2-pagination flex items-center justify-center flex-wrap gap-2 mt-10 md:mt-14" aria-label="<?php esc_attr_e( 'Posts', 'youumatter2' ); ?>">
	<?php foreach ( $links as $link ) : ?>
		<?php echo $link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- paginate_links output is already escaped by core ?>
	<?php endforeach; ?>
</nav>
