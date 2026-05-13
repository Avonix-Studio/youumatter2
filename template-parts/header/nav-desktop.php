<?php
/**
 * Sticky desktop header bar. Logo + primary nav + Book button + hamburger.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_book      = (bool) get_theme_mod( 'yum2_header_show_book_cta', true );
$show_pill_mod  = (bool) get_theme_mod( 'yum2_header_show_status_pill', true );
$show_pill      = $show_pill_mod && yum2_get_contact( 'accepting_clients' ) && ! is_404() && ! is_search();
?>
<header class="sticky top-0 z-30 transition-all duration-300 border-b border-transparent bg-cream/60 backdrop-blur-sm [.yum2-scrolled_&]:bg-cream/80 [.yum2-scrolled_&]:backdrop-blur-md [.yum2-scrolled_&]:border-forest/15 [.yum2-scrolled_&]:shadow-[0_4px_20px_-12px_rgba(26,58,25,0.18)]">
	<div
		data-yum2-progress
		aria-hidden
		class="absolute left-0 bottom-0 h-[2px] w-full bg-forest pointer-events-none origin-left scale-x-0 transition-transform duration-150 ease-out"
	></div>

	<div class="max-w-6xl mx-auto px-5 md:px-8 flex items-center justify-between h-16 md:h-[74px]">
		<?php if ( has_custom_logo() ) : ?>
			<?php // the_custom_logo() emits its own <a class="custom-logo-link">; don't double-wrap. ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center shrink-0" aria-label="<?php esc_attr_e( 'youumatter2 home', 'youumatter2' ); ?>">
				<?php get_template_part( 'template-parts/shared/wordmark', null, array( 'class' => 'text-xl md:text-2xl' ) ); ?>
			</a>
		<?php endif; ?>

		<nav class="yum2-nav hidden md:flex items-center absolute left-1/2 -translate-x-1/2" aria-label="<?php esc_attr_e( 'Primary', 'youumatter2' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'flex items-center gap-8 m-0 p-0 list-none text-sm text-forest/85',
					'depth'          => 1,
					'fallback_cb'    => '__return_empty_string',
				)
			);
			?>
		</nav>

		<div class="flex items-center gap-2 shrink-0">
			<?php if ( $show_pill ) : ?>
				<span class="hidden md:inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-forest/15 rounded-full px-3 py-1 text-xs text-forest" style="font-size:11px;font-weight:600;">
					<span class="relative flex size-1.5">
						<span class="absolute inset-0 rounded-full bg-forest animate-ping opacity-60"></span>
						<span class="relative rounded-full size-1.5 bg-forest"></span>
					</span>
					<?php esc_html_e( 'Accepting', 'youumatter2' ); ?>
				</span>
			<?php endif; ?>

			<?php if ( $show_book ) : ?>
				<div class="hidden md:block">
					<?php
					get_template_part(
						'template-parts/shared/book-button',
						null,
						array(
							'label'   => __( 'Book a Session', 'youumatter2' ),
							'variant' => 'primary',
							'class'   => 'h-10 px-5',
						)
					);
					?>
				</div>
			<?php endif; ?>

			<button
				type="button"
				class="md:hidden size-10 flex items-center justify-center text-forest rounded-full hover:bg-forest/5 transition-colors"
				aria-label="<?php esc_attr_e( 'Open menu', 'youumatter2' ); ?>"
				aria-controls="yum2-mobile-drawer"
				:aria-expanded="open ? 'true' : 'false'"
				@click="open = true"
			>
				<?php echo yum2_icon( 'menu', array( 'size' => 22, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>
	</div>
</header>
