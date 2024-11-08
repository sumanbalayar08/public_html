<?php

/**
 * @link                 https://www.totalprocessing.com
 * @since                5.2.9
 * @package              Totalprocessing_Card_Payments_And_Gateway_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:          Total Processing Card Payments & Gateway for WooCommerce
 * Plugin URI:           https://www.totalprocessing.com/woocommerce/cards
 * Description:          Accept all major credit and debit cards. Fast, seamless, and flexible.
 * Version:              7.0.3
 * Author:               Total Processing Limited
 * Copyright:            2022 Total Processing Limited.
 * Author URI:           https://www.totalprocessing.com
 * License:              GNU General Public License v3.0
 * License URI:          http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:          totalprocessing-card-payments-and-gateway-woocommerce
 * Domain Path:          /languages
 * WC tested up to:      9
 * WC requires at least: 5.6
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'TOTALPROCESSING_PAYMENTGATEWAY_PLUGIN_BASEPATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'TOTALPROCESSING_PAYMENTGATEWAY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'TOTALPROCESSING_PAYMENTGATEWAY_PLUGIN_BASEURL', plugin_dir_url( __FILE__ ) );

define( 'WC_SUBSCRIPTIONS_IS_ACTIVE', 
    in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) );

/**
 * Currently plugin version.
 */
//define( 'TOTALPROCESSING_CARD_PAYMENTS_AND_GATEWAY_WOOCOMMERCE_VERSION', '5.2.0' );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-totalprocessing-constants.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-totalprocessing-card-payments-and-gateway-woocommerce-activator.php
 */
function activate_totalprocessing_card_payments_and_gateway_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-totalprocessing-card-payments-and-gateway-woocommerce-activator.php';
	Totalprocessing_Card_Payments_And_Gateway_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-totalprocessing-card-payments-and-gateway-woocommerce-deactivator.php
 */
function deactivate_totalprocessing_card_payments_and_gateway_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-totalprocessing-card-payments-and-gateway-woocommerce-deactivator.php';
	Totalprocessing_Card_Payments_And_Gateway_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_totalprocessing_card_payments_and_gateway_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_totalprocessing_card_payments_and_gateway_woocommerce' );

/**
 * WooCommerce not activated admin notice
 *
 * @since    5.2.0
 */
function totalprocessing_card_payments_and_gateway_woocommerce_install_wc_notice(){
	?>
	<div class="error">
		<p><?php _e( 'Total Processing Card Payments & Gateway for WooCommerce is enabled but not effective. It requires WooCommerce in order to work.', 'totalprocessing-card-payments-and-gateway-woocommerce' ); ?></p>
	</div>
	<?php
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    5.2.0
 */
function run_totalprocessing_card_payments_and_gateway_woocommerce() {

	$plugin = new Totalprocessing_Card_Payments_And_Gateway_Woocommerce();
	$plugin->run();

}

// Registers WooCommerce Blocks integration.
add_action( 'woocommerce_blocks_loaded', 'totalprocessing_card_payments_and_gateway_woocommerce_block_support' );
function totalprocessing_card_payments_and_gateway_woocommerce_block_support() {
    if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
        require_once 'blocks/class-wc-tp-cards-payments-blocks.php';
        add_action(
            'woocommerce_blocks_payment_method_type_registration',
            function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
            	$payment_method_registry->register( new Totalprocessing_Card_Payments_And_Gateway_Blocks_Support() );
            }
        );
    }
} 

/**
 * Declaring HPOS compatibility
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/**
 * Check if WooCommerce is activated
 *
 * @since    5.2.0
 */
function run_totalprocessing_card_payments_and_gateway_woocommerce_init(){
	if ( function_exists( 'WC' ) ) {
		/**
         * The core plugin class that is used to define internationalization,
         * admin-specific hooks, and public-facing site hooks.
         */
        require plugin_dir_path( __FILE__ ) . 'includes/class-totalprocessing-card-payments-and-gateway-woocommerce.php';
		run_totalprocessing_card_payments_and_gateway_woocommerce();
	}
	else{
		add_action( 'admin_notices', 'totalprocessing_card_payments_and_gateway_woocommerce_install_wc_notice' );
	}
}
add_action('plugins_loaded','run_totalprocessing_card_payments_and_gateway_woocommerce_init');
