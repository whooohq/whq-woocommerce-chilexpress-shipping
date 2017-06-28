<?php
if (!defined('ABSPATH')) {
	die();
}

/**
 * Scripts enqueue
 */
add_action( 'wp_enqueue_scripts', 'whq_wcchp_enqueue_scripts' );
function whq_wcchp_enqueue_scripts() {
	$whq_wcchp_active = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );

	if($whq_wcchp_active == 'yes') {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_data = get_plugin_data( __FILE__ );

		wp_enqueue_script( 'whq_wcchilexpress', WHQ_WCCHP_PLUGIN_URL . 'assets/js/whq_wcchp_front.js', array('jquery', 'woocommerce'), $plugin_data['Version'], true );
	}
}
