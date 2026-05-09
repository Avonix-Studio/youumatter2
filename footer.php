<?php
/**
 * Site footer. Newsletter + contact strip + meta band, then mobile bottom-nav.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_newsletter = (bool) get_theme_mod( 'yum2_footer_show_newsletter', true );
?>

<?php if ( $show_newsletter ) : ?>
	<?php get_template_part( 'template-parts/footer/newsletter' ); ?>
<?php endif; ?>

<?php get_template_part( 'template-parts/footer/contact-strip' ); ?>
<?php get_template_part( 'template-parts/footer/meta' ); ?>

<?php get_template_part( 'template-parts/shared/bottom-nav' ); ?>

<?php wp_footer(); ?>
</body>
</html>
