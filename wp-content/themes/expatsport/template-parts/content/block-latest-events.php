<?php 
	//For Normal Pages
	$title = get_sub_field('title');
	$description = get_sub_field('description');
    $events = get_sub_field('events');
	include 'extras.php';
?>
<section class="section section--latest-articles white-bg <?php echo $sectionClass; ?>" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper <?php echo $paddingClass; ?> p80">
		<?php if($events): ?>
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
			<div class="row max-1366 latest-events__content">
				<?php foreach( $events as $key => $event ): ?>
					<?php
				        // Determine if it's the first or last element
				        $is_first = ($key === 0);
				        $is_last = ($key === count($events) - 1);

				        // Build the class string
				        $classes = '';
				        if (!$is_last) {
				            $classes .= 'mb-5 mb-md-0 ';
				        }
				        $imageID = get_post_thumbnail_id($event->ID);
				    ?>
					<div class="col-12 col-md-3 events-each <?php echo trim($classes); ?>">
						<div class="events-each__img b-radius">
							<a href="<?php echo get_permalink($event->ID); ?>">
								<div class="image-fill">
									<?php echo get_custom_image($imageID, false, $class = '', $event->post_title ); ?>
								</div>
							</a>
						</div>
						<div class="events-each__title text-center px-3">
							<a href="<?php echo get_permalink($event->ID); ?>"><h2 class="Fira-Regular m-0"><?php echo $event->post_title; ?></h2></a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>	