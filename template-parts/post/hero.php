<?php
/**
 * Single-post hero card. Sage-light rounded card with breadcrumbs,
 * category pill, H1, italic excerpt, byline row.
 *
 * Caller is inside The Loop.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cats        = get_the_category();
$primary_cat = ! empty( $cats ) ? $cats[0] : null;
$crumbs      = yum2_breadcrumb();
$post_id     = get_the_ID();
$author_name = get_the_author();
$initials    = '';
$parts       = preg_split( '/\s+/', $author_name );
foreach ( $parts as $p ) {
	if ( '' !== $p ) {
		$initials .= strtoupper( substr( $p, 0, 1 ) );
		if ( strlen( $initials ) >= 2 ) {
			break;
		}
	}
}
if ( '' === $initials ) {
	$initials = 'SO';
}
$excerpt = get_the_excerpt();
?>
<section class="relative px-5 md:px-8 pt-6 md:pt-10 bg-cream">
	<div class="max-w-6xl mx-auto">
		<div class="relative bg-sage-light rounded-[28px] md:rounded-[40px] px-6 md:px-14 pt-7 md:pt-10 pb-9 md:pb-12 overflow-hidden">
			<div aria-hidden class="absolute -top-24 -right-24 w-[360px] h-[360px] rounded-full pointer-events-none" style="background:radial-gradient(circle at center, rgba(228,239,227,0.7) 0%, rgba(200,220,199,0) 65%);"></div>

			<div class="relative max-w-3xl">
				<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'youumatter2' ); ?>" class="flex items-center flex-wrap gap-1.5 text-forest/70" style="font-size:12.5px;">
					<?php $count = count( $crumbs ); foreach ( $crumbs as $i => $c ) : ?>
						<?php if ( '' !== $c['url'] ) : ?>
							<a href="<?php echo esc_url( $c['url'] ); ?>" class="hover:text-forest transition-colors"><?php echo esc_html( $c['label'] ); ?></a>
						<?php else : ?>
							<?php
							// Last crumb (current page) shows the post title truncated.
							$is_last     = ( $i === $count - 1 );
							$is_category = ( $primary_cat && $c['label'] === $primary_cat->name );
							?>
							<span
								class="<?php echo $is_category ? 'text-terracotta' : ( $is_last ? 'text-forest truncate max-w-[160px] sm:max-w-[260px] md:max-w-[360px]' : '' ); ?>"
								<?php if ( $is_category ) : ?>style="font-weight:600;"<?php endif; ?>
								<?php if ( $is_last ) : ?>title="<?php echo esc_attr( $c['label'] ); ?>"<?php endif; ?>
							>
								<?php echo esc_html( $c['label'] ); ?>
							</span>
						<?php endif; ?>
						<?php if ( $i < $count - 1 ) : ?>
							<span aria-hidden class="text-forest/40">
								<?php echo yum2_icon( 'chevron-right', array( 'size' => 12, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						<?php endif; ?>
					<?php endforeach; ?>
				</nav>

				<?php if ( $primary_cat ) : ?>
					<a href="<?php echo esc_url( get_category_link( $primary_cat ) ); ?>" class="inline-flex items-center bg-forest text-cream tracking-[0.16em] uppercase rounded-full px-4 py-1.5 mt-5 hover:bg-forest/90 transition-colors" style="font-size:10.5px;font-weight:700;">
						<?php echo esc_html( $primary_cat->name ); ?>
					</a>
				<?php endif; ?>

				<h1 class="text-forest mt-4" style="font-family:'Newsreader',serif;font-size:clamp(32px,5vw,54px);line-height:1.08;letter-spacing:-0.02em;font-weight:500;text-wrap:balance;">
					<?php the_title(); ?>
				</h1>

				<?php if ( '' !== $excerpt ) : ?>
					<p class="italic text-forest/75 mt-4 max-w-2xl" style="font-family:'Newsreader',serif;font-size:clamp(16px,1.6vw,19px);line-height:1.55;">
						<?php echo esc_html( $excerpt ); ?>
					</p>
				<?php endif; ?>

				<div class="mt-6 pt-5 border-t border-forest/15 flex items-center gap-3.5 flex-wrap">
					<div aria-hidden class="shrink-0 size-11 rounded-full flex items-center justify-center text-cream bg-forest" style="font-family:'Newsreader',serif;font-size:15px;font-weight:600;">
						<?php echo esc_html( $initials ); ?>
					</div>
					<div class="min-w-0">
						<p class="text-forest" style="font-size:14px;font-weight:600;">
							<?php echo esc_html( $author_name ); ?>
						</p>
						<div class="flex items-center flex-wrap gap-x-3 gap-y-1 text-forest/70 mt-0.5" style="font-size:12.5px;">
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="inline-flex items-center gap-1.5">
								<?php echo yum2_icon( 'calendar', array( 'size' => 11, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo esc_html( get_the_date( get_option( 'date_format' ) ) ); ?>
							</time>
							<span class="inline-flex items-center gap-1.5">
								<?php echo yum2_icon( 'clock', array( 'size' => 11, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php echo esc_html( yum2_reading_time( $post_id ) ); ?>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
