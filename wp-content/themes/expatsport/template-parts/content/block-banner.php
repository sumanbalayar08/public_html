<?php
	//For Normal Pages
	$bannerHidden = get_field('banner_hidden'); 
	$is_banner_slider = get_field('is_banner_slider'); 
	$is_video_banner = get_field('is_video_banner'); 
	$is_half_banner = get_field('is_half_banner'); 

	$each_banner = get_field('each_banner');
	$banner_fields = get_field('banner_fields');
	$banner_fields_half = get_field('banner_fields_half');  
	
	$banner_overlay_image = get_field('banner_overlay_image');
	$banner_overlay_color = get_field('banner_overlay_color');
?>
<?php if(!$bannerHidden): ?>
	<?php if($banner_overlay_image): ?>
		<style type="text/css">
			.banner-image::after { background: <?php echo $banner_overlay_image['url']; ?> no-repeat; }
		</style>
	<?php endif; ?>
	<?php if($banner_overlay_color): ?>
		<style type="text/css">
			.banner-image::after { background: <?php echo $banner_overlay_color; ?>; }
		</style>
	<?php endif; ?>

	<?php if($is_banner_slider): ?>
	    <?php  get_template_part( 'template-parts/components/comp', 'banner-slider',$each_banner); ?>
	<?php endif; ?>
	<?php if($is_video_banner): ?>
	    <?php  get_template_part( 'template-parts/components/comp', 'banner-video',$banner_fields); ?>
	<?php endif; ?>
	<?php if($is_half_banner): ?>
	    <?php  get_template_part( 'template-parts/components/comp', 'half-banner',$banner_fields_half); ?>
	<?php endif; ?>

<?php endif; ?>