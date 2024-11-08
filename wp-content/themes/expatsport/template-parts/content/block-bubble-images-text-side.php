<?php 
	//For Normal Pages
	$tagline = get_sub_field('tagline');
    $heading = get_sub_field('heading');
    $text = get_sub_field('text');
	$ctas = get_sub_field('ctas');
    $images = get_sub_field('images');
    $orientation = get_sub_field('orientation');
    include 'extras.php';
?>
<section class="section section--images-text-side no-overflow " data-st-scene="success">
	<div class="sec-wrapper <?php echo $paddingClass; ?> ">
		<div class="row gx-0 flex-column-reverse flex-md-row">
			<div class="col-12 col-md-6 bubble-img">
				<?php 
					$imgPosClass = array (
						0 =>  'top',
						1 =>  'middle',
						2 =>  'bottom',
					);
				?>
				<?php foreach( $images as $key => $image ): ?>
					<div class="<?php echo $imgPosClass[$key]; ?>-img">
						<div class="img-w">
							<?php echo get_custom_image($image['image']['ID'], false, $class = '','about Image ' . $key ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="col-12 col-md-6 mb-5 mb-md-0">
				<div class="col-12 col-md-8">
					<div class="sec">
						<?php if($tagline): ?>
							<span class="font18 font24"><?php echo $tagline; ?></span>
						<?php endif; ?>
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
	<div class="right-arrow-bg">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/circles-svg-same-color.svg" class="right-arrow-bg-img right op2">
		<!-- <img src="<?php echo get_template_directory_uri(); ?>/assets/images/circles-svg.svg" class="right-arrow-bg-img left anim-svg"> -->
		<?php  get_template_part( 'template-parts/components/comp', 'arrow-svg', array('classname' => 'right-arrow-bg-img left') ); ?>
	</div>
</section>

