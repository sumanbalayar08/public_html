<?php
get_header();

global $post;

// Get the product categories
$product_categories = wp_get_post_terms($post->ID, 'product_cat');

$template_found = false; // Flag to check if a template was loaded

if (!empty($product_categories)) {
    // Loop through the categories to check which one the product belongs to
    foreach ($product_categories as $category) {
        if ($category->slug == 'f1') {
            get_template_part('page-templates/single-product-category-f1');
            $template_found = true;
            break;
        } elseif ($category->slug == 'ufc') {
            get_template_part('page-templates/single-product-category-ufc');
            $template_found = true;
            break;
        }
    }
}

// Fallback to default single product template if no category-specific template was found
if (!$template_found) {
    wc_get_template_part('content', 'single-product');
}

get_footer();
?>
