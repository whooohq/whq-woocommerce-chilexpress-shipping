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
				$this->list_cities     = new WC_WHQ_Cities_CL();

				// Load the settings.
				$this->init_form_fields();
				$this->init_settings();

				// Define user set variables
				$this->enabled                       = $this->get_option( 'enabled' );
				$this->title                         = $this->get_option( 'title' );
				$this->shipping_origin               = $this->get_option( 'shipping_origin' );
				$this->soap_login                    = $this->get_option( 'soap_login' );
				$this->soap_password                 = $this->get_option( 'soap_password' );
				$this->hide_cart_shipping_calculator = $this->get_option( 'hide_cart_shipping_calculator' );
				$this->availability                  = true;

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
						'options'     => $this->list_cities->cities
					),
					'hide_cart_shipping_calculator' => array(
						'title'       => __( 'Ocultar calculadora de envíos', 'whq-wcchp' ),
						'label'       => __( 'Oculta la calculadora de envíos en el carro de compras.', 'whq-wcchp' ),
						'type'        => 'checkbox',
						'description' => __( 'El cálculo de costo de envíos desde Chilexpress no se encuentra implementado en la calculadora rápida del carro de compras (previo al Checkout/Finalizar Compra). Activando esta opción, puedes ocultar la calculadora rápida, y solo mostrar el cálculo de los gastos de envío en el Checkout/Finalizar Compra.', 'whq-wcchp' ),
						'default'     => 'yes'
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

				if ( isset( $_POST['s_city'] ) && $_POST['s_city'] != null ) {
					$city = $_POST['s_city'];
				} else {
					$city = $package['destination']['city'];
				}

				if( $city != null ) {
					$chp_cost  = whq_wcchp_get_tarificacion($city, $this->shipping_origin, $weight, $length, $width, $height);
					$final_cost = '0';

					if(count($chp_cost->respValorizarCourier->Servicios) == 1){
						$final_cost = $chp_cost->respValorizarCourier->Servicios->ValorServicio;
					}else{
						foreach ($chp_cost->respValorizarCourier->Servicios as $key => $value) {
							if( $value->CodServicio == 3){
								$final_cost = $value->ValorServicio;
							}
						}
					}

					$this->add_rate( array(
						'id'    => $this->id,
						'label' => $this->title . "",
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
		}
	}
}
