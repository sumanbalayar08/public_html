<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * Payments Blocks integration
 *
 * @since 1.0.3
 */
final class Totalprocessing_Card_Payments_And_Gateway_Blocks_Support extends AbstractPaymentMethodType {

	/**
	 * The gateway instance.
	 *
	 * @var wc_tp_cardsv3
	 */
	private $gateway;

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'wc_tp_cardsv3';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( 'woocommerce_wc_tp_cardsv3_settings', [] );
		$gateways       = WC()->payment_gateways->payment_gateways();
		$this->gateway  = $gateways[ $this->name ];
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return $this->gateway->is_available();
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		$script_path       = '/assets/js/block/block.js';
		$script_asset_path = TOTALPROCESSING_PAYMENTGATEWAY_PLUGIN_BASEPATH . 'assets/js/block/block.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require( $script_asset_path )
			: array(
				'dependencies' => array(),
				'version'      => '1.2.0'
			);
		$script_url        = TOTALPROCESSING_PAYMENTGATEWAY_PLUGIN_BASEURL . $script_path;

		wp_register_script(
			'wc-wc_tp_cardsv3-payments-blocks',
			$script_url,
			$script_asset[ 'dependencies' ],
			$script_asset[ 'version' ],
			true
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'wc-wc_tp_cardsv3-payments-blocks', 'totalprocessing-card-payments-and-gateway-woocommerce', TOTALPROCESSING_PAYMENTGATEWAY_PLUGIN_BASEPATH . 'languages/' );
		}

		return [ 'wc-wc_tp_cardsv3-payments-blocks' ];
	}

    public function get_icons(){
        $paymentBrands            = $this->get_setting( 'paymentBrands' );
        $icons_arr                = [];
        foreach($paymentBrands as $brand){
            /*$icons_arr[]    = [
                'id'   => $this->name,
                'src'  => TOTALPROCESSING_PAYMENTGATEWAY_PLUGIN_BASEURL .'assets/img/'. $brand. '-3d.svg',
                'alt'  => $brand,
            ];*/
            $icons_arr[$brand]    = [ 
                'src'  => TOTALPROCESSING_PAYMENTGATEWAY_PLUGIN_BASEURL.'assets/img/'. $brand. '-3d.svg',
                'alt'  => $brand
            ];
        }
        return $icons_arr;
    }

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		return [
            'icons'       => $this->get_icons(),
			'title'       => $this->get_setting( 'title' ),
			'description' => $this->get_setting( 'description' ),
			'uuid'        => $this->gateway->getCheckoutIdOrder(),
			'frameurl'    => $this->gateway->getTPiFrameURL(),
			'supports'    => array_filter( $this->gateway->supports, [ $this->gateway, 'supports' ] )
		];
	}
}
