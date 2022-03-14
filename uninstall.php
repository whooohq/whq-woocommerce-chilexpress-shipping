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
);

foreach ( $options_leftovers as $option ) {
	delete_option( $option );
}

// Delete plugin's transients
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE ('\_transient%\_whq_wcchp\_%');" );
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE ('\_transient_timeout%\_whq_wcchp\_%');" );
