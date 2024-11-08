<?php
use TotalProcessing\WC_TotalProcessing_Constants AS TP_CONSTANTS;

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.totalprocessing.com
 * @since      5.2.0
 *
 * @package    Totalprocessing_Card_Payments_And_Gateway_Woocommerce
 * @subpackage Totalprocessing_Card_Payments_And_Gateway_Woocommerce/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      5.2.0
 * @package    Totalprocessing_Card_Payments_And_Gateway_Woocommerce
 * @subpackage Totalprocessing_Card_Payments_And_Gateway_Woocommerce/includes
 * @author     Total Processing Limited <support@totalprocessing.com>
 */
class Totalprocessing_Card_Payments_And_Gateway_Woocommerce_Deactivator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    5.2.0
     */
    public static function deactivate() {
        self::tpcp_gateway_cardsv2_deactivation();
        self::cronstarter_deactivate();
    }

    public static function tpcp_gateway_cardsv2_deactivation() {
        delete_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_e2e' );
        $iFramePageId = (int)get_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe' );
        if($iFramePageId > 0){
            wp_delete_post( $iFramePageId , true );
            delete_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe_url' );
        }
        delete_option( TP_CONSTANTS::GLOBAL_PREFIX . 'gateway_cardsv2_iframe' );
        return false;
    }

    public static function cronstarter_deactivate() {
        // find out when the last event was scheduled
        $timestamp = wp_next_scheduled( TP_CONSTANTS::GLOBAL_PREFIX . 'dupe_payment_validation' );
        // unschedule previous event if any
        wp_unschedule_event( $timestamp, TP_CONSTANTS::GLOBAL_PREFIX . 'dupe_payment_validation' );
    }
}
