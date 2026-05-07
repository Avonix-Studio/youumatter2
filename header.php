<?php
/**
 * Site header.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preload" as="font" type="font/woff2" href="<?php echo esc_url( get_template_directory_uri() . '/assets/fonts/newsreader/Newsreader-500.woff2' ); ?>" crossorigin>
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-cream text-ink font-body antialiased' ); ?>>
<?php wp_body_open(); ?>

<a class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:bg-forest focus:text-cream focus:px-3 focus:py-2 focus:rounded" href="#primary">
	<?php esc_html_e( 'Skip to content', 'youumatter2' ); ?>
</a>

<header class="site-header bg-cream/95 backdrop-blur sticky top-0 z-40 border-b border-forest/10">
	<div class="mx-auto max-w-7xl px-4 md:px-6 py-4 flex items-center justify-between">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2" aria-label="<?php esc_attr_e( 'youumatter2 home', 'youumatter2' ); ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="font-heading text-2xl text-forest tracking-tight">
					<span class="italic">youu</span>matter<span class="text-terracotta">2</span>
				</span>
			<?php endif; ?>
		</a>

		<nav class="site-nav hidden md:flex items-center gap-8" aria-label="<?php esc_attr_e( 'Primary', 'youumatter2' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'flex items-center gap-8 text-sm text-ink',
						'depth'          => 1,
						'fallback_cb'    => '__return_empty_string',
					)
				);
			}
			?>
		</nav>
	</div>
</header>
