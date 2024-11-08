<?php
// Add AJAX actions
add_action('wp_ajax_add_ticket_to_cart', 'add_ticket_to_cart');
add_action('wp_ajax_nopriv_add_ticket_to_cart', 'add_ticket_to_cart');

function add_ticket_to_cart() {
    // Check for nonce security
    check_ajax_referer('ajax_add_to_cart_nonce', 'security');

    if (!isset($_POST['tickets_amount']) || !isset($_POST['package_price'])) {
        wp_send_json_error('Missing data');
        wp_die();
    }

    $tickets_amount = intval($_POST['tickets_amount']);
    $package_price = floatval($_POST['package_price']);

    // Initialize WooCommerce session
    WC()->session->set('tickets_amount', $tickets_amount);
    WC()->session->set('package_price', $package_price);

    // Return success response
    wp_send_json_success('Ticket added to cart');
    wp_die();
}

// Add ticket data to order meta
add_action('woocommerce_checkout_create_order', 'add_ticket_data_to_order', 20, 2);

function add_ticket_data_to_order($order, $data) {
    $tickets_amount = WC()->session->get('tickets_amount');
    $package_price = WC()->session->get('package_price');

    if ($tickets_amount) {
        $order->update_meta_data('_tickets_amount', $tickets_amount);
    }
    if ($package_price) {
        $order->update_meta_data('_package_price', $package_price);
    }
}
// Hook into AJAX actions for logged-in and non-logged-in users
add_action('wp_ajax_handle_vip_contact_form', 'handle_vip_contact_form');
add_action('wp_ajax_nopriv_handle_vip_contact_form', 'handle_vip_contact_form');

function handle_vip_contact_form() {
    // Verify nonce for security
    check_ajax_referer('ajax_quote_contact_nonce', 'security');

    // Check if form data is set
    if (!isset($_POST['form_data'])) {
        wp_send_json_error('No form data received');
        return;
    }

    // Parse the form data into an array
    $form_data = $_POST['form_data'];
    parse_str($form_data, $parsed_data);

    // Sanitize the form fields
    $first_name = sanitize_text_field($parsed_data['firstName']);
    $last_name = sanitize_text_field($parsed_data['lastName']);
    $email = sanitize_email($parsed_data['email']);
    $phone = sanitize_text_field($parsed_data['phone']);
    $message = sanitize_textarea_field($parsed_data['message']);
    $tickets_amount = isset($parsed_data['tickets_amount']) ? intval($parsed_data['tickets_amount']) : 0;
    $package_price = isset($parsed_data['package_price']) ? floatval($parsed_data['package_price']) : 0;

    // Prepare the email content
    $email_content = "New VIP Contact Form Request:\n\n";
    $email_content .= "First Name: $first_name\n";
    $email_content .= "Last Name: $last_name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Phone: $phone\n";
    $email_content .= "Message: $message\n\n";
    $email_content .= "Tickets Amount: $tickets_amount\n";
    $email_content .= "Package Price: $package_price\n";

    // Set the admin email address
    $admin_email = get_option('admin_email');
    // Set headers to include a proper From address and content type
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $first_name . ' ' . $last_name . ' <' . $email . '>', // Include user email as the sender
        'Reply-To: ' . $email
    );

    // Send the email
    $sent = wp_mail($admin_email, 'VIP Contact Form Request', $email_content, $headers);

    // Return the appropriate response based on whether the email was sent
    if ($sent) {
        wp_send_json_success('Email sent successfully');
    } else {
        wp_send_json_error('Failed to send email');
    }
}

// add_action('wp_ajax_generate_checkout_id', 'generate_checkout_id');
// add_action('wp_ajax_nopriv_generate_checkout_id', 'generate_checkout_id');

// function generate_checkout_id()
// {
//     // Check for nonce validation
//     if (!check_ajax_referer('ajax_checkout_id_nonce', 'security', false)) {
//         echo json_encode(['success' => false, 'message' => 'Nonce verification failed']);
//         wp_die();
//     }

//     $checkoutResult = createCheckout();

//     if (!empty($checkoutResult['id'])) {
//         echo json_encode(['success' => true, 'checkoutId' => $checkoutResult['id']]);
//     } else {
//         $errorMessage = isset($checkoutResult['result']['description']) ? $checkoutResult['result']['description'] : 'Unknown error';
//         echo json_encode(['success' => false, 'message' => $errorMessage]);
//     }

//     wp_die(); // This is required to terminate immediately and return the proper AJAX response.
// }


// function createCheckout()
// {
//     $url = "https://eu-test.oppwa.com/v1/checkouts";
//     $data = http_build_query([
//         'entityId' => '8ac7a4c9924b2e1001924be47b94013c',
//         'amount' => '92.00',  
//         'currency' => 'GBP',
//         'paymentType' => 'DB'
//     ]);

//     $ch = curl_init();
//     curl_setopt_array($ch, [
//         CURLOPT_URL => $url,
//         CURLOPT_HTTPHEADER => [
//             'Authorization:Bearer OGFjN2E0Yzk5MjRiMmUxMDAxOTI0YmU0N2E1ZTAxM2F8UVV4RXdpN1RGQTJEV3pUUHhKS0A=' // Replace with your token
//         ],
//         CURLOPT_POST => 1,
//         CURLOPT_POSTFIELDS => $data,
//         CURLOPT_SSL_VERIFYPEER => false,
//         CURLOPT_RETURNTRANSFER => true
//     ]);

//     $responseData = curl_exec($ch);
//     $error = curl_error($ch);
//     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//     curl_close($ch);

//     // Check if there was an error or if the HTTP status code is not 200 (OK)
//     if ($error || $httpCode !== 200) {
//         return ['success' => false, 'message' => 'cURL Error: ' . $error . ' HTTP Code: ' . $httpCode];
//     }

//     return json_decode($responseData, true);
// }

add_action('wp_ajax_handle_checkout', 'handle_checkout');
add_action('wp_ajax_nopriv_handle_checkout', 'handle_checkout');

function handle_checkout() {
    // Get and sanitize POST data

    
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone_number = sanitize_text_field($_POST['phone_number']);
    $country = sanitize_text_field($_POST['country']);
    $nationality = sanitize_text_field($_POST['nationality']);
    $card_number = sanitize_text_field($_POST['card_number']);
    $expiry_month = sanitize_text_field($_POST['expiry_month']);
    $expiry_year = sanitize_text_field($_POST['expiry_year']);
    $cvv = sanitize_text_field($_POST['cvv']);

    $amount = sanitize_text_field($_POST['amount']);

    // Concatenate full cardholder name
    $holder = "$first_name $last_name";

    // Define the shopper result URL (where the shopper is redirected after payment)
    $shopperResultUrl = "https://yourwebsite.com/payment-result.php"; // Change this to your actual URL

    // Pre-authorize payment
    $preAuthResponse = preAuthorizePayment($holder, $card_number, $expiry_month, $expiry_year, $cvv, $amount, $shopperResultUrl);

    // Check response and capture if successful
    if ($preAuthResponse && $preAuthResponse['result']['code'] === "000.200.000") {
        $paymentId = $preAuthResponse['id'];
        $captureResponse = capturePayment($paymentId, $amount);

        if ($captureResponse && $captureResponse['result']['code'] === "000.200.000") {
            wp_send_json_success(['message' => 'Payment successful!']);
        } else {
            $error_message = $captureResponse['result']['description'] ?? 'Capture failed with no description';
            wp_send_json_error(['message' => $error_message]);
        }
    } else {
        $error_message = $preAuthResponse['result']['description'] ?? 'Pre-authorization failed with no description';
        wp_send_json_error(['message' => $error_message]);
    }

    wp_die(); // Necessary to terminate AJAX request
}

function preAuthorizePayment($holder, $number, $expiryMonth, $expiryYear, $cvv, $amount, $shopperResultUrl) {
    $url = "https://eu-test.oppwa.com/v1/payments";
    $data = "entityId=8ac7a4c9924b2e1001924be47b94013c" .
            "&amount=$amount" .
            "&currency=GBP" .
            "&paymentBrand=VISA" .
            "&paymentType=DB" .
            "&card.number=$number" .
            "&card.holder=$holder" .
            "&card.expiryMonth=$expiryMonth" .
            "&card.expiryYear=$expiryYear" .
            "&card.cvv=$cvv" .
            "&shopperResultUrl=$shopperResultUrl";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization:Bearer OGFjN2E0Yzk5MjRiMmUxMDAxOTI0YmU0N2E1ZTAxM2F8UVV4RXdpN1RGQTJEV3pUUHhKS0A='
    ));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    $responseError = curl_error($ch);
    curl_close($ch);

    if ($responseError) {
        error_log("cURL Error: " . $responseError); // Log cURL error if any
    }

    error_log("Pre-Authorization Response: " . $responseData);

    return json_decode($responseData, true);
}

function capturePayment($paymentId, $amount) {
    $url = "https://eu-test.oppwa.com/v1/payments/$paymentId";
    $data = "entityId=8ac7a4c9924b2e1001924be47b94013c" .
            "&amount=$amount" .
            "&paymentType=DB" .
            "&currency=GBP";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization:Bearer OGFjN2E0Yzk5MjRiMmUxMDAxOTI0YmU0N2E1ZTAxM2F8UVV4RXdpN1RGQTJEV3pUUHhKS0A='
    ));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    curl_close($ch);

    return json_decode($responseData, true);
}
