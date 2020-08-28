<?php
if (!defined('ABSPATH')) {
	die();
}

add_filter( 'plugin_action_links_' . $whq_wcchp_default['plugin_basename'], 'whq_wcchp_add_action_links' );
function whq_wcchp_add_action_links ( $links ) {
	// TODO: fix link when converted to use WC's Shipping Zones
	$whq_wcchp_link = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=chilexpress' ) . '">Ajustes</a>',
	);

	return array_merge( $links, $whq_wcchp_link );
}

/**
 * Incompatible Plugins, admin notice check
 */
add_action( 'admin_init', 'whq_wcchp_incompatible_plugins_check' );
function whq_wcchp_incompatible_plugins_check() {
	global $pagenow;

	$show_notice = false;

	$incompatible_plugins = array(
		'woocommerce-checkout-field-editor/woocommerce-checkout-field-editor.php',
		'woo-checkout-field-editor-pro/checkout-form-designer.php',
		'comunas-de-chile-para-woocommerce/woocoomerce-comunas.php',
		'calculo-de-despacho-via-starken-para-woocommerce/calculo-starken-woocommerce.php',
		'masterbip-woocommerce-regiones-pesos-y-comunas-de-chile/masterbip-woocommerce-regiones-pesos-comunas-chile.php',
	);

	if ( current_user_can( 'activate_plugins' ) && ( ! wp_doing_ajax() ) ) {
		if ( is_array( $incompatible_plugins ) && ! empty( $incompatible_plugins ) ) {
			foreach ($incompatible_plugins as $plugin) {
				if ( is_plugin_active( $plugin ) || is_plugin_active_for_network( $plugin ) ) {
					$show_notice = true;
				}
			}
		}
	}

	if ( true === $show_notice && ( $pagenow == 'plugins.php' || ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'wc-settings' ) && ( isset( $_REQUEST['section'] ) && $_REQUEST['section'] == 'chilexpress' ) ) ) {
		add_action( 'admin_notices', 'whq_wcchp_incompatible_plugins' );
	}
}

/**
 * Incompatible Plugins, admin notice
 */
function whq_wcchp_incompatible_plugins() {
	global $whq_wcchp_default;

	//PHP minimum version
	if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) OR version_compare( phpversion(), '5.6', '<' ) ) {
		?>
		<div class="notice error is-dismissible whq_wcchp_incompatible_plugins">
			<p>Actualmente estás corriendo PHP versión <?php echo phpversion(); ?>. Nuestro plugin requiere PHP 5.6 o superior, como recomienda <a href="https://wordpress.org/about/requirements/">WordPress</a>. Versiones de PHP inferiores a la 5.6 <a href="http://php.net/supported-versions.php">no son soportadas actualmente</a>, y constituyen un potencial problema de seguridad.<br />Te comendamos actualizar tu versión de PHP.</p>
		</div>
		<?php

		deactivate_plugins( plugin_basename( $whq_wcchp_default['plugin_file'] ) );

		return;
	}

	if( empty( get_option( 'whq_wcchp_incompatible_plugins' ) ) ) {
	?>
		<div class="notice error is-dismissible whq_wcchp_incompatible_plugins">
			<p>Hemos detectado al menos un plugin con incompatibilidad (parcial o total) conocida, que puede afectar el funcionamiento del sistema de envíos de Chilexpress para WooCommerce.<br/>Por favor, <a href="https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/18">revisa el listado</a> antes de reportar un bug en el sistema de envíos a través de Chilexpress.</p>
		</div>
	<?php
	}
}

/**
 * Incompatible Plugins, hook WordPres plugins upgrade process to delete our option flag
 *
 * @param $upgrader_object Array
 * @param $options Array
 */
add_action( 'upgrader_process_complete', 'whq_wcchp_wp_upgrade_completed', 10, 2 );
function whq_wcchp_wp_upgrade_completed( $upgrader_object, $options ) {
	global $whq_wcchp_default, $wpdb;

	//If an update is made, and it's for plugins, then...
	if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {

		if( is_array( $options['plugins'] ) ) {
			foreach( $options['plugins'] as $plugin ) {
				if( $plugin == $whq_wcchp_default['plugin_basename'] ) {
					//It's our plugin, do some cleanup
					delete_option( 'whq_wcchp_incompatible_plugins' );

					//Clear our transients in case a change on cached data was made
					$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE ('\_transient%\_whq_wcchp\_%');" );
					$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE ('\_transient_timeout%\_whq_wcchp\_%');" );
				}
			}
		}

	}
}

/**
 * Incompatible Plugins, on any plugin activation, delete our option flag
 */
function whq_wcchp_detect_plugin_activation( $plugin, $network_activation ) {
	//To know if an incompatible plugin is installed and activated, and warn the user
	delete_option( 'whq_wcchp_incompatible_plugins' );
}
add_action( 'activated_plugin', 'whq_wcchp_detect_plugin_activation', 10, 2 );

/**
 * Fix for some incompatible plugins (just the ones that can be fixed from here)
 */
function whq_wcchp_remove_incompatible_actions_and_filters() {
	// Fix for https://wordpress.org/plugins/woocommerce-chilean-peso-currency/
	if ( function_exists( 'ctala_install_cleancache' ) ) {
		$plugin = 'woocommerce-chilean-peso-currency/woocommerce-chilean-peso.php';

		//Make sure that woocommerce-chilean-peso-currency plugin is active
		if ( ! is_multisite() && ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		} elseif ( is_multisite() && ! function_exists( 'is_plugin_active_for_network') ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if( ! is_multisite() && is_plugin_active( $plugin ) ) {
			remove_filter( 'woocommerce_states', 'custom_woocommerce_states' );
		} elseif ( is_multisite() && is_plugin_active_for_network( $plugin ) ) {
			remove_filter( 'woocommerce_states', 'custom_woocommerce_states' );
		}
	}
}
add_action( 'init', 'whq_wcchp_remove_incompatible_actions_and_filters', 20 );
