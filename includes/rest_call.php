<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

function whq_wcchp_get_rest_regions(){
	write_log('>>>> entering REST whq_wcchp_get_rest_regions');
	global $whq_wcchp_default;

	$rest_api_key_cobertura = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'rest_api_key_cobertura' );
	$url='';
	$environment=WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_api_enviroment' );
	if ($environment=='QA'){
		$url=$whq_wcchp_default['chilexpress_rest_regiones_QA'];
	} else {
		$url=$whq_wcchp_default['chilexpress_rest_regiones_PROD'];
	}
	write_log('Call REST API: '.$url);
	$curl = curl_init();

	//echo $subskey.'<br>';
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Cache-Control:no-cache'));
	curl_setopt($curl, CURLOPT_URL, $url);
	//echo $url.'<br>';
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	//curl_setopt($curl,CURLOPT_HEADER,1);
	curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
	//var_dump($curl);die();

	$resultJSON = curl_exec($curl);
	//echo '<br>';
	//    var_dump($curl);
	//    echo '<br>Header: ';
	//var_dump(curl_getinfo($ch));

	if (curl_errno($curl)) {
		write_log('Error Calling Chilexpress Rest API: '.curl_error($curl));
		write_log($resultJSON);
		curl_close($curl);
		return 'Error Calling Chilexpress Rest API: '.curl_error($curl);
	} else {
		curl_close($curl);
		write_log ($resultJSON);
		$resultObj=json_decode($resultJSON);
		if (!empty($resultObj) ) {
			write_log('Thank you very much... I received an an answer from the API');

			if (empty($resultObj->errors)) {
				write_log('Good no errors so far');

				if ($resultObj->statusDescription == "Extraccion exitosa") {
					write_log('I received a valid answer for whq_wcchp_get_rest_regions');

					$regiones=$resultObj->regions;
					return $regiones;
				} else {
					return 'Error Calling Chilexpress Rest API: '. $resultObj->statusDescription;
				}
			}
		}
	}
}

function whq_wcchp_get_rest_comunas($codRegion, $codTipoCobertura){
	write_log('>>>> entering whq_wcchp_get_rest_comunas: '.$codRegion);
	global $whq_wcchp_default;

	$rest_api_key_cobertura = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'rest_api_key_cobertura' );
	$environment=WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_api_enviroment' );
	if ($environment=='QA'){
		$url=$whq_wcchp_default['chilexpress_rest_comunas_QA'];
	} else {
		$url=$whq_wcchp_default['chilexpress_rest_comunas_PROD'];
	}
	$url=str_replace('ReplaceRegionCode',$codRegion,$url);
	write_log('Call REST API: '.$url);
	$curl = curl_init();

	//echo $subskey.'<br>';
	$headers = array(
		'Content-type: application/json',
		'Cache-Control: no-cache',
	);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_URL, $url);
	//echo $url.'<br>';
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	//curl_setopt($curl,CURLOPT_HEADER,1);
	curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
	//var_dump($curl);die();

	$resultJSON = curl_exec($curl);
	//echo '<br>';
	//    var_dump($curl);
	//    echo '<br>Header: ';
	//var_dump(curl_getinfo($ch));

	if (curl_errno($curl)) {
		write_log('Error Calling Chilexpress Rest API: '.curl_error($curl));
		write_log($resultJSON);
		curl_close($curl);
		return 'Error Calling Chilexpress Rest API: '.curl_error($curl);
	} else {
		curl_close($curl);
		write_log ($resultJSON);
		$resultObj=json_decode($resultJSON);
		if (!empty($resultObj) ) {
			write_log('Thank you very much... I received an an answer from the API');

			if (empty($resultObj->errors)) {
				write_log('Good no errors so far');

				if ($resultObj->statusDescription == "Extraccion exitosa") {
					write_log('I received a valid answer for whq_wcchp_get_rest_comunas');

					$comunas=$resultObj->coverageAreas;
					foreach ($comunas as $comuna){
						$comuna->GlsComuna=$comuna->coverageName;
						$comuna->CodComuna=$comuna->countyCode;
					}
					return $comunas;
				} else {
					return 'Error Calling Chilexpress Rest API: '. $resultObj->statusDescription;
				}
			}
		}
	}
}

function whq_wcchp_get_rest_tarification( $destination, $origin, $weight, $length, $width, $height){
	write_log('>>>> entering REST whq_wcchp_get_rest_tarification');
	global $whq_wcchp_default;

	$environment=WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_api_enviroment' );
	if ($environment=='QA'){
		$qa=true;
		$url=$whq_wcchp_default['chilexpress_rest_cotizacion_QA'];
	} else {
		$qa=false;
		$url=$whq_wcchp_default['chilexpress_rest_cotizacion_PROD'];
	}
	write_log('Call REST API: '.$url);

	$curl = curl_init();

	//POST
	curl_setopt($curl, CURLOPT_POST, 1);

	$body='{
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
	curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

	if ($qa){
		$rest_api_key_tarificacion = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'rest_api_key_tarificacion_qa' );
	} else {
		$rest_api_key_tarificacion = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'rest_api_key_tarificacion_prod' );
	}

	//echo $subskey.'<br>';
	$headers = array(
		'Content-type: application/json',
		'Cache-Control: no-cache',
		'Ocp-Apim-Subscription-Key: '.$rest_api_key_tarificacion,
	);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_URL, $url);
	//echo $url.'<br>';
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	//curl_setopt($curl,CURLOPT_HEADER,1);
	curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
	//var_dump($curl);die();

	write_log($headers);
	write_log($body);
	$resultJSON = curl_exec($curl);
	//echo '<br>';
	//    var_dump($curl);
	//    echo '<br>Header: ';
	//var_dump(curl_getinfo($ch));

	if (curl_errno($curl)) {
		write_log('Error Calling Chilexpress Rest API: '.curl_error($curl));
		write_log($resultJSON);
		curl_close($curl);
		return 'Error Calling Chilexpress Rest API: '.curl_error($curl);
	} else {
		curl_close($curl);
		write_log ($resultJSON);
		$resultObj=json_decode($resultJSON);
		if (!empty($resultObj) ) {
			write_log('Thank you very much... I received an an answer from the API');

			if (empty($resultObj->errors)) {
				write_log('Good no errors so far');

				if ($resultObj->statusDescription == "OK") {
					write_log('I received a valid answer for whq_wcchp_get_rest_tarification');

					$tarifas=$resultObj->data->courierServiceOptions;
					return $tarifas;
				} else {
					return 'Error Calling Chilexpress Rest API: '. $resultObj->statusDescription;
				}
			}
		}
	}
}
