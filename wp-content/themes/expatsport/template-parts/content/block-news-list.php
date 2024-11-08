<?php 
	wp_enqueue_script( 'news-list', get_stylesheet_directory_uri() . '/assets/js/parts/news-list.js', array('custom-script'), '1.0', true );

	$title = get_sub_field('title');
	$loadMore = get_sub_field('load_more');
?>
<section <?php generate_section_id($args); ?> class="news-list <?php generate_section_classes($args); ?>" <?php generate_section_styles($args); ?> data-st-scene="news-list" data-ignore-latest="<?php echo $ignoreLatest; ?>" data-ignore-featured="<?php echo $ignoreFeatured; ?>">

	<div class="container">
		<?php 
			if($title){
				get_template_part( 'template-parts/components/comp', 'section-title', array('title'=>$title) ); 
			}
		?>

		<?php
			$filterArgs = array(
				'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
			);
		    $custom_query = filter_news($filterArgs);
		?>
		<?php if ($custom_query->have_posts()) : ?>
			<div class="news-grid">
				<div class="news-grid__content row gx-5 align-items-stretch">
					<?php render_news_grid_items($custom_query, array('firstFeatured'=>true)); ?>
				</div>
				<?php if($loadMore): ?>
					<div class="news-grid__load-more-wrapper text-center">
						<?php render_news_grid_load_more_btn($custom_query, $filterArgs['paged']); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>