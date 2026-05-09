<?php
/**
 * Mobile-only bottom action bar. Home / Blog / WhatsApp / Book.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_url = get_post_type_archive_link( 'post' );
if ( ! $blog_url ) {
	$blog_url = home_url( '/blog/' );
}

$is_blog_active = is_home() || is_singular( 'post' ) || is_post_type_archive( 'post' ) || is_category() || is_tag();
$is_home_active = is_front_page();

$items = array(
	array(
		'key'    => 'home',
		'label'  => __( 'Home', 'youumatter2' ),
		'href'   => home_url( '/' ),
		'icon'   => 'home',
		'active' => $is_home_active,
	),
	array(
		'key'    => 'blog',
		'label'  => __( 'Blog', 'youumatter2' ),
		'href'   => $blog_url,
		'icon'   => 'newspaper',
		'active' => $is_blog_active,
	),
	array(
		'key'    => 'whatsapp',
		'label'  => __( 'WhatsApp', 'youumatter2' ),
		'href'   => yum2_whatsapp_url(),
		'icon'   => 'message-circle',
		'active' => false,
		'rel'    => 'noopener',
	),
);

$calendly = (string) yum2_get_contact( 'calendly_url' );
$onclick  = '' !== $calendly ? sprintf( 'return yum2OpenCalendly(%s)', wp_json_encode( $calendly ) ) : 'return false';
?>
<nav
	aria-label="<?php esc_attr_e( 'Quick actions', 'youumatter2' ); ?>"
	class="md:hidden fixed bottom-0 inset-x-0 z-30 bg-sage-light/95 backdrop-blur-md rounded-t-[32px] shadow-[0_-10px_40px_rgba(26,58,25,0.08)] border-t border-forest/10"
	style="padding-bottom: calc(env(safe-area-inset-bottom) + 10px);"
>
	<ul class="flex items-center justify-around px-3 pt-2.5">
		<?php foreach ( $items as $item ) : ?>
			<li>
				<a
					href="<?php echo esc_url( $item['href'] ); ?>"
					<?php if ( 'whatsapp' === $item['key'] ) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>
					class="flex flex-col items-center gap-1 px-3 py-1.5 transition-colors <?php echo $item['active'] ? 'text-forest' : 'text-forest/65 hover:text-forest'; ?>"
				>
					<?php echo yum2_icon( $item['icon'], array( 'size' => 20, 'stroke' => $item['active'] ? 2.2 : 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="uppercase" style="font-size:10px;font-weight:500;letter-spacing:0.5px;">
						<?php echo esc_html( $item['label'] ); ?>
					</span>
				</a>
			</li>
		<?php endforeach; ?>
		<li>
			<button
				type="button"
				class="inline-flex items-center gap-2 bg-forest hover:bg-forest/90 text-cream rounded-full px-5 py-2.5 transition-colors"
				style="font-size:12px;font-weight:600;letter-spacing:0.3px;"
				onclick="<?php echo esc_attr( $onclick ); ?>"
				<?php if ( '' === $calendly ) : ?>aria-disabled="true"<?php endif; ?>
			>
				<?php echo yum2_icon( 'calendar', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="uppercase tracking-[0.5px]"><?php esc_html_e( 'Book', 'youumatter2' ); ?></span>
			</button>
		</li>
	</ul>
</nav>
