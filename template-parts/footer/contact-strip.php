<?php
/**
 * Three-column contact strip. Email / Call / WhatsApp. Whole row clickable.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = array(
	array(
		'label'  => __( 'Email me', 'youumatter2' ),
		'meta'   => yum2_get_contact( 'email' ),
		'href'   => yum2_email_url(),
		'icon'   => 'mail',
		'target' => '_self',
	),
	array(
		'label'  => __( 'Call me', 'youumatter2' ),
		'meta'   => yum2_get_contact( 'phone_display' ),
		'href'   => yum2_phone_url(),
		'icon'   => 'phone',
		'target' => '_self',
	),
	array(
		'label'  => __( 'WhatsApp me', 'youumatter2' ),
		'meta'   => __( 'Quick reply, usually same day', 'youumatter2' ),
		'href'   => yum2_whatsapp_url(),
		'icon'   => 'message-circle',
		'target' => '_blank',
	),
);
?>
<section class="bg-[#f8f3e9] border-y border-forest/10">
	<div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 md:divide-x divide-y md:divide-y-0 divide-forest/10">
		<?php foreach ( $items as $item ) : ?>
			<a
				href="<?php echo esc_url( $item['href'] ); ?>"
				<?php if ( '_blank' === $item['target'] ) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>
				class="group flex items-center justify-between gap-4 px-6 md:px-8 py-5 md:py-9 hover:bg-forest/5 transition-colors"
			>
				<div class="flex items-center gap-4 min-w-0 flex-1">
					<span class="shrink-0 text-forest">
						<?php echo yum2_icon( $item['icon'], array( 'size' => 22, 'stroke' => 1.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<div class="flex flex-col min-w-0">
						<span class="text-forest leading-tight" style="font-family:'Newsreader',serif;font-size:clamp(18px,2vw,22px);font-weight:500;">
							<?php echo esc_html( $item['label'] ); ?>
						</span>
						<span class="text-forest/60 truncate mt-1 leading-tight" style="font-size:13px;">
							<?php echo esc_html( $item['meta'] ); ?>
						</span>
					</div>
				</div>
				<span class="shrink-0 text-terracotta transition-transform duration-300 group-hover:-translate-y-1 group-hover:translate-x-1">
					<?php echo yum2_icon( 'arrow-up-right', array( 'size' => 24, 'stroke' => 1.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
