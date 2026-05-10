<?php
/**
 * Contact: 2-column main content. Left = featured WhatsApp card +
 * intro. Right = stacked contact-method cards (Email, Phone, In-person,
 * Online, Hours) + crisis card.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email         = (string) yum2_get_contact( 'email' );
$phone_display = (string) yum2_get_contact( 'phone_display' );
$clinic        = (string) yum2_get_contact( 'clinic_address' );
$hours         = (string) yum2_get_contact( 'clinic_hours' );

$cards = array(
	array(
		'icon'  => 'mail',
		'label' => __( 'Email', 'youumatter2' ),
		'value' => $email,
		'sub'   => __( 'Replies within 24 hours, weekdays.', 'youumatter2' ),
		'href'  => yum2_email_url(),
	),
	array(
		'icon'  => 'phone',
		'label' => __( 'Phone', 'youumatter2' ),
		'value' => $phone_display,
		'sub'   => __( 'Mon to Sat, 10 AM to 7 PM.', 'youumatter2' ),
		'href'  => yum2_phone_url(),
	),
	array(
		'icon'  => 'map-pin',
		'label' => __( 'In-person', 'youumatter2' ),
		'value' => '' !== $clinic ? $clinic : __( 'Pitampura, New Delhi', 'youumatter2' ),
		'sub'   => __( 'Exact address shared on booking.', 'youumatter2' ),
		'href'  => '',
	),
	array(
		'icon'  => 'monitor',
		'label' => __( 'Online', 'youumatter2' ),
		'value' => __( 'Google Meet', 'youumatter2' ),
		'sub'   => __( 'For clients anywhere in India.', 'youumatter2' ),
		'href'  => '',
	),
	array(
		'icon'  => 'clock',
		'label' => __( 'Hours', 'youumatter2' ),
		'value' => __( 'Mon to Sat', 'youumatter2' ),
		'sub'   => '' !== $hours ? $hours : __( 'Mon to Sat · 10:00 AM to 7:00 PM', 'youumatter2' ),
		'href'  => '',
	),
);
?>
<section class="bg-cream px-5 md:px-8 py-14 md:py-20">
	<div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-[1.35fr_1fr] gap-10 lg:gap-14">

		<div class="bg-[#f8f3e9] border border-forest/15 rounded-[24px] p-6 md:p-10 shadow-[0_24px_60px_-30px_rgba(26,58,25,0.2)]">

			<p class="text-terracotta tracking-[2px] uppercase mb-3" style="font-size:11px;font-weight:600;">
				<?php esc_html_e( 'Easiest way in', 'youumatter2' ); ?>
			</p>
			<h2 class="text-forest mb-4" style="font-family:'Newsreader',serif;font-size:clamp(28px,3.4vw,38px);line-height:1.15;letter-spacing:-0.01em;font-weight:400;">
				<?php esc_html_e( 'A short WhatsApp', 'youumatter2' ); ?>
				<em class="italic" style="color:#c07a5a;"><?php esc_html_e( 'is plenty.', 'youumatter2' ); ?></em>
			</h2>
			<p class="italic text-forest/65 mb-6" style="font-family:'Newsreader',serif;font-size:17px;line-height:1.55;">
				<?php esc_html_e( "Tell me what's bringing you here, what you've tried, or just say hi. A few sentences is plenty. I'll reply with a few time options and a soft first step.", 'youumatter2' ); ?>
			</p>
			<a
				href="<?php echo esc_url( yum2_whatsapp_url( __( 'Hi Sanya, I would like to book a session.', 'youumatter2' ) ) ); ?>"
				target="_blank" rel="noopener noreferrer"
				class="inline-flex items-center gap-2 bg-forest hover:bg-forest/90 text-cream rounded-full h-[52px] px-6 transition-colors shadow-[0_14px_30px_rgba(26,58,25,0.18)]"
				style="font-size:15px;font-weight:600;"
			>
				<?php echo yum2_icon( 'message-circle', array( 'size' => 16, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php esc_html_e( 'Message on WhatsApp', 'youumatter2' ); ?>
			</a>

			<div class="h-px bg-forest/12 my-8"></div>

			<p class="text-terracotta tracking-[2px] uppercase mb-3" style="font-size:11px;font-weight:600;">
				<?php esc_html_e( 'Prefer to book directly?', 'youumatter2' ); ?>
			</p>
			<p class="text-forest mb-5" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.45;font-weight:400;">
				<?php esc_html_e( "Pick a time on Calendly. It's a 50-minute first session, no diagnosis, no pressure to continue.", 'youumatter2' ); ?>
			</p>
			<?php
			get_template_part(
				'template-parts/shared/book-button',
				null,
				array(
					'label'   => __( 'Book a first session', 'youumatter2' ),
					'variant' => 'outline',
					'icon'    => true,
				)
			);
			?>
		</div>

		<aside class="flex flex-col gap-4">
			<?php foreach ( $cards as $card ) : ?>
				<?php $linked = '' !== $card['href']; ?>
				<<?php echo $linked ? 'a' : 'div'; ?>
					<?php if ( $linked ) : ?>href="<?php echo esc_url( $card['href'] ); ?>"<?php endif; ?>
					class="flex items-start gap-3.5 p-4 rounded-[16px] border border-forest/15 bg-[#f8f3e9] <?php echo $linked ? 'hover:border-forest transition-colors' : ''; ?>"
				>
					<span class="shrink-0 size-11 rounded-full bg-sage-light flex items-center justify-center text-forest">
						<?php echo yum2_icon( $card['icon'], array( 'size' => 18, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<div class="min-w-0 flex-1">
						<p class="text-terracotta tracking-[1.6px] uppercase mb-1" style="font-size:10.5px;font-weight:600;">
							<?php echo esc_html( $card['label'] ); ?>
						</p>
						<p class="text-forest break-words" style="font-family:'Newsreader',serif;font-size:18px;font-weight:500;">
							<?php echo esc_html( $card['value'] ); ?>
						</p>
						<p class="text-forest/65 mt-0.5" style="font-size:13px;">
							<?php echo esc_html( $card['sub'] ); ?>
						</p>
					</div>
				</<?php echo $linked ? 'a' : 'div'; ?>>
			<?php endforeach; ?>

			<div class="bg-[#f8f3e9] border border-forest/15 rounded-[22px] p-6">
				<p class="text-terracotta tracking-[2px] uppercase mb-2" style="font-size:11px;font-weight:600;">
					<?php esc_html_e( 'In crisis?', 'youumatter2' ); ?>
				</p>
				<p class="text-forest mb-3" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.4;">
					<?php esc_html_e( "If you're in immediate distress, please reach out now.", 'youumatter2' ); ?>
				</p>
				<p class="text-forest/65" style="font-size:13.5px;line-height:1.7;">
					<?php esc_html_e( 'iCall India:', 'youumatter2' ); ?>
					<a href="tel:9152987821" class="text-forest hover:underline">9152987821</a><br>
					<?php esc_html_e( 'Vandrevala Foundation:', 'youumatter2' ); ?>
					<a href="tel:18602662345" class="text-forest hover:underline">1860 266 2345</a>
				</p>
			</div>
		</aside>
	</div>
</section>
