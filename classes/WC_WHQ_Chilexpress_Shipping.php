<?php
if (!defined('ABSPATH')) {
	die();
}

/**
 * Chilexpress Shipping Class for WooCommerce
 */
function whq_wcchp_init_class() {
	if ( ! class_exists('WC_Payment_Gateway') ) {
		return;
	}

	if ( ! class_exists( 'WC_WHQ_Chilexpress_Shipping' ) ) {
		class WC_WHQ_Chilexpress_Shipping extends WC_Shipping_Method {

			/**
			 * Constructor shipping class
			 *
			 * @access public
			 * @return void
			 */
			public function __construct(){
				$this->id = 'chilexpress';
				$this->method_title = __( 'Chilexpress', 'whq-wcchp' );
				$this->method_description = __( 'Utiliza la API de Chilexpress para el cálculo automático de costos de envío. Sugerencias y reporte de errores en <a href="https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues" target="_blank">GitHub</a>.', 'whq-wcchp' );

				// Load the settings.
				$this->init_form_fields();
				$this->init_settings();

				// Define user set variables
				$this->enabled               = $this->get_option( 'enabled' );
				$this->title                 = $this->get_option( 'title' );
				$this->shipping_origin       = $this->get_option( 'shipping_origin' );
				$this->locations_cache       = $this->get_option( 'locations_cache' );
				$this->soap_login            = $this->get_option( 'soap_login' );
				$this->soap_password         = $this->get_option( 'soap_password' );
				$this->availability          = true;

				// Save settings in admin if you have any defined
				add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
			}

			/**
			 * Init settings
			 *
			 * @access public
			 * @return void
			 */
			public static function init() {
				// Load the settings API
				$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
				$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

				// Save settings in admin if you have any defined
				add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
			}

			/**
			 * Form fields
			 *
			 * @access public
			 * @return void
			 */
			public function init_form_fields() {
				$this->form_fields = array(
					'enabled' => array(
						'title'   => __( 'Activar/Desactivar', 'whq-wcchp' ),
						'type'    => 'checkbox',
						'label'   => __( 'Habilitar envíos vía Chilexpress', 'whq-wcchp' ),
						'default' => 'yes'
					),
					'title' => array(
						'title'       => __( 'Título del método de envío', 'whq-wcchp' ),
						'type'        => 'text',
						'description' => __( 'El título del método de envío que el usuario verá en la página de checkout', 'whq-wcchp' ),
						'default'     => __( 'Envío vía Chilexpress', 'whq-wcchp' ),
					),
					'shipping_origin' => array(
						'title'       => __( 'Origen de los envios', 'whq-wcchp' ),
						'type'        => 'select',
						'description' => __( 'Ciudad/Localidad de origen, desde donde se realiza el envío', 'whq-wcchp' ),
						'options'     => $this->get_cities()
					),
					'locations_cache' => array(
						'title'       => __( 'Caché de ubicaciones', 'whq-wcchp' ),
						'type'        => 'number',
						'description' => __( '(En horas) Chilexpress entrega un listado de ubicaciones (Regiones y Ciudades) dinámico. Para evitar saturar la API de Chilexpress, aquellos listados son guardados localmente en WordPress (Transients). Ingresa el número de horas a mantener en el caché el listado de regiones y cuidades. Una hora mínimo. Máximo un mes.', 'whq-wcchp' ),
						'default'     => 24,
					),
					'soap_login' => array(
						'title'       => __( 'Chilexpress API Username', 'whq-wcchp' ),
						'type'        => 'text',
						'description' => __( '(Opcional) Usuario a utilizar en las llamadas a la API de Chilexpress. Dejar en blanco para utilizar datos de conexión por defecto (públicos) que Chilexpress provee.', 'whq-wcchp' ),
						'default'     => __( '', 'whq-wcchp' ),
					),
					'soap_password' => array(
						'title'       => __( 'Chilexpress API Password', 'whq-wcchp' ),
						'type'        => 'password',
						'description' => __( '(Opcional) Contraseña a utilizar en las llamadas a la API de Chilexpress. Dejar en blanco para utilizar datos de conexión por defecto (públicos) que Chilexpress provee.', 'whq-wcchp' ),
						'default'     => __( '', 'whq-wcchp' ),
					),
				);
			}

			public static function get_chilexpress_option( $option_name = '' ) {
				$options = get_option( 'woocommerce_chilexpress_settings' );

				return $options["$option_name"];
			}

			private function get_cities() {
				global $whq_wcchp_default;

				$url    = $whq_wcchp_default['plugin_url'] . 'wsdl/WSDL_GeoReferencia_QA.wsdl';
				$ns     = $whq_wcchp_default['chilexpress_url'] . '/CorpGR/';
				$route  = 'ConsultarCoberturas';
				$method = 'reqObtenerCobertura';

				$codregion        = 99; //Bring it on!
				$codtipocobertura = 2;
				$parameters       = [ 'CodRegion'        => $codregion,
									  'CodTipoCobertura' => $codtipocobertura ];

				$cities = whq_wcchp_call_soap($ns, $url, $route, $method, $parameters)->respObtenerCobertura->Coberturas;

				whq_wcchp_array_move( $cities, 2, 86 );

				$cities_array = array();
				foreach ($cities as $city) {
					$cities_array["$city->CodComuna"] = $city->GlsComuna;
				}

				return $cities_array;
			}

			public function is_available( $package ) {
				foreach ( $package['contents'] as $item_id => $values ) {
					$_product = $values['data'];
					$weight   = (int) absint( $_product->get_weight() );

					return true;
				}

				return true;
			}

			/**
			 * calculate_shipping function.
			 *
			 * @access public
			 * @param mixed $package
			 * @return void
			 */
			public function calculate_shipping( $package = array() ) {
				$weight = 0;
				$length = 0;
				$width  = 0;
				$height = 0;

				foreach ( $package['contents'] as $item_id => $values ) {
					$_product = $values['data'];
					$weight   = (int) absint( $weight + $_product->get_weight() * $values['quantity'] );
					$length   = (int) absint( $length + $_product->get_length() * $values['quantity'] );
					$width    = (int) absint( $width + $_product->get_width() * $values['quantity'] );
					$height   = (int) absint( $height + $_product->get_height() * $values['quantity'] );
				}

				if ( isset( $_POST['s_city'] ) && !is_null( $_POST['s_city'] ) ) {
					$city = $_POST['s_city'];
				} else {
					//And what about WC()->customer->get_shipping_city() ?
					$city = $package['destination']['city'];
				}

				if( !is_null( $city ) ) {
					$chp_cost   = whq_wcchp_get_tarificacion($city, $this->shipping_origin, $weight, $length, $width, $height);
					$final_cost = 0;

					if( count( $chp_cost->respValorizarCourier->Servicios ) == 1 ) {
						$final_cost = $chp_cost->respValorizarCourier->Servicios->ValorServicio;
					} else {
						foreach ( $chp_cost->respValorizarCourier->Servicios as $key => $value ) {
							//Next working day
							if( $value->CodServicio == 3 ) {
								$final_cost = $value->ValorServicio;
							}
						}
					}

					if( empty( $final_cost ) || $final_cost <= 0 ) {
						$final_cost = 100000; //Chilexpress haven't already calculated it? (Probably doesn't have a location yet)
					}

					$this->add_rate( array(
						'id'    => $this->id,
						'label' => $this->title . '',
						'cost'  => $final_cost
					));
				}
			}

			static function create_states( $states ) {
				$cities       = [];
				$states['CL'] = array();

				foreach ($cities as $key => $city) {
					$code                = $city['code'];
					$states['CL'][$code] = $city['name'];
				}

				return $states;
			}

			static function add_cart_fee( WC_Cart $cart ) {
				WC()->cart->calculate_shipping();
			}

			/**
			 * Validate the cache duration
			 */
			public function validate_locations_cache_field( $key, $value ) {
				if ( isset( $value ) && $value < 1 ) {
					WC_Admin_Settings::add_error( esc_html__( 'El caché mínimo para las localidades y regiones es de una hora.', 'woocommerce-integration-demo' ) );

					$value = 1;
				}

				if ( isset( $value ) && $value > 744 ) {
					WC_Admin_Settings::add_error( esc_html__( 'El caché máximo para las localidades y regiones es de un mes (744 horas).', 'woocommerce-integration-demo' ) );

					$value = 744;
				}

				return $value;
			}
		}
	}
}
