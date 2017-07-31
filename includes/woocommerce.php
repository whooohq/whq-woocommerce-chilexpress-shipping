<?php
if (!defined('ABSPATH')) {
	die();
}

/**
 * Add our new shipping method
 */
add_filter( 'woocommerce_shipping_methods', 'whq_wcchp_add_shipping_method' );
function whq_wcchp_add_shipping_method( $methods ) {
	$methods['chilexpress'] = 'WC_WHQ_Chilexpress_Shipping';

	return $methods;
}

/**
 * Cart calculator, add city
 */
add_action( 'woocommerce_init', 'whq_wcchp_cart_enable_city', 10, 1 );
function whq_wcchp_cart_enable_city( $array ) {
	$whq_wcchp_active = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );

	if( false === $whq_wcchp_active ) {
		$whq_wcchp_active == 'no';
	}

	if( $whq_wcchp_active == 'yes' ) {
		add_filter( 'woocommerce_shipping_calculator_enable_city', '__return_true' );
	}
}
