<?php 
	//For Normal Pages
	$location_map_content = get_sub_field('location_map_content'); //map_coordinates
    include 'extras.php';
?>
<section class="section section--location-map white-bg <?php echo $sectionClass; ?>" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper p80 <?php echo $paddingClass; ?>">
		<div class="max-1250">
			<div class="map-sec mb-4">
				<?php foreach( $location_map_content as $key => $location_map_content_each ): ?>
					<div class="each-map <?php echo $key ==0 ? 'active' : ''; ?>">
						<?php echo $location_map_content_each['map_content']; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="row gx-0 location-map">
				<div class="row gx-0 align-items-stretch">
					<?php foreach( $location_map_content as $key => $location_map_content_each ): ?>
						<div class="col-12 col-md-6  mb-4 mb-md-0">
							<div class="h-100 me-0 me-md-3">
								<div class="blue-bg p-4 b-radius ">
									<div class="row">
										<div class="col-12 col-md-6 mb-4 mb-md-0">
											<div class="row gx-0">
												<div class="col-auto">
													<div class="">
														<img src="<?php echo get_template_directory_uri(); ?>/assets/images/location-pin-cropped.svg" height="35" width="35" alt="location pin icon" class="icon35">
													</div>
												</div>
												<div class="col-auto ps-3">
													<div class="font18"><?php echo $location_map_content_each['address']['title']; ?></div>
													<div class="Fira-Light mt-3">
														<?php echo $location_map_content_each['address']['details']; ?>
													</div>
													<div class="btn-sec mt-4">
														<?php getBtn('orange', $location_map_content_each['link'], 'white', 'orange' ); ?>
													</div>
												</div>
											</div>
										</div>
										<div class="col-12 col-md-6">
											<div class="row gx-0">
												<div class="col-auto">
													<div class="">
														<img src="<?php echo get_template_directory_uri(); ?>/assets/images/phone-cropped.svg" height="35" width="35" alt="location pin icon" class="icon35">
													</div>
												</div>
												<div class="col-auto ps-3">
													<div class="font18"><?php echo $location_map_content_each['phone']['title']; ?></div>
													<div class="Fira-Light mt-3">
														<?php echo $location_map_content_each['phone']['details']; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>