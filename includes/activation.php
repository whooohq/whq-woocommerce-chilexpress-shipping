<?php
if (!defined('ABSPATH')) {
	die();
}

register_activation_hook( $whq_wcchp_default['plugin_file'], 'whq_wcchp_plugin_install' );
function whq_wcchp_plugin_install() {
	whq_wcchp_check_requirements();
}

function whq_wcchp_check_requirements() {
	global $whq_wcchp_default;

	//PHP minimum version
	if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) OR version_compare( phpversion(), '5.6', '<' ) ) {
		deactivate_plugins( plugin_basename( $whq_wcchp_default['plugin_file'] ) );

		wp_die( 'Actualmente estás corriendo PHP versión ' . phpversion() . '. Nuestro plugin requiere PHP 5.6 o superior, como recomienda <a href="https://wordpress.org/about/requirements/">WordPress</a>. Versiones de PHP inferiores a la 5.6 <a href="http://php.net/supported-versions.php">no son soportadas actualmente</a>, y constituyen un potencial problema de seguridad.<br />Te comendamos actualizar tu versión de PHP.' );
	}

	//WordPress minimum version
	if ( ! version_compare( get_bloginfo( 'version' ), '4.4', '>=' ) ) {
		deactivate_plugins( plugin_basename( $whq_wcchp_default['plugin_file'] ) );

		wp_die( 'El plugin, al igual que WooCommerce, requiere WordPress 4.4 o superior para funcionar. Por favor, actualiza tu versión de WordPress.' );
	}

	//No SOAP enabled?
	if ( ! extension_loaded('soap') ) {
		deactivate_plugins( plugin_basename( $whq_wcchp_default['plugin_file'] ) );

		wp_die( 'El plugin requiere la extensión SOAP para poder conectarse a Chilexpress. Por favor, <a href="http://php.net/manual/en/book.soap.php">activa SOAP en tu servidor</a>.' );
	}
}
