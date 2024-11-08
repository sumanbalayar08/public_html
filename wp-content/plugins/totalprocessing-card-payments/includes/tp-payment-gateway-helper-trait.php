<?php
use TotalProcessing\WC_TotalProcessing_Constants AS TP_CONSTANTS;
use Automattic\WooCommerce\Utilities\OrderUtil;

trait TotalProcessingGatewayHelperTrait{
    public function createPciFramePage(){
        $iFramePostVars = array(
            'post_title' => TP_CONSTANTS::GATEWAY_ID . '_iframe_page',
            'post_content' => '',
            'post_status' => 'publish',
            'post_parent' => 0,
            'menu_order' => 0,
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_category' => array(),
            'post_type' => 'page',
            'page_template' => 'pci-frame-templatev3.php'
        );
        $iFramePostId = wp_insert_post( $iFramePostVars , false );
        if($iFramePostId){
            update_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe', $iFramePostId );
            return (int)$iFramePostId;
        }
        return false;
    }
    
    public function checkValidJsonDecode( $json ){
        $return       = false;
        $obj          = json_decode( $json );
        switch ( json_last_error() ) {
            case JSON_ERROR_NONE:
                $return = $obj;
                break;
            case JSON_ERROR_DEPTH:
                $return = false;
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $return = false;
                break;
            case JSON_ERROR_CTRL_CHAR:
                $return = false;
                break;
            case JSON_ERROR_SYNTAX:
                $return = false;
                break;
            case JSON_ERROR_UTF8:
                $return = false;
                break;
            default:
                $return = false;
                break;
        }
        return $return;
    }
    
    public function checkLogLevelViaOppwaResultCode( $code ){
        //oppwaLogLevels
        $array      = $this->arrStaticData( 'oppwaLogLevels' );
        $code       = (string)$code;
        $retVal     = [$code,'debug','non_matched'];
        foreach($array as $level => $statuses){
            foreach($statuses as $status => $pattern){
                preg_match($pattern, $code, $matches);
                if(count($matches) > 0){
                    $retVal[1] = $level;
                    $retVal[2] = $status;
                    break;
                }
            }
        }
        return $retVal;
    }
    
    public function filter_woocommerce_get_customer_payment_tokens( $tokens, $customer_id, $gateway_id ){
        if(count($tokens) === get_option( 'posts_per_page' )){
            $tokens = WC_Payment_Tokens::get_tokens( [ 'user_id' => $customer_id , 'gateway_id' => $gateway_id, 'limit' => 100 ] );
            return $tokens;
        } else {
            return $tokens;
        }
    }
    
    public function checkGatewaySupports( $doCreateRegistration ){
        $array = [
            'products',
            'pre-orders',
            'refunds'
        ];
        if( $doCreateRegistration === 'yes'){
            $array[] = 'tokenization';
            $array[] = 'subscriptions';
            $array[] = 'subscription_cancellation';
            $array[] = 'subscription_reactivation';
            $array[] = 'subscription_suspension';
            $array[] = 'subscription_amount_changes';
            $array[] = 'subscription_payment_method_change';
            $array[] = 'subscription_date_changes';
        }
        return $array;
    }
    
    public function externalIpHooks(){
        $ips = [
            '88.202.228.211',
            '46.37.161.246',
            '46.37.161.247',
            '172.21.174.146',
            '172.21.174.147'
        ];
        return $ips;
    }
    
    public function getClientOrdersCountForLast6Months( $userId ) {
        global $wpdb;

        //Check if HPOS usage is enabled.
        if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
            $sql        = "SELECT COUNT(1) FROM {$wpdb->prefix}wc_orders p 
            INNER JOIN {$wpdb->prefix}wc_orders_meta pm 
                ON p.id=pm.order_id 
            WHERE status IN ('wc-processing', 'wc-completed')  
                AND p.date_created_gmt > date_sub(UTC_TIMESTAMP(),Interval 6 month)
                AND (meta_key = '_customer_user' AND meta_value=".(int)$userId.")";
        } else {
            $sql        = "SELECT COUNT(1) FROM {$wpdb->prefix}posts p 
            INNER JOIN {$wpdb->prefix}postmeta pm 
                ON p.ID=pm.post_id 
            WHERE post_status IN ('wc-processing', 'wc-completed')  
                AND p.post_date > date_sub(now(),Interval 6 month)
                AND (meta_key = '_customer_user' AND meta_value=".(int)$userId.")";
        }

        $orderCount = $wpdb->get_var( $sql );
        return $orderCount;
    }
    
    public function getSummaryOrders( $args ) {
        $orders = wc_get_orders( $args );
        return $orders;
    }
    
    public function getDupeCheckSystemStatus() {
        return ($this->get_option( 'dupePaymentCheck' ) === 'yes' ? true : false);
    }
    
    public function retrieveUnlimitedTokens( $customer_id, $gateway_id, $platformBase, $getMeta = false ){
        $tokenArr = [];
        $tokens = WC_Payment_Tokens::get_tokens( [ 'user_id' => $customer_id , 'gateway_id' => $gateway_id, 'limit' => 100 ] );
        foreach($tokens as $token){
            if($platformBase === 'test.oppwa.com' && $token->meta_exists('test') !== true){
                continue;
            } else if($platformBase === 'oppwa.com' && $token->meta_exists('test') === true){
                continue;
            }
            if($getMeta === true){
                $tokenArr[] = [
                    'registrationId' => $token->get_token(),
                    'holder' => ucwords($token->get_meta('holder')),
                    'paymentBrand' => $token->get_meta('paymentBrand'),
                    'last4' => $token->get_meta('last4'),
                    'bin' => $token->get_meta('bin')
                ];
            } else {
                $tokenArr[] = $token->get_token();
            }
        }
        return $tokenArr;
    }
    
    public function isOptionSetBool($item,$reverse=true){
        if($item === $reverse){
            return ["valid"=>true,"mark"=>"warning","description"=>"Disabled","private"=>false];
        } else {
            return ["valid"=>false,"mark"=>"yes","description"=>"Enabled","private"=>false];
        }
    }
    
    public function statusCheckIsEnabled($item,$customDescription='',$reverse=false,$errorClass='error',$successClass='yes'){
        $condition='yes';
        $arrayFalse=["valid"=>false,"mark"=>$errorClass,"description"=>"Disabled","private"=>false];
        $arrayTrue=["valid"=>true,"mark"=>$successClass,"description"=>"Enabled","private"=>false];
        if($reverse){
            $arrayFalse["valid"] = true;
        }
        if($item == $condition){
            $array = $arrayTrue;
        } else {
            $array = $arrayFalse;
        }
        if(trim($customDescription) != ''){
            $array['description'] = $array['description'].' ('. $customDescription.')';
        }
        return $array;
    }
    
    public function stringCheck($item,$pattern,$errorClass='error',$successClass='yes'){
        $arrayFalse=["valid"=>false,"mark"=>$errorClass,"description"=>"","private"=>false];
        $arrayTrue=["valid"=>true,"mark"=>$successClass,"description"=>"","private"=>false];
        preg_match_all($pattern, $item, $matches, PREG_SET_ORDER, 0);
        if(count($matches)>0){
            return $arrayTrue;
        }
        if($pattern == '/[a-zA-Z0-9]{58}[=]{2}/m' || $pattern == '/[a-z0-9]{32}/m'){
            if($errorClass == 'warning'){
                $arrayFalse['description'] = "*Not essential when in current processing environment";
            } else {
                $arrayFalse['description'] = "ERROR processing will fail in selected processing environment!";
            }
        }
        return $arrayFalse;
    }
    
    public function dirFileCheck($fullPath,$existsWritable,$errorClass='error',$successClass='yes'){
        $arrayFalse=["valid"=>false,"mark"=>$errorClass,"description"=>$fullPath,"private"=>true];
        $arrayTrue=["valid"=>true,"mark"=>$successClass,"description"=>$fullPath,"private"=>false];
        if($existsWritable == 'exists'){
            //exists
            if(file_exists($fullPath)) {
                return $arrayTrue;
            } else {
                return $arrayFalse;
            }
        } else if($existsWritable == 'writable'){
            //writable
            if (is_writable($fullPath)) {
                return $arrayTrue;
            } else {
                return $arrayFalse;
            }
        }
        return $arrayFalse;
    }
    
    public function pluginOptionExistsData( $option, $errorClass = 'error', $successClass = 'yes' ){
        $optionname     = TP_CONSTANTS::GLOBAL_PREFIX . $option;
        $value = get_option( $optionname, 'Option not set (required)' );
        $arrayFalse=[ "valid" => false, "mark" => $errorClass, "description" => $value . ' ' . $optionname, "private" => true ];
        $arrayTrue=["valid"=>true,"mark"=>$successClass,"description"=>$value,"private"=>false];
        if($value === 'Option not set (required)'){
            if(strlen($value) >= 1){
                if(strpos($option,'iframe_url') !== false){
                    $arrayFalse["button"]=["value"=>"Generate iFrame Page","pl_action"=>"generateFrame"];
                }
                return $arrayFalse;
            }
        }
        return $arrayTrue;
    }
    
    public function isPluginFrameURLPublished( ){
        $optionname     = TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe';
        $frameid        = get_option( $optionname, '' );
        $frameURLStatus = get_post_status( $frameid );
        $optionname     = TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe_url';
        $value          = get_option( $optionname, '' );
        if( empty( $value ) || $frameURLStatus != 'publish' ){
            return false;
        }
        return true;
    }
    
    public function pluginFrameURLStatus( $pl_action ){
        $optionname     = TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe';
        $frameid        = get_option( $optionname, '' );
        $frameURLStatus = get_post_status( $frameid );
        $optionname     = TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe_url';
        $value          = get_option( $optionname, '' );
        $arrayFalse     = [ "valid" => false, "mark" => 'error', "description" => 'Option ' . $optionname . ' not set ', "private" => true ];
        $arrayTrue      = [ "valid" => true, "mark" => 'yes', "description" => $value, "private" => false ];
        if( empty( $value ) || $frameURLStatus != 'publish' ){
            delete_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe_url' );
            update_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe', "-1" );
            $arrayFalse["button"] = ["value"=>"Generate iFrame Page","pl_action"=> $pl_action];
            return $arrayFalse;
        }
        return $arrayTrue;
    }
    
    public function savedCardsSyncStatus(){
        $optionname     = TP_CONSTANTS::GLOBAL_PREFIX . 'synced_data';
        $optionvalue    = get_option( $optionname, 'no' );
        $arrayFalse     = [ "valid" => false, "mark" => 'error', "description" => '<div style="display:inline-block;" id="tpsyncedcardstatus">Saved cards are not synced yet</div>', "private" => true ];
        $arrayTrue      = [ "valid" => true, "mark" => 'yes', "description" => '<div style="display:inline-block;">Saved cards are synced</div>', "private" => false ];
        if( empty( $optionvalue ) || $optionvalue == 'no' ){
            $arrayFalse["button"] = [ "value" => "Sync", "pl_action" => TP_CONSTANTS::GLOBAL_PREFIX . 'sync_saved_cards_tokens', 'btnclass' => 'looping-ajax-call' ];
            return $arrayFalse;
        }
        return $arrayTrue;
    }

    public function usingSSL($errorClass='error',$successClass='yes'){
        $arrayFalse=["valid"=>false,"mark"=>$errorClass,"description"=>"","private"=>false];
        $arrayTrue=["valid"=>true,"mark"=>$successClass,"description"=>"","private"=>false];
        if(is_ssl()){
            return $arrayTrue;
        }
        return $arrayFalse;
    }
    
    public function reqShipping(){
        $cart_contents    = WC()->cart->cart_contents;
        $needs_shipping   = false;
        if ( ! empty( $cart_contents ) ) {
            foreach ( $cart_contents as $cart_item_key => $values ) {
                if ( $values['data']->needs_shipping() ) {
                    $needs_shipping = true;
                    break;
                }
            }
        }
        return $needs_shipping;
    }
    
    public function unsetEmptyArrayVars($array){
        foreach($array as $k => $v){
            if(is_array($v)){
                foreach($v as $k1 => $v1){
                    if(empty($v1)){
                        unset($array[$k][$k1]);
                    }
                }
                if(count($v)===0){
                    unset($array[$k]);
                }
            } else {
                if(empty($v)){
                    unset($array[$k]);
                }
            }
        }
        return $array;
    }
    
    public function store_order_meta_keys($order_id){
        $order = wc_get_order($order_id);
        foreach($_POST as $k => $v){
            $k = sanitize_text_field($k);
            if(is_array($v)){
                $v = json_encode($v,ENT_QUOTES);
            } else {
                $v = sanitize_text_field($v);
            }
            if(strpos(TP_CONSTANTS::GATEWAY_ID, $k) !== false){
                $metaKey = str_replace(TP_CONSTANTS::GATEWAY_ID.'_','',$k);
                $order->add_meta_data($metaKey,$v);
            }
        }
        $order->save();
    }

    protected function writeLog( $label, $obj, $level = false, $uuid = null ){
        $debug     = $this->get_option( 'serversidedebug' );
        if( $debug != 'yes' ){
            return;
        }

        if( !isset( $this->logLevels ) || !in_array( $level, $this->logLevels )){
            return;
        }

        $cartHash = "";
        if(!empty( $uuid )){
            $cartHash = $uuid;
        }else if(isset(WC()->cart)){
            $cartHash = WC()->cart->get_cart_hash();
        }

        if(empty($cartHash)){
            $cartHash = time();
        }

        $logger                   = wc_get_logger();
        $GLOBALS['logcounter']    = isset( $GLOBALS['logcounter'] ) ? $GLOBALS['logcounter'] + 1 : 1;

        $context    = array( 'source' => TP_CONSTANTS::GATEWAY_ID );
        $logMessage = '['.$cartHash.'] Stack #' . $GLOBALS['logcounter'] . " : " . $label . ( $obj != null ? "\n" . wc_print_r( $obj, true ) : '' );

        switch ( $level ) {
            case 'critical':
                $logger->critical( $logMessage, $context );
                break;
            case 'debug':
                $logger->debug( $logMessage, $context );
                break;
            case 'emergency':
                $logger->emergency( $logMessage, $context );
                break;
            case 'error':
                $logger->error( $logMessage, $context );
                break;
            case 'warning':
                $logger->warning( $logMessage, $context );
                break;
            default:
               $logger->info( $logMessage, $context );
        }

        return true;
    }

    public function arrStaticData($key){
        $array= include dirname( __FILE__ ) . '/payment-gateway-static-data-array.php';
        if( isset( $array[ $key ] ) ){
            return $array[ $key ];
        }
        return [];
    }

    public function getOppwaCardMessageByCode( $code ){
        if( preg_match( "/^(900\.[1234]00|000\.400\.030)/", $code ) ){
            return "We are not sure on the state of your payment, please contact the store, before attempting payment again.";
        }
        $array = include dirname( __FILE__ ) . '/error-codes-and-messages.php';
        if( isset( $array[ $code ] ) ){
            return $array[ $code ];
        }
        return "";
    }
    
    
    public function getFAQsArray(){
        $array = include dirname( __FILE__ ) . '/payment-gateway-faqs-array.php';
        if( count( $array ) > 0 ){
            return $array;
        }
        return [];
    }

    public function generateStatusArray($retHtml=false,$colSpan=4){
        global $wp_version;
        $array=[];
        if($retHtml){
            $html='';
        }
        if(!is_admin()){
            return $array;
        }
        $array=[
            [
                "group" => "General Settings",
                "data" => [
                    ["name"=>"WooCommerce Card payments", "status"=> $this->statusCheckIsEnabled($this->enabled,'',false)],
                    ["name"=>"iFrame Page", "helptip" => "This must be available to get this gateway work good", "status" => $this->pluginFrameURLStatus( TP_CONSTANTS::GLOBAL_PREFIX . 'generateFrame' )],
                    ["name"=>"Saved Cards Sync", "helptip" => "Please run this to sync saved cards from old versions", "status" => $this->savedCardsSyncStatus()],
                    ["name"=>"SSL Protocol", "status"=> $this->usingSSL()],
                ]
            ],
            [
                "group" => "Gateway Configurations",
                "data" => [
                    ["name"=>"Selected Payment Environment", "status"=> ["valid"=>true,"mark"=> ($this->platformBase != 'test.oppwa.com' ? 'yes' : 'warning') ,"description"=>$this->platformBase,"private"=>false]],
                    ["name"=>"TEST Processing: accessToken", "helptip"=>"", "status"=> $this->stringCheck($this->accessToken_test,'/[a-zA-Z0-9]{55,}[=]{1,}/m',($this->platformBase != 'test.oppwa.com' ? 'warning' : 'error'),'yes')],
                    ["name"=>"TEST Processing: entityId", "helptip"=>"", "status"=> $this->stringCheck($this->entityId_test,'/[a-z0-9]{32}/m',($this->platformBase != 'test.oppwa.com' ? 'warning' : 'error'),'yes')],
                    ["name"=>"LIVE Processing: accessToken", "helptip"=>"", "status"=> $this->stringCheck($this->accessToken,'/[a-zA-Z0-9]{55,}[=]{1,}/m',($this->platformBase != 'oppwa.com' ? 'warning' : 'error'),'yes')],
                    ["name"=>"LIVE Processing: entityId", "helptip"=>"", "status"=> $this->stringCheck($this->entityId,'/[a-z0-9]{32}/m',($this->platformBase != 'oppwa.com' ? 'warning' : 'error'),'yes')],
                ]
            ],
            [
                "group" => "Webhook variables (for Total Processing ref.)",
                "data" => [
                    ["name"=>"var:domain", "status"=> ["valid"=>true,"description"=>$_SERVER['HTTP_HOST'],"private"=>true]],
                    ["name"=>"var:wp_path", "status"=> ["valid"=>true,"description"=>site_url(),"private"=>true]],
                    ["name"=>"get_home_path", "status"=> ["valid"=>true,"description"=>get_home_path(),"private"=>true]],
                    /*["name"=>"Logfile path", "status"=> $this->dirFileCheck( $this->logPath , 'exists')],*/
                    ["name"=>"ABSPATH", "status"=> ["valid"=>true,"description"=>trailingslashit( ABSPATH )]],
                    ["name"=>"SERVER_SOFTWARE", "status"=> ["valid"=>true,"description"=> $_SERVER['SERVER_SOFTWARE'] . ' PHP v' . phpversion(),"private"=>false]],
                    ["name"=>"SERVER_PROTOCOL", "status"=> ["valid"=>true,"description"=> $_SERVER['SERVER_PROTOCOL'],"private"=>false]],
                    ["name"=>"WP_VERSION", "status"=> ["valid"=>true,"description"=> $wp_version,"private"=>false]],
                    ["name"=>"WC_VERSION", "status"=> ["valid"=>true,"description"=> WC_VERSION,"private"=>false]],
                    ["name"=>"TPMOD", "status"=> ["valid"=>true,"description"=> TP_CONSTANTS::VERSION,"private"=>false]],
                    ["name"=>"OPENSSL_VERSION_TEXT", "status"=> ["valid"=>true,"description"=> OPENSSL_VERSION_TEXT,"private"=>false]],
                    ["name"=>"OPENSSL_VERSION_NUMBER", "status"=> ["valid"=>true,"description"=> OPENSSL_VERSION_NUMBER,"private"=>false]],
                ]
            ],
        ];
        if($retHtml){
            foreach($array as $tbl){
                $html.='<table class="wc_status_table widefat" cellspacing="0">'."\n";
                $html.='<thead>'."\n";
                $html.='<tr>'."\n";
                $html.='<th colspan="'.$colSpan.'" data-export-label="'.$tbl['group'].'">'."\n";
                $html.='<h2>'.$tbl['group'].'</h2></th>'."\n";
                $html.='</tr>'."\n";
                $html.='</thead>'."\n";
                $html.='<tbody>'."\n";
                if(isset($tbl['group']) && is_array($tbl['data'])){
                    foreach($tbl['data'] as $tr){
                        $html.='<tr>'."\n";
                        $html.='<td data-export-label="'.$tr['name'].'">'.$tr['name'].'</td>'."\n";
                        $html.='<td class="help">'."\n";
                        if(isset($tr['helptip'])){
                            $html.=wc_help_tip($tr['helptip'])."\n";
                        }
                        $html.='</td>'."\n";
                        $html.='<td>'."\n";
                        if(isset($tr['status']['mark'])){
                            if((bool)$tr['status']['valid'] === true){
                                $dashIcon = $tr['status']['mark'];
                            } else {
                                $dashIcon = 'warning';
                            }
                            $html.='<mark class="'.$tr['status']['mark'].'"><span class="dashicons dashicons-'.$dashIcon.'"></span> '."\n";
                        }
                        if(isset($tr['status']['private']) && (bool)$tr['status']['private']===true){
                            $html.='<code class="private">'."\n";
                        }
                        $html.= $tr['status']['description'];
                        if(isset($tr['status']['private']) && (bool)$tr['status']['private']===true){
                            $html.='</code>'."\n";
                        }
                        if(isset($tr['status']['mark'])){
                            $html.='</mark>'."\n";
                        }
                        $html.='</td>'."\n";
                        $html.='<td style="text-align:right;">'."\n";
                        if(isset($tr['status']['button'])){
                            $class = $tr['status']['button']['btnclass'] ?? "tp-admin-ajax";
                            $html.='<button type="button" class="button '.$class.'" data-pl_action="'.$tr['status']['button']['pl_action'].'" value="'.$tr['status']['button']['value'].'">'.$tr['status']['button']['value'].'</button>'."\n";
                        }
                        $html.='</td>'."\n";
                        $html.='</tr>'."\n";
                    }
                }
                $html.='</tbody>'."\n";
                $html.='</table>'."\n";
            }
            return $html;
        }
        return $array;
    }
    
    public function tpcp_gateway_cardsv2_e2e() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        if( !in_array( $ip_address, $this->externalIpHooks() ) ){
            return false;
        }
        $postData       = [];
        if($_POST){
            $postData   = array_merge( $postData, $_POST );
        }
        $json           = json_encode( $postData, ENT_QUOTES );
        update_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_e2e', $json );
        return true;
    }
    
    public function tpcp_gateway_cardsv2_cbk() {
        global $wpdb;
        $postdata            = file_get_contents( "php://input" );
        $data                = json_decode( $postdata, true );
        if( is_array( $data ) ){
            if( isset( $data['cbk_json'] ) ){
                $data['cbk_json'] = json_encode( $data['cbk_json'], ENT_QUOTES );
                //SELECT post_id FROM `wp_postmeta` WHERE `meta_key` = '_transaction_id' AND `meta_value` = '';
                $table  = $wpdb->prefix . TP_CONSTANTS::GLOBAL_PREFIX . 'cbktbl';
                $format = array( '%s', '%s', '%s', '%s', '%s', '%s' );
                $wpdb->insert( $table, $data, $format );
            }
        }
    }
    
    public function getTPiFrameURL(){
        $iFrameURL        = get_the_guid( (int)get_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe' ) );
        if( is_ssl() ){
            return str_replace( 'http://', 'https://', $iFrameURL );
        }
        return $iFrameURL;
    }

    public function getEntityID(){
        return ($this->platformBase == 'oppwa.com' ? $this->entityId : $this->entityId_test);
    }

    public function getAccessToken(){
        return ($this->platformBase === 'oppwa.com' ? $this->accessToken : $this->accessToken_test);
    }
    
    public function generatePciFramePageOnMissing(){
        $iFramePostId    = 0;
        if( !$this->isPluginFrameURLPublished( ) ){
            $iFramePostId = $this->createPciFramePage();
        }
        if($iFramePostId){
            add_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe_url', get_the_guid( (int)get_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe' ) ) );
            return $iFramePostId;
        }
        return $iFramePostId;
    }
    
    public function updateRequiredOldSettingsData(){
        $is_synced  = get_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_settings_sync', 'no' );
        if( $is_synced == 'yes' ){
            return;
        }
        $oldVersionSettings = ( new WC_Payment_Gateway_TotalProcessing_Cards_Old_helper( ) )->getSettings();

        if( isset( $oldVersionSettings['platformBase'] ) && !empty( $oldVersionSettings['platformBase'] ) ){
            $this->update_option( 'platformBase', $oldVersionSettings['platformBase'] );
        }

        if( isset( $oldVersionSettings['entityId_test'] ) && !empty( $oldVersionSettings['entityId_test'] ) ){
            $this->update_option( 'entityId_test', $oldVersionSettings['entityId_test'] );
        }

        if( isset( $oldVersionSettings['accessToken_test'] ) && !empty( $oldVersionSettings['accessToken_test'] ) ){
            $this->update_option( 'accessToken_test', $oldVersionSettings['accessToken_test'] );
        }

        if( isset( $oldVersionSettings['entityId'] ) && !empty( $oldVersionSettings['entityId'] ) ){
            $this->update_option( 'entityId', $oldVersionSettings['entityId'] );
        }

        if( isset( $oldVersionSettings['accessToken'] ) && !empty( $oldVersionSettings['accessToken'] ) ){
            $this->update_option( 'accessToken', $oldVersionSettings['accessToken'] );
        }

        if( isset( $oldVersionSettings['paymentType'] ) && !empty( $oldVersionSettings['paymentType'] ) ){
            $this->update_option( 'paymentType', $oldVersionSettings['paymentType'] );
        }

        if( isset( $oldVersionSettings['createRegistration'] ) && !empty( $oldVersionSettings['createRegistration'] ) ){
            $this->update_option( 'createRegistration', $oldVersionSettings['createRegistration'] );
        }

        if( isset( $oldVersionSettings['includeCartData'] ) && !empty( $oldVersionSettings['includeCartData'] ) ){
            $this->update_option( 'includeCartData', $oldVersionSettings['includeCartData'] );
        }

        if( isset( $oldVersionSettings['legacyEndpoints'] ) && !empty( $oldVersionSettings['legacyEndpoints'] ) ){
            $this->update_option( 'legacyEndpoints', $oldVersionSettings['legacyEndpoints'] );
        }

        if( isset( $oldVersionSettings['checkoutOrderCleanup'] ) && !empty( $oldVersionSettings['checkoutOrderCleanup'] ) ){
            $this->update_option( 'checkoutOrderCleanup', $oldVersionSettings['checkoutOrderCleanup'] );
        }

        if( isset( $oldVersionSettings['paymentBrands'] ) && !empty( $oldVersionSettings['paymentBrands'] ) ){
            $this->update_option( 'paymentBrands', $oldVersionSettings['paymentBrands'] );
        }

        if( isset( $oldVersionSettings['threeDv2'] ) && !empty( $oldVersionSettings['threeDv2'] ) ){
            $this->update_option( 'threeDv2', $oldVersionSettings['threeDv2'] );
        }

        if( isset( $oldVersionSettings['threeDv2Params'] ) && !empty( $oldVersionSettings['threeDv2Params'] ) ){
            $this->update_option( 'threeDv2Params', $oldVersionSettings['threeDv2Params'] );
        }

        if( isset( $oldVersionSettings['transactionType3d'] ) && !empty( $oldVersionSettings['transactionType3d'] ) ){
            $this->update_option( 'transactionType3d', $oldVersionSettings['transactionType3d'] );
        }

        if( isset( $oldVersionSettings['paymentLogoCss'] ) && !empty( $oldVersionSettings['paymentLogoCss'] ) ){
            $this->update_option( 'paymentLogoCss', $oldVersionSettings['paymentLogoCss'] );
        }

        if( isset( $oldVersionSettings['labelHex'] ) && !empty( $oldVersionSettings['labelHex'] ) ){
            $this->update_option( 'labelHex', $oldVersionSettings['labelHex'] );
        }

        if( isset( $oldVersionSettings['autoFocusFrameCcNo'] ) && !empty( $oldVersionSettings['autoFocusFrameCcNo'] ) ){
            $this->update_option( 'autoFocusFrameCcNo', $oldVersionSettings['autoFocusFrameCcNo'] );
        }

        if( isset( $oldVersionSettings['framePrimaryColor'] ) && !empty( $oldVersionSettings['framePrimaryColor'] ) ){
            $this->update_option( 'framePrimaryColor', $oldVersionSettings['framePrimaryColor'] );
        }

        if( isset( $oldVersionSettings['frameAccentColor'] ) && !empty( $oldVersionSettings['framePrimaryColor'] ) ){
            $this->update_option( 'frameAccentColor', $oldVersionSettings['frameAccentColor'] );
        }

        update_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_settings_sync', 'yes' );
    }

    public function generateDebuggingLogsHTML(){
        global $wp_version;
        $files             = $this->get_wc_logfiles();
        $options           = '';
        $firstFileHandle   = !empty( $_POST['fld_log_handle'] ) ? $_POST['fld_log_handle'] : '';
        foreach( $files AS $key => $file ){
            $firstFileHandle = empty( $firstFileHandle ) ? $file : $firstFileHandle;
            $options .= '<option value="'.$file.'" ' . ( $file == $firstFileHandle ? 'selected' : '' ) . '>'.$key.'</option>';
        }
        $html    = '<div id="logforwardstatuscontainer"></div>
                    <table class="wc_status_table widefat" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <td style="text-align:left;width:1%;vertical-align:middle;">
                                    Selected:
                                </td>
                                <td style="text-align:left;vertical-align:middle;" nowrap>
                                    ' . $firstFileHandle . '
                                </td>
                                <td style="text-align:right;vertical-align:middle;" nowrap>
                                    Debug Files
                                </td>
                                <td style="width:2%;vertical-align:middle;">
                                    <select name="fld_log_handle">
                                    ' . $options . ' 
                                    </select>
                                </td>
                                <td style="width:2%;vertical-align:middle;">
                                    <button type="submit">View</button>
                                </td>
                                <td style="width:5%;vertical-align:middle;" nowrap>
                                    <button type="button" class="send_log_to_tp_support" data-action="' . TP_CONSTANTS::GLOBAL_PREFIX . 'forward_debugdata_to_tp_support">Send To TP</button>
                                </td>
                                <td style="width:5%;vertical-align:middle;">
                                    <button type="button" class="download_log_file" data-action="' . TP_CONSTANTS::GLOBAL_PREFIX . 'download_debugdata_file">Download</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7">
                                    <div id="log-viewer">
                                        <pre>'.esc_html( @file_get_contents( WC_LOG_DIR . $firstFileHandle ) ).'</pre>
                                    </div>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <script type="text/javascript">
                    jQuery(function($){
                        $(document).on("click", ".send_log_to_tp_support", function(e){
                            e.preventDefault();
                            var el       = $(this);
                            var filename = $("select[name=fld_log_handle]").val();
                            var action = el.data("action");
                            return wp.ajax.post(action,{"filename":filename})
                                .then(function(response) {
                                    console.log(response);
                                    if(response.message){
                                        $("#logforwardstatuscontainer").html(response.message);
                                    }
                                });
                        });
                        $(document).on("click", ".download_log_file", function(e){
                            e.preventDefault();
                            var el       = $(this);
                            var filename = $("select[name=fld_log_handle]").val();
                            var action   = el.data("action");
                            var durl     = "'.admin_url('admin-ajax.php').'?action="+action+"&file="+filename;
                            window.location = durl;
                        });
                    });
                    </script>';
        return $html;
    }

    public function get_wc_logfiles(){
        $log_files  = WC_Log_Handler_File::get_log_files();
        krsort( $log_files );
        $arr        = [];
        foreach( $log_files AS $key => $file ){
            if( strpos( $key, TP_CONSTANTS::GATEWAY_ID ) !== false ){
                $arr[$key] = $file;
            }
        }
        return $arr;
    }

    public function get_wc_logfiles_path( $handler ){
        $log_files_path  = WC_Log_Handler_File::get_log_file_path( $handler );
        return $log_files_path;
    }

    public function get_wc_logfiles_content( $handler ){
        return$log_files_path  = WC_Log_Handler_File::get_log_file_path( $handler );
        $content         = esc_html( file_get_contents( $log_files_path ) );
        return $content;
    }

    public function showModalInlineStyles(){
        if($this->useModalPayFrames === true){
            $pBaseURL    = TP_Constants::getPluginBaseURL();
            ?>
            <style>
            #cnpFrame {width: 100%; border: none;}
            #cnpSwal {margin-bottom: 0.3rem; margin-top: 1rem;}
            #cnpNav {display: flex; align-items: center; justify-content: center;}
            #cnpNav button {
                font-size: small;
                margin: 5px;
                border-radius: 0.25em;
                font-weight: lighter;
                margin: 0.3125em;
                padding: 0.625em 1.1em;
                box-shadow: none;
                font-weight: 500;
            }
            /*wpwl*/
            #wpwlDynBrand {width:72px; padding:8px; position: absolute; right: 0px; top: 0;}
            #wpwlDynBrandImg {border-radius: unset; height: -webkit-fill-available; margin:0!important; float: right; max-height: 22px;}
            form.wpwl-form .form-row-first{float:left; width:48%!important;margin-right: <?php echo $this->modalFramewpwlControlMarginRight;?>%!important}
            form.wpwl-form .form-row-last{float:left; width:48%!important;margin-right:0!important;}
            form.wpwl-form .form-row-wide{margin-bottom: 1.75rem; width:100%!important;margin-right: 5px;float:left;}
            form.wpwl-form .form-row input::placeholder{color: #CCC; font-size: 14px; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;}
            .wpwl-control-expiry, .wpwl-control-cardHolder {color:#545454!important; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif!important; height: 38.5px!important; border: 1px solid #CCC!important; background: transparent!important;  margin-bottom:1px!important; border-radius:0px!important; box-shadow: none!important;-webkit-appearance: none!important; -moz-appearance: none!important; appearance: none!important;}
            .wpwl-group-brand, /*.wpwl-wrapper-registration-brand,*/ .wpwl-group-cardHolder, .wpwl-group-submit {display:none;}
            .wpwl-wrapper-registration-registrationId, .wpwl-wrapper-registration-brand {padding-right:4px!important;}
            .wpwl-wrapper-registration-details { padding-right: 10px!important; }
            .wpwl-brand-VISA {
                border-radius: unset; 
                margin-top: 12px!important; 
                width: 36px;
                height: 24px;
                background: url("<?php echo $pBaseURL;?>assets/img/VISA.svg") no-repeat;
                background-size: contain;
            }
            .wpwl-brand-MASTER {
                border-radius: unset; 
                margin-top: 7px!important;
                width: 32px;
                height: 20px;
                background: url("<?php echo $pBaseURL;?>assets/img/MASTER.svg") no-repeat;
                background-size: contain;
            }
            .wpwl-brand-AMEX {
                border-radius: unset; 
                margin-top: 6px!important;
                width: 22px;
                height: 24px;
                background: url("<?php echo $pBaseURL;?>assets/img/AMEX.svg") no-repeat;
                background-size: contain;
            }
            .wpwl-brand-MAESTRO {
                border-radius: unset; 
                margin-top: 7px!important;
                width: 32px;
                height: 20px;
                background: url("<?php echo $pBaseURL;?>assets/img/MAESTRO.svg") no-repeat;
                background-size: contain;
            }
            /*.wpwl-group-brand, .wpwl-wrapper-registration-brand, .wpwl-group-cardHolder {display:none;}*/
            .wpwl-control-cardNumber, .wpwl-control-expiry, .wpwl-control-cvv, .wpwl-control-cardHolder {width:100%!important;}
            .wpwl-wrapper-cardNumber, .wpwl-wrapper-expiry, .wpwl-wrapper-cvv, .wpwl-wrapper-cardHolder {float:none!important; width:100%!important; position:unset!important;}
            .frameContainer {margin-top:16px;}
            /*.wpwl-form {max-width: unset!important;}*/
            .wpwl-form {max-width: 100%; margin:0; padding:0;}
	    .wpwl-control, .wpwl-control-expiry, .IFRAME_STYLE_1 {text-align:left;background:<?php echo $this->modalFramewpwlControlBackground;?>!important;color:<?php echo $this->modalFramewpwlControlFontColor;?>!important;border:<?php echo $this->modalFramewpwlControlBorderWidth;?>px solid <?php echo $this->modalFramewpwlControlBorderColor;?>!important;border-radius:<?php echo $this->modalFramewpwlControlBorderRadius?>px!important;}
            div.wpwl-hint {font-family:'Helvetica Neue',Helvetica,Arial,sans-serif; font-size:smaller!important; text-align: left!important;}
            input.wpwl-control-expiry:-webkit-input-placeholder {color:#CCC!important; font-size: 14px!important;}
            input.wpwl-control-expiry:-ms-input-placeholder {color:#CCC!important; font-size: 14px!important;}
            input.wpwl-control-expiry::placeholder {color:#CCC!important;}
            label.tpFrameLabel {color:#333333; font-size: 11px; display: block; text-align: left; line-height: 140%; margin-bottom: 3px; max-width: 100%; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;}
            span.tp-required {color:#FF0000;}
            .wpwl-wrapper-registration-details {width:unset; margin-bottom: 0;}
            .wpwl-group-registration {border:none;}
            .wpwl-form-has-inputs {
                padding: 0px!important;
                border: none!important;
                background-color: none!important;
                border-radius: 0px!important;
                -webkit-box-shadow: none!important;
                box-shadow: none!important;
            }
            .wpwl-group-registration {
                border: none;
                margin-bottom: 0;
            }
            #tpIframeRg {margin:0; padding:8px; border:none; text-align: left!important; font-size: smaller!important;}
            #tpIframeRg label {color:<?php echo $this->modalFrameAccentColor;?>!important; font-size: smaller;}
            .wpwl-group-registration.wpwl-selected {
                border-color: #CCC!important;
                border:1px solid #CCC!important;
                border-radius:1px!important;
            }
            .wpwl-group-registration.wpwl-selected label {
                color: #333!important;
            }
            .wpwl-wrapper-registration-registrationId {width:unset!important;}
            .wpwl-wrapper-registration-cvv {float:right!important;}
            label.wpwl-registration {line-height: 36px;}
            div.wpwl-wrapper-registration-cvv {line-height: 20px;}
            div.wpwl-group {width:unset;}
	    /*div.wpwl-group {width:unset;background:transparent;color: <?php echo $this->modalFramewpwlControlFontColor;?>;border-radius:<?php echo $this->modalFramewpwlControlBorderRadius;?>px!important;margin-right:<?php echo $this->modalFramewpwlControlMarginRight;?>px!important;border: <?php echo $this->modalFramewpwlControlBorderWidth;?>px solid <?php echo $this->modalFramewpwlControlBorderColor;?>!important;}*/
            .wpwl-container-registration div.wpwl-group{border: none!important;}
            div#wpwl-registrations {display: block;}
            div.wpwl-group-registration {font-size: small!important;}
            .wpwl-group-registration.wpwl-selected {border:none!important;}
            div.wpwl-wrapper-registration {color:<?php echo $this->modalFrameAccentColor;?>!important; border:none!important;}
            div.wpwl-wrapper-registration .wpwl-control-cvv {margin-top:5px;}
            div.wpwl-container {margin-bottom: 0.5rem;}
            .flexFootTp {flex-flow: column;}
            .padBottom {padding-bottom: 1.6rem!important;}
            .wpwl-group-cardHolder {padding-top:0.5rem!important; clear:both!important;}
            .wpwl-has-error {
                color: #a94442!important;
                border-color: #a94442!important;
            }
            td.woocommerce-PaymentMethod--field1 {text-align:center!important;}
            td.woocommerce-PaymentMethod--field1 img {
            	height: 28px;
                display: inline;
                margin: 0;
                padding-top: 0px;
                border-radius: unset;
                max-width: 52px;
            }
            .wpwl-wrapper-registration-expiry{
                padding-right: 22px;
            }
            .swal2-modal{
                background: <?php echo $this->modalFramePrimaryColor;?>;
            }
            .swal2-title {
              font-size: 1.8em!important;
              color: <?php echo $this->modalFrameAccentColor;?>!important;
            }
            .swal2-content{
                width: 100%!important;
                padding: 0!important;
            }

            button.swal2-styled{
                background: <?php echo $this->modalFrameButtonBGColor;?>!important;
                color: <?php echo $this->modalFrameButtonTextColor;?>!important;
            }

            button.swal2-styled.swal2-confirm{
                background: <?php echo $this->modalFrameProcessButtonBGColor;?>!important;
                color: <?php echo $this->modalFrameProcessButtonTextColor;?>!important;
            }

            button.swal2-styled.swal2-cancel{
                background: <?php echo $this->modalFrameCancelButtonBGColor;?>!important;
                color: <?php echo $this->modalFrameCancelButtonTextColor;?>!important;
            }

            button.cnpTarget{
                background: <?php echo $this->modalFrameButtonBGColor;?>!important;
                border:1px solid <?php echo $this->modalFrameButtonBGColor;?>!important;
                color: <?php echo $this->modalFrameButtonTextColor;?>!important;
            }

            @media only screen and (max-width: 920px) {
              .wpwl-wrapper-registration-cvv {
                width: 30%;
              }
            }

            @media only screen and (max-width: 500px) {
              .wpwl-wrapper-registration-brand{ display: none;}
              .wpwl-wrapper-registration-number, .wpwl-wrapper-registration-expiry {
                padding-right: 20px!important;
              }
              .wpwl-wrapper-registration-cvv {
                width: 30%;
              }
            }
            </style>
            <?php
        }
    }

    public function is_checkout_block() {
        return WC_Blocks_Utils::has_block_in_page( wc_get_page_id('checkout'), 'woocommerce/checkout' );
    }

    public function show_tp_errors(){
        if( isset( $_REQUEST['tp_error'] ) && !empty( $_REQUEST['tp_error'] ) ):
        ?>
        <ul class="woocommerce-error">
            <li><?php echo wp_kses_post( $_REQUEST['tp_error'] ); ?></li>
        </ul>
        <?php
        endif;
    }
}
