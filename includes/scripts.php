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

	if($whq_wcchp_active == 'yes') {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_data = get_plugin_data( __FILE__ );

		wp_enqueue_script( 'whq_wcchilexpress', $whq_wcchp_default['plugin_url'] . 'assets/js/whq_wcchp_front.js', array('jquery', 'woocommerce', 'jquery-blockui', 'select2'), $plugin_data['Version'], true );
	}
}
