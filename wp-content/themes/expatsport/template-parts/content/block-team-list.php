<?php 
    $title = get_sub_field('title');
    $description = get_sub_field('description');
    $listing_format = get_sub_field('listing_format');
    $team_members = get_sub_field('team_members');
    $enable_circle_dots_bg = get_sub_field('enable_circle_dots_bg');
    $enable_slider = get_sub_field('enable_slider');
	include 'extras.php';
?>
<section class="section section--team-listing" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper p80 <?php echo $paddingClass; ?>">
		<?php if($title): ?>
			<div class="heading-sc">
				<h2 class="text-upper m-0 text-center"><?php echo $title; ?></h2>
			</div>
		<?php endif; ?>
		<?php if($description): ?>
			<div class="max-1024 text-center">
				<?php echo $description; ?>
			</div>
		<?php endif; ?>
		<?php if($listing_format == 'textontop'): ?>
			<div class="<?php echo $maxWidth; ?> mt-5 teamwithfixedHeight">
				<?php if($team_members): ?>
				<div class="row gx-0 justify-content-center align-items-start">
					<?php foreach( $team_members as $key => $team_member ): ?>
					<div class="col-12 col-md-4 team-each b-radius ">
						<?php
					        $is_first = ($key === 0);
					        $is_last = ($key === count($team_members) - 1);
					        $classes = '';
					        if (!$is_last) {
					            $classes .= 'mb-3 mb-md-0 ';
					        }
					    ?>
						<div class="<?php echo trim($classes); ?>">
							<div class="team-listing-each__inner overlay d-flex align-items-end justify-content-center b-radius">
								<div class="team-listing-each__image">
									<div class="image-fill">
										<?php 
											$imageID = get_post_thumbnail_id($team_member->ID);
											echo get_custom_image($imageID, false, $class = 'b-radius', $team_member->post_title ); 
										?>
									</div>
								</div>
								<div class="team-listing-each__text text-center">
									<div class="team-listing-each__title Fira-Bold"><?php echo $team_member->post_title; ?></div>
									<div class="team-listing-each__position">
										<?php echo get_field('position_title',$team_member->ID); ?>
									</div>
									<div class="team-listing-each__descp m-0 my-4 d-none">
										<?php echo $team_member->post_content;; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		<?php else: ?>
			<div class="<?php echo $maxWidth; ?>">
				<div class="row gx-0 mt-5 teamwithfixedImgHeight  <?php echo ($enable_slider? 'team-slider':''); ?>">
					<?php foreach( $team_members as $key => $team_member ): ?>
						<?php
					        $is_first = ($key === 0);
					        $is_last = ($key === count($team_members) - 1);
					        $classes = '';
					        if (!$is_last) {
					            $classes .= 'mb-3 mb-md-0 ';
					        }
					    ?>
						<div class="col team-each">
							<div class="<?php echo trim($classes); ?>">
								<div class="team-listing-each__inner border b-radius">
									<div class="team-listing-each__image  b-radius">
										<div class="image-fill">
											<?php 
												$imageID = get_post_thumbnail_id($team_member->ID);
												echo get_custom_image($imageID, false, $class = '', $team_member->post_title ); 
											?>
										</div>
									</div>
									<div class="team-listing-each__text white-bg">
										<div class="team-listing-each__title Fira-Bold"><?php echo $team_member->post_title; ?></div>
										<div class="team-listing-each__position">
											<?php echo get_field('position_title',$team_member->ID); ?>
										</div>
										<div class="primary-color team-listing-each__link">
											<a class="view-team">About <?php echo $team_member->post_title; ?> ></a>
										</div>
									</div>
									<div class="team-listing-each__descp d-flex flex-column justify-content-between">
										<div>
											<div class="team-listing-each__title Fira-Bold"><?php echo $team_member->post_title; ?></div>
											<div class="team-listing-each__position">
												<?php echo get_field('position_title',$team_member->ID); ?>
											</div>
											<div class="team-listing-each__link">
												<?php echo $team_member->post_content;; ?>
											</div>
										</div>
										<div>
											<a class="close-team primary-color cursor-p">Back ></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if($enable_slider): ?>
					<div class="d-flex team-list-slider-arrow mt-5 justify-content-center">
						<div class="me-3">
							<div class="arrow-circle centerContent prev-arrow">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/right-arrow-dark.png" width="50">
							</div>
						</div>
						<div>
							<div class="arrow-circle centerContent next-arrow">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/right-arrow-dark.png" width="50">
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	<?php if($enable_circle_dots_bg): ?>	
		<?php  get_template_part( 'template-parts/components/comp', 'arrow-svg', array('classname' => 'team-listing-bg-arrow1') ); ?>
		<?php  get_template_part( 'template-parts/components/comp', 'arrow-svg', array('classname' => 'team-listing-bg-arrow1 team-listing-bg-arrow1--right') ); ?>
	<?php endif; ?>

	<?php if($enable_slider): ?>	
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/circles-svg-same-color.svg" class="team-listing-bg-arrow1">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/circles-svg-same-color.svg" class="team-listing-bg-arrow1 team-listing-bg-arrow1--right">
	<?php endif; ?>

	</div>
</section>	