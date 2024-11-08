<?php 
	wp_enqueue_script( 'articles-list', get_stylesheet_directory_uri() . '/assets/js/parts/articles-list.js', array('custom-script'), '1.0', true );

	$title = get_sub_field('title');
	$loadMore = get_sub_field('load_more');
	$ignoreLatest = get_sub_field('ignore_latest');
	$ignoreFeatured = get_sub_field('ignore_featured');
?>
<section <?php generate_section_id($args); ?> class="articles-list <?php generate_section_classes($args); ?>" <?php generate_section_styles($args); ?> data-st-scene="articles-list" data-ignore-latest="<?php echo $ignoreLatest; ?>" data-ignore-featured="<?php echo $ignoreFeatured; ?>">

	<div class="container">
		<?php get_template_part( 'template-parts/components/comp', 'section-title', array('title'=>$title) ); ?>

		<?php
			$filterArgs = array(
	            'ignoreLatest' => $ignoreLatest,
	            'ignoreFeatured' => $ignoreFeatured
	        );
		?>
		<?php get_template_part( 'template-parts/components/comp', 'articles-grid', array('loadMore'=>$loadMore, 'filterArgs'=>$filterArgs) ); ?>
	</div>
</section>