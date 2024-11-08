<?php
/**
 * Template for displaying UFC category single product.
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post(); 
        global $product;
        // Custom UFC design and layout for single product
        ?>
        <div class="ufc-single-product">
            <h1 class="ufc-product-title"><?php the_title(); ?></h1>
            <div class="ufc-product-image">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail(); ?>
                <?php endif; ?>
            </div>
            <div class="ufc-product-details">
                <p><strong>Event Location:</strong> <?php echo esc_html(get_field('event_location')); ?></p>
                <p><strong>Start Date:</strong> <?php echo esc_html(get_field('start_date')); ?></p>
                <p><strong>End Date:</strong> <?php echo esc_html(get_field('end_date')); ?></p>
            </div>
            <div class="ufc-product-description">
                <?php the_content(); ?>
            </div>
            <div class="ufc-add-to-cart">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>
        </div>
        <?php
    endwhile;
endif;

get_footer();
