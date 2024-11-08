<?php $banner = $args;  ?>
<section class="section section--banner autoheight elWithHeaderPadding">
	<div class="section-wrapper">
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
	</div>
</section>