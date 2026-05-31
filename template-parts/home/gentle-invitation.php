<?php
/**
 * Home: gentle invitation. "Take your time, I'll be here when you're ready."
 * Plus the practical-details card (clinic / sessions / hours / reach me)
 * as the right column on desktop.
 *
 * Home page only. The Customizer toggle yum2_home_show_gentle_invitation
 * gates the entire section; it defaults to true.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! get_theme_mod( 'yum2_home_show_gentle_invitation', true ) ) {
	return;
}

/* Copy lives in inc/content.php under 'gentle_invitation'. */
$c             = yum2_content( 'gentle_invitation' );
$accepting     = (bool) yum2_get_contact( 'accepting_clients' );
$clinic        = (string) yum2_get_contact( 'clinic_address' );
$hours         = (string) yum2_get_contact( 'clinic_hours' );
$phone_display = (string) yum2_get_contact( 'phone_display' );
$email         = (string) yum2_get_contact( 'email' );
?>
<section class="relative bg-cream px-5 md:px-8 pt-16 md:pt-24 pb-12 md:pb-16 overflow-hidden">
	<div aria-hidden class="absolute -top-40 -left-32 w-[520px] h-[520px] rounded-full pointer-events-none" style="background:radial-gradient(circle at center, rgba(228,239,227,0.85) 0%, rgba(242,237,227,0) 65%);"></div>
	<div aria-hidden class="absolute -bottom-32 right-0 w-[420px] h-[420px] rounded-full pointer-events-none" style="background:radial-gradient(circle at center, rgba(248,243,233,0.7) 0%, rgba(242,237,227,0) 65%);"></div>

	<div class="relative max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-[1.15fr_1fr] gap-10 md:gap-16 items-start">
		<div>
			<p class="yum2-reveal text-terracotta tracking-[2px] uppercase mb-5" style="font-size:12px;font-weight:600;transition-delay:0s;">
				<?php echo esc_html( $c['label'] ); ?>
			</p>

			<h2 class="yum2-reveal text-forest mb-5" style="font-family:'Newsreader',serif;font-size:clamp(34px,5.2vw,60px);line-height:1.05;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;transition-delay:0.08s;">
				<?php echo esc_html( $c['heading'] ); ?>
				<em class="italic" style="color:#c07a5a;font-weight:400;"><?php echo esc_html( $c['heading_em'] ); ?></em>
			</h2>

			<p class="yum2-reveal italic text-[#3d4f3e] max-w-md mb-8" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;transition-delay:0.18s;">
				<?php echo esc_html( $c['body'] ); ?>
			</p>

			<div class="yum2-reveal flex flex-wrap items-center gap-3 mb-8" style="transition-delay:0.28s;">
				<?php
				get_template_part(
					'template-parts/shared/book-button',
					null,
					array(
						'label'   => $c['btn_book'],
						'variant' => 'primary',
						'icon'    => true,
					)
				);
				?>
				<a
					href="<?php echo esc_url( yum2_whatsapp_url( $c['whatsapp_msg'] ) ); ?>"
					target="_blank" rel="noopener noreferrer"
					class="inline-flex items-center gap-2 bg-transparent border-2 border-forest/25 hover:border-forest text-forest rounded-full h-[52px] px-6 transition-colors"
					style="font-size:15px;font-weight:600;"
				>
					<?php echo yum2_icon( 'message-circle', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo esc_html( $c['btn_whatsapp'] ); ?>
				</a>
			</div>

			<?php if ( $accepting ) : ?>
				<p class="yum2-reveal inline-flex items-center gap-2 text-[#3d4f3e]" style="font-size:13px;transition-delay:0.38s;">
					<span class="relative flex size-2">
						<span class="absolute inline-flex h-full w-full rounded-full bg-forest opacity-60 animate-ping"></span>
						<span class="relative inline-flex size-2 rounded-full bg-forest"></span>
					</span>
					<?php esc_html_e( 'Currently accepting new clients', 'youumatter2' ); ?>
				</p>
			<?php endif; ?>
		</div>

		<aside class="yum2-reveal relative bg-[#f8f3e9] border border-forest/15 rounded-[22px] p-6 md:p-7 shadow-[0_24px_60px_-30px_rgba(26,58,25,0.25)]" style="transition-delay:0.25s;">
			<p class="text-terracotta tracking-[2px] uppercase mb-5" style="font-size:11px;font-weight:600;">
				<?php esc_html_e( 'Practical details', 'youumatter2' ); ?>
			</p>
			<ul class="flex flex-col gap-4 list-none m-0 p-0">
				<?php
				$rows = array(
					array(
						'icon'  => 'map-pin',
						'label' => __( 'Clinic', 'youumatter2' ),
						'value' => $clinic,
					),
					array(
						'icon'  => 'monitor',
						'label' => __( 'Sessions', 'youumatter2' ),
						'value' => __( 'Online (Google Meet) · In-person', 'youumatter2' ),
					),
					array(
						'icon'  => 'clock',
						'label' => __( 'Hours', 'youumatter2' ),
						'value' => $hours,
					),
				);
				foreach ( $rows as $row ) :
					?>
					<li class="flex items-start gap-3.5">
						<span class="shrink-0 size-9 rounded-full bg-[#e4efe3] flex items-center justify-center text-forest">
							<?php echo yum2_icon( $row['icon'], array( 'size' => 15, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<div class="min-w-0">
							<p class="text-forest" style="font-family:'Newsreader',serif;font-size:16px;font-weight:500;">
								<?php echo esc_html( $row['label'] ); ?>
							</p>
							<p class="text-[#3d4f3e]" style="font-size:13.5px;line-height:1.5;">
								<?php echo esc_html( $row['value'] ); ?>
							</p>
						</div>
					</li>
				<?php endforeach; ?>

				<li class="flex items-start gap-3.5">
					<span class="shrink-0 size-9 rounded-full bg-[#e4efe3] flex items-center justify-center text-forest">
						<?php echo yum2_icon( 'phone', array( 'size' => 15, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<div class="min-w-0">
						<p class="text-forest" style="font-family:'Newsreader',serif;font-size:16px;font-weight:500;">
							<?php esc_html_e( 'Reach me', 'youumatter2' ); ?>
						</p>
						<?php if ( '' !== $phone_display ) : ?>
							<a href="<?php echo esc_url( yum2_phone_url() ); ?>" class="text-[#3d4f3e] hover:text-forest transition-colors block" style="font-size:13.5px;line-height:1.5;">
								<?php echo esc_html( $phone_display ); ?>
							</a>
						<?php endif; ?>
						<?php if ( '' !== $email ) : ?>
							<a href="<?php echo esc_url( yum2_email_url() ); ?>" class="text-[#3d4f3e] hover:text-forest transition-colors block" style="font-size:13.5px;line-height:1.5;">
								<?php echo esc_html( $email ); ?>
							</a>
						<?php endif; ?>
					</div>
				</li>
			</ul>
		</aside>
	</div>
</section>
