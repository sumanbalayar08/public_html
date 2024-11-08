<?php 
	//For Normal Pages
    $heading = get_sub_field('heading');
    $sub_heading = get_sub_field('sub_heading');
    $text = get_sub_field('text');
	$ctas = get_sub_field('ctas');
    $images = get_sub_field('images');
    include 'extras.php';
?>
<section class="section section--event-offering white-bg" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper p80 <?php echo $paddingClass; ?>">
		<div class="heading-sc heading-sc--left pt-5">
			<?php if($heading): ?>
				<h2 class="text-upper m-0"><?php echo $heading; ?></h2>
			<?php endif; ?>
			<?php if($sub_heading): ?>
				<div class="descp mt-3">
					<?php echo $sub_heading; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="row align-items-center">
			<div class="col-12 col-md-8 mb-5 mb-md-0">
				<?php if($images): ?>
					<div class="row gx-3 h-100">
						<?php foreach( $images as $key => $image ): ?>
							<div class="col-12 col-md-4 event-offering__img mb-3 mb-md-0">
								<div class="event-offering__img__inner b-radius">
									<div class="image-fill"><?php echo get_custom_image($image['image']['ID'], false, $class = '','Offerimg Image ' . $key ); ?></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-12 col-md-4 ps-0 ps-md-5">
				<?php if($text): ?>
					<div class=" mobile-center">
						<?php echo $text; ?>
					</div>
				<?php endif; ?>
				<?php if($ctas): ?>
					<div class="mt-5 mobile-center">
						<div class="d-md-flex justify-content-center justify-content-md-start">
							<?php foreach( $ctas as $key => $cta ): ?>
								<?php $target = $banner['banner_button']['target'] ? 'target="_blank"' : ''; ?> 
								<?php
							        // Determine if it's the first or last element
							        $is_first = ($key === 0);
							        $is_last = ($key === count($ctas) - 1);

							        // Build the class string
							        $classes = '';
							        if (!$is_last) {
							            $classes .= 'mb-3 mb-md-0 ';
							        }
							        if (!$is_first) {
							            $classes .= 'ms-md-4';
							        }
							    ?>
								<div class="<?php echo trim($classes); ?>">
									<?php getBtn('orange', $cta['link'], 'white', 'orange' ); ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>