<?php
/**
 * Site header. Doctype, head, body open, sticky desktop nav, mobile drawer.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5DLJRMNF');</script>
<!-- End Google Tag Manager -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preload" as="font" type="font/woff2" href="<?php echo esc_url( get_template_directory_uri() . '/assets/fonts/newsreader/Newsreader-500.woff2' ); ?>" crossorigin>
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'bg-cream text-ink font-body antialiased' ); ?>>
<?php wp_body_open(); ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DLJRMNF"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<a class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:bg-forest focus:text-cream focus:px-3 focus:py-2 focus:rounded" href="#primary">
	<?php esc_html_e( 'Skip to content', 'youumatter2' ); ?>
</a>

<div
	x-data="{ open: false, _trapRelease: null }"
	x-init="$watch('open', value => {
		document.documentElement.classList.toggle('overflow-hidden', value);
		if (value && window.yum2 && window.yum2.trapFocus) {
			_trapRelease = window.yum2.trapFocus($refs.panel);
		} else if (_trapRelease) {
			_trapRelease();
			_trapRelease = null;
		}
	})"
	@keydown.escape.window="open = false"
>
	<?php
	get_template_part( 'template-parts/header/nav-desktop' );
	get_template_part( 'template-parts/header/nav-mobile-drawer' );
	?>
<?php /* Alpine wrapper stays open until footer.php so sticky positioning has room. */ ?>
