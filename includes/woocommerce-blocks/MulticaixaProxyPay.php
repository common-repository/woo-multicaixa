<?php
namespace Automattic\WooCommerce\Blocks\Payments\Integrations;

use Automattic\WooCommerce\Blocks\Assets\Api;

/**
 * Multicaixa payment method integration
 */
final class MulticaixaProxyPay extends AbstractPaymentMethodType {
	/**
	 * Payment method name defined by payment methods extending this class.
	 *
	 * @var string
	 */
	protected $name = 'multicaixa_proxypay';

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = Multicaixa_WooCommerce()->multicaixa_settings;
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return ! empty( $this->settings['enabled'] ) && 'yes' === $this->settings['enabled'];
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {
		wp_register_script( 'wc-payment-method-multicaixa-proxypay', plugins_url( 'build/index.js', __FILE__ ), array(), Multicaixa_WooCommerce()->version.( WP_DEBUG ? '.'.rand( 0, 9999 ) : '' ), true );
		return [ 'wc-payment-method-multicaixa-proxypay' ];
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		return apply_filters( 'multicaixa_proxypay_blocks_payment_method_data', array(
			'title'                             => isset( $this->settings['title'] ) ? $this->settings['title'] : '',
			'description'                       => isset( $this->settings['description'] ) ? $this->settings['description'] : '',
			'icon'                              => Multicaixa_WooCommerce()->multicaixa_icon_url,
		) );
	}
}
