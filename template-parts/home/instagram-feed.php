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

/* Copy lives in inc/content.php under 'instagram'.
 * Try the live Behold feed first; fall back to placeholder tiles if Behold
 * is unreachable or hasn't returned data yet. */
$c          = yum2_content( 'instagram' );
$live_posts = yum2_get_instagram_posts( 6 );
$is_live    = ! empty( $live_posts );
$tiles      = $is_live ? $live_posts : $c['tiles'];

/* Alpine modal state needs JSON-encoded post data for in-page playback.
 * Only live posts get the modal treatment; placeholders link out to IG. */
$modal_data = $is_live ? wp_json_encode( array_values( $live_posts ) ) : '[]';
?>
<section class="relative bg-cream px-5 md:px-8 pt-14 md:pt-20 pb-14 md:pb-20 overflow-hidden" x-data='yum2IgFeed(<?php echo $modal_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>)' x-init="init()">
	<div class="relative max-w-6xl mx-auto">

		<div class="grid grid-cols-1 md:grid-cols-[1.2fr_1fr] gap-5 md:gap-14 items-end mb-10 md:mb-12">
			<div>
				<p class="text-terracotta tracking-[2px] uppercase mb-4" style="font-size:12px;font-weight:600;">
					<?php echo esc_html( $c['label'] ); ?>
				</p>
				<h2 class="text-forest" style="font-family:'Newsreader',serif;font-size:clamp(30px,4.8vw,52px);line-height:1.08;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
					<?php echo esc_html( $c['heading'] ); ?>
					<em class="italic" style="color:#c07a5a;font-weight:400;"><?php echo esc_html( $c['heading_em'] ); ?></em>
				</h2>
			</div>
			<div class="flex flex-col gap-4">
				<p class="italic text-forest/65" style="font-family:'Newsreader',serif;font-size:18px;line-height:1.55;">
					<?php echo esc_html( $c['description'] ); ?>
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
			<?php
			foreach ( $tiles as $i => $tile ) :
				$has_thumb       = $is_live && ! empty( $tile['thumb'] );
				$is_live_video   = $is_live && ! empty( $tile['media_url'] ) && 'VIDEO' === ( isset( $tile['media_type'] ) ? $tile['media_type'] : '' );
				/* Live tiles open the modal; placeholders link out to IG. */
				$tile_href       = $is_live && ! empty( $tile['permalink'] ) ? $tile['permalink'] : $ig_url;
				$badge_label     = $tile['is_reel'] ? __( 'Reel', 'youumatter2' ) : __( 'Post', 'youumatter2' );
				$aria_open_label = sprintf(
					/* translators: %s: post type, "Reel" or "Post" */
					__( 'Play Instagram %s', 'youumatter2' ),
					$badge_label
				);

				$card_classes = 'group relative block aspect-[9/16] w-full rounded-[18px] overflow-hidden border border-forest/15 hover:border-forest/35 hover:shadow-[0_22px_44px_-18px_rgba(26,58,25,0.16)] transition-[border-color,box-shadow,transform] duration-500 hover:-translate-y-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-forest focus-visible:ring-offset-2 focus-visible:ring-offset-cream cursor-pointer';
				?>
				<div class="shrink-0 snap-start w-[58%] sm:w-[40%] md:w-[25%] lg:w-[22%]">
					<?php if ( $is_live ) : ?>
						<button
							type="button"
							@click="open(<?php echo (int) $i; ?>)"
							aria-label="<?php echo esc_attr( $aria_open_label ); ?>"
							class="<?php echo esc_attr( $card_classes ); ?>"
							style="background:#e4efe3;"
						>
					<?php else : ?>
						<a
							href="<?php echo esc_url( $tile_href ); ?>"
							target="_blank" rel="noopener noreferrer"
							class="<?php echo esc_attr( $card_classes ); ?>"
							style="background:<?php echo esc_attr( isset( $tile['bg'] ) ? $tile['bg'] : '#e4efe3' ); ?>;"
						>
					<?php endif; ?>

						<?php if ( $has_thumb ) : ?>
							<img
								src="<?php echo esc_url( $tile['thumb'] ); ?>"
								alt=""
								loading="lazy" decoding="async"
								class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]"
							>
							<div aria-hidden class="absolute inset-0 pointer-events-none" style="background:linear-gradient(to bottom, rgba(0,0,0,0.18) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0) 55%, rgba(0,0,0,0.6) 100%);"></div>
						<?php else : ?>
							<div aria-hidden class="absolute inset-0 opacity-40 pointer-events-none" style="background:radial-gradient(circle at 70% 20%, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0) 60%);"></div>
						<?php endif; ?>

						<?php /* Centered play affordance on reels - the universal "this plays" signal. */ ?>
						<?php if ( $tile['is_reel'] ) : ?>
							<div aria-hidden class="absolute inset-0 flex items-center justify-center pointer-events-none">
								<span class="flex items-center justify-center size-14 rounded-full bg-white/95 backdrop-blur-sm shadow-[0_12px_28px_rgba(0,0,0,0.35)] transition-transform duration-300 group-hover:scale-110">
									<svg viewBox="0 0 24 24" class="size-6 text-forest translate-x-[2px]" fill="currentColor" aria-hidden>
										<path d="M8 5v14l11-7z"/>
									</svg>
								</span>
							</div>
						<?php endif; ?>

						<?php /* Top-left dark pill — high contrast on any thumbnail. */ ?>
						<div class="relative h-full p-4 flex flex-col justify-between">
							<div class="flex items-start justify-between">
								<span class="inline-flex items-center gap-1.5 bg-black/65 backdrop-blur-sm text-white rounded-full px-2.5 py-1" style="font-size:10px;font-weight:700;letter-spacing:0.1em;">
									<?php echo yum2_icon( 'instagram', array( 'size' => 11, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<span class="uppercase tracking-[0.12em]"><?php echo esc_html( $badge_label ); ?></span>
								</span>
							</div>

							<?php if ( ! empty( $tile['caption'] ) ) : ?>
								<p class="italic line-clamp-3 <?php echo $has_thumb ? 'text-white' : 'text-forest'; ?>" style="font-family:'Newsreader',serif;font-size:clamp(13px,1.2vw,15px);line-height:1.35;font-weight:500;text-shadow:<?php echo $has_thumb ? '0 1px 8px rgba(0,0,0,0.5)' : 'none'; ?>;">
									<?php echo esc_html( $tile['caption'] ); ?>
								</p>
							<?php else : ?>
								<span></span>
							<?php endif; ?>
						</div>

					<?php if ( $is_live ) : ?>
						</button>
					<?php else : ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php /* Lightbox modal - reused for all reel/post clicks. Only mounts when live data is available. */ ?>
	<?php if ( $is_live ) : ?>
		<div
			x-show="activeIndex !== null"
			x-cloak
			x-transition.opacity.duration.200ms
			@keydown.escape.window="close()"
			@click.self="close()"
			class="fixed inset-0 z-50 flex items-center justify-center p-4 md:p-8 bg-black/80 backdrop-blur-sm"
			role="dialog"
			aria-modal="true"
			:aria-label="current.is_reel ? '<?php echo esc_js( __( 'Instagram Reel', 'youumatter2' ) ); ?>' : '<?php echo esc_js( __( 'Instagram Post', 'youumatter2' ) ); ?>'"
		>
			<div
				@click.stop
				x-transition:enter="transition ease-out duration-300"
				x-transition:enter-start="opacity-0 scale-95"
				x-transition:enter-end="opacity-100 scale-100"
				class="relative w-full max-w-md max-h-[90vh] flex flex-col bg-[#0a0f0a] rounded-[20px] overflow-hidden shadow-[0_24px_80px_rgba(0,0,0,0.5)]"
			>
				<button
					type="button"
					@click="close()"
					aria-label="<?php esc_attr_e( 'Close', 'youumatter2' ); ?>"
					class="absolute top-3 right-3 z-10 size-10 rounded-full bg-black/55 hover:bg-black/75 text-white flex items-center justify-center backdrop-blur-sm transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60"
				>
					<svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden>
						<path d="M18 6L6 18M6 6l12 12"/>
					</svg>
				</button>

				<?php /* Video for reels, image for posts. Only one mounts per open. */ ?>
				<template x-if="current.media_type === 'VIDEO' && current.media_url">
					<video
						x-ref="player"
						:src="current.media_url"
						:poster="current.thumb"
						controls
						playsinline
						preload="metadata"
						class="w-full aspect-[9/16] bg-black object-contain"
					></video>
				</template>
				<template x-if="current.media_type !== 'VIDEO' && current.thumb">
					<img :src="current.thumb" :alt="current.caption" class="w-full aspect-[9/16] bg-black object-contain">
				</template>

				<div class="p-4 md:p-5 bg-[#0a0f0a] text-white flex flex-col gap-3">
					<p x-show="current.caption" x-text="current.caption" class="italic text-white/85 line-clamp-3" style="font-family:'Newsreader',serif;font-size:15px;line-height:1.45;"></p>
					<a
						:href="current.permalink"
						target="_blank" rel="noopener noreferrer"
						class="self-start inline-flex items-center gap-2 text-white/80 hover:text-white text-sm font-semibold transition-colors"
					>
						<?php echo yum2_icon( 'instagram', array( 'size' => 14, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php esc_html_e( 'View on Instagram', 'youumatter2' ); ?>
						<span aria-hidden>&rarr;</span>
					</a>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>
