<?php 
	//For Normal Pages
	$title = get_sub_field('title');
    $sports_services = get_sub_field('sports_services');
    include 'extras.php';
?>
<section class="section section-horizontal-slider white-bg" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper <?php echo $paddingClass; ?> p80">
		<div class="row align-items-stretch">
			<div class="col-12 col-md-4 product-eac">
				<div class="horizontal-slider__list">
					<?php foreach( $sports_services as $key => $sports_service ): ?>
						<div class="horizontal-slider__list__each <?php echo $key == 0 ? 'active' : ''; ?>" data-slide="<?php echo $key; ?>">
							<div class="horizontal-slider__list__each__title h4-style mb-3">
								<?php echo $sports_service->post_title; ?> 
							</div>
							<div class="horizontal-slider__list__each__descp">
								<?php echo get_the_excerpt($sports_service->ID); ?> 
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-12 col-md-8 horizontal-slider-detail">
				<?php foreach( $sports_services as $key => $sports_service ): ?>
					<div class="horizontal-slider-detail__each <?php echo ($key == 0 ? 'active' : ''); ?> h-100" data-slide="<?php echo $key; ?>">
						<div class="row h-100 ps-md-3">
							<div class="col-12 col-md-4 p-relative horizontal-slider-detail__img b-radius">
								<?php $imageID = get_post_thumbnail_id($sports_service->ID); ?> 
								<div class="image-fill">
									<?php echo get_custom_image($imageID, false, $class = '', $sports_service->post_title ); ?>
								</div>
							</div>
							<div class="col-12 col-md-8 p-relative">
								<div class="h-100 horizontal-slider-detail__text b-radius  d-flex align-items-center">
									<div>
										<div class="horizontal-slider-detail__each__title">
											<h2 class="m-0"><?php echo $sports_service->post_title; ?> </h2>
										</div>
										<div class="horizontal-slider-detail__each__descp descp">
											<?php echo $sports_service->post_content; ?> 
										</div>
										<div class="horizontal-slider-detail__each__btn btn-sec">
											<?php 
												$cta = array ( 'url' => get_permalink($sports_service->ID), 'title' => 'More Info' );
												getBtn('', $cta, 'white', 'white' ); 
											?>
										</div>
									</div>
								</div>
								<div class="horizontal-slider-detail__text__bg">
									<div class="image-fill">
										<img src="<?php echo get_template_directory_uri(); ?>/assets/images/circles-svg-same-color-no-big-circle.svg" width="100" height="100" alt="circle dots image">
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>