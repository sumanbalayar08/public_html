<?php 
	wp_enqueue_script( 'testimonials', get_stylesheet_directory_uri() . '/assets/js/parts/testimonials.js', array('custom-script'), '1.1', true );

	$title = get_sub_field('title');
	$categoryFilters = get_sub_field('category_filter');
    $queryArgs = array(
    	'post_type' => 'testimonial',
    	'post_status' => 'publish',
    	'posts_per_page' => 6,
    	'orderby' => 'date',
    	'order' => 'DESC',
	);
	if(isset($categoryFilters) && is_array($categoryFilters)){
		$queryArgs['tax_query'][] = array(
			'taxonomy' => 'testimonial_category',
			'terms' => $categoryFilters
		);
	}
	$testimonials_query = new wp_query( $queryArgs );

	while(count($testimonials_query->posts) < 3){
		$testimonials_query->posts = array_merge($testimonials_query->posts, $testimonials_query->posts);
		$testimonials_query->post_count = count($testimonials_query->posts);
	}
?>

<?php if( $testimonials_query->have_posts() ): ?>
<section <?php generate_section_id($args); ?> class="testimonials <?php generate_section_classes($args); ?>" <?php generate_section_styles($args); ?> data-st-scene="testimonials">

	<div class="container">
		<div class="d-md-flex testimonials__bg">
			<img class="testimonials__bg__image" src="<?php echo get_template_directory_uri(); ?>/assets/img/left-quote-light.svg" width="45" height="38" loading="lazy" alt="open quote" />
			
			<div class="testimonials__title mb-5 mb-md-0" data-st-anim="testimonials" data-st-scrub="true" data-st-start="top bottom" data-st-end="top 60%" data-st-from='{"yPercent": -20, "opacity":0}'>
				<h2 class="mt-0 mb-5"><?php echo $title; ?></h2>
				<div class="testimonials__slider-arrows d-flex"></div>
			</div>
			<div class="testimonials__content">
				<div class="testimonials__slider" data-st-anim="testimonials" data-st-scrub="true" data-st-start="top bottom" data-st-end="top 60%" data-st-trigger="self" data-st-from='{"xPercent": 30, "opacity":0}'>

					<?php while( $testimonials_query->have_posts() ): $testimonials_query->the_post();  ?>
						<?php
							$name = get_field('name');
							$company = get_field('company');
							$position = get_field('position');
							$text = get_field('text');
							$photo = get_field('photo');
						?>
						<div class="testimonials__item flex-shrink-0 d-flex flex-column transition-300ms">
							<div class="flex-grow-1 d-flex flex-column transition-300ms">
								<div class="testimonials__item__top flex-grow-1 transition-300ms">
									<div class="testimonials__item__icon">
										<img class="responsive transition-300ms" src="<?php echo get_template_directory_uri(); ?>/assets/img/left-quote-grey.svg" width="45" height="38" loading="lazy" alt="open quote" />
										<img class="responsive active transition-300ms" src="<?php echo get_template_directory_uri(); ?>/assets/img/left-quote-blue.svg" width="45" height="38" loading="lazy" alt="open quote" />
									</div>
									<div class="testimonials__item__quote">
										<?php echo $text; ?>
									</div>
								</div>
								<?php if(isset($photo['ID']) || $name || $company || $position): ?>
									<div class="testimonials__item__bottom">
										<?php 
											if(isset($photo['ID'])){
												echo wp_get_attachment_image( $photo['ID'], 'image-500', false, ['class'=>'testimonials__item__logo'] );
											}  
										?>
										<?php if($name || $company): ?>
											<div class="font-bold"><?php echo $name; ?> <?php echo $company; ?></div>
										<?php endif; ?>
										<?php if($position): ?>
											<div><?php echo $position; ?></div>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>