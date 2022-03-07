<?php
/**
 * Plugin Name: Chilexpress Shipping for WooCommerce
 * Plugin URI: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping
 * Description: Método de envío por Chilexpress para WooCommerce, con sistema de cálculo de envíos automático utilizando la API de Chilexpress
 * Version: 1.5.0
 * Author: Whooo & contributors
 * Author URI: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/graphs/contributors
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Text Domain: whq-wcchp
 *
 * WC requires at least: 5.0.0
 * WC tested up to: 6.0.0
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

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	$whq_wcchp_woocommerce_active = true;
} else {
	$whq_wcchp_woocommerce_active = false;
}

if ( true === $whq_wcchp_woocommerce_active ) {
	$whq_wcchp_default = array(
		'plugin_version'                   => '1.5.0',
		'plugin_file'                      => __FILE__,
		'plugin_basename'                  => plugin_basename( __FILE__ ),
		'plugin_path'                      => trailingslashit( plugin_dir_path( __FILE__ ) ),
		'plugin_url'                       => trailingslashit( plugin_dir_url( __FILE__ ) ),
		'chilexpress_url'                  => 'http://www.chilexpress.cl',
		'chilexpress_soap_login'           => 'UsrTestServicios',
		'chilexpress_soap_pass'            => 'U$$vr2$tS2T',
		'chilexpress_soap_wsdl_QA'         => 'http://testservices.wschilexpress.com',
		'chilexpress_soap_wsdl_PROD'       => 'http://ws.ssichilexpress.cl',
		'chilexpress_rest_regiones_QA'     => 'https://testservices.wschilexpress.com/georeference/api/v1.0/regions',
		'chilexpress_rest_regiones_PROD'   => 'https://services.wschilexpress.com/georeference/api/v1.0/regions',
		'chilexpress_rest_comunas_QA'      => 'https://testservices.wschilexpress.com/georeference/api/v1.0/coverage-areas?RegionCode=ReplaceRegionCode&type=0',
		'chilexpress_rest_comunas_PROD'    => 'https://services.wschilexpress.com/georeference/api/v1.0/coverage-areas?RegionCode=ReplaceRegionCode&type=0',
		'chilexpress_rest_cotizacion_QA'   => 'https://testservices.wschilexpress.com/rating/api/v1.0/rates/courier',
		'chilexpress_rest_cotizacion_PROD' => 'https://services.wschilexpress.com/rating/api/v1.0/rates/courier',
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

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/scripts.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/scripts.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/ajax.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/ajax.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/soap_call.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/soap_call.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/rest_call.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/rest_call.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'classes/WC_WHQ_States_Cities_CL.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'classes/WC_WHQ_States_Cities_CL.php';
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'classes/WC_WHQ_Chilexpress_Shipping.php' )) {
		include_once $whq_wcchp_default['plugin_path'] . 'classes/WC_WHQ_Chilexpress_Shipping.php';

		add_action( 'woocommerce_shipping_init', 'whq_wcchp_init_class' );
		add_action( 'woocommerce_cart_calculate_fees', array( 'WC_WHQ_Chilexpress_Shipping', 'add_cart_fee' ) );
	}

	if ( file_exists( $whq_wcchp_default['plugin_path'] . 'includes/woocommerce.php' ) ) {
		include_once $whq_wcchp_default['plugin_path'] . 'includes/woocommerce.php';
	}
}

