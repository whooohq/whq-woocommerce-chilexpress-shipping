<?php
if (!defined('ABSPATH')) {
	die();
}

register_activation_hook( $whq_wcchp_default['plugin_file'], 'whq_wcchp_plugin_install' );
function whq_wcchp_plugin_install() {
	whq_wcchp_check_requirements();
}

function whq_wcchp_check_requirements() {
	//No SOAP enabled?
	if ( ! extension_loaded('soap') ) {
		deactivate_plugins( plugin_basename( $whq_wcchp_default['plugin_file'] ) );
		wp_die( 'SOAP es requerido para poder conectarse a Chilexpress. Por favor, <a href="http://php.net/manual/en/book.soap.php">activa SOAP en tu servidor</a>.' );
	}
}
