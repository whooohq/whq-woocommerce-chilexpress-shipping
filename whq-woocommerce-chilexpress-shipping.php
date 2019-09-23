<?php
/**
 * Plugin Name: WooCommerce Chilexpress Shipping
 * Plugin URI: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping
 * Description: Método de envío por Chilexpress para WooCommerce, con sistema de cálculo de envíos automático utilizando la API de Chilexpress
 * Version: 1.4.18
 * Author: Whooo & contributors
 * Author URI: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/graphs/contributors
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Text Domain: whq-wcchp
 *
 * WC requires at least: 3.6.0
 * WC tested up to: 3.7.0
 */

/**
 * Whooo
 * http://whooohq.com
 *
 * Authors:
 * Jhoynerk Caraballo [ https://github.com/jhoynerk ]
 * Esteban Cuevas [ https://github.com/TCattd ]
 *
 * Contributors:
 * https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/graphs/contributors
 */

/**
 * Check if WooCommerce is active
 */
$whq_wcchp_woocommerce_active = false;

if ( ! is_multisite() && ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
} elseif ( is_multisite() && ! function_exists( 'is_plugin_active_for_network') ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! is_multisite() ) {
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		$whq_wcchp_woocommerce_active = true;
	}
} else {
	if ( is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
		$whq_wcchp_woocommerce_active = true;
	}
}

if ( true === $whq_wcchp_woocommerce_active ) {
	$whq_wcchp_default = array(
		'plugin_version'             => '1.4.18',
		'plugin_file'                => __FILE__,
		'plugin_basename'            => plugin_basename(__FILE__),
		'plugin_path'                => trailingslashit( plugin_dir_path(__FILE__) ),
		'plugin_url'                 => trailingslashit( plugin_dir_url(__FILE__) ),
		'chilexpress_url'            => 'http://www.chilexpress.cl',
		'chilexpress_soap_login'     => 'UsrTestServicios',
		'chilexpress_soap_pass'      => 'U$$vr2$tS2T',
		'chilexpress_soap_wsdl_QA'   => 'http://testservices.wschilexpress.com',
		'chilexpress_soap_wsdl_PROD' => 'http://ws.ssichilexpress.cl',
	);

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/helpers.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/helpers.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/activation.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/activation.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/wordpress.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/wordpress.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/woocommerce.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/woocommerce.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/scripts.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/scripts.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/ajax.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/ajax.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/soap_call.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/soap_call.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'classes/WC_WHQ_States_Cities_CL.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'classes/WC_WHQ_States_Cities_CL.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'classes/WC_WHQ_Chilexpress_Shipping.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'classes/WC_WHQ_Chilexpress_Shipping.php';

		add_action( 'plugins_loaded', 'whq_wcchp_init_class' );
		add_action( 'woocommerce_cart_calculate_fees', array('WC_WHQ_Chilexpress_Shipping', 'add_cart_fee') );
	}
}
