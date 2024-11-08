<?php

use TotalProcessing\WC_TotalProcessing_Constants AS TP_CONSTANTS;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WC_Payment_Gateway_Addons_TotalProcessing{
    public $gatewaysClasses        = [];

    public function __construct() {
    }

	public function add_woocommerce_payment_gateway( $className ) {
		$this->gatewaysClasses[]  = $className;
	}

	public function get_woocommerce_payment_gateways() {
		return $this->gatewaysClasses;
	}

	public function add_woocommerce_payment_gateways( $gateways ) {
        $allGateways             = $this->get_woocommerce_payment_gateways();
        if( count( $allGateways ) > 0 ){
            foreach( $allGateways AS $singleGateway ){
                $gateways[]      = $singleGateway;
            }
        }
        return $gateways;
	}
} //end class
