<?php 
	//For Normal Pages
	$form_title = get_sub_field('form_title');
    $form_details = get_sub_field('form_details');

    $email_details_title = get_sub_field('email_details_title');
    $email_details = get_sub_field('email_details');

    $opening_hours_title = get_sub_field('opening_hours_title');
    $opening_hours_details = get_sub_field('opening_hours_details');

    $social_icons = get_sub_field('social_icons');

    include 'extras.php';
?>
<section class="section section--contact-details white-bg <?php echo $sectionClass; ?>" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper p80 <?php echo $paddingClass; ?>">
		<div class="max-1250">
			<?php if($form_title): ?>
				<div class="mb-4 font18">
					<?php echo $form_title; ?>
				</div>
			<?php endif; ?>
			<div class="row contact-details">
				<?php if($form_details): ?>
					<div class="col-12 col-md-6 mb-5 mb-md-0">
						<?php
							$form_details = str_replace(['<p>', '</p>'], '', $form_details);
							echo $form_details;
						 ?> 
					</div>
				<?php endif; ?>
				<div class="col-12 col-md-6">
					<div class="row">
						<?php if($email_details): ?>
						<div class="col-12 col-md-6">
							<div class="b-radius grey-bg p-4 pb-5">
								<div class="">
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/email-cropped.svg" height="35" width="35" alt="email icon" class="icon35">
								</div>
								<div class="my-3"><strong><?php echo $email_details_title; ?></strong></div>
								<div>
									<?php echo $email_details; ?>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<?php if($opening_hours_details): ?>
						<div class="col-12 col-md-6">
							<div class="b-radius grey-bg p-4 pb-5">
								<div class="">
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/clock-cropped.svg" height="35" width="35" alt="clock icon" class="icon35">
								</div>
								<div class="my-3"><strong><?php echo $opening_hours_title; ?></strong></div>
								<div>
									<?php echo $opening_hours_details; ?>
								</div>
							</div>
						</div>
						<?php endif; ?>
					</div>
					<?php if($social_icons): ?>
						<div class="row gx-0 mt-4">
							<div class="col-12 row">
								<?php foreach( $social_icons as $key => $social_icon ): ?>
									<?php
								        // Determine if it's the first or last element
								        $is_first = ($key === 0);
								        $is_last = ($key === count($social_icons) - 1);

								        // Build the class string
								        $classes = '';
								        if (!$is_last) {
								            $classes .= ' me-3 ';
								        }
								    ?>
									<div class="col-1 grey-bg b-radius p-3 <?php echo $classes; ?> d-flex align-items-center justify-content-center social-icon">
										<a href="<?php echo $social_icon['link']['url']; ?>">
											<img src="<?php echo $social_icon['icon']['url']; ?>" height="20" width="20" alt="<?php echo $social_icon['link']['title']; ?>">
										</a>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>