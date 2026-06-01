<?php
/**
 * Themed search form. Matches the outlined-pill brand aesthetic used
 * everywhere else (cream input, forest border, rounded-full button).
 *
 * Returned by get_search_form() throughout the theme.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$unique = wp_unique_id( 'yum2-search-' );
?>
<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="not-prose w-full max-w-xl">
	<label for="<?php echo esc_attr( $unique ); ?>" class="sr-only">
		<?php esc_html_e( 'Search for posts', 'youumatter2' ); ?>
	</label>
	<div class="flex items-stretch bg-[#f2ede3] border border-forest/15 rounded-full overflow-hidden focus-within:border-forest transition-colors">
		<span aria-hidden class="flex items-center pl-5 text-forest/60">
			<?php echo yum2_icon( 'search', array( 'size' => 16, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</span>
		<input
			id="<?php echo esc_attr( $unique ); ?>"
			type="search"
			name="s"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			placeholder="<?php esc_attr_e( 'Search the journal&hellip;', 'youumatter2' ); ?>"
			autocomplete="off"
			class="flex-1 min-w-0 bg-transparent border-0 px-3 py-3 text-forest placeholder:text-[#3d4f3e]/55 outline-none"
			style="font-size:14.5px;"
		>
		<button
			type="submit"
			class="shrink-0 bg-forest hover:bg-forest/90 text-cream px-5 transition-colors"
			style="font-size:13px;font-weight:600;letter-spacing:0.02em;"
		>
			<?php esc_html_e( 'Search', 'youumatter2' ); ?>
		</button>
	</div>
</form>
