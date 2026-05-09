<?php
/**
 * Category filter pills. "All" + dynamic categories from get_categories().
 * Active state derived from is_category() / get_queried_object().
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cats = get_categories(
	array(
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
		'number'     => 12,
	)
);

$current_id  = is_category() ? (int) get_queried_object_id() : 0;
$all_active  = ! is_category();
$blog_url    = get_permalink( (int) get_option( 'page_for_posts' ) );
if ( ! $blog_url ) {
	$blog_url = home_url( '/' );
}
?>
<div class="relative flex-1 min-w-0">
	<p class="hidden lg:block text-terracotta tracking-[2px] uppercase mb-3" style="font-size:11px;font-weight:600;">
		<?php esc_html_e( 'Filter by topic', 'youumatter2' ); ?>
	</p>
	<div class="flex gap-2 overflow-x-auto snap-x snap-mandatory pr-8 lg:pr-0" style="scrollbar-width:none;">
		<a
			href="<?php echo esc_url( $blog_url ); ?>"
			class="yum2-filter-pill snap-start <?php echo $all_active ? 'is-active' : ''; ?>"
		>
			<?php esc_html_e( 'All', 'youumatter2' ); ?>
		</a>
		<?php foreach ( $cats as $cat ) : $is_active = ( (int) $cat->term_id === $current_id ); ?>
			<a
				href="<?php echo esc_url( get_category_link( $cat ) ); ?>"
				class="yum2-filter-pill snap-start <?php echo $is_active ? 'is-active' : ''; ?>"
			>
				<?php echo esc_html( $cat->name ); ?>
			</a>
		<?php endforeach; ?>
	</div>
	<div aria-hidden class="lg:hidden pointer-events-none absolute right-0 bottom-0 h-10 w-10" style="background:linear-gradient(to right, rgba(242,237,227,0) 0%, rgba(242,237,227,1) 70%);"></div>
</div>
