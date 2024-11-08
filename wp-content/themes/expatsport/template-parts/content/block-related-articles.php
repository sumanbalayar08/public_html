<?php 
	$categories = get_the_terms( $post->ID, 'category');
	$categoryIDs = array();
	foreach ($categories as $key => $each) {
		$categoryIDs[] = $each->term_id;
	}

	$args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 6, 
        'orderby' => 'date',
        'order' => 'DESC',
        'ignore_sticky_posts' => 1,
        'post__not_in' => array($post->ID),
        'tax_query' => array(
        	array(
				'taxonomy' => 'category',
				'terms' => $categoryIDs
			)
        )
    );
    $related_articles = new WP_Query($args);

	if ($related_articles->have_posts()):
?>
	<section class="related-articles <?php generate_section_classes($args); ?>" <?php generate_section_styles($args); ?> data-st-scene="related-articles">
		<?php generate_section_background_image(array(), array('imgSize'=>'banner', 'containerAttributes'=>'data-st-anim="related-articles"')); ?>

		<div class="container">
			<?php get_template_part( 'template-parts/components/comp', 'section-title', array('title'=>__('You may also like', 'electra')) ); ?>

			<div class="related-articles__list row gx-5 align-items-stretch">
				<?php 
					while ($related_articles->have_posts()): 
						$related_articles->the_post(); 
						
						$colClass = 'col-12 col-md-4';
						$colClass .= ' d-flex flex-column mb-4 mb-md-5';

						echo '<div class="' . $colClass . '">';
						get_template_part( 'template-parts/components/comp', 'article-grid-item', $args );
						echo '</div>';
					endwhile; 
					wp_reset_postdata();
				?>
			</div>

		</div>
	</section>
<?php
	endif;
?>