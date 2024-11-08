<?php 
	//For Normal Pages
	$title = get_sub_field('title');
    $articles = get_sub_field('articles');
	$cta_section = get_sub_field('cta_section');
	include 'extras.php';
?>
<section class="section section--latest-articles yellow-bg" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
	<div class="sec-wrapper <?php echo $paddingClass; ?> p80">
		<?php if($title): ?>
			<div class="heading-sc">
				<h2 class="text-upper m-0 text-center"><?php echo $title; ?></h2>
			</div>
		<?php endif; ?>
		<?php if($articles): ?>
		<div class="row max-1366 latest-articles__content">
			<?php foreach( $articles as $key => $article ): ?>
				<?php
			        // Determine if it's the first or last element
			        $is_first = ($key === 0);
			        $is_last = ($key === count($articles) - 1);

			        // Build the class string
			        $classes = '';
			        if (!$is_last) {
			            $classes .= 'mb-5 mb-md-0 ';
			        }
			        $imageID = get_post_thumbnail_id($article->ID);
			    ?>
				<div class="col-12 col-md-4 article-each <?php echo trim($classes); ?> d-flex flex-column justify-content-between">
					<div>
						<div class="img-w article-each__img">
							<?php echo get_custom_image($imageID, false, $class = '', $article->post_title ); ?>
						</div>
						<div class="article-each__date d-flex align-items-center justify-content-between">
							<div class="Fira-Bold">
								<?php echo get_the_date('F j, Y',$article->ID); ?>
							</div>
							<div class="article-each__cat">
								<?php 
					                $categories = get_the_category($article->ID);
					                if ( ! empty( $categories ) ):
					                    foreach ( $categories as $key => $category ):
					            ?>              
					                <a class="category__each" href='<?php echo get_category_link($category->term_id); ?>'>
					                    <?php echo esc_html( $category->name ) ?> 
					                </a>
					            <?php 
					                    endforeach;
					                endif;
					            ?>
							</div>
						</div>
						<div class="article-each__title mobile-center">
							<h2 class="font24 Fira-Regular m-0"><?php echo $article->post_title; ?></h2>
						</div>
					</div>
					<div>
						<div class="article-each_link mobile-center">
							<div class="btn-sec">
								<?php 
									$cta = array ( 'url' => get_permalink($article->ID), 'title' => 'Continue Reading' );
									getBtn('white', $cta, 'white', 'dark' ); 
								?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<?php if($cta_section): ?>
			<div class="btn-sec text-center latest-articles__viewall">
				<?php getBtn('black', $cta_section['link'], 'dark', 'white' ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>	