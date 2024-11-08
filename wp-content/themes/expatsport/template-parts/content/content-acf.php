<?php

// Check value exists.
if( have_rows('contents') ):

    // Loop through rows.
    $rowKey = 0;
    while ( have_rows('contents') ) : the_row();

        if( get_row_layout() == 'bubble_image_text_section' ):
        	get_template_part( 'template-parts/content/block', 'bubble-images-text-side', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'image_text_section' ):
            get_template_part( 'template-parts/content/block', 'image-text-side', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'sports_experience_section' ):
            get_template_part( 'template-parts/content/block', 'auto-column-boxes', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'sports_service_section' ):
            get_template_part( 'template-parts/content/block', 'sports-services', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'packages_section' ):
            get_template_part( 'template-parts/content/block', 'packages', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'two_column_text_section' ):
            get_template_part( 'template-parts/content/block', 'text-side', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'partners_section' ):
            get_template_part( 'template-parts/content/block', 'partners', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'clients_section' ):
            get_template_part( 'template-parts/content/block', 'clients', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'latest_articles_section' ):
            get_template_part( 'template-parts/content/block', 'latest-articles', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'locations_section' ):
            get_template_part( 'template-parts/content/block', 'locations', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'faq_section' ):
            get_template_part( 'template-parts/content/block', 'faqs', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'team_list_section' ):
            get_template_part( 'template-parts/content/block', 'team-list', array('rowKey'=>$rowKey) );   

        elseif( get_row_layout() == 'team_detail_section' ):
            get_template_part( 'template-parts/content/block', 'team-detail', array('rowKey'=>$rowKey) );     

        elseif( get_row_layout() == 'cta_banner_section' ):
            get_template_part( 'template-parts/content/block', 'cta-banner', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'latest_events_section' ):
            get_template_part( 'template-parts/content/block', 'latest-events', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'location_map_section' ):
            get_template_part( 'template-parts/content/block', 'location-maps', array('rowKey'=>$rowKey) ); 
        
        elseif( get_row_layout() == 'contact_form_section' ):
            get_template_part( 'template-parts/content/block', 'contact-form', array('rowKey'=>$rowKey) );  

        elseif( get_row_layout() == 'event_about_section' ):
            get_template_part( 'template-parts/content/block', 'event-about', array('rowKey'=>$rowKey) );   

        elseif( get_row_layout() == 'event_offering_section' ):
            get_template_part( 'template-parts/content/block', 'event-offering', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'event_package_section' ):
            get_template_part( 'template-parts/content/block', 'event-package', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'three_column_image_cta_section' ):
            get_template_part( 'template-parts/content/block', 'three-column-image-cta', array('rowKey'=>$rowKey) );
            

        /*elseif( get_row_layout() == 'cta_section' ):
            get_template_part( 'template-parts/content/block', 'cta-section', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'products_list' ):
            get_template_part( 'template-parts/content/block', 'products-list', array('rowKey'=>$rowKey) );
 
        elseif( get_row_layout() == 'articles_list' ):
            get_template_part( 'template-parts/content/block', 'articles-list', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'news_list' ):
            get_template_part( 'template-parts/content/block', 'news-list', array('rowKey'=>$rowKey) );

        elseif( get_row_layout() == 'text_only_section' ):
            get_template_part( 'template-parts/content/block', 'plain-text', array('rowKey'=>$rowKey) );*/

        endif;
    // End loop.
    endwhile;
endif;

?>