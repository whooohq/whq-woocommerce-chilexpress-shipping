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
 * First regions, then location/city
 */
add_filter( 'woocommerce_checkout_fields', 'whq_wcchp_order_checkout_fields' );
function whq_wcchp_order_checkout_fields( $fields ) {
	$fields['billing']['billing_city']['priority']    = 80;
	$fields['shipping']['shipping_city']['priority']  = 80;
	$fields['billing']['billing_state']['priority']   = 70;
	$fields['shipping']['shipping_state']['priority'] = 70;

	return $fields;
}

/**
 * TODO: shipment total in cart, not yet implemented
 */
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'whq_wcchp_disable_shipping_calc_on_cart', 99 );
function whq_wcchp_disable_shipping_calc_on_cart( $show_shipping ) {
	$whq_wcchp_active = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );

	if($whq_wcchp_active == 'yes' && is_cart()) {
		return false;
	}

	return $show_shipping;
}
