<?php
function render_news_grid_items($query, $options=array()){
    $key = 0;
    while ($query->have_posts()):
        $args = array('boxSize'=>'portrait');
        $colClass = 'col-12 col-md-4';

        if($key == 0 && isset($options['firstFeatured'])){
            $args['boxSize'] = 'landscape';
            $colClass = 'col-12 col-md-8';
        }
        $colClass .= ' d-flex flex-column mb-4 mb-md-5';

        $query->the_post(); 
        echo '<div class="' . $colClass . '">';
        get_template_part( 'template-parts/components/comp', 'news-grid-item', $args );
        echo '</div>';

        $key++;
    endwhile;
    wp_reset_postdata();
}


function render_posts_grid_no_posts(){
    echo '<div class="text-center">';
    _e('There are no posts matching your search query', 'electra');
    echo '</div>';
}


function get_custom_image($image_id, $use_picture = false, $class = '', $alt = '') {
    if (!$image_id) return '';

    $image_meta = wp_get_attachment_metadata($image_id);
    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    $image_alt = $image_alt ? esc_attr($image_alt) : ($alt ? $alt : ''); // Escaping alt for security
    $default_format = 'jpg'; // Default format if WebP is not available

    // Get the URL for the original image
    $image_url = wp_get_attachment_url($image_id);

    // Generate the HTML for the image
    $html = '';

    // If a CSS class is provided, escape it for security
    $class_attr = $class ? 'class="' . esc_attr($class) . '"' : '';

    if ($use_picture) {
        // Picture tag with WebP support
        $html .= '<picture>';
        foreach ($image_meta['sizes'] as $size => $size_data) {
            $webp_url = wp_get_attachment_image_src($image_id, $size)[0];
            $sizes = wp_get_attachment_image_sizes($image_id, $size);

            $html .= sprintf(
                '<source type="image/webp" srcset="%s" sizes="%s">',
                esc_url($webp_url),
                esc_attr($sizes)
            );
        }
        // Fallback to default image format
        $html .= sprintf(
            '<img %s src="%s" alt="%s" width="%d" height="%d" srcset="%s" sizes="%s">',
            $class_attr,
            esc_url($image_url),
            $image_alt,
            esc_attr($image_meta['width']),
            esc_attr($image_meta['height']),
            esc_attr(wp_get_attachment_image_srcset($image_id)),
            esc_attr(wp_get_attachment_image_sizes($image_id))
        );
        $html .= '</picture>';
    } else {
        // Regular img tag with srcset and sizes
        $html .= sprintf(
            '<img %s src="%s" alt="%s" width="%d" height="%d" srcset="%s" sizes="%s">',
            $class_attr,
            esc_url($image_url),
            $image_alt,
            esc_attr($image_meta['width']),
            esc_attr($image_meta['height']),
            esc_attr(wp_get_attachment_image_srcset($image_id)),
            esc_attr(wp_get_attachment_image_sizes($image_id))
        );
    }

    return $html;
}


function getBtn($additionalClass = '', $link, $rightArrow, $rightArrowHover ){
    /*if($link):
        $target = $link['target'] ? 'target="_blank"' : '';
        echo '<a href="' . $link['url'] . '" class="btn d-inline-flex ' . esc_attr($additionalClass) . '"  ' . $target . '>';
        echo '<span>' . $link['title'] . '</span>';
        echo '<img src="' . get_template_directory_uri() . '/assets/images/right-arrow-' . $rightArrow . '.png" width="10" height="16" class="ms-3">';
        echo '<img src="' . get_template_directory_uri() . '/assets/images/right-arrow-' . $rightArrowHover . '.png" width="10" height="16" class="btn-arrow-img-absolute">';
        echo '</a>';
    endif;*/
    if ($link):
        $target = isset($link['target']) ? 'target="_blank"' : '';
        echo '<a href="' . esc_url($link['url']) . '" class="button ' . esc_attr($additionalClass) . '"  ' . $target . '>';
        echo '    <div class="inner d-flex align-items-center">';
        echo '        <div class="label d-flex align-items-center">';
        echo '            <div class="me-2">' . esc_html($link['title']) . '</div>';
        echo '            <img src="' . get_template_directory_uri() . '/assets/images/right-arrow-' . esc_attr($rightArrow) . '.png" width="10" height="16" class="">';
        echo '        </div>';
        echo '    </div>';
        echo '    <div class="inner">';
        echo '        <div class="label d-flex align-items-center">';
        echo '            <div class="me-2">' . esc_html($link['title']) . '</div>';
        echo '            <img src="' . get_template_directory_uri() . '/assets/images/right-arrow-' . esc_attr($rightArrowHover) . '.png" width="10" height="16" class="">';
        echo '        </div>';
        echo '    </div>';
        echo '</a>';
    endif;
}


function button_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'title' => 'Click Here',
            'link' => '#',
            'bgcolor' => 'orange',
            'hover' => 'white',
            'extraClass' => '',
            'target' => 'false',
        ), 
        $atts, 
        'button'
    );

    $link = array(
        'url' => $atts['link'],
        'title' => $atts['title'],
        'target' => $atts['target']
    );

    ob_start();
    getBtn($atts['extraClass'], $link, $atts['bgcolor'], $atts['hover']);
    return ob_get_clean();
}

add_shortcode('button', 'button_shortcode');
