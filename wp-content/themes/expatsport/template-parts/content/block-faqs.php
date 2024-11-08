<?php 
    $title = get_sub_field('title');
    $faqs = get_sub_field('faq');
	include 'extras.php';
?>
<section class="section section--faqs white-bg <?php echo $sectionClass; ?>" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper p80 <?php echo $paddingClass; ?>">
		<div class="heading-sc text-center">
			<?php if($title): ?>
				<h2 class="text-upper m-0 mb-5"><?php echo $title; ?></h2>
			<?php endif; ?>
		</div>
		<div class="max-1024">
			<div class="faqs">
				<?php if($faqs): ?>
					<?php foreach( $faqs as $key => $faq ): ?>
						<div class="faqs__each mb-2 p-4 <?php echo $key == 0 ? 'active' : '';  ?> b-radius">
							<div class="d-flex justify-content-between">
								<div class="faqs__each__content ps-3">
									<div class="faqs__each__question">
										<?php echo $faq['title']; ?>
									</div>
									<div class="faqs__each__answer pt-3 mt-3">
										<?php echo $faq['description']; ?>
									</div>
								</div>
								<div class="faqs__each__icon flex-grow-0 flex-shrink-0 ms-3"></div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>	