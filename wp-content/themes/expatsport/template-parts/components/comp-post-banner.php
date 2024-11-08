<div class="post-banner" data-st-scene="post-banner">
	<div class="post-banner__overlay">
    	<div style="position: absolute; inset: 0; background-image: linear-gradient(180deg, #000000 0%, rgba(0, 0, 0, 60) 50%, rgba(0, 0, 0, 0) 100%); background-size: 100% 20%; background-repeat: no-repeat; opacity: 0.5;"></div>
    </div>

	<?php the_post_thumbnail('banner'); ?>
</div>

<?php if(isset($args['displayTitle'])): ?>
	<div class="container">
		<h1 class="text-center mb-1"><?php the_title(); ?></h1>
	</div>
<?php endif; ?>