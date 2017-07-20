<?php
/**
 * WooCommerce Chilexpress Shipping uninstall
 */

if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$options_leftovers = array(
	'whq_wcchp_incompatible_plugins',
);

foreach ($options_leftovers as $option) {
	delete_option( $option );
}
