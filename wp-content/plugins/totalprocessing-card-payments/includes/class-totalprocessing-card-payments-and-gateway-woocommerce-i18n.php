<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.totalprocessing.com
 * @since      5.2.0
 *
 * @package    Totalprocessing_Card_Payments_And_Gateway_Woocommerce
 * @subpackage Totalprocessing_Card_Payments_And_Gateway_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      5.2.0
 * @package    Totalprocessing_Card_Payments_And_Gateway_Woocommerce
 * @subpackage Totalprocessing_Card_Payments_And_Gateway_Woocommerce/includes
 * @author     Total Processing Limited <support@totalprocessing.com>
 */
class Totalprocessing_Card_Payments_And_Gateway_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    5.2.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'totalprocessing-card-payments-and-gateway-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
