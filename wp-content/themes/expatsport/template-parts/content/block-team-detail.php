<?php 
    $team_member = get_sub_field('team_member');
	include 'extras.php';
?>
<section class="section section--team-detail white-bg" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper p80 <?php echo $paddingClass; ?>">
		<div class="max-1250">
			<?php if($team_member): ?>
			<div class="row  pt-5 flex-column-reverse flex-md-row align-items-center ">
				<div class="col-12 col-md-6 d-md-flex justify-content-end pe-5">
					<?php if($team_member[0]): ?>
						<div class="team-detail-image">
							<div class="team-detail-image__inner">
								<div class="image-fill">
									<?php 
										$imageID = get_post_thumbnail_id($team_member[0]->ID);
										echo get_custom_image($imageID, false, $class = '','about Image ' . $key ); 
									?>
								</div>
							</div>
							<div class="team-detail-image__inner clipping-sec">
								<div class="image-fill">
									<?php 
										$imageID = get_post_thumbnail_id($team_member[0]->ID);
										echo get_custom_image($imageID, false, $class = '','about Image ' . $key ); 
									?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="col-12 col-md-6 mb-5 mb-md-0">
					<div class="col-12 col-md-8 mobile-center">
						<h2 class="text-upper m-0"><?php echo $team_member[0]->post_title; ?></h2>
						<div class="position my-3">
							<?php echo get_field('position_title',$team_member[0]->ID); ?>
						</div>
						<div class="descp m-0 my-4">
							<?php echo $team_member[0]->post_content;; ?>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="bg-arrow bg-arrow--team-detail">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/orange-angle-circles.svg" class="team-detail-arrow-bg orange">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/grey-angle-circles.svg" class="team-detail-arrow-bg grey">
	</div>
</section>	