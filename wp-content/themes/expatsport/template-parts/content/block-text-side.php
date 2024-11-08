<?php 
    $column1_text = get_sub_field('column1_text');
    $column2_text = get_sub_field('column2_text');
    $bg_image = get_sub_field('bg_image');
	$ctas = get_sub_field('ctas');
	include 'extras.php';
?>
<section class="section section--two-col-text" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper <?php echo $paddingClass; ?>">
		<div class="<?php echo $maxWidth; ?>">
			<div class="row align-items-center">
				<div class="col-12 <?php echo $col1; ?>">
					<div>
						<?php if($column1_text): ?>
								<?php echo $column1_text; ?>
						<?php endif; ?>
					</div>
				</div>
				<?php if($column2_text): ?>
					<div class="col-12 <?php echo $col2; ?>">
						<?php if($column2_text): ?>
							<div class="descp">
								<?php echo $column2_text; ?>
							</div>
						<?php endif; ?>
						<?php if($ctas): ?>
							<div class="d-md-flex">
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
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php if($enable_background_image && $background_image_fields['bg_image']): ?>
		<div class="section-bg">
			<?php echo get_custom_image($background_image_fields['bg_image']['ID'], false, $class = '', 'section background image' ); ?>
		</div>
	<?php endif; ?>
</section>	