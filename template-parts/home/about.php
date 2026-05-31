<?php
/**
 * Home: About Sanya. Portrait card + intro + beliefs + approach chips.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Copy lives in inc/content.php under 'about'. */
$c        = yum2_content( 'about' );
$portrait = get_template_directory_uri() . '/assets/images/sanya-portrait.jpg';

$beliefs   = $c['beliefs'];
$approaches = $c['approaches'];
?>
<section class="relative bg-[#f8f3e9] px-5 md:px-8 pt-16 md:pt-24 pb-16 md:pb-24 overflow-hidden">
	<div aria-hidden class="absolute top-10 right-0 w-[420px] h-[420px] rounded-full pointer-events-none" style="background:radial-gradient(circle at center, rgba(209,229,208,0.45) 0%, rgba(248,243,233,0) 70%);"></div>

	<div class="relative max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-[0.9fr_1.1fr] gap-10 md:gap-16 items-center">
		<div class="yum2-reveal relative mx-auto md:mx-0 w-full max-w-[360px] md:max-w-none" style="transition-delay:0s;">
			<div aria-hidden class="absolute -inset-4 rounded-[36px] bg-[#d1e5d0]/55" style="transform:rotate(-1deg);"></div>
			<div class="relative rounded-[28px] overflow-hidden shadow-[0_24px_48px_-16px_rgba(19,60,20,0.22)]" style="aspect-ratio:4/5;">
				<img
					src="<?php echo esc_url( $portrait ); ?>"
					alt="<?php esc_attr_e( 'Sanya Oberoi, Counselling Psychologist', 'youumatter2' ); ?>"
					class="w-full h-full object-cover"
					loading="lazy" decoding="async"
				>
			</div>

			<div class="absolute -top-3 -right-3 md:-right-5 bg-white rounded-2xl pl-3.5 pr-4 py-2.5 shadow-[0_14px_32px_rgba(19,60,20,0.12)] border border-[#e0d9ce] flex items-center gap-2.5" style="transform:rotate(2deg);">
				<span class="size-2 rounded-full bg-[#c07a5a]" aria-hidden></span>
				<div class="flex flex-col leading-tight">
					<span class="text-[#c07a5a] uppercase tracking-[0.14em]" style="font-size:9.5px;font-weight:700;">
						<?php echo esc_html( $c['credential_label'] ); ?>
					</span>
					<span class="text-[#1a3a19]" style="font-size:12.5px;font-weight:600;">
						<?php echo esc_html( $c['credential_value'] ); ?>
					</span>
				</div>
			</div>

			<div class="absolute -bottom-6 -left-3 md:-bottom-8 md:-left-8 bg-[#f2ede3] rounded-2xl px-4 py-3 md:px-5 md:py-4 border border-[#e0d9ce] shadow-[0_14px_28px_rgba(19,60,20,0.1)] w-[230px] md:w-[270px]">
				<?php
				foreach ( $c['stat_rows'] as $row ) :
					?>
					<div class="flex items-center justify-between gap-3 py-2.5 md:py-3 border-b border-[rgba(26,58,25,0.1)] last:border-b-0">
						<span class="text-[#c07a5a] uppercase tracking-[0.14em] shrink-0" style="font-size:10.5px;font-weight:700;">
							<?php echo esc_html( $row['label'] ); ?>
						</span>
						<span class="text-[#1a3a19] text-right" style="font-size:13.5px;font-weight:500;white-space:nowrap;">
							<?php echo esc_html( $row['value'] ); ?>
						</span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="md:pl-2 mt-6 md:mt-0">
			<p class="yum2-reveal text-[#c07a5a] tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;transition-delay:0.1s;">
				<?php echo esc_html( $c['label'] ); ?>
			</p>

			<h2 class="yum2-reveal text-[#1a3a19] mb-7" style="font-family:'Newsreader',serif;font-size:clamp(34px,5vw,58px);line-height:1.05;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;transition-delay:0.2s;">
				<?php echo esc_html( $c['heading'] ); ?>
				<em class="italic" style="color:#c07a5a;font-weight:400;"><?php echo esc_html( $c['heading_em'] ); ?></em>
			</h2>

			<p class="yum2-reveal text-[#3d4f3e] mb-6" style="font-size:17px;line-height:1.65;transition-delay:0.3s;">
				<?php echo esc_html( $c['bio'] ); ?>
			</p>

			<div class="yum2-reveal bg-[#f2ede3] border border-[#e0d9ce] rounded-[20px] p-5 md:p-6 mb-7" style="transition-delay:0.4s;">
				<p class="text-[#c07a5a] uppercase tracking-[0.14em] mb-4" style="font-size:11px;font-weight:700;">
					<?php echo esc_html( $c['beliefs_label'] ); ?>
				</p>
				<ul class="flex flex-col gap-3.5">
					<?php foreach ( $beliefs as $b ) : ?>
						<li class="grid grid-cols-[28px_1fr] gap-3 items-start">
							<span class="text-[#c07a5a] tracking-[0.1em] pt-[3px]" style="font-size:11px;font-weight:700;">
								<?php echo esc_html( $b['k'] ); ?>
							</span>
							<div>
								<span class="text-[#1a3a19] mr-2" style="font-family:'Newsreader',serif;font-size:17px;font-weight:500;letter-spacing:-0.005em;">
									<?php echo esc_html( $b['t'] ); ?>
								</span>
								<span class="italic text-[#3d4f3e]" style="font-family:'Newsreader',serif;font-size:16px;">
									<?php echo esc_html( $b['s'] ); ?>
								</span>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="yum2-reveal flex flex-nowrap md:flex-wrap justify-center md:justify-start gap-1.5 md:gap-2 mb-8" style="transition-delay:0.55s;">
				<?php foreach ( $approaches as $a ) : ?>
					<span
						class="bg-white border border-[rgba(26,58,25,0.12)] text-[#1a3a19] rounded-full px-2.5 py-1 md:px-4 md:py-1.5 transition-colors duration-300 hover:bg-[#2b5329] hover:text-white hover:border-[#2b5329] whitespace-nowrap cursor-default"
						style="font-size:11px;font-weight:600;"
						title="<?php echo esc_attr( $a['gist'] ); ?>"
					>
						<?php echo esc_html( $a['name'] ); ?>
					</span>
				<?php endforeach; ?>
			</div>

			<div class="yum2-reveal flex items-center justify-between gap-4 flex-wrap" style="transition-delay:0.65s;">
				<div class="flex items-center gap-3">
					<svg width="112" height="36" viewBox="0 0 112 36" fill="none" aria-hidden>
						<path d="M4 22 C10 8, 22 6, 26 18 C28 26, 20 30, 18 24 C16 16, 30 10, 38 16 C44 20, 40 28, 46 24 C52 20, 54 10, 62 14 C70 18, 64 28, 72 26 C82 24, 86 14, 96 18 C104 21, 102 28, 108 24" stroke="#1a3a19" stroke-width="1.6" stroke-linecap="round" fill="none"/>
						<path d="M18 32 L42 32" stroke="#c07a5a" stroke-width="1.2" stroke-linecap="round" opacity="0.7"/>
					</svg>
					<span class="italic text-[#3d4f3e]" style="font-family:'Newsreader',serif;font-size:14px;">
						<?php esc_html_e( 'Sanya', 'youumatter2' ); ?>
					</span>
				</div>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="group inline-flex items-center gap-1.5 text-[#2b5329] hover:text-[#1f3d1e] transition-colors" style="font-size:14px;font-weight:600;">
					<?php echo esc_html( $c['read_more'] ); ?>
					<span aria-hidden class="transition-transform group-hover:translate-x-1">&rarr;</span>
				</a>
			</div>
		</div>
	</div>
</section>
