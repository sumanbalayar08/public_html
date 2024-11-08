<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Expatsport
 */

get_header(); 

?>
	<?php
		while ( have_posts() ) :
			the_post();

			// Banner Section
			get_template_part( 'template-parts/content/block', 'banner' );

			if(get_the_content() != ""){
				echo "<section class='content-page-wysiwyg container' id='content-page'>";
				the_content();
				echo "</section>";
			}

			// content
			get_template_part( 'template-parts/content/content', 'acf' );

		endwhile; // End of the loop.
	?>
<?php

get_footer();
