<?php $banner_fields = $args;  ?>
<section class="section section--banner overflow-hidden elWithHeaderPadding video-banner">
	<div class="section-wrapper">
		<div class="banner-each custom-px  d-flex align-items-start justify-content-center flex-column">
			<?php if($banner_fields['banner_video']): ?>
				<!-- Banner Video Start -->   
		        <div class="banner__video full-span-video">
		            <video width="1920" autoplay muted playsinline loop>
		                <source src="<?php echo $banner_fields['banner_video']['url']; ?>" type="video/mp4">
		            </video>
		        </div>
			<?php endif; ?>
			<div class="banner-content col-12 col-md-6 banner__text__after">
				<div class="transition-1s">
					<h1 class="m-0 text-upper banner-title text-upper">
						<?php if($banner_fields['banner_tagline']): ?>
							<span><?php echo $banner_fields['banner_tagline']; ?></span>
						<?php endif; ?>
						<?php if($banner_fields['banner_title']): ?>
							<?php echo $banner_fields['banner_title']; ?>
						<?php endif; ?>
					</h1>
				</div>
				<?php if($banner_fields['banner_description']): ?>
					<div class="banner-content font20 transition-1point5s">
						<?php echo $banner_fields['banner_description']; ?>
					</div>
				<?php endif; ?>
				<?php if($banner_fields['banner_button']): ?>
					<div class="transition-1point75s">
						<div class="mt-4 banner-btn">
							<?php getBtn('', $banner_fields['banner_button'], 'white', 'white' ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>