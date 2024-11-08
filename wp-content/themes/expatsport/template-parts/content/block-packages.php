<?php
// For Normal Pages
$title = get_sub_field('title');
$description = get_sub_field('description');
$header_cta = get_sub_field('header_cta');
include 'extras.php';
?>

<section class="section section-experience-list white-bg" data-st-scene="success" <?php echo $backgroundBGColorStyle; ?>>
    <div class="sec-wrapper <?php echo $paddingClass; ?> p80">
        <div class="d-md-flex align-items-start">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <div class="heading-sc m-0 mb-4">
                    <?php if ($title): ?>
                        <h2 class="text-upper m-0"><?php echo $title; ?></h2>
                    <?php endif; ?>
                    <?php if ($description): ?>
                        <div class="descp mt-3">
                            <?php echo $description; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-5 mb-md-0">
                <?php if ($header_cta): ?>
                    <div class="filter-sec d-md-flex justify-content-end mobile-center">
                        <?php foreach ($header_cta as $key => $cta): ?>
                            <?php
                            // Determine if it's the first or last element
                            $is_first = ($key === 0);
                            $is_last = ($key === count($header_cta) - 1);
                            $classes = '';
                            if (!$is_last)
                                $classes .= 'mb-3 mb-md-0 ';
                            if (!$is_first)
                                $classes .= 'ms-md-4';
                            ?>
                            <div class="<?php echo trim($classes); ?>">
                                <?php getBtn('orange', $cta['link'], 'orange', 'white'); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="p-relative">
            <div class="row experience-slider">
                <?php
                // Query to get all products in the F1 category
                $args_f1 = array(
                    'post_type' => 'product',
                    'posts_per_page' => -1, // Get all products
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'f1', // Change to the slug of the F1 category
                        ),
                    ),
                );
                $loop_f1 = new WP_Query($args_f1);

                if ($loop_f1->have_posts()):
                    while ($loop_f1->have_posts()):
                        $loop_f1->the_post();
                        // Get the product name
                        $product_name = get_the_title();
                        // Get the custom fields for each product
                        $start_date = get_field('start_date');
                        $end_date = get_field('end_date');
                        $f1_event_location = get_field('f1_event_location');
                        // Format the dates
                        $start_day = date('d', strtotime($start_date));
                        $start_month = date('M', strtotime($start_date));

                        $end_day = date('d', strtotime($end_date));
                        $end_month = date('M', strtotime($end_date));
                        $product_link = get_permalink();
                        ?>
                        <div class="col experience-each">
                            <a href="<?php echo get_permalink(); ?>">
                                <div class="experience-each__content b-radius">
                                    <div class="experience-each__conten__cat primary-bg">Formula 1</div>
                                    <div class="experience-each__content__img">
                                        <div class="image-fill">
                                            <?php if (has_post_thumbnail()): ?>
                                                <?php the_post_thumbnail(); ?>
                                            <?php else: ?>
                                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.png"
                                                    alt="<?php echo $product_name; ?>">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="experience-each__content__text">
                                        <div class="experience-each__content__title Fira-Bold mb-2">
                                            <?php echo esc_html($product_name); ?>
                                        </div>
                                        <div class="experience-each__content__location d-flex align-items-center mb-2">
                                            <div class="col-1">
                                                <div class="">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pin.png"
                                                        width="12">
                                                </div>
                                            </div>
                                            <div class="col-11"><?php echo esc_html($f1_event_location); ?></div>
                                        </div>
                                        <div class="experience-each__content__date d-flex align-items-center mb-2">
                                            <div class="col-1">
                                                <div class="">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar.png"
                                                        width="16">
                                                </div>
                                            </div>
                                            <div class="col-11">
                                                <?php echo esc_html($start_day); ?>         <?php echo esc_html($start_month); ?>
                                                -
                                                <?php echo esc_html($end_day); ?>         <?php echo esc_html($end_month); ?>
                                            </div>
                                        </div>
                                        <div class="experience-each__content__btn btn-sec text-center">
                                            <?php $cta = array('title' => 'Buy Now'); ?>
                                            <?php getBtn('orange', $cta, 'orange', 'white'); ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    endwhile;
                else:
                    echo '<p>No F1 events available at the moment.</p>';
                endif;
                wp_reset_postdata();
                ?>
            </div>
            <div class="d-flex experience-each-slider-arrow">
                <div class="me-3">
                    <div class="arrow-circle centerContent prev-arrow">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right-arrow-dark.png"
                            width="50">
                    </div>
                </div>
                <div>
                    <div class="arrow-circle centerContent next-arrow">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/right-arrow-dark.png"
                            width="50">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>