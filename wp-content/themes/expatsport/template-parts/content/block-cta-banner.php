<?php 
	//For Normal Pages
    $title = get_sub_field('title');
    $description = get_sub_field('description');
	$ctas = get_sub_field('ctas');
    $alignment = get_sub_field('alignment');
    include 'extras.php';
?>
<section class="section  no-overflow section--cta-banner <?php echo $alignment; ?>" data-st-scene="success">
	<div class="sec-wrapper <?php echo $paddingClass; ?> ">
		<div class="row gx-0 <?php echo ($alignment == "right" ? ' justify-content-end ' : '');  ?>">
			<div class="col-12 col-md-5">
				<div class="p-5">
					<div class="sec">
						<?php if($title): ?>
							<h2 class="text-upper mt-0"><?php echo $title; ?></h2>
						<?php endif; ?>
					</div>
					<?php if($description): ?>
					<div class="descp">
						<?php echo $description; ?>
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
									<?php if($alignment == "right"): ?>
										<?php getBtn('orange', $cta['link'], 'orange', 'white' ); ?>
									<?php else: ?>
										<?php getBtn('', $cta['link'], 'white', 'white' ); ?>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php if($enable_background_image && $background_image_fields['bg_image']): ?>
		<div class="section-bg">
			<?php echo get_custom_image($background_image_fields['bg_image']['ID'], false, $class = '', 'section background image' ); ?>
		</div>
	<?php endif; ?>
</section>

