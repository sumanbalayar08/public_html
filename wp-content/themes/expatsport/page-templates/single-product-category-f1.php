<?php
// Get the global post object
global $post;

// Check if it's a single product in the F1 category
if ('product' === get_post_type() && has_term('f1', 'product_cat', $post)):
    $basic_packages = get_field('f1_basic_package_types');
    $premium_packages = get_field('f1_premium_package_types');

    $f1_intro_images = get_field(selector: 'f1_intro_images');
    ?>

    <div class="f1-ticket-selection__container">
        <div class="dropdown-slider">
            <div class="dropdown-slider-tab">
                <div class="dropdown-slider-title"><span id="dropdown-step">STEP 1:</span> SEAT TICKET</div>
                <img class="icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.png" width="20">
            </div>
            <div class="collapsible-container expanding">
                <div class="modal-step-1">
                    <div class="f1_intro_images">
                        <?php if (have_rows('f1_intro_images')): ?>
                            <?php while (have_rows('f1_intro_images')):
                                the_row(); ?>
                                <?php
                                $f1_intro_image_1 = get_sub_field('f1_intro_image_1');
                                if ($f1_intro_image_1): ?>
                                    <img src="<?php echo esc_url($f1_intro_image_1['url']); ?>" width="100"
                                        alt="<?php echo esc_attr($f1_intro_image_1['alt']); ?>">
                                <?php endif; ?>

                                <?php
                                $f1_intro_image_2 = get_sub_field('f1_intro_image_2');
                                if ($f1_intro_image_2): ?>
                                    <img src="<?php echo esc_url($f1_intro_image_2['url']); ?>" width="100"
                                        alt="<?php echo esc_attr($f1_intro_image_1['alt']); ?>">
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No image available.</p>
                        <?php endif; ?>
                    </div>


                    <?php if (have_rows('f1_basic_package_types')): ?>
                        <!-- Basic Packages -->
                        <?php while (have_rows('f1_basic_package_types')):
                            the_row(); ?>
                            <div class="f1-ticket-options"
                                basic-total-tickets="<?php the_sub_field('f1_basic_package_total_tickets'); ?>">
                                <div class="f1-ticket-desc">
                                    <span>
                                        <?php the_sub_field('f1_package_title'); ?>
                                    </span>
                                    <p><?php the_sub_field('f1_package_description'); ?></p>
                                    <p id="total-tickets">Total Tickets: <?php the_sub_field('f1_basic_package_total_tickets'); ?>
                                    </p>
                                    <button class="ticket-package" data-package="basic">Discover More</button>
                                </div>
                                <?php
                                $image = get_sub_field('f1_package_image');
                                if ($image): ?>
                                    <img src="<?php echo esc_url($image['url']); ?>" width="100"
                                        alt="<?php echo esc_attr($image['alt']); ?>">
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No basic packages available.</p>
                    <?php endif; ?>

                    <!-- Premium Packages -->
                    <?php if (have_rows('f1_premium_package_types')): ?>
                        <?php while (have_rows('f1_premium_package_types')):
                            the_row(); ?>
                            <div class="f1-ticket-options"
                                vip-total-tickets="<?php the_sub_field('f1_vip_package_total_tickets'); ?>">
                                <?php
                                $image = get_sub_field('f1_package_image');
                                if ($image): ?>
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                                <?php endif; ?>
                                <div class="f1-ticket-desc">
                                    <span>
                                        <?php the_sub_field('f1_package_title'); ?>
                                    </span>
                                    <p><?php the_sub_field('f1_package_description'); ?></p>
                                    <p id="total-tickets">Total Tickets: <?php the_sub_field('f1_vip_package_total_tickets'); ?></p>

                                    <button class="ticket-package" data-package="vip">Discover More</button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No premium packages available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (have_rows('f1_basic_package_types')): ?>
            <!-- Basic Packages -->
            <?php while (have_rows('f1_basic_package_types')):
                the_row(); ?>
                <div class="dropdown-slider hidden" data-step="2" data-package="basic">
                    <div class="dropdown-slider-tab">
                        <div class="dropdown-slider-title"><span id="dropdown-step">STEP 2:</span> CRAFT YOUR PERFECT EXPERIENCE
                        </div>
                        <img class="icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.png" width="20">
                    </div>
                    <div class="collapsible-container">
                        <div class="modal">
                            <div class="modal-header">
                                <h2>West Social Club</h2>
                                <button class="close-btn">&times;</button>
                            </div>

                            <div class="tab-bar">
                                <div class="tab">West Grandstand</div>
                                <div class="tab">1 Ticket Option</div>
                                <div class="tab">
                                    <span class="icon">🔄</span>
                                    <span class="icon">🔍</span>
                                    <span class="icon">🗺️</span>
                                </div>
                                <div class="tab f1-ratings">
                                    F1 Ratings: <span class="stars">★★★★★</span>
                                </div>
                            </div>

                            <div class="image-section">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/stadium.png">
                            </div>

                            <div class="info-bar">
                                <div class="info-tab">
                                    <span class="icon">🖼️</span> Gallery
                                </div>
                                <div class="info-tab">
                                    <span class="icon">📍</span> Location
                                </div>
                            </div>

                            <div class="footer">
                                Select your tickets
                            </div>

                            <div class="price-select">
                                <div>West Social Club (Fri, Sat and Sun)</div>
                                <h3 class="f1-package-price">£<?php the_sub_field('f1_basic_package_price'); ?></h3>
                                <div class="qty-controls">
                                    <button class="qty-btn" id="decrease">−</button>
                                    <span class="tickets-amount">1</span>
                                    <button class="qty-btn" id="increase">+</button>
                                </div>
                                <button class="add-to-cart">Add to Cart</button>
                            </div>

                            <div class="additional-package-details">
                                <h2>More of Your Package Experience</h2>
                                <div class="package-groups">
                                    <div class="package-detail">
                                        <img src="/car-63930_640.webp" />
                                        <h2>F1 Information Details</h2>
                                        <h5>Abu Dhabi, UAE</h5>
                                        <p>Elevate your Formula 1 experience with our F1 Premium Package, crafted for those who
                                            seek the
                                            ultimate in luxury and exclusivity. This package offers an unparalleled weekend of
                                            high-octane
                                            thrills and
                                            first-class service.</p>
                                    </div>

                                    <div class="package-detail">
                                        <img src="/car-63930_640.webp" />
                                        <h2>F1 Information Details</h2>
                                        <h5>Abu Dhabi, UAE</h5>
                                        <p>Elevate your Formula 1 experience with our F1 Premium Package, crafted for those who
                                            seek the
                                            ultimate in luxury and exclusivity. This package offers an unparalleled weekend of
                                            high-octane
                                            thrills and
                                            first-class service.</p>
                                    </div>
                                    <div class="package-detail">
                                        <img src="/car-63930_640.webp" />
                                        <h2>F1 Information Details</h2>
                                        <h5>Abu Dhabi, UAE</h5>
                                        <p>Elevate your Formula 1 experience with our F1 Premium Package, crafted for those who
                                            seek the
                                            ultimate in luxury and exclusivity. This package offers an unparalleled weekend of
                                            high-octane
                                            thrills and
                                            first-class service.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="concert-details">
                                <div class="concert-title">Included in Your Experience</div>
                                <div class="concert">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/edsheeran.png">
                                    <h4 class="eminem">Eminem <br />Concert</h4>
                                </div>

                                <div class="concert">
                                    <h4 class="eminem">Yas Island <br />Theme Park</h4>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/yas-island.jpg">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No basic packages available.</p>
        <?php endif; ?>


        <?php if (have_rows('f1_premium_package_types')): ?>
            <?php while (have_rows('f1_premium_package_types')):
                the_row(); ?>
                <div class="dropdown-slider hidden" data-step="2" data-package="vip">
                    <div class="dropdown-slider-tab">
                        <div class="dropdown-slider-title"><span id="dropdown-step">STEP 2:</span> PERSONALISE YOUR
                            ACCOMMODATION</div>
                        <img class="icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.png" width="20">
                    </div>
                    <div class="collapsible-container">
                        <div class="modal">
                            <div class="modal-header">
                                <h2>West Social Club</h2>
                                <button class="close-btn">&times;</button>
                            </div>

                            <div class="tab-bar">
                                <div class="tab">West Grandstand</div>
                                <div class="tab">1 Ticket Option</div>
                                <div class="tab">
                                    <span class="icon">🔄</span>
                                    <span class="icon">🔍</span>
                                    <span class="icon">🗺️</span>
                                </div>
                                <div class="tab f1-ratings">
                                    F1 Ratings: <span class="stars">★★★★★</span>
                                </div>
                            </div>

                            <div class="image-section">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/stadium.png">
                            </div>

                            <div class="info-bar">
                                <div class="info-tab">
                                    <span class="icon">🖼️</span> Gallery
                                </div>
                                <div class="info-tab">
                                    <span class="icon">📍</span> Location
                                </div>
                            </div>

                            <div class="footer">
                                Select your tickets
                            </div>

                            <div class="price-select">
                                <div>West Social Club (Fri, Sat and Sun)</div>
                                <h3 class="f1-package-price">£<?php the_sub_field('f1_vip_package_price'); ?></h3>
                                <div class="qty-controls">
                                    <button class="qty-btn" id="decrease">−</button>
                                    <span class="tickets-amount">1</span>
                                    <button class="qty-btn" id="increase">+</button>
                                </div>
                                <button class="add-to-cart">Add to Cart</button>
                            </div>

                            <div class="how-to-container">
                                <div class="product-steps-heading">
                                    How to Book UFC Package
                                </div>
                                <div class="product-steps">
                                    <div class="product-step">
                                        <div class="product-help-circle">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/calendar1.png"
                                                width="30">
                                        </div>
                                        <div class="product-help-desc">
                                            <span class="product-step-heading">Step 1</span>
                                            <span class="product-step-desc">Select Your Dates</span>
                                        </div>

                                    </div>
                                    <div class="product-step">
                                        <div class="product-help-circle">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hotel.png"
                                                width="30">
                                        </div>
                                        <div class="product-help-desc">
                                            <span class="product-step-heading">Step 2</span>
                                            <span class="product-step-desc">Choose Your Hotel </span>
                                        </div>
                                    </div>
                                    <div class="product-step">
                                        <div class="product-help-circle">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ticket.png"
                                                width="30">
                                        </div>
                                        <div class="product-help-desc">
                                            <span class="product-step-heading">Step 3</span>
                                            <span class="product-step-desc">Pick your Ticket Category</span>
                                        </div>
                                    </div>
                                    <div class="product-step">
                                        <div class="product-help-circle">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/coconuttree.png"
                                                width="30">
                                        </div>
                                        <div class="product-help-desc">
                                            <span class="product-step-heading">Step 4</span>
                                            <span class="product-step-desc">Arrive on Yas Island for Ultimate UFC
                                                Experience</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="image-1">
                                <img src="/12.png" />
                            </div>

                            <div class="image-2">
                                <h2>Included In your Experience</h2>
                                <img src="/west.png" width="600" />
                            </div>

                            <div class="concert-details">
                                <div class="concert-title">Included in Your Experience</div>
                                <div class="concert">
                                    <img src="/edsheeran.png" />
                                    <h4 class="eminem">Secure Your <br />Own Area</h4>
                                </div>

                                <div class="concert">
                                    <h4 class="eminem">F1 Track Access</h4>
                                    <img src="/drinking and enjoying.png" />

                                </div>
                            </div>

                            <!-- <div class="quote-container">
                                <h2>Request a quote</h2>
                                <div class="quote-sub-container">
                                    <div class="quote-form">
                                        <form id="quoteRequestForm">
                                            <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
                                            <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
                                            <input type="email" id="email" name="email" placeholder="Email" required>
                                            <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>
                                            <textarea id="message" name="message" placeholder="Your message" rows="4"
                                                required></textarea>
                                            <button type="submit">Submit</button>
                                        </form>
                                    </div>
                                    <div class="quote-info">
                                        <h3>Call me back</h3>
                                        <p>Our specialists are here to help! Get in touch with our team of experts who are ready
                                            to assist
                                            you in
                                            finding the best solution for your needs.</p>
                                        <p><strong>Mon - Fri: 9:00 AM - 5:00 PM PST</strong><br>+1 123 456 7890</p>
                                        <button class="callback-btn">CALL ME BACK</button>
                                    </div>
                                </div>
                            </div> -->
                            <div class="quote-container">
                                <p class="quote-container-title">Request a quote</p>
                                <form id="quoteRequestForm">
                                    <div class="quote-sub-container">
                                        <div class="quote-form">
                                            <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
                                            <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
                                            <input type="email" id="email" name="email" placeholder="Email" required>
                                            <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>
                                            <textarea id="message" name="message" placeholder="Your message" rows="4"
                                                required></textarea>
                                        </div>
                                        <div class="quote-info">
                                            <h3>Call me back</h3>
                                            <p>Our specialists are here to help! Get in touch with our team of experts who
                                                are ready
                                                to assist
                                                you in
                                                finding the best solution for your needs.</p>
                                            <p><strong>Mon - Fri: 9:00 AM - 5:00 PM PST</strong><br>+1 123 456 7890</p>
                                            <button class="callback-btn">CALL ME BACK</button>
                                        </div>
                                    </div>
                                    <button type="submit">Submit</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No basic packages available.</p>
        <?php endif; ?>

        <div class="dropdown-slider hidden" data-step="3" data-package="basic">
            <div class="dropdown-slider-tab">
                <div class="dropdown-slider-title"><span id="dropdown-step">STEP 3:</span> PAYMENT OPTION</div>
                <img class="icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.png" width="20">
            </div>
            <div class="collapsible-container">
                <div class="checkout-container">
                    <!-- Left Section: Guest Details and Payment -->
                    <h2>Guest Details and Payment</h2>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="email">Email Address*</label>
                            <input type="email" id="payment-email" placeholder="Enter your email">
                        </div>
                        <div class="input-group">
                            <label for="confirm-email">Confirm Email Address*</label>
                            <input type="email" id="confirm-email" placeholder="Re-enter your email">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="first-name">First Name*</label>
                            <input type="text" id="first-name" placeholder="Enter first name">
                        </div>
                        <div class="input-group">
                            <label for="last-name">Last Name*</label>
                            <input type="text" id="last-name" placeholder="Enter last name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group">
                            <label for="country">Country of Residence*</label>
                            <input type="text" id="country" placeholder="Enter your country">
                        </div>
                        <div class="input-group">
                            <label for="nationality">Nationality*</label>
                            <input type="text" id="nationality" placeholder="Select nationality">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group full-width">
                            <label for="phone-number">Phone Number*</label>
                            <input type="tel" id="phone-number" placeholder="Phone number">
                        </div>
                    </div>

                    <div class="privacy-policy">
                        <input type="checkbox" id="policy-check">
                        <label for="policy-check">I acknowledge the Terms and Privacy Policy</label>
                    </div>

                    <h3>Please Enter Payment Details</h3>

                    <!-- <div class="payment-options">
                        <input type="radio" id="credit-card" name="payment-method" checked>
                        <label for="credit-card">Credit/Debit Card</label>

                        <input type="radio" id="paypal" name="payment-method">
                        <label for="paypal">PayPal</label>
                    </div> -->

                    <div class="form-row" id="credit-card-details">
                        <div class="input-group">
                            <label for="card-number">Card Number*</label>
                            <input type="text" id="card-number" placeholder="Enter card number" pattern="\d{13,19}"
                                maxlength="19" required>
                        </div>
                        <div class="input-group">
                            <label for="expiry-date">Expiry Date*</label>
                            <input type="text" id="expiry-date" placeholder="MM/YY" pattern="(0[1-9]|1[0-2])\/([0-9]{2})"
                                maxlength="5" required>
                        </div>
                        <div class="input-group">
                            <label for="cvv">CVV*</label>
                            <input type="text" id="cvv" placeholder="CVV" pattern="\d{3,4}" maxlength="4" required>
                        </div>
                    </div>

                    <div class="form-row" id="paypal-phone" style="display: none;">
                        <div class="input-group full-width">
                            <label for="paypal-phone-number">Phone Number*</label>
                            <input type="tel" id="paypal-phone-number" placeholder="Phone number">
                        </div>
                    </div>

                    <div class="terms-check">
                        <input type="checkbox" id="terms-check">
                        <label for="terms-check">I have read and accepted the Terms & Conditions</label>
                    </div>

                    <button class="payment-btn">MAKE PAYMENT</button>

                    <div class="payment-icons">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/visa.png" alt="Visa">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/mastercard.png"
                            alt="MasterCard">
                    </div>

                </div>
                <!-- <button class="complete-step">Complete Step 3</button> -->
            </div>
        </div>

        <div class="dropdown-slider hidden" data-step="4" data-package="basic">
            <div class="dropdown-slider-tab">
                <div class="dropdown-slider-title"><span id="dropdown-step">STEP 4:</span> ORDER SUCCESS</div>
                <img class="icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/dropdown.png" width="20">
            </div>
            <div class="collapsible-container">
                <div class="success-animation">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                    </svg>
                    <div class="order-text">
                        Your transaction has been completed successfully. We have<br /> enrolled details of your order.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
else:
    echo '<p>Product not found.</p>';
endif;
?>