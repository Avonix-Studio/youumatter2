<?php
/**
 * Footer meta band. Logo + tagline + footer menu + socials + legal + copy.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tagline   = (string) get_theme_mod( 'yum2_footer_tagline', __( 'A quiet space for therapy, reflection, and steady growth.', 'youumatter2' ) );
$year      = gmdate( 'Y' );
$copyright = (string) get_theme_mod(
	'yum2_footer_copyright',
	__( '© %year% youumatter2. Sanya Oberoi. All rights reserved.', 'youumatter2' )
);
$copyright = str_replace( '%year%', $year, $copyright );
?>
<footer class="bg-sage-light px-6 pt-10 pb-6">
	<div class="max-w-6xl mx-auto">
		<div class="grid grid-cols-1 md:grid-cols-[1fr_auto] gap-6 md:gap-10 pb-6">
			<div>
				<div class="inline-flex items-center mb-3">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center" aria-label="<?php esc_attr_e( 'youumatter2 home', 'youumatter2' ); ?>">
							<?php get_template_part( 'template-parts/shared/wordmark', null, array( 'class' => 'text-xl' ) ); ?>
						</a>
					<?php endif; ?>
				</div>
				<?php if ( '' !== $tagline ) : ?>
					<p class="text-forest/65 max-w-sm" style="font-size:13px;line-height:1.6;">
						<?php echo esc_html( $tagline ); ?>
					</p>
				<?php endif; ?>
			</div>

			<nav class="yum2-footer-nav flex flex-wrap md:justify-end gap-x-5 gap-y-2 md:max-w-md" aria-label="<?php esc_attr_e( 'Footer', 'youumatter2' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'flex flex-wrap md:justify-end gap-x-5 gap-y-2 m-0 p-0 list-none text-forest/80 uppercase tracking-[1.3px]',
						'depth'          => 1,
						'fallback_cb'    => '__return_empty_string',
					)
				);
				?>
			</nav>
		</div>

		<div class="h-px bg-forest/10"></div>

		<div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pt-4">
			<div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-forest/55" style="font-size:12px;">
				<span><?php echo esc_html( $copyright ); ?></span>
				<span aria-hidden class="text-forest/30">·</span>
				<a href="https://avonixstudio.com" target="_blank" rel="noopener noreferrer"
				   class="hover:text-forest transition-colors">
					<?php esc_html_e( 'Made by Avonix Studio', 'youumatter2' ); ?>
				</a>
				<?php if ( has_nav_menu( 'legal' ) ) : ?>
					<span aria-hidden class="text-forest/30">·</span>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'legal',
							'container'      => false,
							'menu_class'     => 'inline-flex flex-wrap items-center gap-x-3 gap-y-1 m-0 p-0 list-none [&>li>a]:hover:text-forest [&>li]:after:content-["·"] [&>li]:after:ml-3 [&>li]:after:text-forest/30 last:[&>li]:after:content-[""]',
							'depth'          => 1,
							'fallback_cb'    => '__return_empty_string',
						)
					);
					?>
				<?php endif; ?>
			</div>

			<?php
			get_template_part(
				'template-parts/shared/social-icons',
				null,
				array(
					'class' => 'flex gap-3',
				)
			);
			?>
		</div>
	</div>
</footer>
