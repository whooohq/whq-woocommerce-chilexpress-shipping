<?php
if (!defined('ABSPATH')) {
	die();
}

function whq_wcchp_call_soap($ns, $url, $route, $method, $data = '') {
	global $whq_wcchp_default;

	// SOAP login & password
	$soap_login    = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_login' );
	$soap_password = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_password' );

	if( empty( $soap_login ) || false === $soap_login ) {
		$soap_login    = $whq_wcchp_default['chilexpress_soap_login'];
	}

	if( empty( $soap_password ) || false === $soap_password ) {
		$soap_password = $whq_wcchp_default['chilexpress_soap_pass'];
	}

	// Transient duration
	if( ( $route == 'ConsultarRegiones' && $method == 'reqObtenerRegion' && $data == '' ) || ( $route == 'ConsultarCoberturas' && $method == 'reqObtenerCobertura' ) ) {
		$locations_cache = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'locations_cache' );

		if( false === $locations_cache ) {
			$locations_cache = 7; // Days
		} else {
			$locations_cache = absint( $locations_cache ); // Days
		}

		// No less than a week and more than two months
		if( $locations_cache < 7 || $locations_cache > 60) {
			$locations_cache = 7;
		}

		// Days to hours
		$locations_cache = $locations_cache * 24;

		$transient_duration = 60 * 60 * $locations_cache; //Hours
	} else {
		$transient_duration = HOUR_IN_SECONDS; //One hour
	}

	// Transient ID
	$transient_id = 'whq_wcchp_' . $route . '_' . $method . '_' . md5( json_encode( $data ) ); // https://stackoverflow.com/a/7723730/920648

	if ( false === ( $result = get_transient( $transient_id ) ) ) {

		try {
			$client_options = array(
				'login'    => $soap_login,
				'password' => $soap_password
			);

			$client = new SoapClient( $url, $client_options );

			$header_body = array(
				'transaccion' => array(
					'fechaHora'            => date( 'Y-m-d\TH:i:s.Z\Z', time() ),
					'idTransaccionNegocio' => '0',
					'sistema'              => 'TEST',
					'usuario'              => 'TEST'
				)
			);
			$header = new SOAPHeader( $ns, 'headerRequest', $header_body );

			$client->__setSoapHeaders( $header );
			$result = $client->__soapCall( $route, [ $route => [ $method => $data ] ] );

			if ( is_soap_fault( $result ) ) {
				return false;
			}

			//List of cities down
			if( isset( $result->respObtenerCobertura->CodEstado ) && $result->respObtenerCobertura->CodEstado === -1 ) {
				return false;
			}

			if( ! empty( $result ) && false !== $result ) {
				set_transient( $transient_id, $result, $transient_duration );
			}

			return $result;
		} catch( SoapFault $e ) {
			return false;
		}

	}

	return $result;
}

function whq_wcchp_get_tarification($destination, $origin, $weight, $length, $width, $height) {
	global $whq_wcchp_default;

	$soap_api_enviroment = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_api_enviroment' );

	if ( empty( $soap_api_enviroment ) ) {
		$soap_api_enviroment = 'QA';
	}

	$url 	= $whq_wcchp_default['chilexpress_soap_wsdl_' . $soap_api_enviroment] . '/TarificarCourier?wsdl';
	$ns     = $whq_wcchp_default['chilexpress_url'] . '/TarificaCourier/';
	$route  = 'TarificarCourier';
	$method = 'reqValorizarCourier';
	$parameters = [ 'CodCoberturaOrigen' => $origin,
	'CodCoberturaDestino'                => $destination,
	'PesoPza'                            => $weight,
	'DimAltoPza'                         => $length,
	'DimAnchoPza'                        => $width,
	'DimLargoPza'                        => $height ];

	return whq_wcchp_call_soap($ns, $url, $route, $method, $parameters);
}
