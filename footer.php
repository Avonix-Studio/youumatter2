<?php
/**
 * Site footer.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<footer class="site-footer bg-forest text-cream mt-24">
	<div class="mx-auto max-w-7xl px-4 md:px-6 py-12 text-sm">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>.</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
