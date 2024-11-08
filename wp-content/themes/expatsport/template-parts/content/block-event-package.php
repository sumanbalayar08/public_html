<?php 
	//For Normal Pages
    $heading = get_sub_field('heading');
    $sub_heading = get_sub_field('sub_heading');
    $each_package = get_sub_field('each_package');
    $featured_image = get_sub_field('featured_image');
    include 'extras.php';
?>
<section class="section section--event-package" <?php echo $backgroundBGColorStyle; ?> >
	<div class="sec-wrapper <?php echo $paddingClass; ?>">
		<div class="row gx-0 flex-column-reverse flex-md-row">
			<div class="col-12 col-md-5">
				<div class="h-100 p-relative">
					<div class="image-fill"><?php echo get_custom_image($featured_image['ID'], false, $class = '','About Image ' . $key ); ?></div>
				</div>
			</div>
			<div class="col-12 col-md-7 mb-5 mb-md-0  grey-bg p-5">
				<div class="">
					<?php if($heading): ?>
						<h2 class="text-upper m-0"><?php echo $heading; ?></h2>
					<?php endif; ?>
				</div>
				<?php if($each_package): ?>
					<div class="event-package-tab">
						<div class="row event-package-tab__content">
							<?php foreach( $each_package as $key => $each_package_detail ): ?>
								<div class="col-12 col-md-3 font20 event-package-tab__each <?php echo $key==0 ? 'active' : ''; ?>">
									<div class="d-inline-flex"><?php echo $each_package_detail['package_name']; ?></div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if($each_package): ?>
					<?php foreach( $each_package as $key => $each_package_detail ): ?>
						<div class="event-package-detail-outer<?php echo $key==0 ? 'active' : ''; ?>">
							<div class="row event-package-detail">
								<div class="col-12 col-md-4">
									<div class="event-package-detail__img">
										<div class="image-fill"><?php echo get_custom_image($each_package_detail['image']['ID'], false, $class = '', $each_package_detail['title'] ); ?></div> 
									</div>
								</div>
								<div class="col-12 col-md-8">
									<div class="p-0 pt-4 p-md-4">
										<h3 class="m-0"><?php echo $each_package_detail['title']; ?></h3>
										<div class="event-package-detail__text mobile-center">
											<?php echo $each_package_detail['description']; ?>
										</div>
										<?php if($each_package_detail['ctas']): ?>
											<div class="d-md-flex mobile-center">
												<?php $target = $banner['banner_button']['target'] ? 'target="_blank"' : ''; ?> 
													<?php
												        // Determine if it's the first or last element
												        $is_first = ($key === 0);
												        $is_last = ($key === count($each_package_detail['ctas']) - 1);

												        // Build the class string
												        $classes = '';
												        if (!$is_last) {
												            $classes .= 'mb-3 mb-md-0 ';
												        }
												        if (!$is_first) {
												            $classes .= 'ms-md-4';
												        }
												    ?>
												<div class="each__content__btn btn-sec <?php echo trim($classes); ?>">
													<?php getBtn('orange', $cta['link'], 'white', 'orange' ); ?>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>