<?php
/*
Plugin Name: WooCommerce Chilexpress Shipping
Plugin URI: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping
Description: Sistema de cálculo de envíos en Chile para WooCommerce, utilizando Chilexpress
Version: 1.0.1
Author: Whooo
Author URI: http://whooohq.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: whq-wcchp
*/

/**
 * Authors & Contributors:
 *
 * Jhoynerk Caraballo [ https://github.com/jhoynerk ]
 * Esteban Cuevas [ https://github.com/TCattd ]
 */

define( 'WHQ_WCCHP_CHILEXPRESS_URL', 'http://www.chilexpress.cl' );
define( 'WHQ_WCCHP_CHILEXPRESS_SOAP_USER', 'UsrTestServicios' );
define( 'WHQ_WCCHP_CHILEXPRESS_SOAP_PASS', 'U$$vr2$tS2T' );
define( 'WHQ_WCCHP_PLUGIN_FILE', __FILE__ );
define( 'WHQ_WCCHP_PLUGIN_PATH', trailingslashit( plugin_dir_path(__FILE__) ) );
define( 'WHQ_WCCHP_PLUGIN_URL', trailingslashit( plugin_dir_url(__FILE__) ) );

/**
 * Check if WooCommerce is active
 */
$whq_wcchp_active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'woocommerce/woocommerce.php', $whq_wcchp_active_plugins) ) {
	if (file_exists(WHQ_WCCHP_PLUGIN_PATH . 'includes/activation.php')) {
		include_once WHQ_WCCHP_PLUGIN_PATH . 'includes/activation.php';
	}

	if (file_exists(WHQ_WCCHP_PLUGIN_PATH . 'includes/woocommerce.php')) {
		include_once WHQ_WCCHP_PLUGIN_PATH . 'includes/woocommerce.php';
	}

	if (file_exists(WHQ_WCCHP_PLUGIN_PATH . 'includes/scripts.php')) {
		include_once WHQ_WCCHP_PLUGIN_PATH . 'includes/scripts.php';
	}

	if (file_exists(WHQ_WCCHP_PLUGIN_PATH . 'includes/ajax.php')) {
		include_once WHQ_WCCHP_PLUGIN_PATH . 'includes/ajax.php';
	}

	if (file_exists(WHQ_WCCHP_PLUGIN_PATH . 'includes/soap_call.php')) {
		include_once WHQ_WCCHP_PLUGIN_PATH . 'includes/soap_call.php';
	}

	if (file_exists(WHQ_WCCHP_PLUGIN_PATH . 'classes/WC_WHQ_Chilexpress_Shipping.php')) {
		include_once WHQ_WCCHP_PLUGIN_PATH . 'classes/WC_WHQ_Chilexpress_Shipping.php';

		add_action( 'plugins_loaded', 'whq_wcchp_init_class' );
		add_action( 'woocommerce_cart_calculate_fees', array('WC_WHQ_Chilexpress_Shipping', 'add_cart_fee') );
	}
}
