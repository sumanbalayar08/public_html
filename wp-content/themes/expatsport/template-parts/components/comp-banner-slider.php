<?php $each_banner = $args;  ?>
<section class="section section--banner overflow-hidden elWithHeaderPadding">
	<div class="section-wrapper banner-slider <?php echo ( count($each_banner) >1 ? 'moreSlide' : 'singleSlide' ); ?>">
		<?php foreach( $each_banner as $key => $banner ): ?>
			<div class="banner-each custom-px  d-flex align-items-start justify-content-center flex-column">
				<?php if($banner['banner_image']): ?>
					<div class="banner-image">
						<?php echo get_custom_image($banner['banner_image']['ID'], false, $class = '','Banner Image'); ?>
					</div>
				<?php endif; ?>
				<div class="banner-content col-12 col-md-6">
					<div class="">
						<h1 class="m-0 text-upper banner-title text-upper">
							<?php if($banner['banner_tagline']): ?>
								<span><?php echo $banner['banner_tagline']; ?></span>
							<?php endif; ?>
							<?php if($banner['banner_title']): ?>
								<?php echo $banner['banner_title']; ?>
							<?php endif; ?>
						</h1>
					</div>
					<?php if($banner['banner_description']): ?>
						<div class="banner-content font20">
							<?php echo $banner['banner_description']; ?>
						</div>
					<?php endif; ?>
					<?php if($banner['banner_button']): ?>
						<div class="">
							<div class="mt-4 banner-btn">
								<?php getBtn('', $banner['banner_button'], 'white', 'white' ); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php if(count($each_banner)>1): ?>
		<div class="banner-arrow">
			<div class="banner-arrow__left">
				<div class="">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-with-orange-dot.png" alt="banner arrow btn left" width="50" height="50">
				</div>
			</div>
			<div class="banner-arrow__right">
				<div class="">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-with-orange-dot.png" alt="banner arrow btn right" width="50" height="50">
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>