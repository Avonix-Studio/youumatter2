<?php
/**
 * About: "Training & Practice" -- Education + Clinical Training + Frameworks.
 *
 * Hardcoded arrays per CLAUDE.md content strategy. Sanya can edit these
 * inline in this template part if her credentials change.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$education = array(
	array(
		'year'  => __( '2020 to 2022', 'youumatter2' ),
		'label' => __( 'M.A. Clinical Psychology', 'youumatter2' ),
		'place' => __( 'Amity University', 'youumatter2' ),
	),
	array(
		'year'  => __( '2017 to 2020', 'youumatter2' ),
		'label' => __( 'B.A. (Hons.) Psychology', 'youumatter2' ),
		'place' => __( 'University of Delhi', 'youumatter2' ),
	),
	array(
		'year'  => __( 'Ongoing', 'youumatter2' ),
		'label' => __( 'Continuing supervision', 'youumatter2' ),
		'place' => __( 'Peer & senior consultations, monthly', 'youumatter2' ),
	),
);

$hospitals = array(
	__( 'Sir Ganga Ram Hospital, Department of Psychiatry', 'youumatter2' ),
	__( 'Fortis Healthcare, Department of Mental Health & Behavioural Sciences', 'youumatter2' ),
	__( 'Delhi Mind Clinic', 'youumatter2' ),
	__( 'Kochhar Psychiatry Center', 'youumatter2' ),
	__( 'Sukoon Health Care', 'youumatter2' ),
);

$frameworks = array(
	__( 'CBT', 'youumatter2' ),
	__( 'Narrative Therapy', 'youumatter2' ),
	__( 'Mindfulness', 'youumatter2' ),
	__( 'Emotion-Focused', 'youumatter2' ),
);
?>
<section class="relative bg-sage-light px-5 md:px-8 py-20 md:py-28 overflow-hidden">
	<div class="relative max-w-5xl mx-auto">

		<p class="text-forest tracking-[2px] uppercase mb-5" style="font-size:12px;font-weight:600;">
			<?php esc_html_e( 'Training & Practice', 'youumatter2' ); ?>
		</p>
		<h2 class="text-forest mb-12" style="font-family:'Newsreader',serif;font-size:clamp(30px,4.4vw,48px);line-height:1.1;letter-spacing:-0.02em;font-weight:400;">
			<?php esc_html_e( 'Where the work was learned.', 'youumatter2' ); ?>
		</h2>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16">

			<div>
				<div class="flex items-center gap-2.5 mb-6">
					<span class="size-9 rounded-full bg-[#f8f3e9] flex items-center justify-center text-terracotta">
						<?php echo yum2_icon( 'graduation-cap', array( 'size' => 16, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<h3 class="text-forest" style="font-family:'Newsreader',serif;font-size:22px;font-weight:500;">
						<?php esc_html_e( 'Education', 'youumatter2' ); ?>
					</h3>
				</div>
				<ul class="flex flex-col list-none m-0 p-0">
					<?php foreach ( $education as $row ) : ?>
						<li class="py-4 border-b border-forest/18 last:border-b-0">
							<p class="text-terracotta tracking-[0.14em] uppercase mb-1" style="font-size:10.5px;font-weight:700;">
								<?php echo esc_html( $row['year'] ); ?>
							</p>
							<p class="text-forest" style="font-family:'Newsreader',serif;font-size:18px;font-weight:500;line-height:1.3;">
								<?php echo esc_html( $row['label'] ); ?>
							</p>
							<p class="text-forest/65 mt-0.5" style="font-size:13.5px;">
								<?php echo esc_html( $row['place'] ); ?>
							</p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div>
				<div class="flex items-center gap-2.5 mb-6">
					<span class="size-9 rounded-full bg-[#f8f3e9] flex items-center justify-center text-terracotta">
						<?php echo yum2_icon( 'building-2', array( 'size' => 16, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<h3 class="text-forest" style="font-family:'Newsreader',serif;font-size:22px;font-weight:500;">
						<?php esc_html_e( 'Clinical Training', 'youumatter2' ); ?>
					</h3>
				</div>
				<ul class="flex flex-col list-none m-0 p-0">
					<?php foreach ( $hospitals as $hospital ) : ?>
						<li class="py-3.5 border-b border-forest/18 last:border-b-0 text-forest" style="font-family:'Newsreader',serif;font-size:16.5px;font-weight:500;line-height:1.4;">
							<?php echo esc_html( $hospital ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<div class="mt-14 text-center">
			<p class="italic text-forest/65 mb-4" style="font-family:'Newsreader',serif;font-size:16px;">
				<?php esc_html_e( 'Therapy frameworks I draw from:', 'youumatter2' ); ?>
			</p>
			<div class="flex flex-wrap items-center justify-center gap-2">
				<?php foreach ( $frameworks as $f ) : ?>
					<span class="bg-[#f8f3e9] border border-forest/15 text-forest rounded-full px-4 py-1.5" style="font-size:13px;font-weight:600;">
						<?php echo esc_html( $f ); ?>
					</span>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
