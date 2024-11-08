<?php
	/**
	 * Template Name: Blog Archive Template
	 * @package Expatsport
	 * @version 1.0
	 */

	get_header(null, array('headerClass'=>'solid')); 
?>
	<div class="elWithHeaderPadding"></div>
	<section <?php generate_section_id($args); ?> class="archive-articles pt-5 <?php generate_section_classes($args); ?>" <?php generate_section_styles($args); ?> data-st-scene="archive-articles">
		<div class="container">
			<?php 
				$title = __('Articles'); 
				if(is_category()){
					$title = __('Category', 'electra') . ': ' .  single_term_title('', false);
				}else if(is_archive()){
					$title =  get_the_archive_title();
				}
			?>
			<?php get_template_part( 'template-parts/components/comp', 'section-title', array('title'=>$title, 'headingStyle'=>'h2') ); ?>

			<div class="articles-grid">
				<?php if (have_posts()) : ?>
					<div class="articles-grid__content row gx-5 align-items-stretch">
						<?php while (have_posts()): the_post(); ?>
							<div class="col-12 col-md-4 d-flex flex-column mb-5">
								<?php get_template_part( 'template-parts/components/comp', 'article-grid-item' ); ?>
							</div>
						<?php endwhile; ?>
					</div>

					<!-- Pagination -->
					<?php
						global $wp_query;
						$total_pages = $wp_query->max_num_pages;
						if ( $total_pages > 1 ) :
							$current_page = max( 1, get_query_var( 'paged' ) ); 
							$base = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
							$format  = isset( $format ) ? $format : '';
					?>
								<div class="pagination">
					                <?php 
										echo paginate_links( 
											array( // WPCS: XSS ok.
												'base'      => $base,
												'format'    => $format,
												'add_args'  => false,
												'current'   => $current_page,
												'total'     => $total_pages,
												'prev_text' => '<span><img class="me-1" alt="&lt;" src="' .get_stylesheet_directory_uri() . '/assets/img/icon-chevron-left.svg" width="25" /> ' . __('Prev', 'electra') . '</span>',
												'next_text' => '<span>' . __('Next', 'electra') . ' <img class="ms-1" alt="&lt;" src="' .get_stylesheet_directory_uri() . '/assets/img/icon-chevron-right.svg" width="25" /></span>',
												'type'      => 'list',
												'end_size'  => 3,
												'mid_size'  => 3,
											)
										); 

									?>
					            </div>
					<?php 
						endif;
					?>
					<!-- Pagination End -->

				<?php else: ?>
					<?php render_articles_grid_no_articles(); ?>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php
	get_template_part( 'template-parts/content/block', 'cta-section', array('fetchFromOptions'=>true, 'optionsPrefix'=>'blog_cta_') );

	get_footer();
?>
