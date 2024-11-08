<?php 
	//For Normal Pages
	$title = get_sub_field('title');
    $clients = get_sub_field('clients');
	$cta_section = get_sub_field('cta_section');
	include 'extras.php';
?>
<section class="section section--clients white-bg" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?> >
	<div class="sec-wrapper <?php echo $paddingClass; ?> p80">
		<div class="heading-sc text-center">
			<?php if($title): ?>
				<h2 class="text-upper"><?php echo $title; ?></h2>
			<?php endif; ?>
		</div>
		<div class="row max-1366 align-items-start">
			<div class="col-12 col-md-4">
				<div class="clients-content-img-slider">
					<?php foreach( $clients as $key => $client ): ?>
						<?php $client_logo = get_field('client_logo',$client->ID); ?>
						<div>
							<div class="img-w">
								<?php echo get_custom_image($client_logo['ID'], false, $class = '', $client->post_title ); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-12 col-md-8">
				<div class="clients-content">
					<div class="clients-content-slider">
						<?php foreach( $clients as $key => $client ): ?>
							<div class="clients-content-slider__each">
								<div class="clients-content__words Fira-Regular">
									<?php echo get_field('client_quote',$client->ID); ?>
								</div>
								<div class="d-flex client-info">
									<?php $client_profile__image = get_field('client_profile__image',$client->ID); ?>
									<div class="clients-content__img">
										<?php echo get_custom_image($client_profile__image['ID'], false, $class = '', $client->post_title ); ?>
									</div>
									<div class="clients-content__det ps-3">
										<div class="clients-content__title font18 Fira-Bold">
											<?php echo get_field('client_profile_name',$client->ID); ?>
										</div>
										<div class="clients-content__position Fira-Regular">
											<?php echo get_field('client_profile_designation',$client->ID); ?>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="d-flex clients-content-slider-arrow">
						<div class="me-3">
							<div class="arrow-circle centerContent prev-arrow">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/right-arrow-white.png" width="50">
							</div>
						</div>
						<div>
							<div class="arrow-circle centerContent next-arrow">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/right-arrow-white.png" width="50">
							</div>
						</div>
					</div>
				</div>
				<?php if($cta_section): ?>
					<div class="d-md-flex py-4 align-items-center border-bottom justify-content-between mobile-center">
						<div class="mb-3 mb-md-0">
							<div class="btn-sec">
								<?php getBtn('orange', $cta_section['link'], 'orange', 'white' ); ?>
							</div>
						</div>
						<?php if($cta_section['text']): ?>
							<div class="ms-3 font24 Fira-Bold cta-text">
								<?php echo $cta_section['text']; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="top-left-arrow-bg d-flex align-items-center justify-content-center">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/circle.svg" class="first">
	</div>
</section>