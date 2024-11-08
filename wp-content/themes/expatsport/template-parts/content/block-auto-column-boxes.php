<?php 
	//For Normal Pages
	$title = get_sub_field('title');
    $description = get_sub_field('description');
    $experiences = get_sub_field('experiences');
	$cta_section = get_sub_field('cta_section');
	include 'extras.php';
?>
<section class="section section--auto-size-img sports-experience yellow-bg" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper <?php echo $paddingClass; ?> p80">
		<div class="heading-sc text-center">
			<?php if($title): ?>
				<h2 class="text-upper m-0"><?php echo $title; ?></h2>
			<?php endif; ?>
			<?php if($description): ?>
				<div class="descp mt-3">
					<?php echo $description; ?> 
				</div>
			<?php endif; ?>
		</div>
		<?php if($description): ?>
		<div class="row section--auto-size-img__content">
			<?php foreach( $experiences as $key => $experience ): ?>
				<?php
			        // Determine if it's the first or last element
			        $is_first = ($key === 0);
			        $is_last = ($key === count($experiences) - 1);

			        // Build the class string
			        $classes = '';
			        if (!$is_last) {
			            $classes .= 'mb-3 mb-md-0 ';
			        }
			        $imageID = get_post_thumbnail_id($experience->ID);
			    ?>
				<div class="col-12 col-md-2 section--auto-size-img__each <?php echo trim($classes); ?>">
					<div class="section--auto-size-img__each__wrapper">
						<div class="image-fill b-radius">
							<?php echo get_custom_image($imageID, false, $class = '', $experience->post_title ); ?>
						</div>
						<div class="section--auto-size-img__each__text">
							<div class="h2-style section--auto-size-img__each__title">
								<?php echo $experience->post_title; ?> 
							</div>
							<div class="section--auto-size-img__each__text__slide">
								<div class="section--auto-size-img__each__descp">
									<?php echo $experience->post_content; ?> 
								</div>
								<div class="section--auto-size-img__each__btn">
									<?php 
										$cta = array ( 'url' => get_permalink($experience->ID), 'title' => 'More Info' );
										getBtn('', $cta, 'white', 'white' ); 
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<?php if($cta_section): ?>
		<div class="d-md-flex sec-cta align-items-center justify-content-center font24">
			<?php if($cta_section['text']): ?>
				<div class="mb-3 mb-md-0 cta-text"><?php echo $cta_section['text']; ?> </div>
			<?php endif; ?>
			<div class="btn-sec ms-md-3">
				<?php getBtn('black', $cta_section['link'], 'dark', 'white' ); ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>
