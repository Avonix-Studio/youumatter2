<?php
/**
 * Search results.
 *
 * Reuses the blog hero and the shared loop. Hero copy reflects the query.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$query = get_search_query();
?>

<main id="primary" class="site-main">

	<?php
	get_template_part(
		'template-parts/blog/hero',
		null,
		array(
			'eyebrow'  => __( 'Search', 'youumatter2' ),
			'title'    => __( 'Searching for', 'youumatter2' ),
			'accent'   => '"' . $query . '"',
			'subtitle' => '' !== $query
				? sprintf(
					/* translators: %s: search query */
					__( 'Showing essays that mention "%s".', 'youumatter2' ),
					$query
				)
				: __( 'Type a word or phrase to find an essay.', 'youumatter2' ),
		)
	);

	get_template_part( 'template-parts/blog/loop' );
	?>

</main>

<?php
get_footer();
