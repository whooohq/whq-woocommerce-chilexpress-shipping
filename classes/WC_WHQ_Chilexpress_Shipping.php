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
				$this->method_description = __( 'Utiliza la API SOAP de Chilexpress para el cálculo automático de costos de envío. Sugerencias y reporte de errores en <a href="https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues" target="_blank">GitHub</a>.', 'whq-wcchp' );

				// Load the settings.
				$this->init_form_fields();
				$this->init_settings();

				// Define user set variables
				$this->enabled         = $this->get_option( 'enabled' );
				$this->title           = $this->get_option( 'title' );
				$this->shipping_origin = $this->get_option( 'shipping_origin' );
				$this->soap_login      = $this->get_option( 'soap_login' );
				$this->soap_password   = $this->get_option( 'soap_password' );
				$this->availability    = true;

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
						'title'   => __( 'Habilitar/Deshabilitar', 'whq-wcchp' ),
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
						'options'     => ['PAYS' => 'AISEN',
										'ALGA' => 'ALGARROBO',
										'AHOS' => 'ALTO HOSPICIO',
										'ANCU' => 'ANCUD',
										'ANDA' => 'ANDACOLLO',
										'ANGO' => 'ANGOL',
										'ANTO' => 'ANTOFAGASTA',
										'ARAU' => 'ARAUCO',
										'ARIC' => 'ARICA',
										'BUIN' => 'BUIN',
										'BULN' => 'BULNES',
										'CABI' => 'CABILDO',
										'CABR' => 'CABRERO',
										'CALA' => 'CALAMA',
										'CALB' => 'CALBUCO',
										'CALD' => 'CALDERA',
										'CTAN' => 'CALERA DE TANGO',
										'CANE' => 'CANETE',
										'CARA' => 'CARAHUE',
										'CART' => 'CARTAGENA',
										'CASA' => 'CASABLANCA',
										'CAST' => 'CASTRO',
										'CAUQ' => 'CAUQUENES',
										'LOSC' => 'CERRILLOS',
										'CNAV' => 'CERRO NAVIA',
										'CHAN' => 'CHANARAL',
										'CHEP' => 'CHEPICA',
										'CHIG' => 'CHIGUAYANTE',
										'CHCH' => 'CHILE CHICO',
										'CHIL' => 'CHILLAN',
										'CHIM' => 'CHIMBARONGO',
										'CHON' => 'CHONCHI',
										'PCIS' => 'CISNES',
										'COCH' => 'COCHRANE',
										'COEL' => 'COELEMU',
										'COIC' => 'COIHUECO',
										'COIN' => 'COINCO',
										'COLB' => 'COLBUN',
										'COLI' => 'COLINA',
										'COLL' => 'COLLIPULLI',
										'COLT' => 'COLTAUCO',
										'COMB' => 'COMBARBALA',
										'CONC' => 'CONCEPCION',
										'CCHA' => 'CONCHALI',
										'CCON' => 'CONCON',
										'CONS' => 'CONSTITUCION',
										'COPI' => 'COPIAPO',
										'COQU' => 'COQUIMBO',
										'CORO' => 'CORONEL',
										'COYH' => 'COYHAIQUE',
										'CURC' => 'CURACAUTIN',
										'CRCV' => 'CURACAVI',
										'CURA' => 'CURANILAHUE',
										'CURI' => 'CURICO',
										'DALC' => 'DALCAHUE',
										'DIEG' => 'DIEGO DE ALMAGRO',
										'DONI' => 'DONIHUE',
										'ELBO' => 'EL BOSQUE',
										'ECAR' => 'EL CARMEN',
										'ELMO' => 'EL MONTE',
										'QSCO' => 'EL QUISCO',
										'TABO' => 'EL TABO',
										'ECEN' => 'ESTACION CENTRAL',
										'FRER' => 'FREIRE',
										'FREI' => 'FREIRINA',
										'FRUT' => 'FRUTILLAR',
										'FUTR' => 'FUTRONO',
										'GORB' => 'GORBEA',
										'GRAN' => 'GRANEROS',
										'HIJU' => 'HIJUELAS',
										'HORP' => 'HUALAIHUE',
										'HPEN' => 'HUALPEN',
										'HUAS' => 'HUASCO',
										'HUEC' => 'HUECHURABA',
										'ILLA' => 'ILLAPEL',
										'INDE' => 'INDEPENDENCIA',
										'IQUI' => 'IQUIQUE',
										'IMAI' => 'ISLA DE MAIPO',
										'LACA' => 'LA CALERA',
										'LACI' => 'LA CISTERNA',
										'LACR' => 'LA CRUZ',
										'LAFL' => 'LA FLORIDA',
										'LAGR' => 'LA GRANJA',
										'LALI' => 'LA LIGUA',
										'LAPI' => 'LA PINTANA',
										'LARE' => 'LA REINA',
										'LASE' => 'LA SERENA',
										'LAUN' => 'LA UNION',
										'LRAN' => 'LAGO RANCO',
										'LAJA' => 'LAJA',
										'LAMP' => 'LAMPA',
										'LANC' => 'LANCO',
										'LCAB' => 'LAS CABRAS',
										'LCON' => 'LAS CONDES',
										'LAUT' => 'LAUTARO',
										'LEBU' => 'LEBU',
										'LIMA' => 'LIMACHE',
										'LINA' => 'LINARES',
										'LCHE' => 'LITUECHE',
										'LLAN' => 'LLANQUIHUE',
										'LLAY' => 'LLAY LLAY',
										'LOBA' => 'LO BARNECHEA',
										'LOES' => 'LO ESPEJO',
										'LOPR' => 'LO PRADO',
										'LOLO' => 'LOLOL',
										'LONC' => 'LONCOCHE',
										'LONG' => 'LONGAVI',
										'ALAM' => 'LOS ALAMOS',
										'LAND' => 'LOS ANDES',
										'LANG' => 'LOS ANGELES',
										'LLAG' => 'LOS LAGOS',
										'LMUE' => 'LOS MUERMOS',
										'LVIL' => 'LOS VILOS',
										'LOTA' => 'LOTA',
										'MACH' => 'MACHALI',
										'MACU' => 'MACUL',
										'MAFI' => 'MAFIL',
										'MIPU' => 'MAIPU',
										'MALO' => 'MALLOA',
										'MARC' => 'MARCHIGUE',
										'MARI' => 'MARIA ELENA',
										'MAUL' => 'MAULLIN',
										'MEJI' => 'MEJILLONES',
										'MELI' => 'MELIPILLA',
										'MOLI' => 'MOLINA',
										'MOPA' => 'MONTE PATRIA',
										'MULC' => 'MULCHEN',
										'NACI' => 'NACIMIENTO',
										'NANC' => 'NANCAGUA',
										'PNAT' => 'NATALES',
										'NEGR' => 'NEGRETE',
										'NOGA' => 'NOGALES',
										'NVAI' => 'NUEVA IMPERIAL',
										'NUNO' => 'NUNOA',
										'OLIV' => 'OLIVAR',
										'OLMU' => 'OLMUE',
										'OSOR' => 'OSORNO',
										'OVAL' => 'OVALLE',
										'PHUR' => 'PADRE HURTADO',
										'PLCA' => 'PADRE LAS CASAS',
										'PAIL' => 'PAILLACO',
										'PAIN' => 'PAINE',
										'PALM' => 'PALMILLA',
										'PANG' => 'PANGUIPULLI',
										'PARR' => 'PARRAL',
										'PEDR' => 'PEDRO AGUIRRE CERDA',
										'PEMU' => 'PEMUCO',
										'PENA' => 'PENAFLOR',
										'PLOL' => 'PENALOLEN',
										'PENC' => 'PENCO',
										'PERA' => 'PERALILLO',
										'PEUM' => 'PEUMO',
										'PICD' => 'PICHIDEGUA',
										'PICH' => 'PICHILEMU',
										'PINT' => 'PINTO',
										'PIRQ' => 'PIRQUE',
										'PITR' => 'PITRUFQUEN',
										'PLAC' => 'PLACILLA SEXTA REGION',
										'PORV' => 'PORVENIR',
										'POZO' => 'POZO ALMONTE',
										'PROV' => 'PROVIDENCIA',
										'PUCH' => 'PUCHUNCAVI',
										'PUCO' => 'PUCON',
										'PUDA' => 'PUDAHUEL',
										'PALT' => 'PUENTE ALTO',
										'PMON' => 'PUERTO MONTT',
										'PVAR' => 'PUERTO VARAS',
										'PUNI' => 'PUNITAQUI',
										'PUNT' => 'PUNTA ARENAS',
										'PURE' => 'PUREN',
										'PURR' => 'PURRANQUE',
										'PUYG' => 'PUYEHUE',
										'QUEL' => 'QUELLON',
										'QUEM' => 'QUEMCHI',
										'QILI' => 'QUILICURA',
										'QULL' => 'QUILLON',
										'QLTA' => 'QUILLOTA',
										'QUIL' => 'QUILPUE',
										'ACHA' => 'QUINCHAO',
										'QTIL' => 'QUINTA DE TILCOCO',
										'QNOR' => 'QUINTA NORMAL',
										'QUIN' => 'QUINTERO',
										'QUIR' => 'QUIRIHUE',
										'RANC' => 'RANCAGUA',
										'RECO' => 'RECOLETA',
										'RNCO' => 'RENAICO',
										'RENC' => 'RENCA',
										'RENG' => 'RENGO',
										'REQU' => 'REQUINOA',
										'RIOB' => 'RIO BUENO',
										'RNEG' => 'RIO NEGRO',
										'ROME' => 'ROMERAL',
										'SALA' => 'SALAMANCA',
										'SANT' => 'SAN ANTONIO',
										'SBER' => 'SAN BERNARDO',
										'SCAR' => 'SAN CARLOS',
										'SCLE' => 'SAN CLEMENTE',
										'SFEL' => 'SAN FELIPE',
										'SFER' => 'SAN FERNANDO',
										'SFRA' => 'SAN FRANCISCO DE MOSTAZAL',
										'SIGN' => 'SAN IGNACIO',
										'SJAV' => 'SAN JAVIER',
										'SJOA' => 'SAN JOAQUIN',
										'SMIG' => 'SAN MIGUEL',
										'SPAB' => 'SAN PABLO',
										'SPAT' => 'SAN PEDRO DE ATACAMA',
										'SPED' => 'SAN PEDRO DE LA PAZ',
										'SRAM' => 'SAN RAMON',
										'SANR' => 'SAN ROSENDO',
										'SVIC' => 'SAN VICENTE DE TAGUA TAGUA',
										'SBAR' => 'SANTA BARBARA',
										'SCRU' => 'SANTA CRUZ',
										'STGO' => 'SANTIAGO CENTRO',
										'SDGO' => 'SANTO DOMINGO',
										'SGOR' => 'SIERRA GORDA',
										'TALA' => 'TALAGANTE',
										'TALC' => 'TALCA',
										'THNO' => 'TALCAHUANO',
										'TALT' => 'TALTAL',
										'TEMU' => 'TEMUCO',
										'TENO' => 'TENO',
										'TAMA' => 'TIERRA AMARILLA',
										'TILT' => 'TIL TIL',
										'TOCO' => 'TOCOPILLA',
										'TOME' => 'TOME',
										'TRAI' => 'TRAIGUEN',
										'TUCA' => 'TUCAPEL',
										'VALD' => 'VALDIVIA',
										'VALL' => 'VALLENAR',
										'VALP' => 'VALPARAISO',
										'VICT' => 'VICTORIA',
										'VICU' => 'VICUNA',
										'VALG' => 'VILLA ALEGRE',
										'VALE' => 'VILLA ALEMANA',
										'VILL' => 'VILLARRICA',
										'VINA' => 'VINA DEL MAR',
										'VITA' => 'VITACURA',
										'YUMB' => 'YUMBEL',
										'YUNG' => 'YUNGAY']
					),
					'soap_login' => array(
						'title'       => __( 'SOAP Login', 'whq-wcchp' ),
						'type'        => 'text',
						'description' => __( '(Opcional) Usuario a utilizar en las llamadas al SOAP de Chilexpress. Dejar en blanco para utilizar datos de conexión por defecto (públicos) que Chilexpress provee.', 'whq-wcchp' ),
						'default'     => __( '', 'whq-wcchp' ),
					),
					'soap_password' => array(
						'title'       => __( 'SOAP Password', 'whq-wcchp' ),
						'type'        => 'password',
						'description' => __( '(Opcional) Contraseña a utilizar en las llamadas al SOAP de Chilexpress. Dejar en blanco para utilizar datos de conexión por defecto (públicos) que Chilexpress provee.', 'whq-wcchp' ),
						'default'     => __( '', 'whq-wcchp' ),
					),
				);
			}

			public function get_chilexpress_option( $option_name = '' ) {
				$options = get_option("woocommerce_chilexpress_settings");

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
				$weight     = 0;
				$length     = 0;
				$width      = 0;
				$height     = 0;

				foreach ( $package['contents'] as $item_id => $values ) {
					$_product   = $values['data'];
					$weight     = (int) absint( $weight + $_product->get_weight() * $values['quantity'] );
					$length     = (int) absint( $length + $_product->length * $values['quantity'] );
					$width      = (int) absint( $width + $_product->width * $values['quantity'] );
					$height     = (int) absint( $height + $_product->height * $values['quantity'] );
				}

				if ( $_POST['s_city'] != null ) {
					$city = $_POST['s_city'];
				} else {
					$city = $package["destination"]["city"];
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
