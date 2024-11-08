<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once dirname( __FILE__ ) . '/includes/constants.php';

class WC_TotalProcessing_OpenBanking_Gateway extends WC_Payment_Gateway {
    use TotalProcessingGatewayDebugTrait;

    public function __construct() {
        $this->id                                = TP_OPENBANKING_GATEWAY_ID;
        $this->icon                              = '';
        $this->has_fields                        = true;
        $this->method_title                      = TP_OPENBANKING_GATEWAY_TITLE;
        $this->method_description                = $this->get_option( 'description', TP_OPENBANKING_GATEWAY_DESCRIPTION );
        $this->init_form_fields();
        $this->init_settings();
        $this->title                             = $this->get_option( 'title' );
        $this->description                       = $this->get_option( 'description' );
        $this->enabled                           = $this->get_option( 'enabled' );
        $this->jsLogging                         = ($this->get_option( 'jsLogging' ) === 'yes' ? true : false);
        $this->serversidedebug                   = ($this->get_option( 'serversidedebug' ) === 'yes' ? true : false);
        $this->logLevels                         = (array)$this->get_option( 'logLevels' );
        $this->gatewayLiveOrTestStatus           = $this->get_option( 'gatewayLiveOrTestStatus' );
        $this->entityId_test                     = $this->get_option( 'entityId_test' );
        $this->accessToken_test                  = $this->get_option( 'accessToken_test' );
        $this->entityId                          = $this->get_option( 'entityId' );
        $this->accessToken                       = $this->get_option( 'accessToken' );
        $this->paymentType                       = $this->get_option( 'paymentType' );
        $this->includeCartData                   = ($this->get_option( 'includeCartData' ) === 'yes' ? true : false);
        $this->iframeForceSsl                    = false;
        $this->proxyRequests                     = false;
        $this->proxyUrl                          = TP_OPENBANKING_GATEWAY_PROXY_URL;
        $this->secureCodeLogos                   = true;
        $this->useModalPayFrames                 = true;
        $this->debug_contact_email               = TP_OPENBANKING_DEBUG_CONTACT_EMAIL;
        // Initialise settings
        //Load funcs
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    }
    
    /*
     * Function to preparing of setting fields for different tabs
     */
    public function settings_fields( $merge = false ) {
        $settings                  = include dirname( __FILE__ ) . '/includes/setting-fields.php';
        
        if( $merge === true ){
            $retArr = [];
            foreach( $settings as $subSettings ){
                if( count( $subSettings ) > 0 ){
                    if( isset( $subSettings['fields'] ) ){
                        if( count( $subSettings['fields'] ) > 0 ){
                            $retArr = array_merge( $retArr, $subSettings['fields'] );
                        }
                    }
                }
            }
            return $retArr;
        }
        return $settings;
    }

    /*
     * Function to initialization of setting fields for different tabs
     */
    public function init_form_fields(){
        $fields            = $this->settings_fields(true);
        $this->form_fields = $fields;
    }
    
    /*
     * Function to initialization of admin option fields
     */
    public function admin_options() {
        require_once dirname( __FILE__ ) . '/includes/admin/admin-options.php';
    }
    
    public function check_plugin(){
        //check for checkout page only.
        if($this->enabled === 'yes'){
            return true;
        }
        return false;
    }
    
    public function run(){
        //run iFrame generate
        //add_action( 'woocommerce_checkout_init', [ $this, 'validateOnCheckoutInit' ] );
        add_action( 'admin_notices', array( $this, 'tp_display_global_errors' ) );
        add_action( 'wp_ajax_' . $this->id . '_forward_debugdata_to_tp_support', array( $this, 'ajax_forward_debugdata_to_tp_support' ) );
        add_action( 'wp_ajax_' . $this->id . '_download_debugdata_file', array( $this, 'ajax_download_debugdata_file' ) );

        //scheduler for payment status check
        add_filter( 'cron_schedules', [$this, 'tpob_custom_schedule'], 10, 1 );
        add_action( 'init', [$this, 'tpob_scheduled_event'] );
        add_action( 'tpob_check_next_scheduled', [$this, 'tpob_checkorder_transaction_and_updatestatus'] );

        //check plugin
        if($this->check_plugin()){
            add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ), PHP_INT_MAX );
            add_action( 'wp_ajax_' . TP_OPENBANKING_GATEWAY_ID . '_check_transaction_status', array( $this, 'checkTransactionStatus' ) );
            add_action( 'wp_ajax_nopriv_' . TP_OPENBANKING_GATEWAY_ID . '_check_transaction_status', array( $this, 'checkTransactionStatus' ) );
            add_action( 'init', array( $this, 'checkForVerifyTx' ) );
        }
    }
    
    public function checkForVerifyTx(){
        if( isset( $_REQUEST[TP_OPENBANKING_PREFIX . 'openbankingfinlookup'] ) && isset( $_GET['resourcePath'] ) ){

            if ( is_ajax() ) {
                $this->writeLog( '----- checkForVerifyTx [Avoided by ajax call] -----', null, 'debug' );
                return;
            }
            $order_id    = $_GET['order_id'] ?? 0;
            
            $id                       = (string)$_REQUEST['id'];
            $resourcePath             = (string)$_REQUEST['resourcePath'];
            
            $responseObject           = $this->verifyTransactionStatus( $resourcePath, $order_id );
            $transaction_id           = (int)( isset( $responseObject->merchantTransactionId ) ? $responseObject->merchantTransactionId : 0 );
            $payment_id               = isset( $responseObject->id ) ? $responseObject->id : "";
            $payment_code             = isset( $responseObject->result->code ) ? $responseObject->result->code : "";
            $responseResult           = $this->parseResponseData( $responseObject );
            if( !empty( $payment_code ) ){
                if( trim( $payment_code ) === '000.000.000' || trim( $payment_code ) === '000.100.110'){
                    $paymentSuccess = true;
                }
            }
            if( $responseResult['result'] == 'success' && !empty( $responseResult['redirect'] ) ){
                wp_redirect( $responseResult['redirect'] ); exit;
            }else{
                $this->writeLog( '----- checkForVerifyTx [Redirec checkout page due to non-success] -----', $this->arrStaticData( $payment_code ), 'debug' );
                wp_redirect( wc_get_checkout_url() ); exit;
            }
        }
    }
    
    public function checkPageShouldRun(){
        global $wp;
        if(is_checkout() === true && empty( $wp->query_vars['order-received'] )){
            return true;
        }
        if(is_wc_endpoint_url( 'order-pay' )){
            return true;
        }
        return false;
    }

    public function payment_scripts() {
        if($this->checkPageShouldRun() === true){
            $tp_pluginVer       = TP_OPENBANKING_VERSION;
            $prefix             = TP_OPENBANKING_PREFIX;
            wp_register_script( $prefix . 'tp_openbanking', plugin_dir_url( __FILE__ ).'assets/js/open-banking-widget-initiator.js', ['jquery','wp-util'], $tp_pluginVer, array("in_footer" => true) );

            wp_register_script( $prefix . 'tp_fetch', 'https://cdn.jsdelivr.net/npm/whatwg-fetch@3.4.0/dist/fetch.umd.min.js' , ['jquery','wp-util'] , true, array("in_footer" => true) );
            wp_register_script( $prefix . 'tp_swal', 'https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js' , ['jquery','wp-util'] , true, array("in_footer" => true) );
            wp_register_script( $prefix . 'tp_swal_poly1', 'https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.12.1/polyfill.min.js' , [] , true, array("in_footer" => true) );
            wp_register_script( $prefix . 'tp_swal_poly2', 'https://cdn.jsdelivr.net/npm/promise-polyfill@8.1.3/dist/polyfill.js' , [] , true, array("in_footer" => true) );

            wp_localize_script( $prefix . 'tp_openbanking', $prefix . 'GlobalVars', [
                "subTotalAmount"      => (string)WC()->cart->get_total(null),
                "checkoutUrl"         => urlencode(wc_get_checkout_url()),
                "pluginPrefix"        => $prefix,
                "pluginId"            => TP_OPENBANKING_GATEWAY_ID,
                "jsLogging"           => $this->jsLogging,
                "pluginVer" => $tp_pluginVer,
                "adminUrl" => get_admin_url().'admin-ajax.php',
                "assetsDir" => plugin_dir_url( dirname( __FILE__ ) ).'assets',
                "frameUrlEncoded" => urlencode( get_the_guid( (int)get_option( TP_OPENBANKING_PREFIX . 'gateway_cardsv2_iframe' ) ) ),
                "loggedIn" => is_user_logged_in(),
                "tpModal" => $this->useModalPayFrames,
                "feNonce" => wp_create_nonce('tp_feLogger'),
            ]);
            
            wp_enqueue_style( $prefix . 'tp_openbanking_style', plugin_dir_url( __FILE__ ).'assets/css/tp-openbank-frame.css?ver=' . $tp_pluginVer , [] , null );
            
            wp_enqueue_script($prefix . 'tp_fetch');
            wp_enqueue_script($prefix . 'tp_swal_poly2');
            wp_enqueue_script($prefix . 'tp_openbanking');

            $inlinescript = "function gettpopenbankGlobalVariable(){
                ". ( $this->jsLogging === true ? "console.log(" . $prefix . "GlobalVars);" : "" ) . "
                return " . $prefix . "GlobalVars;
            }";
            wp_add_inline_script( $prefix . 'tp_openbanking', $inlinescript, 'before' );
            echo '<style>label[for="payment_method_'.TP_OPENBANKING_GATEWAY_ID.'"] { display:inline-flex!important; align-items: center; } label[for="payment_method_'.TP_OPENBANKING_GATEWAY_ID.'"]:after { content:""; display: block; width:100px; margin-left: 5px; height: 22px; background: url('. plugin_dir_url(__FILE__) .'/assets/img/tp-direct-smaller.svg) no-repeat; background-size: contain;position: relative; top: 0;}</style>';
            
            if($this->useModalPayFrames === true){
                wp_enqueue_script($prefix . 'tp_swal_poly1');
                wp_enqueue_script($prefix . 'tp_swal');
            }
        }
    }

    function checkTransactionStatus(){
        global $wpdb;
        $paymentSuccess           = false;
        $url                      = "";
        $resourcePath             = wc_get_post_data_by_key( 'resourcePath', false );
        $order_id                 = wc_get_post_data_by_key( 'order_id', false );
        $resourcePath             = sanitize_text_field( $resourcePath );
        $responseObject           = $this->verifyTransactionStatus( $resourcePath, $order_id );
        $transaction_id           = (int)( isset( $responseObject->merchantTransactionId ) ? $responseObject->merchantTransactionId : 0 );
        $payment_id               = isset( $responseObject->id ) ? $responseObject->id : "";
        $payment_code             = isset( $responseObject->result->code ) ? $responseObject->result->code : "";
        $responseResult           = $this->parseResponseData( $responseObject );
        if( !empty( $payment_code ) ){
            if( trim( $payment_code ) === '000.000.000' || trim( $payment_code ) === '000.100.110'){
                $paymentSuccess = true;
            }
        }
        wp_send_json_success( [ 
            'valid'                  => $paymentSuccess,
            'resourcePath'           => $resourcePath,
            'responseObject'         => $responseObject,
            'responseResult'         => $responseResult
        ]);
    }
    
    public function checkResponsePayload($responseObject){
        $this->writeLog( '----- checkResponsePayload [Response payload] -----', $responseObject, 'debug' );
        if(is_object($responseObject)){
            if(isset($responseObject->result->code)){
                if($this->gatewayLiveOrTestStatus === 'live'){
                    $successCode = '000.000.000';
                } else {
                    $successCode = '000.100.110';
                }
                if((string)$responseObject->result->code === $successCode){
                    return true;
                } else {
                    return (string)$responseObject->result->description;
                }
            }
        }
        return false;
    }
    
    public function verifyTransactionStatus( $resourcePath, $order_id ){
        if( empty( $resourcePath ) ){
            wc_add_notice( __('Uncertain Response. Please report this to the merchant before reattempting payment. They will need to verify if this transaction is successful. Ref:' . $resourcePath), 'error');
            $this->writeLog( '----- verifyTransactionStatus [Orderid: '.$order_id.', resourcePath: empty] -----', null, 'emergency' );
            return false;
        }
        if( empty( $order_id ) ){
            wc_add_notice( __('Uncertain Response. Please report this to the merchant before reattempting payment. They will need to verify if this transaction is successful. Ref:' . $resourcePath), 'error');
            $this->writeLog( '----- verifyTransactionStatus [Orderid: empty, resourcePath: '.$resourcePath.'] -----', null, 'emergency' );
            return false;
        }
        $executionTime           = [];
        $url                     = $this->derivegatewayLiveOrTestBaseURL() . $resourcePath;

        if( strpos( $url, 'entityId' ) === false ){
            $url                .= "?entityId=".$this->getEntityID();
        }

        $headers                 = ['Authorization' => 'Bearer '.$this->getAccessToken()];
        $arg                     = [
            'method'         => 'GET',
            'timeout'        => 15,
            'redirection'    => 5,
            'httpversion'    => '1.0',
            'blocking'       => true,
            'headers'        => $headers
        ];
        $executionTime['verificationInit'] = time();
        
        $response                = wp_remote_request( $url, $arg );
        $executionTime['apiResponseAt'] = time();
        $secondaryCall           = false;
        
        if( is_wp_error( $response ) ) {
            $errorMessage        = $response->get_error_message();
            wc_add_notice( __('Uncertain Response. Please report this to the merchant before reattempting payment. They will need to verify if this transaction is successful. Ref:' . $resourcePath), 'error');
            $this->writeLog( '----- verifyTransactionStatus [verification Query error] -----', ['QueryError' => $errorMessage, 'resource' => $resourcePath, 'response' => $response], 'emergency' );
            return false;
        }elseif( !isset( $response['body'] ) ) {
            wc_add_notice( __('Uncertain Response. Please report this to the merchant before reattempting payment. They will need to verify if this transaction is successful. Ref:' . $resourcePath), 'error');
            $this->writeLog( '----- verifyTransactionStatus [verification Query error] -----', ['QueryError' => $errorMessage, 'resource' => $resourcePath, 'response' => $response], 'emergency' );
            return false;
        }
        $this->writeLog( '----- verifyTransactionStatus [Response] -----', $response, 'debug' );
        $responseResult   = $response['body'];
        $responseData     = json_decode($responseResult);
        $responseData     = ( $secondaryCall === true ) ? ( isset($responseData->payments[0]) ? $responseData->payments[ ( count( $responseData->payments ) - 1 ) ] : new stdClass) : $responseData;
        return $responseData;
    }

    public function parseResponseData($responseData){
        global $woocommerce;
        $debugTimeArray[0] = time();
        $array = ['notices'=>[]];
        $paymentSuccess = 'failed';
        $this->writeLog( '----- parseResponseData [ResponseData] -----', $responseData, 'debug' );
        if(isset($responseData->id)){
            $transaction_id = (string)$responseData->id;
            if(isset($responseData->result->code)){
                $code = (string)$responseData->result->code;
                if($code === '000.000.000' || $code === '000.100.110'){
                    $paymentSuccess = 'success';
                }
                if($code === '000.200.001'){
                    $paymentSuccess = 'hold';
                }
            }
            if(isset($responseData->result->description)){
                $description = str_replace("'", "" , $responseData->result->description);
            }
            if(isset($responseData->merchantTransactionId)){
                $checks = true;
                $order_id = (int)$responseData->merchantTransactionId;
                $payment_result_code = $responseData->result->code ?? '';
                $debugTimeArray[1] = time();
                $order = wc_get_order( $order_id );

                $amount = number_format($order->get_total(), 2, '.', '');
                $order_data = $order->get_data();
                if($checks){
                    if($paymentSuccess === 'success'){
                        //good to go..
                        $order->payment_complete( $transaction_id );
                        $debugTimeArray[2] = time();
                        //if PA// capture
                        wc_reduce_stock_levels($order_id);
                        $debugTimeArray[3] = time();
                        $order->add_order_note( 'Your order is paid! Thank you!', true );
                        $order->add_order_note( $code, false );
                        $order->add_order_note( $description, false );
                        $order->add_meta_data('gatewayLiveOrTestStatus', $this->gatewayLiveOrTestStatus);
                        $order->add_meta_data('paymentType', (string)$responseData->paymentType);
                        $debugTimeArray[4] = time();
                        $order->save();
                        $debugTimeArray[6] = time();
                        $array['result'] = 'success';
                        $array['redirect'] = $this->get_return_url( $order );
                        $debugTimeArray[7] = time();
                        $cartHash          = WC()->cart->get_cart_hash();
                        $woocommerce->cart->empty_cart();
                        $debugTimeArray[8] = time();
                    } elseif( $paymentSuccess === 'hold' ) {
                        $order->add_order_note( $code, false );
                        $order->add_order_note( $description, false );
                        $order->set_status('on-hold');
                        $order->set_transaction_id( $transaction_id );
                        $order->update_meta_data( '_tpob_transaction_counter', 0 );
                        $order->save();
                        //hold order reason..
                        $array['notices'][] = "Your order is being processed. We're finalizing your payment with the bank.<br />You'll get a confirmation email once approved, so no need to resubmit your payment.";
                        $array['error'] = $description;
                    } else {
                        $order->add_order_note( $code, false );
                        $order->add_order_note( $description, false );
                        $order->set_status('failed');
                        $order->save();
                        //decline reason..
                        $array['notices'][] = 'Payment not completed: '.$description;
                        $array['error'] = $description;
                    }
                } else {
                    if( $paymentSuccess === 'success' ){
                        $array['notices'][] = 'Payment is successful, but due to some error we can not process further. Please contact support with payment reference ' . $responseData->id;
                    }else{
                        $array['notices'][] = 'Payment failed';
                    }
                    $array['notices'][] = 'checks failed.';
                }
            } else {
                $this->writeLog( '----- parseResponseData [merchantTransactionId missing] -----', $responseData, 'emergency' );
                $array['notices'][] = 'No order_id found, please retry payment.';
            }
        } else {
            $this->writeLog( '----- parseResponseData [ResponseData ID missing] -----', $responseData, 'emergency' );
            $array['notices'][] = 'No transaction id found, please retry payment.';
        }
        wc_clear_notices();
        foreach($array['notices'] as $notice){
            wc_add_notice( __($notice), 'error');
        }
        return $array;
    }
    
    protected function setCustomerAddressField( $field, $key, $data ) {
        $billing_value  = null;
        $shipping_value = null;
        if ( isset( $data[ "billing_{$field}" ] ) && is_callable( array( WC()->customer, "set_billing_{$field}" ) ) ) {
            $billing_value  = $data[ "billing_{$field}" ];
        }
        if ( isset( $data[ "shipping_{$field}" ] ) && is_callable( array( WC()->customer, "set_shipping_{$field}" ) ) ) {
            $shipping_value = $data[ "shipping_{$field}" ];
        }
        if ( ! is_null( $billing_value ) && is_callable( array( WC()->customer, "set_billing_{$field}" ) ) ) {
            WC()->customer->{"set_billing_{$field}"}( $billing_value );
        }
        if ( ! is_null( $shipping_value ) && is_callable( array( WC()->customer, "set_shipping_{$field}" ) ) ) {
            WC()->customer->{"set_shipping_{$field}"}( $shipping_value );
        }
    }
    
    protected function storeSessAddressVars($data){
        $address_fields=$this->arrStaticData('address_fields');
        //process new data
        array_walk( $address_fields, array( $this, 'setCustomerAddressField' ), $data );
        //save customer data
        WC()->customer->save();
    }
    
    protected function clearSessAddressVars(){
        //clear sess data
        $address_fields=$this->arrStaticData('address_fields');
        foreach($address_fields as $k => $field){
            if ( is_callable( array( WC()->customer, "set_billing_{$field}" ) ) ) {
                WC()->customer->{"set_billing_{$field}"}("");
            }
            if ( is_callable( array( WC()->customer, "set_shipping_{$field}" ) ) ) {
                WC()->customer->{"set_shipping_{$field}"}("");
            }
        }
        WC()->customer->save();
    }
    
    public function payment_fields(){
        ?>
        <p><?php echo $this->method_description;?></p>
        <?php
        
    }
    
    public function orderStatusHandler($status,$order){
        $array=[
            'pending'    => ['result'=>'success', 'redirect' => false, 'refresh' => false, 'reload' => false, 'pending'=>true, 'process' => ["order"=>true]],
            'cancelled'  => ['result'=>'failure', 'redirect' => false, 'refresh' => false, 'reload' => false, 'messages' => ['error' => ['This order has been cancelled. Please retry your order.']]],
            'failed'     => ['result'=>'failure', 'redirect' => false, 'refresh' => false, 'reload' => true, 'messages' => ['error' => ['There was a problem creating your order, please try again.']]],
        ];
        if(array_key_exists($status, $array)){
            return $array[$status];
        }
        return $array['failed'];
    }
    
    public function process_payment($order_id){
        //check the order_id exists.
        $order = wc_get_order($order_id);
        $isFromOrderPayPage  = false;
        if ( isset( $_POST['woocommerce_pay'], $_GET['key'] ) ) {
            $isFromOrderPayPage  = true;
        }
        if($order===false){
            wc_add_notice('There was a problem creating your order, please try again.', 'error');
            return;
        }
        $order_data = $order->get_data();
        $this->writeLog( '----- process_payment [order data] -----', $order_data, 'debug' );
        if( $order_data['status'] == 'failed' ){
            $order->set_status( 'pending' );
            $order->add_order_note( 'Payment retry attempt', false );
            $order->save();
            $order_data['status'] = 'pending';
        }
        $handler = $this->orderStatusHandler( $order_data['status'], $order );
        //reject the failed, cancelled on-hold & success
        if(!isset($handler['pending'])){
            if(isset($handler['messages'])){
                foreach($handler['messages'] as $noticeType => $noticeItems){
                    foreach($noticeItems as $notice){
                        wc_add_notice($notice, $noticeType);
                    }
                }
            }
            return $handler;
        }
        //pending orders!
        $paymentUrl       = plugin_dir_url( __FILE__ ).'callback.php';
        $returnURLArgsArr = array( TP_OPENBANKING_PREFIX . 'openbankingfinlookup' => '1', 'order_id' => $order_id );
        if( TP_OPENBANKING_PROCESS_SAME_WINDOW === true ){
            $paymentUrl   = wc_get_checkout_url();
        }
        $shopperResultUrl = add_query_arg( $returnURLArgsArr, $paymentUrl );
        $additionalParams['shopperResultUrl'] = $shopperResultUrl;

        $handler['fullPost'] = $_POST;
        $checkoutCode = '';
        $checkoutJson = '{}';
        if($checkoutId !== false){
            $checkoutCode = '000.200.100';
            $handler['uuid'] = $checkoutId;
            $handler['pay_url'] = $order->get_checkout_payment_url( false );
        } else {
            $this->writeLog( '----- process_payment [Checkout ID missing] -----', $handler, 'debug' );
            wc_add_notice('Checkout ID is missing', 'error');
            $handler['refresh'] = true;
            return $handler;
        }
        
        //enforce non-checking checkoutId status
        $payload       = $this->prepareOrderDataForPayload( $order_data, $additionalParams );
        $this->writeLog( '----- process_payment [Payload] -----', $payload, 'debug' );
        $frameURL      = $this->getOrderPaymentURL( $payload );
        if( !$frameURL ){
            $this->writeLog( '----- process_payment [Could not generate payment url] -----', $handler, 'debug' );
            wc_add_notice('Could not generate payment URL', 'error');
            $handler['messages'] = 'Could not generate payment URL';
            $handler['refresh']  = true;
        }else{
            $handler['messages'] = 'Payment URL generated successfully';
            $handler['frameurl'] = $frameURL;
            $handler['redirect'] = (TP_OPENBANKING_PROCESS_SAME_WINDOW === true) ? $frameURL : false;
        }
        
        $this->writeLog( '----- process_payment [Return data] -----', $handler, 'debug' );
        if( $isFromOrderPayPage === true ){
            wp_send_json( $handler );exit;
        }
        return $handler;
    }

    public function getEntityID(){
        if( $this->gatewayLiveOrTestStatus == 'live' ){
            return $this->entityId;
        }
        
        if( $this->gatewayLiveOrTestStatus == 'test' ){
            return $this->entityId_test;
        }
        return '';
    }

    public function getAccessToken(){
        if( $this->gatewayLiveOrTestStatus == 'live' ){
            return $this->accessToken;
        }
        if( $this->gatewayLiveOrTestStatus == 'test' ){
            return $this->accessToken_test;
        }
        return '';
    }

    public function derivegatewayLiveOrTestBaseURL(){
        if( $this->gatewayLiveOrTestStatus == 'live' ){
            return 'https://eu-prod.oppwa.com';
        }
        if( $this->gatewayLiveOrTestStatus == 'test' ){
            return 'https://eu-test.oppwa.com';
        }
        return '';
    }

    public function derivegatewayLiveOrTestPaymentEndpoint(){
        if( $this->gatewayLiveOrTestStatus == 'live' ){
            return 'https://eu-prod.oppwa.com/v1/payments';
        }
        if( $this->gatewayLiveOrTestStatus == 'test' ){
            return 'https://eu-test.oppwa.com/v1/payments';
        }
        return '';
    }
    
    /*
     * This sends payload to generate checkoutid for checkout form payment gateway or create registration to create saved cards
     */
    public function getOrderPaymentURL( $payload ){
        if($forceAmount !== false){
            $amount = $forceAmount;
        } else {
            $amount = WC()->cart->get_total(null);
        }
        if((float)$amount === 0){ 
            return false;
        }
        $payload['paymentBrand']                = 'ACI_INSTANTPAY';
        $payload['bankAccount.country']         = 'GB';
        $payload['entityId']                    = $this->getEntityID();
        if( $this->gatewayLiveOrTestStatus == 'test' ){
            $payload['testMode']                = 'INTERNAL';
        }

        $remoteUrl                              = $this->derivegatewayLiveOrTestPaymentEndpoint();
        
        $array = [
            'method'            => 'POST',
            'timeout'           => 30,
            'redirection'       => 5,
            'httpversion'       => '1.0',
            'blocking'          => true,
            'headers'           => [
                'Content-Type'  => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Authorization' => 'Bearer '.$this->getAccessToken()
            ],
            'body' => $payload
        ];
        if($this->proxyRequests === true){
            $array['headers']['Fwdurl'] = $remoteUrl;
            $remoteUrl                  = $this->proxyUrl;
        }
        $response = wp_remote_request( $remoteUrl, $array);

        if( is_wp_error( $response ) ){
            $errorMessage               = $response->get_error_message();
            $this->writeLog( '----- getOrderPaymentURL [Response error: '.$errorMessage.'] -----', $response, 'emergency' );
            return false;
        }
        $this->writeLog( '----- getOrderPaymentURL [Response] -----', $response, 'debug' );
        $responseData                   = json_decode($response['body'], true);
        if( !isset( $responseData['redirect']['url'] ) ){
            $errorMessage               = 'Payment can not be processed due to some error!';
            return false;
        }
        
        return $responseData['redirect']['url'];
    }
    
    public function prepareOrderDataForPayload( $order_data, $additionalParams = [] ){
        global $wp_version;
        $payload = [
            "paymentType"                                      => 'DB',
            "amount"                                           => number_format($order_data['total'], 2, '.', ''),
            "currency"                                         => $order_data['currency'],
            "merchantTransactionId"                            => $order_data['id'],
            "customer.merchantCustomerId"                      => $order_data['customer_id'],
            "customParameters[SHOPPER_amount]"                 => number_format($order_data['total'], 2, '.', ''),
            "customParameters[SHOPPER_currency]"               => $order_data['currency'],
            "customParameters[SHOPPER_order_key]"              => $order_data['order_key'],
            "customParameters[SHOPPER_cart_hash]"              => $order_data['cart_hash'],
            "customParameters[SHOPPER_platform]"               => "WooCommerce",
            "customParameters[SHOPPER_version_data]"           => "WPVER_" . $wp_version . "|" . "WCVER_" . WC_VERSION . "|" . "TPMOD_".$this->id,
            "customParameters[Payment_flow]"                   => "OpenBanking",
            "customParameters[SHOPPER_plugin]"                 => TP_OPENBANKING_VERSION,
            "customer.givenName"                               => $order_data['billing']['first_name'],
            "customer.surname"                                 => $order_data['billing']['last_name'],
            "customer.email"                                   => $order_data['billing']['email'],
            "customer.ip"                                      => $order_data['customer_ip_address'],
            "customer.browserFingerprint.value"                => $order_data['customer_user_agent']
        ];
        if(isset($order_data['billing']['phone'])){
            if(!empty($order_data['billing']['phone'])){
                $payload["customer.mobile"] = $order_data['billing']['phone'];
            }
        }
        if(isset($order_data['billing']['address_1'])){
            if(!empty($order_data['billing']['address_1'])){
                $payload["billing.street1"] = $order_data['billing']['address_1'];
            }
        }
        if(isset($order_data['billing']['address_2'])){
            if(!empty($order_data['billing']['address_2'])){
                $payload["billing.street2"] = $order_data['billing']['address_2'];
            }
        }
        if(isset($order_data['billing']['city'])){
            if(!empty($order_data['billing']['city'])){
                $payload["billing.city"] = $order_data['billing']['city'];
            }
        }
        if(isset($order_data['billing']['postcode'])){
            if(!empty($order_data['billing']['postcode'])){
                $payload["billing.postcode"] = $order_data['billing']['postcode'];
            }
        }
        if(isset($order_data['billing']['country'])){
            if(!empty($order_data['billing']['country'])){
                $payload["billing.country"] = $order_data['billing']['country'];
            }
        }
        if(isset($order_data['shipping']['address_1'])){
            if(!empty($order_data['shipping']['address_1'])){
                $payload["shipping.street1"] = $order_data['shipping']['address_1'];
            }
        }
        if(isset($order_data['shipping']['address_2'])){
            if(!empty($order_data['shipping']['address_2'])){
                $payload["shipping.street2"] = $order_data['shipping']['address_2'];
            }
        }
        if(isset($order_data['shipping']['city'])){
            if(!empty($order_data['shipping']['city'])){
                $payload["shipping.city"] = $order_data['shipping']['city'];
            }
        }
        if(isset($order_data['shipping']['state'])){
            if(!empty($order_data['shipping']['state'])){
                $payload["shipping.state"] = $order_data['shipping']['state'];
            }
        }
        if(isset($order_data['shipping']['postcode'])){
            if(!empty($order_data['shipping']['postcode'])){
                $payload["shipping.postcode"] = $order_data['shipping']['postcode'];
            }
        }
        if(isset($order_data['shipping']['country'])){
            if(!empty($order_data['shipping']['country'])){
                $payload["shipping.country"] = $order_data['shipping']['country'];
            }
        }
        return array_merge($payload, $this->getCartItemsOrderData($order_data['id']), $additionalParams);
    }
    
    public function getCartItemsOrderData($order_id){
        $payload = [];
        if($this->includeCartData !== true){
            return $payload;
        }
        $cartItems = [];
        $order_id = (int)$order_id;
        $oObj = wc_get_order($order_id);
        foreach($oObj->get_items(['line_item']) as $oItemId => $oItem){
            $oProd = $oItem->get_product();
            $cartItem = [
                'name' => substr($oItem->get_name(),0,255),
                'merchantItemId' => $oItem->get_product_id(),
                'quantity' => (int)$oItem->get_quantity(),
                'type' => ($oProd->is_virtual() === true ? 'DIGITAL' : 'PHYSICAL'),
                'sku' => ($oProd->get_sku() === '' ? $oItem->get_product_id() : $oProd->get_sku()),
                'currency' => $oObj->get_currency(),
                'description' => substr(wp_strip_all_tags($oProd->get_description()),0,255),
                'price' => number_format((float)$oObj->get_item_subtotal($oItem), 2, '.', ''),
                'totalAmount' => number_format(((float)$oItem->get_total() + (float)$oObj->get_line_tax($oItem)), 2, '.', ''),
                'taxAmount' => number_format( (float)$oObj->get_item_tax($oItem), 2, '.', '' ),
                'totalTaxAmount' => number_format( (float)$oObj->get_line_tax($oItem), 2, '.', '' )
            ];
            $cartItems[] = $cartItem;
        }
        foreach( $oObj->get_items('shipping') as $oItemId => $shipping_item_obj ){
            $cartItem = [
                'name' => 'Shipping',
                'merchantItemId' => $shipping_item_obj->get_method_id() .':'. $shipping_item_obj->get_instance_id(),
                'quantity' => 1,
                'type' => 'MIXED',
                'currency' => $oObj->get_currency(),
                'description' => substr($shipping_item_obj->get_method_title(),0,255),
                'price' => number_format( (float)$shipping_item_obj->get_total(), 2, '.', '' ),
                'shipping' => number_format( (float)$shipping_item_obj->get_total(), 2, '.', '' ),
                'shippingMethod' => substr($shipping_item_obj->get_name(),0,255),
                'totalAmount' => number_format( (float)$shipping_item_obj->get_total() + (float)$shipping_item_obj->get_total_tax(), 2, '.', '' ),
                'taxAmount' => number_format( (float)$shipping_item_obj->get_total_tax(), 2, '.', '' ),
                'totalTaxAmount' => number_format( (float)$shipping_item_obj->get_total_tax(), 2, '.', '' )
            ];
            $cartItems[] = $cartItem;
        }
        $n = 0;
        foreach($cartItems as $lineItemArray){
            foreach($lineItemArray as $k => $v){
                if(!empty($v) || $v !== ''){
                    $payload['cart.items['.$n.'].'.$k] = $v;
                }
            }
            $n++;
        }
        return $payload;
    }

    public function tp_display_global_errors(){
    }

    function VerifyPaymentStatusForPaymentID( $payID ) {
        $url = $this->derivegatewayLiveOrTestPaymentEndpoint(). "/" . $payID; // Id provided in the original api call response.
        $url .= "?entityId=".$this->getEntityID();
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:Bearer '.$this->getAccessToken()));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return json_decode( $responseData, true );
    }
    
    public function ajax_forward_debugdata_to_tp_support() {
        $DebugFile           = $_REQUEST['filename'] ?? '';
        
        if( !empty( $DebugFile ) && file_exists( WC_LOG_DIR . $DebugFile ) ){
            wp_mail( $this->debug_contact_email, "Openbanking Debug file from " . get_bloginfo( 'name' ), "Please find attached debug file.", [], WC_LOG_DIR . $DebugFile );
            return wp_send_json_success( [ "valid" => true, "message" => "Debug data forwarded to TotalProcessing Support" ] );
        }

        wp_send_json_error( [ "valid" => false, "message" => "Something went wrong, Please try again." ] );
    }
    
    public function ajax_download_debugdata_file() {
        $DebugFile           = $_REQUEST['file'] ?? '';
        
        if( !empty( $DebugFile ) && file_exists( WC_LOG_DIR . $DebugFile ) ){
            header("Content-Disposition: attachment;filename=".$DebugFile); 
            header('Content-Type: application/octet-stream'); 
            header("Pragma: public"); 
            header("Expires: -1"); 
            header("Cache-Control: no-cache"); 
            header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0"); 
            header("Content-Length: ".filesize( WC_LOG_DIR . $DebugFile )); 
            @readfile( WC_LOG_DIR . $DebugFile );
            die();
        }

        wp_send_json_error( [ "valid" => false, "message" => "Something went wrong, Please try again." ] );
    }

    /**
     * Set event time.
     *
     * @param array $schedules all schedules list.
     * @return array $schedules return event schedules.
     */
    function tpob_custom_schedule( $schedules ) {
        $time                                 = 5*60; // minutes
        $schedules['tpob_set_crone_time'] = array(
            'interval' => $time,
            'display'  => __( 'Total Processing Payment status check Every 5 Minutes' ),
        );
        return $schedules;
    }

    /**
     * Schedule event.
     *
     * @return void
     */
    function tpob_scheduled_event() {
        // Schedule an action if it's not already scheduled.
        if ( ! wp_next_scheduled( 'tpob_check_next_scheduled' ) ) {
            wp_schedule_event( time(), 'tpob_set_crone_time', 'tpob_check_next_scheduled' );
        }
    }
    
    /**
     * Update price according to crone.
     *
     * @return void
     */
    function tpob_checkorder_transaction_and_updatestatus() {
        global $wpdb;
        $args = array(
            'status'        => 'on-hold',
            'meta_key'      => '_tpob_transaction_counter', // Postmeta key field
            'meta_value'    => 100, // Postmeta value field
            'meta_compare'  => '<=',
            'meta_type'     => 'UNSIGNED',
            //'return'        => 'ids' // Accepts a string: 'ids' or 'objects'. Default: 'objects'.
        );
        
        $orders = wc_get_orders( $args );
        
        if( $orders ){
            foreach( $orders AS $order ){
                $transaction_id        = $order->get_transaction_id();
                $tpob_checkcounter     = intval( $order->get_meta('_tpob_transaction_counter') );
                $tpob_checkcounter++;
                $order->update_meta_data( '_tpob_transaction_counter', $tpob_checkcounter );
                $order->save();
                if( empty( $transaction_id ) ){
                    $order->set_status( 'failed' );
                    $order->add_order_note( 'Transaction id missing', false );
                    $order->save();
                    continue;
                }
                $transactionResponse   = $this->VerifyPaymentStatusForPaymentID( $transaction_id );
                $responseCode          = $transactionResponse['result']['code'] ?? '';
                $responseDesc          = $transactionResponse['result']['description'] ?? '';
                
                if($responseCode === '000.000.000' || $responseCode === '000.100.110'){
                    $paymentSuccess = 'success';
                }elseif( $responseCode === '000.200.001' ){
                    $paymentSuccess = 'nextTry';
                }
                if($paymentSuccess === 'success'){
                    //good to go..
                    $order->payment_complete( $transaction_id );
                    wc_reduce_stock_levels( $order->get_id() );
                    $order->add_order_note( 'Your order is paid! Thank you!', true );
                    $order->add_order_note( $responseCode, false );
                    $order->add_order_note( $responseDesc, false );
                    $order->add_meta_data('gatewayLiveOrTestStatus', $this->gatewayLiveOrTestStatus);
                    $order->add_meta_data('paymentType', (string)$responseData->paymentType);
                    $order->save();
                }elseif( $paymentSuccess === 'nextTry' ){
                    $order->add_order_note( 'Attempted verification:' . $tpob_checkcounter, false );
                    $order->add_order_note( $responseCode, false );
                    $order->add_order_note( $responseDesc, false );
                    $order->save();
                }else{
                    $order->add_order_note( 'Failed transaction with attempt:' . $tpob_checkcounter, false );
                    $order->add_order_note( $responseCode, false );
                    $order->add_order_note( $responseDesc, false );
                    $order->set_status( 'failed' );
                    $order->save();
                }
                //$this->writeLog( '----- tpob_checkorder_transaction_and_updatestatus [Response] -----', [$responseCode, $transactionResponse], 'debug' );
            }
        }
    }
} //end class

(new WC_TotalProcessing_OpenBanking_Gateway())->run();
$tpGatewaysObj->add_woocommerce_payment_gateway( 'WC_TotalProcessing_OpenBanking_Gateway' );
