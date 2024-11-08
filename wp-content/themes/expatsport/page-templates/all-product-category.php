<?php

get_header();

// Query to get all products in the F1 category
$args_f1 = array(
    'post_type' => 'product',
    'posts_per_page' => -1, // Get all products
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => 'f1', // Change to the slug of the F1 category
        ),
    ),
);

$loop_f1 = new WP_Query($args_f1);

if ($loop_f1->have_posts()) :
?>
    <div class="event-package-container">
        <?php while ($loop_f1->have_posts()) : $loop_f1->the_post(); 
            // Get the product name
            $product_name = get_the_title();

            // Get the custom fields for each product
            $start_date = get_field('start_date');
            $end_date = get_field('end_date');
            $f1_event_location = get_field('f1_event_location');

            // Format the dates
            $start_day = date('d', strtotime($start_date));
            $end_day = date('d', strtotime($end_date));
            $end_month = date('M', strtotime($end_date));
        ?>
            <a href="<?php echo get_permalink(); ?>" class="event-link">
                <div class="f1-event-container">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/united-arab-emirates.png" alt="abu-dhabi-flag" width="100" />
                    <div class="f1-event-title">
                        <?php echo esc_html($product_name); ?>
                    </div>
                    <div class="f1-event-location">
                        <?php echo esc_html($f1_event_location); ?>
                    </div>
                    <div class="f1-event-date">
                        <?php echo esc_html($start_day); ?> - <?php echo esc_html($end_day); ?> <?php echo esc_html($end_month); ?>
                    </div>
                    <div class="f1-buy">
                        BUY NOW
                    </div>
                </div>
            </a>
        <?php endwhile; ?>
    </div>
<?php
else :
    echo '<p>No F1 events available at the moment.</p>';
endif;

wp_reset_postdata();

// Query to get all products in the UFC category
$args_ufc = array(
    'post_type' => 'product',
    'posts_per_page' => -1, // Get all products
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => 'ufc', // Change to the slug of the UFC category
        ),
    ),
);

$loop_ufc = new WP_Query($args_ufc);

if ($loop_ufc->have_posts()) :
?>
    <div class="event-container">
        <div class="event-title">
            <div id="event-title-head">Book Your Experience Online</div>
            <div id="event-title-desc">View our event packages with attractive ticket and hotel options</div>
        </div>
        <div class="event-package-container">
            <?php while ($loop_ufc->have_posts()) : $loop_ufc->the_post(); 
                $product_name = get_the_title();
                $start_date = get_field('start_date');
                $end_date = get_field('end_date');
                $event_location = get_field('event_location');
                $start_day = date('d', strtotime($start_date));
                $end_day = date('d', strtotime($end_date));
                $end_month = date('M', strtotime($end_date));
            ?>
            <a href="<?php echo get_permalink(); ?>" class="event-link">
                <div class="event-package">
                    <div id="event-image">
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
                    </div>

                    <div id="event-details">
                        <div id="event-name"><?php echo esc_html($product_name); ?></div>
                        <div id="event-date">
                            <i class="fa-solid fa-calendar-days" style="color: #ea5606;"></i>
                            <span><?php echo esc_html($start_day); ?> - <?php echo esc_html($end_day); ?> <?php echo esc_html($end_month); ?></span>
                        </div>
                        <div id="event-location">
                            <i class="fa-solid fa-location-dot" style="color: #ea5606;"></i>
                            <span><?php echo esc_html($event_location); ?></span>
                        </div>
                    </div>
                    <div id="event-btn">
                        <span>View Packages</span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-point-to-right.png" width="10" />
                    </div>
                </div>
            </a>
            <?php endwhile; ?>
        </div>

        <div class="event-buttons">
            <button class="btn1"><span>Get in Touch</span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-2.png" width="10" /></button>
            <button class="btn2"><span>Buy Today</span>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-point-to-right.png" width="10" /></button>
        </div>
    </div>
<?php
else :
    echo '<p>No UFC events available at the moment.</p>';
endif;

wp_reset_postdata();

get_footer();
?>
