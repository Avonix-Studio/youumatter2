<?php
/**
 * Category, tag, date, and author archives for posts.
 *
 * Reuses the blog index hero (with category-aware copy) and the shared
 * loop. The featured-post card is intentionally not rendered on archives.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( is_category() ) {
	$cat        = get_queried_object();
	$hero_title = __( 'Posts in', 'youumatter2' );
	$hero_acc   = $cat ? $cat->name . '.' : __( 'this topic.', 'youumatter2' );
	$hero_sub   = $cat && $cat->description ? wp_strip_all_tags( $cat->description ) : __( 'Honest writing on this corner of the journal.', 'youumatter2' );
} elseif ( is_tag() ) {
	$tag        = get_queried_object();
	$hero_title = __( 'Tagged', 'youumatter2' );
	$hero_acc   = $tag ? '#' . $tag->name . '.' : __( 'this tag.', 'youumatter2' );
	$hero_sub   = __( 'Essays grouped by tag.', 'youumatter2' );
} elseif ( is_author() ) {
	$hero_title = __( 'Essays by', 'youumatter2' );
	$hero_acc   = get_the_author() . '.';
	$hero_sub   = __( 'Recent writing.', 'youumatter2' );
} else {
	$hero_title = __( 'The journal,', 'youumatter2' );
	$hero_acc   = __( 'archived.', 'youumatter2' );
	$hero_sub   = __( 'Older essays.', 'youumatter2' );
}
?>

<main id="primary" class="site-main">

	<?php
	get_template_part(
		'template-parts/blog/hero',
		null,
		array(
			'eyebrow'  => __( 'The Journal', 'youumatter2' ),
			'title'    => $hero_title,
			'accent'   => $hero_acc,
			'subtitle' => $hero_sub,
		)
	);

	get_template_part( 'template-parts/blog/loop' );
	?>

</main>

<?php
get_footer();
