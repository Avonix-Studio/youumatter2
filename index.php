<?php
/**
 * Fallback template. Real templates live in front-page.php, single.php, etc.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'mx-auto max-w-3xl px-4 py-12' ); ?>>
				<h1 class="font-heading text-3xl text-forest mb-4"><?php the_title(); ?></h1>
				<div class="prose"><?php the_content(); ?></div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<div class="mx-auto max-w-3xl px-4 py-24 text-center">
			<h1 class="font-heading text-3xl text-forest mb-4">
				<?php esc_html_e( 'Nothing here yet.', 'youumatter2' ); ?>
			</h1>
		</div>
	<?php endif; ?>
</main>

<?php
get_footer();
