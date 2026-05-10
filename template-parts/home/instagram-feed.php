<?php
/**
 * Home: Instagram feed.
 *
 * TODO Phase 6+: replace static $tiles with Behold.so embed once Sanya
 * creates the account. Behold gives a div + script tag that auto-populates
 * from her IG. Until then, this static placeholder keeps the section
 * visually correct.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! get_theme_mod( 'yum2_home_show_instagram', true ) ) {
	return;
}

$ig_url    = (string) yum2_get_contact( 'instagram' );
$ig_handle = (string) yum2_get_contact( 'instagram_handle' );
if ( '' === $ig_url ) {
	return;
}

$tiles = array(
	array(
		'caption'  => __( "On the days the 'what-ifs' win, try this one grounding practice.", 'youumatter2' ),
		'likes'    => '124k',
		'comments' => '842',
		'is_reel'  => true,
		'bg'       => '#c8dcc7',
	),
	array(
		'caption'  => __( 'What to say when your inner critic gets loud.', 'youumatter2' ),
		'likes'    => '96k',
		'comments' => '1.2k',
		'is_reel'  => true,
		'bg'       => '#ede0d0',
	),
	array(
		'caption'  => __( "Boundaries aren't walls. They're doors with handles on the inside.", 'youumatter2' ),
		'likes'    => '58k',
		'comments' => '412',
		'is_reel'  => true,
		'bg'       => '#d1e5d0',
	),
	array(
		'caption'  => __( 'A reminder for anyone rebuilding their self-worth this week.', 'youumatter2' ),
		'likes'    => '214k',
		'comments' => '3.1k',
		'is_reel'  => true,
		'bg'       => '#e4efe3',
	),
	array(
		'caption'  => __( "Rest isn't a reward. It's the ground the rest grows from.", 'youumatter2' ),
		'likes'    => '42k',
		'comments' => '268',
		'is_reel'  => true,
		'bg'       => '#f8f3e9',
	),
	array(
		'caption'  => __( 'A quiet Sunday practice: ask what felt honest this week.', 'youumatter2' ),
		'likes'    => '1.2k',
		'comments' => '46',
		'is_reel'  => false,
		'bg'       => '#f2ede3',
	),
);
?>
<section class="relative bg-cream px-5 md:px-8 pt-14 md:pt-20 pb-14 md:pb-20 overflow-hidden">
	<div class="relative max-w-6xl mx-auto">

		<div class="grid grid-cols-1 md:grid-cols-[1.2fr_1fr] gap-5 md:gap-14 items-end mb-10 md:mb-12">
			<div>
				<p class="text-terracotta tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;">
					<?php esc_html_e( 'On Instagram', 'youumatter2' ); ?>
				</p>
				<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(30px,4.8vw,52px);line-height:1.08;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
					<?php esc_html_e( 'Small reminders,', 'youumatter2' ); ?>
					<em class="italic" style="color:#c07a5a;font-weight:400;"><?php esc_html_e( 'in your feed.', 'youumatter2' ); ?></em>
				</h2>
			</div>
			<div class="flex flex-col gap-4">
				<p class="italic text-forest/65" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;">
					<?php esc_html_e( 'Reflections, reels, and quiet reminders. Shared between sessions.', 'youumatter2' ); ?>
				</p>
				<a
					href="<?php echo esc_url( $ig_url ); ?>"
					target="_blank" rel="noopener noreferrer"
					class="inline-flex items-center gap-2.5 self-start bg-forest hover:bg-forest/90 text-cream rounded-full h-[46px] px-5 transition-colors shadow-[0_10px_24px_rgba(26,58,25,0.16)]"
					style="font-size:14px;font-weight:600;"
				>
					<?php echo yum2_icon( 'instagram', array( 'size' => 16, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
					if ( '' !== $ig_handle ) {
						echo esc_html(
							sprintf(
								/* translators: %s: instagram handle, e.g. @youumatter2withsanya */
								__( 'Follow %s', 'youumatter2' ),
								$ig_handle
							)
						);
					} else {
						esc_html_e( 'Follow on Instagram', 'youumatter2' );
					}
					?>
				</a>
			</div>
		</div>

		<div
			id="behold-feed"
			class="flex gap-3 md:gap-4 overflow-x-auto snap-x snap-mandatory pr-8 md:pr-10"
			style="scrollbar-width:none;"
		>
			<?php foreach ( $tiles as $tile ) : ?>
				<div class="shrink-0 snap-start w-[58%] sm:w-[40%] md:w-[25%] lg:w-[22%]">
					<a
						href="<?php echo esc_url( $ig_url ); ?>"
						target="_blank" rel="noopener noreferrer"
						class="group relative block aspect-[9/16] rounded-[18px] overflow-hidden border border-forest/15 hover:border-forest/35 hover:shadow-[0_22px_44px_-18px_rgba(26,58,25,0.16)] transition-[border-color,box-shadow,transform] duration-500 hover:-translate-y-1"
						style="background:<?php echo esc_attr( $tile['bg'] ); ?>;"
					>
						<div aria-hidden class="absolute inset-0 opacity-40 pointer-events-none" style="background:radial-gradient(circle at 70% 20%, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0) 60%);"></div>

						<div class="relative h-full p-5 flex flex-col justify-between">
							<div class="flex items-start justify-between">
								<span class="inline-flex items-center gap-1.5 bg-white/80 backdrop-blur-sm border border-forest/8 text-forest rounded-full px-2.5 py-1" style="font-size:10px;font-weight:700;letter-spacing:0.1em;">
									<?php echo yum2_icon( 'instagram', array( 'size' => 10, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php echo $tile['is_reel'] ? esc_html__( 'POST', 'youumatter2' ) : esc_html__( 'POST', 'youumatter2' ); ?>
								</span>
								<?php if ( $tile['is_reel'] ) : ?>
									<span class="inline-flex items-center gap-1 bg-black/60 backdrop-blur-sm text-white rounded-full px-2 py-1" style="font-size:9.5px;font-weight:700;letter-spacing:0.1em;">
										<?php echo yum2_icon( 'play', array( 'size' => 9, 'stroke' => 2.5 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<?php esc_html_e( 'REEL', 'youumatter2' ); ?>
									</span>
								<?php endif; ?>
							</div>

							<p class="italic text-forest line-clamp-4" style="font-family:'Newsreader',serif;font-size:clamp(14px,1.3vw,16px);line-height:1.35;font-weight:500;">
								&ldquo;<?php echo esc_html( $tile['caption'] ); ?>&rdquo;
							</p>

							<div class="flex items-center gap-4 text-forest/65" style="font-size:11.5px;font-weight:600;">
								<span class="inline-flex items-center gap-1">
									<?php echo yum2_icon( 'heart', array( 'size' => 12, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php echo esc_html( $tile['likes'] ); ?>
								</span>
								<span class="inline-flex items-center gap-1">
									<?php echo yum2_icon( 'message-circle', array( 'size' => 12, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php echo esc_html( $tile['comments'] ); ?>
								</span>
							</div>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
