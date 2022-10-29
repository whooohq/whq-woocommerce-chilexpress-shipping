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
	$url                    = '';
	$environment            = whq_get_chilexpress_option( 'soap_api_enviroment' );

	if ( $environment == 'QA' ) {
		$url = $whq_wcchp_default[ 'chilexpress_rest_regiones_QA' ];
	} else {
		$url = $whq_wcchp_default[ 'chilexpress_rest_regiones_PROD' ];
	}

	write_log( 'Call REST API: ' . $url );

	// Transient ID
	$transient_id = 'whq_wcchp_get_rest_regions_' . $transient_version; //

	if ( false === ( $result = get_transient( $transient_id ) ) ) {
		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Cache-Control:no-cache') );
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLINFO_HEADER_OUT, 1 );

		$result = curl_exec( $curl );
		curl_close( $curl );

		if ( ! empty( $result ) && false !== $result ) {
			set_transient( $transient_id, $result, $transient_duration );
		}
	}

	if ( isset( $curl ) && curl_errno( $curl ) ) {
		write_log( 'Error calling Chilexpress Rest API: ' . curl_error( $curl ) );
		write_log( $result );

		delete_transient( $transient_id );

		return 'Error Calling Chilexpress Rest API: ' . curl_error( $curl );
	} else {
		write_log( $result );

		$resultObj = json_decode( $result );

		if ( ! empty( $resultObj ) ) {
			write_log( 'Received answer from API' );

			if (empty( $resultObj->errors ) ) {
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
		$url = $whq_wcchp_default['chilexpress_rest_comunas_QA'];
	} else {
		$url = $whq_wcchp_default['chilexpress_rest_comunas_PROD'];
	}

	$url = str_replace( 'ReplaceRegionCode', $codRegion, $url );
	write_log( 'Call REST API: ' . $url );

	// Transient ID
	$transient_id = 'whq_wcchp_get_rest_comunas_' . $transient_version; //

	if ( false === ( $result = get_transient( $transient_id ) ) ) {
		$curl    = curl_init();
		$headers = array(
			'Content-type: application/json',
			'Cache-Control: no-cache',
		);
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLINFO_HEADER_OUT, 1 );

		$result = curl_exec( $curl );
		curl_close($curl);

		if ( ! empty( $result ) && false !== $result ) {
			set_transient( $transient_id, $result, $transient_duration );
		}
	}

	if ( isset( $curl ) && curl_errno( $curl ) ) {
		write_log( 'Error calling Chilexpress Rest API: ' . curl_error( $curl ) );
		write_log( $result );

		delete_transient( $transient_id );

		return 'Error Calling Chilexpress Rest API: ' . curl_error( $curl );
	} else {
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
		$url = $whq_wcchp_default['chilexpress_rest_cotizacion_QA'];
		$rest_api_key_tarificacion = whq_get_chilexpress_option( 'rest_api_key_tarificacion_qa' );
	} else {
		$qa  = false;
		$url = $whq_wcchp_default['chilexpress_rest_cotizacion_PROD'];
		$rest_api_key_tarificacion = whq_get_chilexpress_option( 'rest_api_key_tarificacion_prod' );
	}

	write_log( 'Call REST API: ' . $url );

	// Transient ID
	$transient_id = 'whq_wcchp_get_rest_tarification_' . $transient_version; //

	if ( false === ( $result = get_transient( $transient_id ) ) ) {
		$curl = curl_init();

		$body ='{
		"originCountyCode": "'.$origin.'",
		"destinationCountyCode": "'.$destination.'",
		"package": {
			"weight": "'.$weight.'",
			"height": "'.$height.'",
			"width": "'.$width.'",
			"length": "'.$length.'"
		},
		"productType": 3,
		"contentType": 1,
		"declaredWorth":0,
		"deliveryTime": 0
		}';

		$headers = array(
			'Content-type: application/json',
			'Cache-Control: no-cache',
			'Ocp-Apim-Subscription-Key: '.$rest_api_key_tarificacion,
		);

		curl_setopt( $curl, CURLOPT_POST, 1 );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $body );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLINFO_HEADER_OUT, 1 );

		write_log( $headers );
		write_log( $body );

		$result = curl_exec( $curl );
		curl_close($curl);

		if ( ! empty( $result ) && false !== $result ) {
			set_transient( $transient_id, $result, $transient_duration );
		}
	}

	if ( isset( $curl ) && curl_errno( $curl ) ) {
		write_log( 'Error calling Chilexpress Rest API: ' . curl_error( $curl ) );
		write_log( $result );

		delete_transient( $transient_id );

		return 'Error Calling Chilexpress Rest API: '.curl_error($curl);
	} else {
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
}
