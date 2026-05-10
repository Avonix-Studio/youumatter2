<?php
/**
 * About: "Why I do this work" section. 3 paragraphs + portrait card.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$portrait  = get_template_directory_uri() . '/assets/images/sanya-portrait.png';
$paragraphs = array(
	__( 'I came to therapy first as a client. What I found there, being heard without being fixed, changed how I moved through the world. I trained in psychology because I wanted to offer that same quiet to other people.', 'youumatter2' ),
	__( "Years later, after seeing clients across hospital wards, schools, and private practice, I'm still convinced of the simplest thing: most of us aren't broken. We're just carrying patterns we didn't choose, in a body that learned to brace too early.", 'youumatter2' ),
	__( 'My job is to sit with you while you put some of it down.', 'youumatter2' ),
);
?>
<section class="relative bg-cream px-5 md:px-8 py-20 md:py-28 overflow-hidden">
	<div class="relative max-w-6xl mx-auto">
		<p class="text-terracotta tracking-[2px] uppercase mb-5" style="font-size:12px;font-weight:600;">
			<?php esc_html_e( 'Why I do this work', 'youumatter2' ); ?>
		</p>
		<h2 class="text-forest mb-12" style="font-family:'Newsreader',serif;font-size:clamp(32px,5vw,56px);line-height:1.05;letter-spacing:-0.02em;font-weight:400;text-wrap:balance;">
			<?php esc_html_e( 'The short version:', 'youumatter2' ); ?>
			<br>
			<em class="italic" style="color:#c07a5a;"><?php esc_html_e( 'I was on the other side of the chair, first.', 'youumatter2' ); ?></em>
		</h2>

		<div class="grid grid-cols-1 md:grid-cols-[1.2fr_1fr] gap-10 md:gap-16 items-start">
			<div class="flex flex-col gap-5 order-2 md:order-1">
				<?php foreach ( $paragraphs as $p ) : ?>
					<p class="text-forest" style="font-size:17px;line-height:1.7;">
						<?php echo esc_html( $p ); ?>
					</p>
				<?php endforeach; ?>
				<p class="italic text-forest/65 mt-3 flex items-center gap-2" style="font-family:'Newsreader',serif;font-size:18px;">
					<span aria-hidden class="inline-block w-6 h-px bg-terracotta"></span>
					<?php esc_html_e( 'Sanya', 'youumatter2' ); ?>
				</p>
			</div>

			<div class="relative mx-auto md:ml-auto w-full max-w-[360px] order-1 md:order-2">
				<div aria-hidden class="absolute -inset-x-4 -bottom-5 top-6 rounded-[28px] bg-sage-light/55" style="transform: translate(20px, 16px);"></div>
				<div class="relative rounded-[24px] overflow-hidden shadow-[0_24px_48px_-20px_rgba(19,60,20,0.22)]" style="aspect-ratio: 4 / 5;">
					<img src="<?php echo esc_url( $portrait ); ?>" alt="<?php esc_attr_e( 'Sanya Oberoi', 'youumatter2' ); ?>" class="w-full h-full object-cover" loading="lazy" decoding="async">
				</div>
			</div>
		</div>
	</div>
</section>
