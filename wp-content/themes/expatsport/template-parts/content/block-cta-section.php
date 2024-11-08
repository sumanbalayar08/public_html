<?php 
	if(isset($args['fetchFromOptions']) && isset($args['optionsPrefix'])){
		$optionsPrefix = $args['optionsPrefix'];
		//For Archive Pages
	    $title = get_field($optionsPrefix . 'title', 'options');
	    $description = get_field($optionsPrefix . 'description', 'options');
	    $cta = get_field($optionsPrefix . 'cta', 'options');
	    $max_width = get_field($optionsPrefix . 'max_content_width', 'options');
	}else{
		//For Normal Pages
		$title = get_sub_field('title');
	    $description = get_sub_field('description');
	    $cta = get_sub_field('cta');
	    $max_width = get_sub_field('max_content_width');
	}
?>
<section <?php generate_section_id($args); ?> class="cta-section py-5 <?php generate_section_classes($args); ?>" <?php generate_section_styles($args); ?> data-st-scene="cta-section">
	
	<?php 
		$bgImageOptions = array('imgSize'=>'banner', 'containerAttributes'=>'data-st-anim="cta-section"');
		if(isset($args['fetchFromOptions']) && isset($args['optionsPrefix'])){
			$bgImageOptions['fetchFromOptions'] = true;
			$bgImageOptions['optionsPrefix'] = $args['optionsPrefix'];
		}
		generate_section_background_image(array(), $bgImageOptions); 
	?>
	
	<div class="container">
		<div class="cta-section__content mx-auto text-center" style="max-width: <?php echo $max_width; ?>rem;">
			<?php if($title): ?>
				<h3 class="my-0" data-st-anim="cta-section" data-st-scrub="true" data-st-start="top bottom" data-st-end="top 70%" data-st-trigger="self" data-st-from='{"yPercent":20, "opacity":0}'><?php echo $title; ?></h3>
			<?php endif; ?>
			<?php if($description): ?>
				<div class="<?php echo $title ? 'mt-4' : ''; ?>" data-st-anim="cta-section" data-st-scrub="true" data-st-start="top bottom" data-st-end="top 70%" data-st-trigger="self" data-st-from='{"yPercent":20, "opacity":0}'>
					<?php echo $description; ?>
				</div>
			<?php endif; ?>
			<?php if($cta): ?>
				<div class="<?php echo $title || $description ? 'mt-4' : ''; ?>" data-st-anim="cta-section" data-st-scrub="true" data-st-start="top bottom" data-st-end="top 70%" data-st-trigger="self" data-st-from='{"yPercent":20, "opacity":0}'>
					<?php generateAnchorLink($cta, 'btn btn--min-width'); ?>
				</div>
	        <?php endif; ?>
		</div>
	</div>
</section>