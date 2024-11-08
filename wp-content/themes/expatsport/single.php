<?php
	get_header(); 

	if (have_posts()) :
		while (have_posts()): the_post();
			get_template_part( 'template-parts/components/comp', 'post-banner' );

			if(get_the_content() != ""){
				echo "<section class='container content-page-wysiwyg' id='content-page'>";
				the_content();
				echo "</section>";
			}
			get_template_part( 'template-parts/content/content', 'acf' );
		endwhile;
	endif;
	wp_reset_query();

	get_footer();
?>
