<?php
if (!defined('ABSPATH')) {
	die();
}

function whq_wcchp_call_soap($ns, $url, $route, $method, $data = '') {
	//$soap_login    = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_login' );
	$soap_login    = WHQ_WCCHP_CHILEXPRESS_SOAP_USER;
	//$soap_password = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'soap_password' );
	$soap_password = WHQ_WCCHP_CHILEXPRESS_SOAP_PASS;

	try {
		$client_options = array(
			'login'    => $soap_login,
			'password' => $soap_password
		);
		$client = new SoapClient($url, $client_options);

		$time_now = date( 'Y-m-d\TH:i:s.Z\Z', time() );
		$header_body = array(
			'transaccion' => array(
				'fechaHora'            => $time_now,
				'idTransaccionNegocio' => '0',
				'sistema'              => 'TEST',
				'usuario'              => 'TEST'
			)
		);
		$header = new SOAPHeader($ns, 'headerRequest', $header_body);

		$client->__setSoapHeaders($header);
		$result = $client->__soapCall($route, [$route => [$method => $data]]);

		return $result;
	} catch(SoapFault $e) {
		return $e;
	}
}

function whq_wcchp_get_tarificacion($destination, $origin, $weight, $length, $width, $height) {
	$ns     = WHQ_WCCHP_CHILEXPRESS_URL . '/TarificaCourier/';
	$url    = WHQ_WCCHP_PLUGIN_URL . 'wsdl/WSDL_Tarificacion_QA.wsdl';
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
