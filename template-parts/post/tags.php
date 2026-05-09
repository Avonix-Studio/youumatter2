<?php
/**
 * Tag pills row, rendered below the article content.
 *
 * Falls back to the post's category names if no tags are set, so every
 * post still gets a small contextual chip row matching the design.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tags = get_the_tags();
$pills = array();

if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
	foreach ( $tags as $t ) {
		$pills[] = array(
			'label' => $t->name,
			'url'   => get_tag_link( $t ),
		);
	}
} else {
	foreach ( (array) get_the_category() as $c ) {
		$pills[] = array(
			'label' => $c->name,
			'url'   => get_category_link( $c ),
		);
	}
}

if ( empty( $pills ) ) {
	return;
}
?>
<div class="not-prose mt-10 md:mt-12 flex flex-wrap gap-2">
	<?php foreach ( $pills as $p ) : $hashtag = '#' . preg_replace( '/\s+/', '', $p['label'] ); ?>
		<a
			href="<?php echo esc_url( $p['url'] ); ?>"
			class="inline-flex items-center bg-cream hover:bg-sage-light/60 text-forest/70 hover:text-forest border border-forest/15 rounded-full px-3.5 py-1.5 transition-colors"
			style="font-size:13px;font-weight:500;"
		>
			<?php echo esc_html( $hashtag ); ?>
		</a>
	<?php endforeach; ?>
</div>
