<?php
function filter_posts($filterArgs){
	$post_type = 'eu-portfolio';
	$allowedPostTypes = array('eu-portfolio', 'eu-de-portfolio');
	if(isset($filterArgs['post_type']) && in_array($filterArgs['post_type'], $allowedPostTypes)){
		$post_type = $filterArgs['post_type'];
	}
	// current page
	$paged = isset($filterArgs['paged']) ? $filterArgs['paged'] : 1;
	// args
    $args = array(
    	'order'	        	=> 'DESC',
        'orderby'	        => 'date',
        'paged' 			=> $paged,
        'post_status'       => 'publish',
        'post_type'         => $post_type,
		'tax_query'         => array()
    );
	// main query
    $custom_query = new WP_Query( $args );
    return $custom_query;
}

function ajax_load_posts() {
	$custom_query = filter_posts($_POST);
	$paged = isset($_POST['paged']) ? $_POST['paged'] : 1;

	$options = array(
		'colCount' => isset($_POST['colCount']) ? $_POST['colCount'] : 4
	);
	ob_start();
	if ($custom_query->have_posts()){
		render_posts_grid_items($custom_query);
	}else if($paged == 1){
		render_posts_grid_no_posts();
	}
    $gridItems = ob_get_contents();
    ob_end_clean();

    ob_start();
    render_posts_grid_load_more_btn($custom_query, $paged);
    $loadMoreBtn = ob_get_contents();
    ob_end_clean();

    echo json_encode( 
    	array(
    		'gridItems' => $gridItems,
    		'loadMoreBtn' => $loadMoreBtn
    	)
    );
    wp_die();
}

add_action( 'wp_ajax_ajax_load_posts', 'ajax_load_posts' );
add_action( 'wp_ajax_nopriv_ajax_load_posts', 'ajax_load_posts' );
