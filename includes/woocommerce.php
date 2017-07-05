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
 * TODO: shipment total in cart, not yet implemented
 */
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'whq_wcchp_disable_shipping_calc_on_cart', 99 );
function whq_wcchp_disable_shipping_calc_on_cart( $show_shipping ) {
	$whq_wcchp_active          = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );
	$whq_wcchp_cart_calculator = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'hide_cart_shipping_calculator' );

	if($whq_wcchp_active == 'yes' && is_cart() && $whq_wcchp_cart_calculator == 'yes') {
		return false;
	}

	return $show_shipping;
}
