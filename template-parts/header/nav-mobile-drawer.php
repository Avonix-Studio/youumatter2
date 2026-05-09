<?php
/**
 * Mobile drawer. Slides down from the top. Wrapped by the same Alpine
 * x-data scope opened in header.php so it shares `open` state with the
 * hamburger button in nav-desktop.php.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$has_mobile_menu = has_nav_menu( 'mobile' );
$menu_location   = $has_mobile_menu ? 'mobile' : 'primary';

$phone_display = (string) yum2_get_contact( 'phone_display' );
$email         = (string) yum2_get_contact( 'email' );
$location      = (string) yum2_get_contact( 'clinic_address' );
?>
<div
	x-show="open"
	x-cloak
	@click="open = false"
	class="fixed inset-0 bg-forest/40 backdrop-blur-sm z-40 md:hidden"
	x-transition.opacity.duration.300ms
></div>

<aside
	id="yum2-mobile-drawer"
	x-show="open"
	x-cloak
	x-ref="panel"
	@click.outside="open = false"
	@click="if ($event.target.tagName === 'A') open = false"
	x-transition:enter="transition ease-out duration-300"
	x-transition:enter-start="-translate-y-full"
	x-transition:enter-end="translate-y-0"
	x-transition:leave="transition ease-in duration-250"
	x-transition:leave-start="translate-y-0"
	x-transition:leave-end="-translate-y-full"
	role="dialog"
	aria-modal="true"
	aria-label="<?php esc_attr_e( 'Site menu', 'youumatter2' ); ?>"
	class="fixed inset-x-0 top-0 z-50 md:hidden bg-cream border-b border-forest/15 shadow-[0_8px_40px_rgba(0,0,0,0.1)]"
>
	<div class="flex items-center justify-between h-16 px-5 border-b border-forest/15">
		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center" aria-label="<?php esc_attr_e( 'youumatter2 home', 'youumatter2' ); ?>">
				<?php get_template_part( 'template-parts/shared/wordmark', null, array( 'class' => 'text-xl' ) ); ?>
			</a>
		<?php endif; ?>
		<button
			type="button"
			@click="open = false"
			class="size-10 flex items-center justify-center text-forest rounded-full hover:bg-forest/5 transition-colors"
			aria-label="<?php esc_attr_e( 'Close menu', 'youumatter2' ); ?>"
		>
			<?php echo yum2_icon( 'x', array( 'size' => 22, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
	</div>

	<nav class="yum2-nav yum2-nav-mobile px-5 pt-5 pb-2" aria-label="<?php esc_attr_e( 'Mobile primary', 'youumatter2' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => $menu_location,
				'container'      => false,
				'menu_class'     => 'flex flex-col m-0 p-0 list-none',
				'depth'          => 1,
				'fallback_cb'    => '__return_empty_string',
			)
		);
		?>
	</nav>

	<div class="mx-5 h-px bg-forest/15 mt-3"></div>

	<div class="px-5 py-5 space-y-1.5">
		<?php if ( '' !== $location ) : ?>
			<p class="text-forest/70" style="font-size:13px;font-weight:500;">
				<?php
				echo esc_html(
					sprintf(
						/* translators: %s: clinic location */
						__( '%s · Online & In-Person', 'youumatter2' ),
						$location
					)
				);
				?>
			</p>
		<?php endif; ?>
		<?php if ( '' !== $phone_display ) : ?>
			<a href="<?php echo esc_url( yum2_phone_url() ); ?>" class="block text-forest hover:text-forest/80" style="font-size:15px;font-weight:500;">
				<?php echo esc_html( $phone_display ); ?>
			</a>
		<?php endif; ?>
		<?php if ( '' !== $email ) : ?>
			<a href="<?php echo esc_url( yum2_email_url() ); ?>" class="block text-forest hover:text-forest/80" style="font-size:15px;font-weight:500;">
				<?php echo esc_html( $email ); ?>
			</a>
		<?php endif; ?>
	</div>

	<div class="px-5 pb-5">
		<?php
		get_template_part(
			'template-parts/shared/book-button',
			null,
			array(
				'label'   => __( 'Book a Session', 'youumatter2' ),
				'variant' => 'primary',
				'class'   => 'w-full h-14',
			)
		);
		?>
	</div>

	<div class="px-5 pb-8">
		<p class="text-forest/70 tracking-[2px] uppercase mb-3" style="font-size:11px;font-weight:600;">
			<?php esc_html_e( 'Follow', 'youumatter2' ); ?>
		</p>
		<?php get_template_part( 'template-parts/shared/social-icons' ); ?>
	</div>
</aside>
