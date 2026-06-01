<?php
/**
 * Contact: "Send a note" form section. Native HTML form (no WPForms) posting
 * to admin-post.php. Four fields only: preferred name, email, optional
 * phone/WhatsApp, message. Plus a required confidentiality checkbox. A
 * hidden honeypot input catches the vast majority of bot submissions.
 *
 * The handler (yum2_handle_contact) lives in inc/template-functions.php.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contacted = isset( $_GET['contacted'] ) ? sanitize_text_field( wp_unslash( $_GET['contacted'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$redirect  = remove_query_arg( 'contacted' );

/* Country dial-code list. India first (default), then alphabetical. ISO code
   beside the dial code keeps each option compact and scannable - "+44 GB"
   reads cleaner than "+44 United Kingdom" in a constrained select. */
$dial_codes = array(
	array( '+91',  'IN', __( 'India', 'youumatter2' ) ),
	array( '+61',  'AU', __( 'Australia', 'youumatter2' ) ),
	array( '+1',   'CA', __( 'Canada', 'youumatter2' ) ),
	array( '+86',  'CN', __( 'China', 'youumatter2' ) ),
	array( '+33',  'FR', __( 'France', 'youumatter2' ) ),
	array( '+49',  'DE', __( 'Germany', 'youumatter2' ) ),
	array( '+852', 'HK', __( 'Hong Kong', 'youumatter2' ) ),
	array( '+62',  'ID', __( 'Indonesia', 'youumatter2' ) ),
	array( '+39',  'IT', __( 'Italy', 'youumatter2' ) ),
	array( '+81',  'JP', __( 'Japan', 'youumatter2' ) ),
	array( '+60',  'MY', __( 'Malaysia', 'youumatter2' ) ),
	array( '+31',  'NL', __( 'Netherlands', 'youumatter2' ) ),
	array( '+64',  'NZ', __( 'New Zealand', 'youumatter2' ) ),
	array( '+92',  'PK', __( 'Pakistan', 'youumatter2' ) ),
	array( '+63',  'PH', __( 'Philippines', 'youumatter2' ) ),
	array( '+974', 'QA', __( 'Qatar', 'youumatter2' ) ),
	array( '+966', 'SA', __( 'Saudi Arabia', 'youumatter2' ) ),
	array( '+65',  'SG', __( 'Singapore', 'youumatter2' ) ),
	array( '+27',  'ZA', __( 'South Africa', 'youumatter2' ) ),
	array( '+82',  'KR', __( 'South Korea', 'youumatter2' ) ),
	array( '+34',  'ES', __( 'Spain', 'youumatter2' ) ),
	array( '+41',  'CH', __( 'Switzerland', 'youumatter2' ) ),
	array( '+66',  'TH', __( 'Thailand', 'youumatter2' ) ),
	array( '+971', 'AE', __( 'United Arab Emirates', 'youumatter2' ) ),
	array( '+44',  'GB', __( 'United Kingdom', 'youumatter2' ) ),
	array( '+1',   'US', __( 'United States', 'youumatter2' ) ),
);
?>
<section class="bg-cream px-5 md:px-8 py-14 md:py-20">
	<div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-[1.35fr_1fr] gap-10 lg:gap-14">

		<div id="contact-form" class="bg-[#f8f3e9] border border-forest/15 rounded-[24px] p-6 md:p-10 shadow-[0_24px_60px_-30px_rgba(26,58,25,0.2)]">

			<?php if ( '1' === $contacted ) : ?>
				<div class="py-10 md:py-14 text-center">
					<span class="inline-flex size-14 rounded-full bg-sage-light items-center justify-center text-forest mb-5">
						<?php echo yum2_icon( 'check', array( 'size' => 26, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<h2 class="text-forest mb-3" style="font-family:'Newsreader',serif;font-size:clamp(26px,3vw,32px);line-height:1.15;font-weight:400;">
						<?php esc_html_e( 'Thank you for reaching out.', 'youumatter2' ); ?>
					</h2>
					<p class="italic text-[#3d4f3e] max-w-md mx-auto" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;">
						<?php esc_html_e( "I've received your note and will write back personally within 24 hours on weekdays. Take care of yourself in the meantime.", 'youumatter2' ); ?>
					</p>
				</div>
			<?php else : ?>
				<h2 class="text-forest mb-2" style="font-family:'Newsreader',serif;font-size:clamp(26px,3vw,32px);line-height:1.15;letter-spacing:-0.01em;font-weight:400;">
					<?php esc_html_e( 'Send a note', 'youumatter2' ); ?>
				</h2>
				<p class="text-[#3d4f3e] mb-7" style="font-size:14.5px;line-height:1.6;">
					<?php esc_html_e( 'The more context you share, the easier it is for me to suggest a good next step. Nothing is mandatory except your name, email, and your message.', 'youumatter2' ); ?>
				</p>

				<?php if ( '0' === $contacted ) : ?>
					<div class="mb-6 rounded-[14px] border border-terracotta/35 bg-terracotta/10 px-4 py-3 text-[#3d4f3e]" style="font-size:13.5px;line-height:1.55;">
						<?php esc_html_e( "Something didn't go through. Please check that your name, email, message, and the confidentiality box are all filled in, then try again.", 'youumatter2' ); ?>
					</div>
				<?php endif; ?>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="flex flex-col gap-5">
					<input type="hidden" name="action" value="yum2_contact">
					<input type="hidden" name="_yum2_redirect" value="<?php echo esc_url( $redirect ); ?>">
					<?php wp_nonce_field( 'yum2_contact', '_yum2_contact_nonce' ); ?>

					<?php /* Honeypot: hidden from humans, irresistible to bots. */ ?>
					<div class="hidden" aria-hidden="true">
						<label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
					</div>

					<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
						<label class="block">
							<span class="block text-forest mb-2" style="font-size:13.5px;font-weight:500;">
								<?php esc_html_e( 'Preferred name', 'youumatter2' ); ?>
								<span class="text-terracotta ml-1" aria-hidden>*</span>
							</span>
							<input type="text" name="name" required
								class="w-full bg-[#f2ede3] border border-forest/15 rounded-[14px] px-4 py-3.5 text-forest placeholder:text-[#3d4f3e]/60 outline-none focus:border-forest transition-colors"
								placeholder="<?php esc_attr_e( 'What should I call you?', 'youumatter2' ); ?>">
						</label>

						<label class="block">
							<span class="block text-forest mb-2" style="font-size:13.5px;font-weight:500;">
								<?php esc_html_e( 'Email', 'youumatter2' ); ?>
								<span class="text-terracotta ml-1" aria-hidden>*</span>
							</span>
							<input type="email" name="email" required
								class="w-full bg-[#f2ede3] border border-forest/15 rounded-[14px] px-4 py-3.5 text-forest placeholder:text-[#3d4f3e]/60 outline-none focus:border-forest transition-colors"
								placeholder="you@example.com">
						</label>
					</div>

					<div>
						<label for="yum2-phone-number" class="block text-forest mb-2" style="font-size:13.5px;font-weight:500;">
							<?php esc_html_e( 'Phone / WhatsApp', 'youumatter2' ); ?>
						</label>
						<div class="flex items-stretch bg-[#f2ede3] border border-forest/15 rounded-[14px] overflow-hidden focus-within:border-forest transition-colors">
							<div class="relative shrink-0">
								<label for="yum2-phone-country" class="sr-only"><?php esc_html_e( 'Country code', 'youumatter2' ); ?></label>
								<select id="yum2-phone-country" name="phone_country"
									class="appearance-none bg-transparent border-0 pl-4 pr-9 py-3.5 text-forest outline-none cursor-pointer"
									style="font-size:14.5px;font-weight:500;">
									<?php foreach ( $dial_codes as $row ) :
										list( $code, $iso, $name ) = $row;
										$key = $code . '-' . $iso;
										?>
										<option value="<?php echo esc_attr( $key ); ?>"
											<?php selected( $key, '+91-IN' ); ?>
											aria-label="<?php echo esc_attr( $name . ' (' . $code . ')' ); ?>">
											<?php echo esc_html( $code . ' ' . $iso ); ?>
										</option>
									<?php endforeach; ?>
								</select>
								<span aria-hidden class="absolute right-2.5 top-1/2 -translate-y-1/2 text-forest/60 pointer-events-none">
									<?php echo yum2_icon( 'chevron-down', array( 'size' => 14, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</span>
							</div>
							<span aria-hidden class="self-center w-px h-7 bg-forest/15"></span>
							<input id="yum2-phone-number" type="tel" name="phone" autocomplete="tel-national"
								class="flex-1 min-w-0 bg-transparent border-0 px-4 py-3.5 text-forest placeholder:text-[#3d4f3e]/60 outline-none"
								placeholder="<?php esc_attr_e( '99538 55858', 'youumatter2' ); ?>">
						</div>
						<span class="block text-[#3d4f3e] mt-1.5" style="font-size:12px;">
							<?php esc_html_e( 'Optional — only used if email bounces.', 'youumatter2' ); ?>
						</span>
					</div>

					<label class="block">
						<span class="block text-forest mb-2" style="font-size:13.5px;font-weight:500;">
							<?php esc_html_e( 'Your message', 'youumatter2' ); ?>
							<span class="text-terracotta ml-1" aria-hidden>*</span>
						</span>
						<textarea name="message" rows="6" required
							class="w-full bg-[#f2ede3] border border-forest/15 rounded-[14px] px-4 py-3.5 text-forest placeholder:text-[#3d4f3e]/60 outline-none focus:border-forest transition-colors resize-y"
							placeholder="<?php esc_attr_e( "What's on your mind, or what you're looking for…", 'youumatter2' ); ?>"></textarea>
						<span class="block text-[#3d4f3e] mt-1.5" style="font-size:12px;">
							<?php esc_html_e( "A few sentences is plenty — we'll go deeper together.", 'youumatter2' ); ?>
						</span>
					</label>

					<label class="flex items-start gap-3 mt-1 cursor-pointer">
						<input type="checkbox" name="consent" required
							class="mt-1 size-4 rounded border-forest/25 text-forest focus:ring-forest accent-[#2b5329]">
						<span class="text-[#3d4f3e]" style="font-size:13px;line-height:1.55;">
							<?php esc_html_e( "I understand this form isn't for emergencies, and my information will be kept confidential and only used to respond to this enquiry.", 'youumatter2' ); ?>
						</span>
					</label>

					<button type="submit"
						class="inline-flex items-center justify-center gap-2 bg-forest hover:bg-forest/90 text-cream rounded-full h-[54px] px-6 transition-colors shadow-[0_14px_30px_rgba(26,58,25,0.18)] mt-2"
						style="font-size:15px;font-weight:600;">
						<?php echo yum2_icon( 'send', array( 'size' => 16, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php esc_html_e( 'Send message', 'youumatter2' ); ?>
					</button>

					<p class="flex items-center gap-2 text-[#3d4f3e] mt-1" style="font-size:12.5px;">
						<span class="text-forest">
							<?php echo yum2_icon( 'shield-check', array( 'size' => 14, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<?php esc_html_e( 'Your message is private. Typical reply within 24 hours on weekdays.', 'youumatter2' ); ?>
					</p>
				</form>
			<?php endif; ?>
		</div>

		<?php // Right column: WhatsApp quick card + contact method cards ?>
		<aside class="flex flex-col gap-4">
			<?php // Quick WhatsApp card at top of sidebar ?>
			<div class="bg-sage-light border border-forest/15 rounded-[22px] p-6">
				<p class="text-terracotta tracking-[1.6px] uppercase mb-2" style="font-size:10.5px;font-weight:700;">
					<?php esc_html_e( 'Not ready to write?', 'youumatter2' ); ?>
				</p>
				<h3 class="text-forest mb-3" style="font-family:'Newsreader',serif;font-size:22px;line-height:1.2;font-weight:500;">
					<?php esc_html_e( 'A quick WhatsApp is', 'youumatter2' ); ?>
					<em class="italic" style="color:#c07a5a;"><?php esc_html_e( 'completely okay too.', 'youumatter2' ); ?></em>
				</h3>
				<a
					href="<?php echo esc_url( yum2_whatsapp_url( __( 'Hi Sanya, just saying hi for now.', 'youumatter2' ) ) ); ?>"
					target="_blank" rel="noopener noreferrer"
					class="inline-flex items-center gap-2 bg-forest hover:bg-forest/90 text-cream rounded-full h-[44px] px-5 transition-colors shadow-[0_10px_20px_rgba(26,58,25,0.16)]"
					style="font-size:13px;font-weight:600;"
				>
					<?php echo yum2_icon( 'message-circle', array( 'size' => 14, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( 'Message on WhatsApp', 'youumatter2' ); ?>
				</a>
			</div>

			<?php
			// Contact method cards.
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

			foreach ( $cards as $card ) :
				$linked = '' !== $card['href'];
				?>
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
						<p class="text-[#3d4f3e] mt-0.5" style="font-size:13px;">
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
				<p class="text-[#3d4f3e]" style="font-size:13.5px;line-height:1.7;">
					<?php esc_html_e( 'iCall India:', 'youumatter2' ); ?>
					<a href="tel:9152987821" class="text-forest hover:underline">9152987821</a><br>
					<?php esc_html_e( 'Vandrevala Foundation:', 'youumatter2' ); ?>
					<a href="tel:18602662345" class="text-forest hover:underline">1860 266 2345</a>
				</p>
			</div>
		</aside>
	</div>
</section>
