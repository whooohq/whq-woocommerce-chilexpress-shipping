<?php
if ( ! defined( 'ABSPATH' ) ) {
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
	//$whq_wcchp_active = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );
	$whq_wcchp_active = whq_get_chilexpress_option( 'enabled' );

	if ( false === $whq_wcchp_active ) {
		$whq_wcchp_active == 'no';
	}

	if ( $whq_wcchp_active == 'yes' ) {
		add_filter( 'woocommerce_shipping_calculator_enable_city', '__return_true' );
	}
}

/**
 * Prevents order with Chilexpress at zero cost
 */
add_action( 'woocommerce_before_checkout_process', 'whq_wcchp_prevent_chilexpress_zerocost_order' );
function whq_wcchp_prevent_chilexpress_zerocost_order() {
	//$whq_wcchp_active = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );
	$whq_wcchp_active = whq_get_chilexpress_option( 'enabled' );

	if ( false === $whq_wcchp_active ) {
		$whq_wcchp_active == 'no';
	}

	if ( $whq_wcchp_active == 'yes' ) {
		$ship_with_chilexpress = false;

		foreach ( $_POST['shipping_method'] as $value ) {
			if ( stripos( $value, 'chilexpress' ) !== false ) {
				$ship_with_chilexpress = true;
			}
		}

		if( $ship_with_chilexpress === true ) {
			$shipping_total = WC()->cart->shipping_total;

			write_log( 'Order shipping total: ' . $shipping_total );

			if( $shipping_total <= 0 ) {
				//Can't be Chilexpress and zero shipping cost at the same time
				throw new Exception( 'Lo sentimos, Chilexpress no se encuentra disponible en estos momentos. Por favor, seleccione otro método de envío o intenteló mas tarde nuevamente.' );
			}
		}
	}
}

/**
 * Change WC state/city order
 */
add_filter( 'woocommerce_checkout_fields', 'whq_wcchp_order_checkout_fields', 9999 );
function whq_wcchp_order_checkout_fields( $fields ) {
	//$whq_wcchp_active = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );
	$whq_wcchp_active = whq_get_chilexpress_option( 'enabled' );
	//$statecity_switch = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'statecity_switch' );
	$statecity_switch = whq_get_chilexpress_option( 'statecity_switch' );

	if ( false === $whq_wcchp_active ) {
		$whq_wcchp_active == 'no';
	}

	if ( false === $statecity_switch ) {
		$statecity_switch == 'no';
	}

	if ( $whq_wcchp_active == 'yes' && $statecity_switch == 'yes' ) {
		$fields['billing']['billing_city']['priority']    = 80;
		$fields['billing']['billing_state']['priority']   = 70;

		$fields['shipping']['shipping_city']['priority']  = 80;
		$fields['shipping']['shipping_state']['priority'] = 70;
	}

	return $fields;
}

add_filter( 'woocommerce_default_address_fields', 'whq_wcchp_override_default_locale_fields', 9999 );
function whq_wcchp_override_default_locale_fields( $fields ) {
	//$whq_wcchp_active = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );
	$whq_wcchp_active = whq_get_chilexpress_option( 'enabled' );
	//$statecity_switch = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'statecity_switch' );
	$statecity_switch = whq_get_chilexpress_option( 'statecity_switch' );

	if ( false === $whq_wcchp_active ) {
		$whq_wcchp_active == 'no';
	}

	if ( false === $statecity_switch ) {
		$statecity_switch == 'no';
	}

	if ( $whq_wcchp_active == 'yes' && $statecity_switch == 'yes' ) {
		$fields['city']['priority']    = 80;
		$fields['state']['priority']   = 70;
	}

	return $fields;
}
