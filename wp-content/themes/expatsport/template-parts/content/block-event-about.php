<?php 
	//For Normal Pages
    $heading = get_sub_field('heading');
    $sub_heading = get_sub_field('sub_heading');
    $text = get_sub_field('text');
	$ctas = get_sub_field('ctas');
    $image = get_sub_field('image');
    include 'extras.php';
?>
<section class="section section--event-about no-overflow " <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper <?php echo $paddingClass; ?>">
		<div class="heading-sc mb-5">
			<?php if($heading): ?>
				<h2 class="text-upper m-0"><?php echo $heading; ?></h2>
			<?php endif; ?>
			<?php if($sub_heading): ?>
				<div class="descp mt-3">
					<?php echo $sub_heading; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="row gx-0 flex-column-reverse flex-md-row">
			<div class="col-12 col-md-4 p-relative ps-0 ps-md-5">
				<div class="event-about__mg">
					<div class="image-fill"><?php echo get_custom_image($image['ID'], false, $class = '','About Image ' . $key ); ?></div>
				</div> 
			</div>
			<div class="col-12 col-md-8 mb-5 mb-md-0 ps-0 ps-md-5">
				<div class="col-12 col-md-8">
					<?php if($text): ?>
						<div class="descp">
							<?php echo $text; ?>
						</div>
					<?php endif; ?>
					<?php if($ctas): ?>
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
									<?php getBtn('', $cta['link'], 'white', 'white' ); ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="right-arrow-bg">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/circles-svg-same-color.svg" class="right-arrow-bg-img right" >
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/circles-svg.svg" class="right-arrow-bg-img left">	
	</div>
</section>