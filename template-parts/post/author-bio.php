<?php
/**
 * Author bio card. Avatar + bio + book CTA.
 *
 * Args:
 *   compact bool When true (sidebar usage), renders a tighter version.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args      = wp_parse_args( $args ?? array(), array( 'compact' => false ) );
$compact   = (bool) $args['compact'];
$author_id = (int) get_post_field( 'post_author', get_the_ID() );
$author    = $author_id ? get_userdata( $author_id ) : false;
$name      = $author ? $author->display_name : __( 'Sanya Oberoi', 'youumatter2' );
$bio       = $author ? trim( (string) get_the_author_meta( 'description', $author_id ) ) : '';

if ( '' === $bio ) {
	$bio = __( 'Counselling Psychologist (M.A. Clinical Psychology). Works with individuals and couples on anxiety, relationships, and emotional well-being. Online and in Pitampura, New Delhi.', 'youumatter2' );
}

$initials = '';
foreach ( preg_split( '/\s+/', $name ) as $p ) {
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
?>
<aside class="not-prose bg-[#f8f3e9] border border-forest/15 rounded-[18px] <?php echo $compact ? 'p-5' : 'p-6 md:p-7'; ?>">
	<div class="flex items-start gap-4">
		<div aria-hidden class="shrink-0 size-12 rounded-full flex items-center justify-center text-cream bg-forest" style="font-family:'Newsreader',serif;font-size:16px;font-weight:600;">
			<?php echo esc_html( $initials ); ?>
		</div>
		<div class="min-w-0">
			<p class="text-terracotta tracking-[0.14em] uppercase mb-0.5" style="font-size:10.5px;font-weight:700;">
				<?php esc_html_e( 'Written by', 'youumatter2' ); ?>
			</p>
			<p class="text-forest" style="font-family:'Newsreader',serif;font-size:<?php echo $compact ? '17' : '20'; ?>px;font-weight:500;">
				<?php echo esc_html( $name ); ?>
			</p>
		</div>
	</div>
	<p class="text-forest/65 mt-3" style="font-size:<?php echo $compact ? '13' : '14'; ?>px;line-height:1.55;">
		<?php echo esc_html( $bio ); ?>
	</p>
	<?php
	$calendly = (string) yum2_get_contact( 'calendly_url' );
	$onclick  = '' !== $calendly ? sprintf( 'return yum2OpenCalendly(%s)', wp_json_encode( $calendly ) ) : 'return false';
	?>
	<button
		type="button"
		class="inline-flex items-center gap-1.5 mt-4 text-forest hover:text-forest/80 transition-colors"
		style="font-size:13.5px;font-weight:600;"
		onclick="<?php echo esc_attr( $onclick ); ?>"
	>
		<?php
		echo esc_html(
			sprintf(
				/* translators: %s: author display name */
				__( 'Book a session with %s', 'youumatter2' ),
				$name
			)
		);
		?>
		<?php echo yum2_icon( 'arrow-right', array( 'size' => 13, 'stroke' => 2 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</button>
</aside>
