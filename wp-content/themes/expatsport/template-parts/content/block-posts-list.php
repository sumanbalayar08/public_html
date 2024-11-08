<?php
	wp_enqueue_script( 'posts-list', get_stylesheet_directory_uri() . '/assets/js/parts/posts-list.js', array('custom-script'), rand(), true );

	$title = get_sub_field('title');
	$colCount = get_sub_field('column_count');
	$loadMore = get_sub_field('load_more');
?>
<section <?php generate_section_id($args); ?> class="posts-list <?php generate_section_classes($args); ?> <?php echo is_tax() ? 'font-light' : null ?>" <?php generate_section_styles($args); ?> data-st-scene="posts-list" data-type="<?php echo $args['post_type']; ?>">

	<?php
		generate_section_background_image(array(), array('imgSize'=>'banner', 'containerAttributes'=>'data-st-anim="posts-list"'));
	?>

	<div class="container">
		<?php 
			$gridArgs = array(
				'post_type' => isset($args['post_type']) ? $args['post_type'] : 'eu-portfolio',
				'colCount' => isset($colCount) ? $colCount : 4,
				'loadMore' => isset($loadMore) ? $loadMore : true, 
			);
			get_template_part( 'template-parts/components/comp', 'posts-grid', $gridArgs ); 
		?>
	</div>
</section>