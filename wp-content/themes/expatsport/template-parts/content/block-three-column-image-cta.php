<?php 
	//For Normal Pages
    $heading = get_sub_field('heading');
    $sub_heading = get_sub_field('sub_heading');
    $each_image_cta = get_sub_field('each_image_cta');
    include 'extras.php';
?>
<section class="section section--three-column-image-cta white-bg" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper <?php echo $paddingClass; ?> p80">
		<?php if($each_image_cta): ?>
			<div class="row max-1366">
				<?php foreach( $each_image_cta as $key => $each_image_cta_content ): ?>
					<div class="col-12 col-md-4 mb-5 mb-md-0 image-cta-each">
						<div class="image-cta-each__inner d-flex flex-column justify-content-end b-radius">
							<div class="image-fill">
								<?php echo get_custom_image($each_image_cta_content['image']['ID'], false, $class = '','About Image ' . $key ); ?>
							</div>
							<div class="image-cta-each__title p-3">
								<a href="<?php echo $each_image_cta_content['link']['url']; ?>" target="_blank">
									<h2 class="m-0"><?php echo $each_image_cta_content['title']; ?></h2>
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>