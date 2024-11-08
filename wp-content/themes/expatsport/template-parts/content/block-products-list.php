<?php 
	wp_enqueue_script( 'products-list', get_stylesheet_directory_uri() . '/assets/js/parts/products-list.js', array('custom-script'), '1.1', true );

    $title = isset($args['title']) ? $args['title'] : get_sub_field('title');
    $headingType = isset($args['headingType']) ? $args['headingType'] : 'h2';
    
    $filterArgs = array(
    	'product_category' => array(), 
    	'product_style' => array(), 
    	'product_color' => array(), 
    	'product_search' => null
    );
    if(is_tax( 'product_category' )){
        $term = get_queried_object();
    	$filterArgs['product_category'][] = $term->term_id;
    }
    if(is_tax( 'product_style' )){
        $term = get_queried_object();
    	$filterArgs['product_style'][] = $term->term_id;
    }
    if(is_tax( 'product_color' )){
        $term = get_queried_object();
    	$filterArgs['product_color'][] = $term->term_id;
    }
    if(isset($_GET['product_search']) && !empty($_GET['product_search'])){
    	$filterArgs['product_search'] = $_GET['product_search'];
    }
    if(isset($_GET["category"])) { 
    	$filterArgs['product_category'] = array_merge($filterArgs['product_category'], $_GET["category"]);
	}
	if(isset($_GET["style"])) { 
    	$filterArgs['product_style'] = array_merge($filterArgs['product_style'], $_GET["style"]);
	}
	if(isset($_GET["color"])) { 
    	$filterArgs['product_color'] = array_merge($filterArgs['product_color'], $_GET["color"]);
	}
?>
<section <?php generate_section_id($args); ?> class="products-list <?php generate_section_classes($args); ?>" <?php generate_section_styles($args); ?> data-st-scene="products-list">

	<div class="container">

		<div class="d-md-flex flex-nowrap align-items-center">
			<div class="flex-grow-1 pe-md-3 mb-3 mb-md-0">
				<?php get_template_part( 'template-parts/components/comp', 'section-title', array('title'=>$title, 'class'=>'mb-0', 'headingType'=>$headingType) ); ?>
			</div>
			<div class="flex-shrink-0 ps-md-3">
				<?php get_template_part( 'template-parts/components/comp', 'cart-count', array() ); ?>
			</div>
		</div>

		<div class="product_search_input-wrapper mt-5">
			<input type="search" placeholder="<?php _e('Search for products'); ?>" name="product_search" class="product_search_input" value="<?php echo $filterArgs['product_search']; ?>" />
			<img class="product_search_input-clear" loading="lazy" src="<?php echo get_template_directory_uri()?>/assets/img/icon-close.svg" width="20" height="auto" alt="x" />
		</div>

		<div class="row gx-4 products-list-wrapper mt-5">
			<div class="col-12 col-md-3 mb-5 mb-md-0">
				<?php get_template_part( 'template-parts/components/comp', 'product-filters', array('filterArgs'=>$filterArgs) ); ?>
			</div>

			<div class="col-12 col-md-9">
				<?php get_template_part( 'template-parts/components/comp', 'products-grid', array('loadMore'=>true, 'filterArgs'=>$filterArgs) ); ?>
			</div>
		</div>

	</div>
</section>