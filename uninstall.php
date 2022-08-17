<?php
/**
 * WooCommerce Chilexpress Shipping uninstall
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

global $wpdb;

// Delete plugin's options
$options_leftovers = array(
	'whq_wcchp_incompatible_plugins',
	'whq_wcchp_eolnotice',
);

foreach ( $options_leftovers as $option ) {
	delete_option( $option );
}

// Delete plugin's transients
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE ('\_transient%\_whq_wcchp\_%');" );
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE ('\_transient_timeout%\_whq_wcchp\_%');" );

// EOL schedule
function whq_wcchp_deactivation() {
	wp_clear_scheduled_hook( 'whq_wccchp_eolnotice_weekly_cleanup' );
}

register_deactivation_hook( __FILE__, 'whq_wcchp_deactivation' );
