<?php
/**
 * Plugin Name: Payment Multicaixa (ProxyPay gateway) for WooCommerce
 * Plugin URI: https://www.webdados.pt/wordpress/plugins/multicaixa-gateway-proxypay-para-woocommerce-wordpress/
 * Description: This plugin allows customers with an Angolan bank account to pay WooCommerce orders in Kwanzas using Multicaixa (Pagamentos por Referência), through ProxyPay’s payment gateway.
 * Version: 2.2
 * Author: PT Woo Plugins (by Webdados)
 * Author URI: https://ptwooplugins.com
 * Text Domain: woo-multicaixa
 * Domain Path: /lang
 * Requires at least: 5.4
 * Requires PHP: 7.0
 * WC requires at least: 5.0
 * WC tested up to: 8.4
**/

/* WooCommerce CRUD & HPOS ready */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Init */
add_action( 'plugins_loaded', 'multicaixa_init', 1 );
function multicaixa_init() {
	if ( ! function_exists( 'get_plugin_data' ) ) {
		include ABSPATH . '/wp-admin/includes/plugin.php';
	}
	$plugin_data = get_plugin_data( __FILE__ );
	define( 'MULTICAIXA_PLUGIN_FILE', __FILE__ );
	define( 'MULTICAIXA_VERSION', $plugin_data['Version'] );
	define( 'MULTICAIXA_WC_REQ_VERSION', $plugin_data['WC requires at least'] );
	load_plugin_textdomain( 'woo-multicaixa' );
	if ( class_exists( 'WooCommerce' ) && defined( 'WC_VERSION' ) && version_compare( WC_VERSION, MULTICAIXA_WC_REQ_VERSION, '>=' ) ) {
			require_once( dirname( __FILE__ ) . '/includes/class-multicaixa-woocommerce.php' );
			require_once( dirname( __FILE__ ) . '/includes/class-wc-multicaixa-woocommerce.php' );
			$GLOBALS['Multicaixa_WooCommerce'] = Multicaixa_WooCommerce();
	} else {
		add_action( 'admin_notices', 'admin_notices_multicaixa_woocommerce_not_active' );
	}
}

/* Main class */
function Multicaixa_WooCommerce() {
	return Multicaixa_WooCommerce::instance(); 
}

/* API class */
function Multicaixa_ProxyPay_API() {
	if ( ! isset( $GLOBALS['Multicaixa_ProxyPay_API'] ) ) {
		require_once( dirname( __FILE__ ) . '/includes/class-multicaixa-proxypay-api.php' );
		$GLOBALS['Multicaixa_ProxyPay_API']          = Multicaixa_ProxyPay_API::instance();
		$GLOBALS['Multicaixa_ProxyPay_API']->url     = Multicaixa_WooCommerce()->url;
		$GLOBALS['Multicaixa_ProxyPay_API']->api_key = Multicaixa_WooCommerce()->get_multicaixa_setting('api_key');
	}
	return Multicaixa_ProxyPay_API::instance(); 
}

/* Admin notice */
function admin_notices_multicaixa_woocommerce_not_active() {
	?>
	<div class="notice notice-error is-dismissible">
		<p>
			<?php
			printf(
				__( '<strong>Payment Multicaixa (ProxyPay gateway) for WooCommerce</strong> is installed and active but <strong>WooCommerce (%s or above)</strong> is not.', 'woo-multicaixa' ),
				MULTICAIXA_WC_REQ_VERSION
			);
			?>
		</p>
	</div>
	<?php
}

/* HPOS Compatible - beta */
add_action( 'before_woocommerce_init', function() {
	if ( version_compare( WC_VERSION, '7.1', '>=' ) && class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
} );

/* If you're reading this you must know what you're doing ;-) Greetings from sunny Portugal! */

