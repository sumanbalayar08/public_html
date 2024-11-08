<?php 
	//For Normal Pages
	$title = get_sub_field('title');
    $partners = get_sub_field('partners');
    include 'extras.php';
?>
<section class="section section--partners white-bg" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper <?php echo $paddingClass; ?> p80">
		<div class=" heading-line-through">
			<div class="heading-sc text-center">
				<?php if($title): ?>
					<h2 class="text-upper m-0 h4-style"><?php echo $title; ?></h2>
				<?php endif; ?>
			</div>
		</div>
		<?php if($partners): ?>
			<div class="row partners-content align-items-center partners-slider">
				<?php foreach( $partners as $key => $partner ): ?>
					<?php $imageID = get_post_thumbnail_id($partner->ID); ?> 
					<div class="col text-center partners-content__each">
						<div class="partners-content__each__img d-flex align-items-center">
							<?php echo get_custom_image($imageID, false, $class = '', $partner->post_title ); ?>
						</div>
						<div class="mt-3"><?php echo $partner->post_title; ?> </div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>