<?php
/**
 * TP Cards constants.
 */
namespace TotalProcessing;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_TotalProcessing_Constants {
    const VERSION                                          = "5.4.2";
    const DEBUG                                            = false;
    const GLOBAL_PREFIX                                    = "tpcpv3_";
    const GATEWAY_ID                                       = "wc_tp_cardsv3";
    const GATEWAY_TITLE                                    = "Total Processing Card Payments";
    const GATEWAY_DESCRIPTION                              = "Card payment customisable in your payment flow. Checkout with Credit/Debit Card.";
    const PROXY_URL                                        = "https://oppwa.totalprocessing.com/";
    const DEBUG_CONTACT_EMAIL                              = "plugins@totalprocessing.com";
    const DISABLE_CHECKOUT_INIT_PAYMENT_CHECK              = true;

    public static function getPluginBaseURL(){
        return TOTALPROCESSING_PAYMENTGATEWAY_PLUGIN_BASEURL;
    }

    public static function getPluginFileData( $var ){
        $data  = get_plugin_data( self::getPluginRootPath() . "/totalprocessing-card-payments-and-gateway-woocommerce.php", false, false );
        return ($data[$var] ?? '');
    }

    public static function getPlatformBaseURL( $isLive ){
        return ( (bool)$isLive === true ) ? 'oppwa.com' : 'test.oppwa.com';
    }

    public static function getExternalFrameURL(){
        return plugin_dir_url( __DIR__ ) . 'templates/pci-frame-external.php?frame=1';
    }

    public static function getPluginRootPath(){
        return plugin_dir_path( dirname( __FILE__ ) );
    }

    public static function getPluginBaseName(){
        return TOTALPROCESSING_PAYMENTGATEWAY_PLUGIN_BASENAME;
    }
    
    public static function getFilePaths( $dir, $outsideRoot = 0, $appendDir = false ){
        if( $outsideRoot === 3 ){
            if( $appendDir ){
                return dirname( __DIR__ ) . '/' . $dir;
            }
            return dirname( __DIR__ );
        }
        $docRoot         = $_SERVER['DOCUMENT_ROOT'];
        $docRoot         = rtrim( $docRoot, '/' );
        $docRootExpl     = explode( '/', $docRoot );
        if( $outsideRoot === 1 ){
            array_pop( $docRootExpl );
        }
        $docBase         = implode( '/', $docRootExpl );
        if( $appendDir ){
            return $docBase . '/' . $dir;
        }
        return $docBase;
    }
}
