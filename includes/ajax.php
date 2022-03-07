<?php
if (!defined('ABSPATH')) {
	die();
}

/**
 * Retrieve regions from Chilexpress API
 */
add_action( 'wp_ajax_whq_wcchp_regions_ajax', 'whq_wcchp_regions_ajax' );
add_action( 'wp_ajax_nopriv_whq_wcchp_regions_ajax', 'whq_wcchp_regions_ajax' );
function whq_wcchp_regions_ajax() {
	global $whq_wcchp_default;

	//$soap_api_enviroment = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_api_enviroment' );
	$soap_api_enviroment = whq_get_chilexpress_option( 'soap_api_enviroment' );

	if ( empty( $soap_api_enviroment ) ) {
		$soap_api_enviroment = 'QA';
	}

	if ( WC_WHQ_Chilexpress_Shipping::soap_or_rest() ) {
		$url 	= $whq_wcchp_default['chilexpress_soap_wsdl_' . $soap_api_enviroment] . '/GeoReferencia?wsdl';
		$ns     = $whq_wcchp_default['chilexpress_url'] . '/CorpGR/';
		$route  = 'ConsultarRegiones';
		$method = 'reqObtenerRegion';

		$regions = whq_wcchp_call_soap($ns, $url, $route, $method);

		if( false !== $regions ) {
			$regions = $regions->respObtenerRegion->Regiones;

			whq_wcchp_array_move( $regions, 14, 0 );
			whq_wcchp_array_move( $regions, 10, 6 );
			whq_wcchp_array_move( $regions, 14, 11 );
		}
	} else {
		$regions = whq_wcchp_get_rest_regions();
	}

	if( false === $regions || NULL === $regions ) {
		wp_send_json_error( $regions );
	} else {
		wp_send_json_success( $regions );
	}
}

/**
 * Retrieve Cities from Chilexpress API
 */
add_action( 'wp_ajax_whq_wcchp_cities_ajax', 'whq_wcchp_cities_ajax' );
add_action( 'wp_ajax_nopriv_whq_wcchp_cities_ajax', 'whq_wcchp_cities_ajax' );
function whq_wcchp_cities_ajax() {
	global $whq_wcchp_default;

	//$soap_api_enviroment = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_api_enviroment' );
	$soap_api_enviroment = whq_get_chilexpress_option( 'soap_api_enviroment' );

	if ( empty( $soap_api_enviroment ) ) {
		$soap_api_enviroment = 'QA';
	}

	$codregion        = sanitize_text_field( $_POST['codregion'] );
	$codtipocobertura = (int) absint( $_POST['codtipocobertura'] );

	if ( WC_WHQ_Chilexpress_Shipping::soap_or_rest() ) {
		$url    = $whq_wcchp_default['chilexpress_soap_wsdl_' . $soap_api_enviroment] . '/GeoReferencia?wsdl';
		$ns     = $whq_wcchp_default['chilexpress_url'] . '/CorpGR/';
		$route  = 'ConsultarCoberturas';
		$method = 'reqObtenerCobertura';

		$parameters       = [ 'CodRegion'        => $codregion,
							  'CodTipoCobertura' => $codtipocobertura ];

		$cities = whq_wcchp_call_soap($ns, $url, $route, $method, $parameters);
		//$cities = false; //Simulate API down

		if( false !== $cities ) {
			$cities = $cities->respObtenerCobertura->Coberturas;

			whq_wcchp_array_move($cities, 2, 86);
		}
	} else {
		$cities = whq_wcchp_get_rest_comunas($codregion, $codtipocobertura);
	}

	if( false === $cities || NULL === $cities || (is_string($cities) && substr($cities,0,5) == 'Error') ) {
		$cities = new WC_WHQ_States_Cities_CL();

		if( false !== $cities && ! empty( $cities ) ) {
			wp_send_json_success( $cities->delivery );
		} else {
			wp_send_json_error( $cities );
		}
	} else {
		wp_send_json_success( $cities );
	}
}

/**
 * Incompatible Plugin dismiss admin notice
 */
add_action( 'wp_ajax_whq_wcchp_incompatible_plugins_dismiss_ajax', 'whq_wcchp_dismiss_admin_notice' );
function whq_wcchp_dismiss_admin_notice() {
	update_option( 'whq_wcchp_incompatible_plugins', 'dismissed', 'no' );

	echo '1';
	die();
}

/**
 * Disable Shipping Zones support
 */
add_action( 'wp_ajax_whq_wcchp_disable_shipping_zones_support_ajax', 'whq_wcchp_disable_shipping_zones_support' );
function whq_wcchp_disable_shipping_zones_support() {
	global $wpdb;

	$instance_id = absint( $_POST['instance_id'] );

	$wcchp_options                           = get_option( 'woocommerce_chilexpress_settings' );
	$wcchp_options['shipping_zones_support'] = 'no';
	update_option( 'woocommerce_chilexpress_settings', $wcchp_options );

	$zone = new WC_Shipping_Zone();
	$zone->delete_shipping_method( $instance_id );

	$wpdb->delete( $wpdb->prefix . 'woocommerce_shipping_zone_methods', array( 'instance_id' => $instance_id ) );

	delete_option( 'woocommerce_chilexpress_' . $instance_id . '_settings' );

	echo '1';
	die();
}
