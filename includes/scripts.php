<?php
if (!defined('ABSPATH')) {
	die();
}

/**
 * Scripts enqueue
 */
add_action( 'wp_enqueue_scripts', 'whq_wcchp_enqueue_scripts' );
function whq_wcchp_enqueue_scripts() {
	global $whq_wcchp_default;

	$whq_wcchp_active = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );

	if( $whq_wcchp_active === false ) {
		$whq_wcchp_active == 'no';
	}

	if( $whq_wcchp_active == 'yes' ) {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( is_cart() ) {
			wp_enqueue_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full' . $suffix . '.js', array( 'jquery' ), '4.0.3' );
			wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css' );

			wp_enqueue_script( 'whq_wcchp_cart', $whq_wcchp_default['plugin_url'] . 'assets/js/whq_wcchp_cart.js', array('jquery', 'woocommerce', 'jquery-blockui', 'select2'), $whq_wcchp_default['plugin_version'], true );
		}

		if( is_checkout() ) {
			wp_enqueue_script( 'whq_wcchp_checkout', $whq_wcchp_default['plugin_url'] . 'assets/js/whq_wcchp_checkout.js', array('jquery', 'woocommerce', 'jquery-blockui', 'select2'), $whq_wcchp_default['plugin_version'], true );
		}
	}
}
