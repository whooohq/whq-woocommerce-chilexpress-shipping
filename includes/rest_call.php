<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// TODO: swap cURL calls for wp_remote_get / wp_remote_post

function whq_wcchp_get_rest_regions() {
	global $whq_wcchp_default;

	$locations_cache    = whq_get_chilexpress_option( 'locations_cache' );
	$transient_duration = 60 * 60 * $locations_cache; // Hours
	$transient_version  = trim( str_ireplace( '.', '', $whq_wcchp_default['plugin_version'] ) );
	$transient_version  = $transient_version . '_' . md5( 'whq_wcchp_get_rest_regions' );

	write_log( '>>>> Entering REST whq_wcchp_get_rest_regions' );

	$rest_api_key_cobertura = whq_get_chilexpress_option( 'rest_api_key_cobertura' );
	$remote_url             = '';
	$environment            = whq_get_chilexpress_option( 'soap_api_enviroment' );

	if ( $environment == 'QA' ) {
		$remote_url = $whq_wcchp_default[ 'chilexpress_rest_regiones_QA' ];
	} else {
		$remote_url = $whq_wcchp_default[ 'chilexpress_rest_regiones_PROD' ];
	}

	write_log( 'Call REST API: ' . $remote_url );

	// Transient ID
	$transient_id = 'whq_wcchp_get_rest_regions_' . $transient_version; //

	if ( false === ( $result = get_transient( $transient_id ) ) ) {
		$args = array(
			'headers' => array(
				'Content-Type' => 'application/json',
			)
		);

		$result = wp_remote_get( $remote_url, $args );

		if ( ! is_array( $result ) && is_wp_error( $result ) ) {
			write_log( 'Error calling Chilexpress Rest API: ' . $result->get_error_message() );
			write_log( $result );

			delete_transient( $transient_id );

			return 'Error Calling Chilexpress Rest API: ' . $result->get_error_message();
		}

		$result = $result['body'];

		set_transient( $transient_id, $result, $transient_duration );
	}

	write_log( $result );

	$resultObj = json_decode( $result );

	if ( ! empty( $resultObj ) ) {
		write_log( 'Received answer from API' );

		if ( empty( $resultObj->errors ) ) {
			write_log( 'No errors so far' );

			if ( $resultObj->statusDescription == 'Extraccion exitosa' ) {
				write_log( 'Received valid answer for whq_wcchp_get_rest_regions' );

				$regiones = $resultObj->regions;

				return $regiones;
			} else {
				delete_transient( $transient_id );

				return 'Error calling Chilexpress Rest API: '. $resultObj->statusDescription;
			}
		}
	}

}

function whq_wcchp_get_rest_comunas( $codRegion, $codTipoCobertura ) {
	global $whq_wcchp_default;

	write_log( '>>>> Entering REST whq_wcchp_get_rest_comunas: ' . $codRegion );

	$locations_cache    = whq_get_chilexpress_option( 'locations_cache' );
	$transient_duration = 60 * 60 * $locations_cache; // Hours
	$transient_version  = trim( str_ireplace( '.', '', $whq_wcchp_default['plugin_version'] ) );
	$transient_version  = $transient_version . '_' . md5( $codRegion );

	$rest_api_key_cobertura = whq_get_chilexpress_option( 'rest_api_key_cobertura' );
	$environment            = whq_get_chilexpress_option( 'soap_api_enviroment' );

	if ( $environment == 'QA' ) {
		$remote_url = $whq_wcchp_default['chilexpress_rest_comunas_QA'];
	} else {
		$remote_url = $whq_wcchp_default['chilexpress_rest_comunas_PROD'];
	}

	$remote_url = str_replace( 'ReplaceRegionCode', $codRegion, $remote_url );
	write_log( 'Call REST API: ' . $remote_url );

	// Transient ID
	$transient_id = 'whq_wcchp_get_rest_comunas_' . $transient_version; //

	if ( false === ( $result = get_transient( $transient_id ) ) ) {
		$args = array(
			'headers' => array(
				'Content-Type' => 'application/json',
			)
		);

		$result = wp_remote_get( $remote_url, $args );

		if ( ! is_array( $result ) && is_wp_error( $result ) ) {
			write_log( 'Error calling Chilexpress Rest API: ' . $result->get_error_message() );
			write_log( $result );

			delete_transient( $transient_id );

			return 'Error Calling Chilexpress Rest API: ' . $result->get_error_message();
		}

		$result = $result['body'];

		set_transient( $transient_id, $result, $transient_duration );
	}

	write_log( $result );
	$resultObj = json_decode( $result );

	if ( ! empty( $resultObj ) ) {
		write_log( 'Received answer from the API' );

		if ( empty( $resultObj->errors ) ) {
			write_log( 'No errors so far' );

			if ( $resultObj->statusDescription == 'Extraccion exitosa' ) {
				write_log( 'Received valid answer for whq_wcchp_get_rest_comunas' );

				$comunas = $resultObj->coverageAreas;

				foreach ( $comunas as $comuna ) {
					$comuna->GlsComuna = $comuna->coverageName;
					$comuna->CodComuna = $comuna->countyCode;
				}

				return $comunas;
			} else {
				delete_transient( $transient_id );

				return 'Error calling Chilexpress Rest API: '. $resultObj->statusDescription;
			}
		}
	}
}

function whq_wcchp_get_rest_tarification( $destination, $origin, $weight, $length, $width, $height ) {
	global $whq_wcchp_default;

	write_log( '>>>> Entering REST whq_wcchp_get_rest_tarification' );

	$locations_cache    = whq_get_chilexpress_option( 'locations_cache' );
	$transient_duration = HOUR_IN_SECONDS; // One hour
	$transient_version  = trim( str_ireplace( '.', '', $whq_wcchp_default['plugin_version'] ) );
	$transient_version  = $transient_version . '_' . md5( $destination . $origin . $weight . $length . $width . $height );

	$environment = whq_get_chilexpress_option( 'soap_api_enviroment' );

	if ( $environment == 'QA' ) {
		$qa  = true;
		$remote_url = $whq_wcchp_default['chilexpress_rest_cotizacion_QA'];
		$rest_api_key_tarificacion = whq_get_chilexpress_option( 'rest_api_key_tarificacion_qa' );
	} else {
		$qa  = false;
		$remote_url = $whq_wcchp_default['chilexpress_rest_cotizacion_PROD'];
		$rest_api_key_tarificacion = whq_get_chilexpress_option( 'rest_api_key_tarificacion_prod' );
	}

	write_log( 'Call REST API: ' . $remote_url );

	// Transient ID
	$transient_id = 'whq_wcchp_get_rest_tarification_' . $transient_version; //

	if ( false === ( $result = get_transient( $transient_id ) ) ) {
		$body = array(
			'originCountyCode'      => $origin,
			'destinationCountyCode' => $destination,
			'package' => array(
				'weight' => $weight,
				'height' => $height,
				'width'  => $width,
				'length' => $length
			),
			'productType'   => 3,
			'contentType'   => 1,
			'declaredWorth' => 0,
			'deliveryTime'  => 0
		);

		$args = array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'Ocp-Apim-Subscription-Key' => $rest_api_key_tarificacion,
			),
			'body' => $body,
		);

		$result = wp_remote_get( $remote_url, $args );

		if ( ! is_array( $result ) && is_wp_error( $result ) ) {
			write_log( 'Error calling Chilexpress Rest API: ' . $result->get_error_message() );
			write_log( $result );

			delete_transient( $transient_id );

			return 'Error Calling Chilexpress Rest API: ' . $result->get_error_message();
		}

		write_log( $headers );
		write_log( $body );

		$result = $result['body'];

		set_transient( $transient_id, $result, $transient_duration );
	}

	write_log( $result );

	$resultObj = json_decode( $result );

	if ( ! empty( $resultObj ) ) {
		write_log('Received answer from API');

		if ( empty( $resultObj->errors ) ) {
			write_log('No errors so far');

			if ( $resultObj->statusDescription == 'OK' ) {
				write_log( 'Received valid answer for whq_wcchp_get_rest_tarification' );

				$tarifas = $resultObj->data->courierServiceOptions;

				return $tarifas;
			} else {
				delete_transient( $transient_id );

				return 'Error calling Chilexpress Rest API: '. $resultObj->statusDescription;
			}
		}
	}
}
