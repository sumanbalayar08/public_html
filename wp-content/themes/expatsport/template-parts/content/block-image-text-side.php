<?php 
	//For Normal Pages
    $heading = get_sub_field('heading');
    $text = get_sub_field('text');
	$ctas = get_sub_field('ctas');
    $image = get_sub_field('image');
    $orientation = get_sub_field('orientation');
    include 'extras.php';
?>
<section class="section section--image-text-side no-overflow " data-st-scene="success">
	<div class="sec-wrapper <?php echo $paddingClass; ?> ">
		<div class="row gx-0 flex-column-reverse flex-md-row">
			<?php if($image): ?>
				<div class="col-12 col-md-6">
					<div class="p-relative h-100">
						<div class="image-fill">
							<?php echo get_custom_image($image['ID'], false, $class = '','about Image ' . $key ); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>	
			<div class="col-12 col-md-6 mb-5 mb-md-0">
				<div class="image-text-side__text">
					<div class="sec">
						<?php if($heading): ?>
							<h2 class="text-upper mt-0"><?php echo $heading; ?></h2>
						<?php endif; ?>
					</div>
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
</section>

