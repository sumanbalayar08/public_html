<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.totalprocessing.com
 * @since      5.2.0
 *
 * @package    Totalprocessing_Card_Payments_And_Gateway_Woocommerce
 * @subpackage Totalprocessing_Card_Payments_And_Gateway_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Totalprocessing_Card_Payments_And_Gateway_Woocommerce
 * @subpackage Totalprocessing_Card_Payments_And_Gateway_Woocommerce/admin
 * @author     Total Processing Limited <support@totalprocessing.com>
 */
class Totalprocessing_Card_Payments_And_Gateway_Woocommerce_Admin {

	/**
	 *
	 * @since    5.2.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    5.2.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    5.2.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    5.2.0
	 */
	public function enqueue_styles() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Totalprocessing_Card_Payments_And_Gateway_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Totalprocessing_Card_Payments_And_Gateway_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/totalprocessing-card-payments-and-gateway-woocommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    5.2.0
	 */
	public function enqueue_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Totalprocessing_Card_Payments_And_Gateway_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Totalprocessing_Card_Payments_And_Gateway_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/totalprocessing-card-payments-and-gateway-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

	}

}
