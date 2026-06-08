<?php
/**
 * About: "Training & Practice" -- Education timeline + Clinical Training cards
 * + Therapy Frameworks grid, on a deep forest background.
 *
 * Hardcoded arrays per CLAUDE.md content strategy. Sanya can edit these inline
 * here if her credentials change. To hide the "Department of ..." sub-line on
 * a hospital card, just leave its 'dept' empty.
 *
 * @package youumatter2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Education timeline reads from the "Site Content" admin
   (Settings → Site Content → About training) with these defaults as fallback.
   Hospitals + frameworks below stay hardcoded -- credentials change rarely. */
$education_default = array(
	array(
		'year'  => __( '2020 – 2022', 'youumatter2' ),
		'label' => __( 'M.A. Clinical Psychology', 'youumatter2' ),
		'place' => __( 'Amity University', 'youumatter2' ),
	),
	array(
		'year'  => __( '2017 – 2020', 'youumatter2' ),
		'label' => __( 'B.A. (Hons.) Psychology', 'youumatter2' ),
		'place' => __( 'Amity University', 'youumatter2' ),
	),
	array(
		'year'  => __( 'Ongoing', 'youumatter2' ),
		'label' => __( 'Continuing supervision', 'youumatter2' ),
		'place' => __( 'Peer & senior consultations, monthly', 'youumatter2' ),
	),
);

$education_rows = yum2_field( 'about_training', array() );
$education      = array();
if ( ! empty( $education_rows ) && is_array( $education_rows ) ) {
	foreach ( $education_rows as $row ) {
		$education[] = array(
			'year'  => isset( $row['year'] ) ? (string) $row['year'] : '',
			'label' => isset( $row['label'] ) ? (string) $row['label'] : '',
			'place' => isset( $row['place'] ) ? (string) $row['place'] : '',
		);
	}
}
if ( empty( $education ) ) {
	$education = $education_default;
}

$hospitals = array(
	array(
		'name' => __( 'Sir Ganga Ram Hospital', 'youumatter2' ),
		'dept' => __( 'Department of Psychiatry', 'youumatter2' ),
	),
	array(
		'name' => __( 'Fortis Healthcare', 'youumatter2' ),
		'dept' => __( 'Department of Mental Health & Behavioural Sciences', 'youumatter2' ),
	),
	array(
		'name' => __( 'Delhi Mind Clinic', 'youumatter2' ),
		'dept' => '',
	),
	array(
		'name' => __( 'Kochhar Psychiatry Center', 'youumatter2' ),
		'dept' => '',
	),
);

$frameworks = array(
	array(
		'label' => __( 'CBT', 'youumatter2' ),
		'desc'  => __( 'Cognitive Behavioural', 'youumatter2' ),
	),
	array(
		'label' => __( 'Narrative Therapy', 'youumatter2' ),
		'desc'  => __( 'Story & meaning', 'youumatter2' ),
	),
	array(
		'label' => __( 'Mindfulness', 'youumatter2' ),
		'desc'  => __( 'Present-moment', 'youumatter2' ),
	),
	array(
		'label' => __( 'Emotion-Focused', 'youumatter2' ),
		'desc'  => __( 'EFT', 'youumatter2' ),
	),
);
?>
<section class="relative bg-[#1a3a19] px-5 md:px-8 py-20 md:py-28 overflow-hidden">

	<?php /* Decorative giant italic word, very faint, centered behind the content. */ ?>
	<div aria-hidden class="absolute inset-0 flex items-center justify-center select-none pointer-events-none">
		<span style="font-family:'Newsreader',serif;font-size:clamp(96px,24vw,320px);font-weight:400;font-style:italic;line-height:1;color:rgba(255,255,255,0.045);letter-spacing:-0.04em;white-space:nowrap;">
			<?php esc_html_e( 'Training', 'youumatter2' ); ?>
		</span>
	</div>

	<div class="relative max-w-5xl mx-auto">

		<p class="yum2-reveal tracking-[2.5px] uppercase mb-4" style="font-size:11.5px;font-weight:700;color:#c07a5a;transition-delay:0s;">
			<?php esc_html_e( 'Training & Practice', 'youumatter2' ); ?>
		</p>
		<h2 class="yum2-reveal italic mb-16" style="font-family:'Newsreader',serif;font-size:clamp(30px,4.4vw,52px);line-height:1.1;letter-spacing:-0.025em;font-weight:400;color:#f2ede3;transition-delay:0.08s;">
			<?php esc_html_e( 'Where the work was learned.', 'youumatter2' ); ?>
		</h2>

		<div class="grid grid-cols-1 lg:grid-cols-2 gap-14 lg:gap-20 mb-16">

			<?php /* Education timeline */ ?>
			<div>
				<div class="yum2-reveal flex items-center gap-2.5 mb-8" style="transition-delay:0.16s;">
					<span class="size-8 rounded-full bg-white/[0.08] flex items-center justify-center" style="color:#c07a5a;">
						<?php echo yum2_icon( 'graduation-cap', array( 'size' => 15, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<h3 class="uppercase" style="font-family:'Newsreader',serif;font-size:13px;font-weight:600;letter-spacing:0.12em;color:#8aab88;">
						<?php esc_html_e( 'Education', 'youumatter2' ); ?>
					</h3>
				</div>

				<div class="relative pl-6">
					<div aria-hidden class="absolute left-0 top-2 bottom-2 w-px" style="background:#c07a5a;opacity:0.4;"></div>
					<ul class="flex flex-col gap-8 list-none m-0 p-0">
						<?php foreach ( $education as $i => $row ) : ?>
							<li class="yum2-reveal relative" style="transition-delay:<?php echo esc_attr( number_format( 0.22 + $i * 0.08, 2 ) ); ?>s;">
								<span aria-hidden class="absolute -left-[25px] top-1.5 size-2 rounded-full" style="background:#c07a5a;"></span>
								<p class="tracking-[0.12em] uppercase mb-1.5" style="font-size:10px;font-weight:700;color:#c07a5a;">
									<?php echo esc_html( $row['year'] ); ?>
								</p>
								<p style="font-family:'Newsreader',serif;font-size:19px;font-weight:500;line-height:1.25;letter-spacing:-0.01em;color:#f2ede3;">
									<?php echo esc_html( $row['label'] ); ?>
								</p>
								<p class="mt-0.5" style="font-size:13.5px;color:#a8c5a6;">
									<?php echo esc_html( $row['place'] ); ?>
								</p>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>

			<?php /* Clinical Training cards */ ?>
			<div>
				<div class="yum2-reveal flex items-center gap-2.5 mb-8" style="transition-delay:0.22s;">
					<span class="size-8 rounded-full bg-white/[0.08] flex items-center justify-center" style="color:#c07a5a;">
						<?php echo yum2_icon( 'building-2', array( 'size' => 15, 'stroke' => 1.8 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<h3 class="uppercase" style="font-family:'Newsreader',serif;font-size:13px;font-weight:600;letter-spacing:0.12em;color:#8aab88;">
						<?php esc_html_e( 'Clinical Training', 'youumatter2' ); ?>
					</h3>
				</div>

				<ul class="flex flex-col gap-3 list-none m-0 p-0">
					<?php foreach ( $hospitals as $i => $h ) : ?>
						<li class="yum2-reveal rounded-2xl px-5 py-4 border transition-colors duration-300 hover:border-[#c07a5a]"
							style="background:#213f20;border-color:#2e5c2c;transition-delay:<?php echo esc_attr( number_format( 0.28 + $i * 0.07, 2 ) ); ?>s;">
							<p style="font-family:'Newsreader',serif;font-size:16.5px;font-weight:500;line-height:1.3;color:#f2ede3;">
								<?php echo esc_html( $h['name'] ); ?>
							</p>
							<?php if ( '' !== $h['dept'] ) : ?>
								<p class="mt-0.5" style="font-size:12.5px;color:#a8c5a6;">
									<?php echo esc_html( $h['dept'] ); ?>
								</p>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<?php /* Therapy frameworks strip */ ?>
		<div class="yum2-reveal pt-10 border-t" style="border-color:#2e5c2c;transition-delay:0.4s;">
			<p class="mb-6 uppercase tracking-[2px]" style="font-size:11px;font-weight:700;color:#8aab88;">
				<?php esc_html_e( 'Therapy frameworks', 'youumatter2' ); ?>
			</p>
			<div class="grid grid-cols-2 md:grid-cols-4 gap-3">
				<?php foreach ( $frameworks as $i => $f ) : ?>
					<div class="yum2-reveal rounded-2xl px-5 py-4 border"
						style="background:#213f20;border-color:#2e5c2c;transition-delay:<?php echo esc_attr( number_format( 0.46 + $i * 0.07, 2 ) ); ?>s;">
						<p style="font-family:'Newsreader',serif;font-size:16px;font-weight:500;line-height:1.2;color:#f2ede3;">
							<?php echo esc_html( $f['label'] ); ?>
						</p>
						<p class="mt-1" style="font-size:11.5px;color:#a8c5a6;">
							<?php echo esc_html( $f['desc'] ); ?>
						</p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
