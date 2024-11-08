<?php 
	//For Normal Pages
	$title = get_sub_field('title');
    $description = get_sub_field('description');
    $locations_title = get_sub_field('locations_title');
    $locations_title_white_centre = get_sub_field('locations_title_white_centre');
    $locations = get_sub_field('locations');
    include 'extras.php';
?>
<section class="section section--location black-bg" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper <?php echo $paddingClass; ?> p80">
		<div class="max-1366">
			<div class="col-12 col-md-5 pb-5">
				<?php if($title): ?>
					<div class="heading-sc">
						<h2 class="text-upper m-0"><?php echo $title; ?></h2>
					</div>
				<?php endif; ?>
				<?php if($description): ?>
					<div class="descp mobile-center">
						<?php echo $description; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="location__details">
				<?php if($locations_title): ?>
					<?php if($locations_title_white_centre): ?>
						<div class="text-center">
							<h2 class="text-upper m-0 heading-line d-inline-flex"><?php echo $locations_title; ?></h2>
						</div>
						<?php else: ?>
						<div class="heading-line-w">
							<h4 class="primary-color text-upper m-0 heading-line d-inline-flex"><?php echo $locations_title; ?></h4>
						</div>
					<?php endif; ?>
				<?php endif; ?>	
				<?php if($locations): ?>
				<div class="row location__content">
					<?php foreach( $locations as $key => $location ): ?>
					<div class="col-12 col-md-3 location-each d-flex align-items-start mb-5 mb-md-0">
						<div class="col-3 col-md-4 me-3">
							<div class="img-w article-each__img">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/time-one.png">
							</div>
						</div>
						<div class="location-each__address col-9 col-md-8">
							<div class="primary-color text-upper font18 Fira-Bol mb-2">
								<?php echo $location->post_title; ?>
							</div>
							<div class="col-12 col-md-10">
								<?php echo $location->post_content; ?>
							</div>
							<!-- <div>Call us: +971 4 435 6612</div> -->
						</div>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php if($enable_background_image && $background_image_fields['bg_image']): ?>
		<div class="section-bg">
			<?php echo get_custom_image($background_image_fields['bg_image']['ID'], false, $class = '', 'section background image' ); ?>
		</div>
	<?php endif; ?>
</section>