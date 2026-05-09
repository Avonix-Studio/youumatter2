<?php
/**
 * Search input pill for the blog filter row. Submits as a standard WP
 * search query (`?s=`) to the home URL.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$query = is_search() ? get_search_query() : '';
?>
<form
	role="search"
	method="get"
	action="<?php echo esc_url( home_url( '/' ) ); ?>"
	class="flex items-center bg-white border border-forest/15 rounded-full h-11 lg:w-[260px] px-4 focus-within:border-forest transition-colors shrink-0"
>
	<label class="sr-only" for="yum2-search-field"><?php esc_html_e( 'Search', 'youumatter2' ); ?></label>
	<span class="text-forest/65 mr-2 shrink-0">
		<?php echo yum2_icon( 'search', array( 'size' => 15, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</span>
	<input
		id="yum2-search-field"
		type="search"
		name="s"
		value="<?php echo esc_attr( $query ); ?>"
		placeholder="<?php esc_attr_e( 'Search posts…', 'youumatter2' ); ?>"
		class="w-full bg-transparent outline-none text-ink placeholder:text-forest/65"
		style="font-size:13.5px;"
	>
</form>
