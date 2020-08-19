<?php
if (! defined('ABSPATH')) {
    die();
}

/**
 * Chilexpress Shipping Class for WooCommerce
 */
function whq_wcchp_init_class()
{
    if (! class_exists('WC_Payment_Gateway')) {
        return;
    }

    if (! class_exists('WC_WHQ_Chilexpress_Shipping')) {
        class WC_WHQ_Chilexpress_Shipping extends WC_Shipping_Method
        {

            /**
             * Constructor shipping class
             *
             * @access public
             * @return void
             */
            public function __construct($instance_id = 0)
            {
                $this->id                 = 'chilexpress';
                $this->instance_id        = absint($instance_id);
                $this->method_title       = __('Chilexpress', 'whq-wcchp');
                $this->method_description = __('<p>Utiliza la API de Chilexpress para el cálculo automático de costos de envío. Sugerencias y reporte de errores en <a href="https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues" target="_blank" rel="noopener noreferrer">GitHub</a>.</p>', 'whq-wcchp');

                // Load the settings.
                //$this->init();
                $this->init_form_fields();
                $this->init_settings();

                // Define user set variables
                $this->enabled                = $this->get_option('enabled');
                $this->title                  = $this->get_option('title');
                $this->shipping_origin        = apply_filters('whq_wcchp_shipping_origin', $this->get_option('shipping_origin'));
                $this->packaging_heuristic    = $this->get_option('packaging_heuristic');
                $this->shipments_types        = $this->get_option('shipments_types');
                $this->shipments_types_names  = $this->get_option('shipments_types_names');
                $this->locations_cache        = $this->get_option('locations_cache');
                $this->fixed_packing_price    = $this->get_option('fixed_packing_price');
                $this->extra_wrapper          = $this->get_option('extra_wrapper');
                $this->tax_status             = $this->get_option('tax_status');
                $this->soap_api_enviroment    = $this->get_option('soap_api_enviroment');
                $this->soap_login             = $this->get_option('soap_login');
                $this->soap_password          = $this->get_option('soap_password');
                $this->shipping_zones_support = $this->get_option('shipping_zones_support');
                $this->disable_shipping_zones = $this->get_option('disable_shipping_zones');
                $this->availability           = true;

                if ($this->get_chilexpress_option('shipping_zones_support') == 'yes') {
                    /*$this->supports = array(
                        'shipping-zones',
                        'instance-settings',
                        //'instance-settings-modal',
                    );

                    // Only on the config page (and not in a modal)
                    if( isset( $_REQUEST['instance_id'] ) && ! empty( $_REQUEST['instance_id'] ) && $_REQUEST['instance_id'] == $this->instance_id ) {
                        $this->method_description .= '<p><a href="#' . $this->instance_id . '" class="wcchp_disable_shipping_zones_support"><span class="dashicons dashicons-no-alt" style="text-decoration: none;"></span>Desactivar soporte para Zonas de Envío</a>. Serás redireccionado a la página de configuración en unos momentos.</p>';
                    }
                    */

                    // Populate Chile's States
                    //add_filter( 'woocommerce_states', array( &$this, 'create_states' ) );
                }

                // Save settings in admin if you have any defined
                add_action('woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ));
            }

            /**
             * Init settings
             *
             * @access public
             * @return void
             */
            public static function init()
            {
                // Load the settings API
                $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

                // Save settings in admin if you have any defined
                add_action('woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ));
            }

            /**
             * Output the admin options table.
             */
            public function admin_options()
            {
                ?>
				<h2>Chilexpress Shipping</h2>
				<table class="form-table">
					<?php $this->generate_settings_html(); ?>
				</table>
				<script type="text/javascript">
					jQuery(document).ready(function( $ ) {
						if( $('#woocommerce_chilexpress_shipments_types').length ) {
							$('#woocommerce_chilexpress_shipments_types').selectWoo();
						}
					});
				</script>
			<?php
            }

            /**
             * Form fields
             *
             * @access public
             * @return void
             */
            public function init_form_fields()
            {
                $this->form_fields = array(
                    'enabled' => array(
                        'title'   => __('Activar/Desactivar', 'whq-wcchp'),
                        'type'    => 'checkbox',
                        'label'   => __('Habilitar envíos vía Chilexpress', 'whq-wcchp'),
                        'default' => 'yes',
                    ),
                    /*'shipping_zones_support' => array(
                        'title'       => __( 'Soporte de Zonas de Envío', 'whq-wcchp' ),
                        'type'        => 'checkbox',
                        'label'       => __( 'Habilitar Zonas de Envío de WooCommerce', 'whq-wcchp' ),
                        'description' => __( 'Al activar esta opción, Chilexpress se transforma en un método de envío compatible con las Zonas de Envío. Al hacerlo, esta página de configuración se moverá las <a href="admin.php?page=wc-settings&tab=shipping&section=">Zonas de Envío</a>.<br/>Debes tener en cuenta que para poder continuar utilizando Chilexpress como método de envío en tu tienda, <strong>tendrás que crear al menos una Zona de Envío, incluir a Chile en ella, y luego agregar Chilexpress como método de envío dentro de Chile</strong>. Hacerlo es mandatorio para poder continuar configurando el método de envío.<br/>Al activar esta opción, serás redireccionado a las Zonas de Envío de WooCommerce.', 'whq-wcchp' ),
                        'default'     => 'disabled',
                    ),*/
                    'title' => array(
                        'title'       => __('Título del método de envío', 'whq-wcchp'),
                        'type'        => 'text',
                        'description' => __('El título del método de envío que el usuario verá en la página de checkout', 'whq-wcchp'),
                        'default'     => __('Chilexpress', 'whq-wcchp'),
                    ),
                    'shipping_origin' => array(
                        'title'       => __('Origen de los envios', 'whq-wcchp'),
                        'type'        => 'select',
                        'class'   => 'wc-enhanced-select',
                        'description' => __('Ciudad/Localidad de origen, desde donde se realiza el envío', 'whq-wcchp'),
                        'options'     => $this->get_cities(),
                    ),
                    'packaging_heuristic' => array(
                        'title'       => __('Método para cálculo de costo de envío (embalaje)', 'whq-wcchp'),
                        'type'        => 'select',
                        'class'   => 'wc-enhanced-select',
                        'description' => __('Heurística utilizada para calcular el precio a base del ensamblaje de varios productos en un mismo pedido (paquete).<br/>
							<ul>
								<li>- Unir lados angostos: un solo paquete uniendo los lados más angostos, recomendable cuando son productos pequeños (método por defecto).</li>
								<li>- Un paquete por item: un paquete por producto, recomendable cuando se envían productos mayores y/o en mayor cantidad.</li>
							</ul>', 'whq-wcchp'),
                        'options'     => array(
                            'join_narrow_sides'       => 'Unir lados angostos',
                            'single_package_per_item' => 'Un paquete por item',
                        ),
                        'default'     => 'join_narrow_sides',
                    ),
                    'shipments_types' => array(
                        'title'       => __('Tipos de envíos soportados', 'whq-wcchp'),
                        'type'        => 'multiselect',
                        'description' => __('Selecciona los tipos de envíos a soportar.<br/>Considera que dependiendo de la ubicación de origen de tus envíos, algunas localidades extremas podrían no contar con un tipo de envío normal (día hábil siguiente), y solo tener disponible envío al tercer día, por lo que <strong>lo recomendado es que selecciones al menos "día hábil siguiente", "día hábil subsiguiente" y "tercer día"</strong>.<br/>Ten presente que los envíos Ultra Rápidos y Overnight están disponibles solo en ciertas ciudades de origen y para algunos destinos.<br/>También <strong>debes tener en cuenta que el envío Ultra Rápido debería ser despachado inmediatamente por tu tienda para cumplir con las espectativas del comprador</strong>.<br/>Conoce más sobre estos <a href="http://www.chilexpress.cl/tiempos-de-entrega-envios-paquetes-documentos" target="_blank" rel="noopener noreferrer">tipos de envíos, acá</a>.', 'whq-wcchp'),
                        'options'     => $this->get_shipments_types(),
                        'default'     => array( 3, 4, 5 ),
                    ),
                    'shipments_types_names' => array(
                        'title'       => __('Renombrar tipos de envíos', 'whq-wcchp'),
                        'type'        => 'text',
                        'description' => __('<strong>Lista separada por comas</strong>. Permite cambiar los textos por defecto de Chilexpress para tus tipos de envío: <i>Ultra Rápido, Overnight, Día Hábil Siguiente, Día Hábil Subsiguiente, Tercer día</i>. <strong>En ese orden, e incluyendo todos los típos de envío</strong> (aunque no los utilices). Dejar en blanco para usar los valores que Chilexpress devuelve por defecto.<br/>Ejemplo:<br/><i>Rápido,Noche,Día Siguiente,Día subsiguiente,Durante la Semana</i>', 'whq-wcchp'),
                        'default'     => 30,
                    ),
                    'locations_cache' => array(
                        'title'       => __('Caché de ubicaciones', 'whq-wcchp'),
                        'type'        => 'number',
                        'description' => __('(En días) Chilexpress entrega un listado de ubicaciones (Regiones y Ciudades) dinámico.<br/>Para evitar saturar la API de Chilexpress, aquellos listados son guardados localmente en WordPress (Transients). Ingresa el número de días a mantener en el caché el listado de regiones y cuidades.<br/>Mínimo una semana, máximo dos meses.', 'whq-wcchp'),
                        'default'     => 30,
                    ),
                    'fixed_packing_price' => array(
                        'title'       => __('Costo extra por embalaje', 'whq-wcchp'),
                        'type'        => 'number',
                        'description' => __('Precio extra por costo de embalaje. Este costo (en CLP) será agregado <strong>por producto</strong> al precio de envío que Chilexpress devuelva (vía API). Si no deseas realizar un cobro extra por paquete/embalaje, dejar en blanco o cero.', 'whq-wcchp'),
                        'default'     => 0,
                    ),
                    'extra_wrapper' => array(
                        'title'       => __('CM extra para caja/embalaje', 'whq-wcchp'),
                        'type'        => 'number',
                        'description' => __('(En centímetros) El plugin permite que añadas X centímetros extra al paquete que enviarás a Chilexpress, esto, para permitirte contabilizar el posible uso de una caja (y el espacio extra que necesitarás para evitar que lo que envias se dañe). Si ya usas ese estimado extra al momento de ingresar el tamaño de los productos a tu tienda, y no deseas utilizar este extra, simplemente deja el valor en cero.', 'whq-wcchp'),
                        'default'     => 0,
                    ),
                    'tax_status' => array(
                        'title'   => __('Tax status', 'woocommerce'),
                        'type'    => 'select',
                        'class'   => 'wc-enhanced-select',
                        'description' => __('Normalmente Chilexpress cobra el impuesto de su servicio de transporte, y no es un gasto que debieras declarar por tu parte.', 'whq-wcchp'),
                        'default' => 'none',
                        'options' => array(
                            'taxable' => __('Taxable', 'woocommerce'),
                            'none'    => _x('None', 'Tax status', 'woocommerce'),
                        ),
                    ),
                    'soap_api_enviroment' => array(
                        'title'       => __('Chilexpress API, ambiente', 'whq-wcchp'),
                        'type'        => 'select',
                        'class'   => 'wc-enhanced-select',
                        'default'     => 'PROD',
                        'description' => __('WS PROD requiere un usuario y contraseña para la API SOAP de Chilexpress. Ante la duda, mantener como WS QA.', 'whq-wcchp'),
                        'options'     => array(
                                    'QA'   => 'WS QA',
                                    'PROD' => 'WS PROD',
                        ),
                    ),
                    'soap_login' => array(
                        'title'       => __('Chilexpress API, usuario', 'whq-wcchp'),
                        'type'        => 'text',
                        'description' => __('(Opcional) Usuario a utilizar en las llamadas a la API de Chilexpress. Ante la duda, mantener en blanco.', 'whq-wcchp'),
                        'default'     => '',
                    ),
                    'soap_password' => array(
                        'title'       => __('Chilexpress API, contraseña', 'whq-wcchp'),
                        'type'        => 'text', // https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/45
                        'description' => __('(Opcional) Contraseña a utilizar en las llamadas a la API de Chilexpress. Ante la duda, mantener en blanco.', 'whq-wcchp'),
                        'default'     => '',
                        'desc_tip'    => false,
                    ),
                );

                // https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/45
                // Only if password is entered, change the type, to mask the password in the db
                if (! empty($this->get_option('soap_password'))) {
                    $this->form_fields['soap_password']['type'] = 'password';
                }

                // Shipping Zones support
                /*$this->instance_form_fields = $this->form_fields;
                unset( $this->instance_form_fields['shipping_zones_support'] );*/
            }

            public static function get_chilexpress_option($option_name = '')
            {
                $options = get_option('woocommerce_chilexpress_settings');

                // TODO: detect if shipping zones is enabled, and get only this option from the global array. Others needs to be called from the respective $instance_id
                if ($option_name == 'shipping_zones_support') {
                    $options = get_option('woocommerce_chilexpress_settings');
                }

                //https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/25
                if (false === $options) {
                    return false;
                }

                if (! is_array($options)) {
                    return false;
                }

                if (false === array_key_exists($option_name, $options)) {
                    return false;
                }

                return $options["$option_name"];
            }

            private function get_shipments_types()
            {
                // ULTRA RÁPIDO:1
                // OVERNIGHT:2
                // DÍA HÁBIL SIGUIENTE:3
                // DÍA HÁBIL SUBSIGUIENTE:4
                // TERCER DÍA:5

                $shipments_types = [
                    1 => 'Ultra rápido',
                    2 => 'Overnight',
                    3 => 'Día hábil siguiente',
                    4 => 'Día hábil subsiguiente',
                    5 => 'Tercer día',
                ];

                return apply_filters('whq_wcchp_shipments_types', $shipments_types);
            }

            /**
             * Get Chilexpress's supported cities
             * @param  integer $type 1 = Admission, 2 = Delivery, 3 = Both
             * @return array        Array containing the cities, with their code as the key
             */
            private function get_cities($type = 1)
            {
                global $whq_wcchp_default;

                $soap_api_enviroment = $this->get_chilexpress_option('soap_api_enviroment');

                if (empty($soap_api_enviroment)) {
                    $soap_api_enviroment = 'QA';
                }

                $url 	= $whq_wcchp_default['chilexpress_soap_wsdl_' . $soap_api_enviroment] . '/GeoReferencia?wsdl';
                $ns     = $whq_wcchp_default['chilexpress_url'] . '/CorpGR/';
                $route  = 'ConsultarCoberturas';
                $method = 'reqObtenerCobertura';

                $codregion        = 99; //Bring it on!
                $codtipocobertura = $type; //Admission
                $parameters       = [ 'CodRegion'        => $codregion,
                                      'CodTipoCobertura' => $codtipocobertura ];

                $cities = whq_wcchp_call_soap($ns, $url, $route, $method, $parameters);
                //$cities = false; //Simulate API down

                if (false === $cities) {
                    //Retrieve the hard-coded ones
                    $cities       = new WC_WHQ_States_Cities_CL();
                    $cities_array = $cities->admission;
                } else {
                    $cities = $cities->respObtenerCobertura->Coberturas;

                    whq_wcchp_array_move($cities, 2, 86);

                    if (is_array($cities)) {
                        $cities_array = array();
                        foreach ($cities as $city) {
                            $cities_array["$city->CodComuna"] = $city->GlsComuna;
                        }
                    } else {
                        $cities_array = array( $cities );
                    }
                }

                return $cities_array;
            }

            /**
             * Get Chilexpress's supported regions (Chile's states)
             * @param  integer $type 1 = Admission, 2 = Delivery, 3 = Both
             * @return array        Array containing the cities, with their code as the key
             */
            private function get_states()
            {
                global $whq_wcchp_default;

                $soap_api_enviroment = $this->get_chilexpress_option('soap_api_enviroment');

                if (empty($soap_api_enviroment)) {
                    $soap_api_enviroment = 'QA';
                }

                $url 	= $whq_wcchp_default['chilexpress_soap_wsdl_' . $soap_api_enviroment] . '/GeoReferencia?wsdl';
                $ns     = $whq_wcchp_default['chilexpress_url'] . '/CorpGR/';
                $route  = 'ConsultarRegiones';
                $method = 'reqObtenerRegion';

                $regions = whq_wcchp_call_soap($ns, $url, $route, $method);
                //$regions = false; //Simulate API down

                if (false === $regions) {
                    //Retrieve the hard-coded ones
                    $regions       = new WC_WHQ_States_Cities_CL();
                    $regions_array = $regions->states;
                } else {
                    $regions = $regions->respObtenerRegion->Regiones;

                    whq_wcchp_array_move($regions, 14, 0);
                    whq_wcchp_array_move($regions, 10, 6);
                    whq_wcchp_array_move($regions, 14, 11);

                    $regions_array = [];

                    foreach ($regions as $key => $value) {
                        $regions_array[ $value->idRegion ] = $value->GlsRegion;
                    }
                }

                if (! is_array($regions_array)) {
                    return false;
                }

                return $regions_array;
            }

            public function is_available($package)
            {
                foreach ($package['contents'] as $item_id => $values) {
                    $_product = $values['data'];
                    $weight   = (int) absint($_product->get_weight());

                    return true;
                }

                return true;
            }

            /**
             * calculate_shipping method
             *
             * @access public
             * @param mixed $package
             * @return void
             */
            public function calculate_shipping($package = array())
            {
                write_log('Shipping Heuristic algorithm selected: ' . $this->packaging_heuristic);

                //Prevent upgraded plugin instances from failing (empty or null value)
                if (! isset($this->packaging_heuristic) || empty($this->packaging_heuristic)) {
                    $this->packaging_heuristic = 'join_narrow_sides';
                }

                $override_shipping_calculation = apply_filters('whq_wcchp_override_shipping_calculation', false);
                $override_packaging_heuristic  = apply_filters('whq_wcchp_override_packaging_heuristic', $this->packaging_heuristic);

                //Fallback to standard heuristic if empty
                if (empty($override_packaging_heuristic)) {
                    $override_shipping_calculation = false;
                }

                if (false === $override_shipping_calculation) {
                    if ($this->packaging_heuristic == 'single_package_per_item') {
                        $this->calculate_shipping_by_item($package);
                    } else {
                        //Default packaging
                        $this->calculate_shipping_by_shortest_side($package);
                    }
                } else {
                    //Override packaging calculation
                    $this->calculate_shipping_override($package, $override_packaging_heuristic);
                }
            }

            /**
             * calculate_shipping_by_shortest_side method
             * @param  array  $package Package data
             */
            public function calculate_shipping_by_shortest_side($package = array())
            {
                write_log('Calculate shipping by adding shortest sides');

                $weight                   = 0;
                $length                   = 0;
                $width                    = 0;
                $height                   = 0;
                $product_package          = array();
                $product_package[0]       = array( 0, 0, 0, 0 );
                $product_package_number   = 1;
                $product_package_quantity = count($package['contents']);

                // Generates a package for each product in the cart.
                foreach ($package['contents'] as $item_id => $values) {
                    $_product = $values['data'];

                    // Calculates the final package weight.
                    $weight = round($weight + $_product->get_weight() * $values['quantity'], 3);

                    // Generates the package for the current product.
                    $length = round($_product->get_length(), 1);
                    $width  = round($_product->get_width(), 1);
                    $height = round($_product->get_height(), 1);

                    $product_package[$product_package_number] = array(0, $length, $width, $height);

                    // Orders the product package dimensions in ascending order.
                    sort($product_package[$product_package_number]);

                    // Multiply the smallest product package dimension by the quantity to obtain the final product package.
                    $product_package[$product_package_number][1] = round($product_package[$product_package_number][1] * $values['quantity'], 1);

                    // Reorders the product package dimensions in ascendind order.
                    sort($product_package[$product_package_number]);

                    // Calculates the volume of each product package.
                    $product_package[$product_package_number][0] = $product_package[$product_package_number][1] * $product_package[$product_package_number][2] * $product_package[$product_package_number][3];

                    write_log("PrdPkgInit({$product_package_number}): Vl={$product_package[$product_package_number][0]} Al={$product_package[$product_package_number][1]} An={$product_package[$product_package_number][2]} La={$product_package[$product_package_number][3]}");

                    $product_package_number++;
                }

                // Orders the product packages by volume descending.
                $auxiliary_array = array( 0, 0, 0, 0 );

                for ($i=1; $i < $product_package_quantity; $i++) {
                    for ($product_package_number=1; $product_package_number < $product_package_quantity; $product_package_number++) {
                        if ($product_package[$product_package_number][0] < $product_package[$product_package_number+1][0]) {
                            $auxiliary_array = $product_package[$product_package_number];

                            $product_package[$product_package_number] = $product_package[$product_package_number+1];

                            $product_package[$product_package_number+1] = $auxiliary_array;
                        }
                    }
                }

                // To save an extra loop, we check WP_DEBUG first
                if (true === WP_DEBUG) {
                    for ($i=1; $i <= $product_package_quantity; $i++) {
                        write_log("PrdPkgVol({$i}): Vl={$product_package[$i][0]} Al={$product_package[$i][1]} An={$product_package[$i][2]} La={$product_package[$i][3]}");
                    }
                }

                // If the product packages are more than 3 then makes a new package for every two product packages to improve the final package.
                if ($product_package_quantity > 3) {
                    //if the product packages are not even then generates a new empty package.
                    if (($product_package_quantity % 2) == 1) {
                        $product_package_quantity++;
                        $product_package[$product_package_quantity] = array(0, 0, 0, 0);
                    }

                    // Joins every two product packages in a new single product package.
                    $product_package_number = 1;

                    for ($i=1; $i < $product_package_quantity; $i+=2) {
                        $product_package[$product_package_number][1] = $product_package[$i][1] + $product_package[$i+1][1];

                        if ($product_package[$i][2] > $product_package[$i+1][2]) {
                            $product_package[$product_package_number][2] = $product_package[$i][2];
                        } else {
                            $product_package[$product_package_number][2] = $product_package[$i+1][2];
                        }

                        if ($product_package[$i][3] > $product_package[$i+1][3]) {
                            $product_package[$product_package_number][3] = $product_package[$i][3];
                        } else {
                            $product_package[$product_package_number][3] = $product_package[$i+1][3];
                        }

                        $product_package[$product_package_number][0] = 0;

                        // Reorders the new product package dimensions in ascendind order.
                        sort($product_package[$product_package_number]);

                        // Calculates the volume for the new product package.
                        $product_package[$product_package_number][0] = $product_package[$product_package_number][1] * $product_package[$product_package_number][2] * $product_package[$product_package_number][3];

                        $product_package_number++;
                    }

                    $product_package_quantity = $product_package_quantity / 2;

                    // To save an extra loop, we check WP_DEBUG first
                    if (true === WP_DEBUG) {
                        for ($i=1; $i <= $product_package_quantity; $i++) {
                            write_log("PrdPkgRed({$i}): Vl={$product_package[$i][0]} Al={$product_package[$i][1]} An={$product_package[$i][2]} La={$product_package[$i][3]}");
                        }
                    }
                }

                // Generates the final package.
                for ($product_package_number=1; $product_package_number <= $product_package_quantity; $product_package_number++) {
                    $product_package[0][1] = $product_package[0][1] + $product_package[$product_package_number][1];

                    if ($product_package[$product_package_number][2] > $product_package[0][2]) {
                        $product_package[0][2] = $product_package[$product_package_number][2];
                    }

                    if ($product_package[$product_package_number][3] > $product_package[0][3]) {
                        $product_package[0][3] = $product_package[$product_package_number][3];
                    }

                    // For each product package included reorders the resulting package by the smallest dimension.
                    sort($product_package[0]);

                    write_log("FinPkgPrdPkg({$product_package_number}): Al={$product_package[0][1]} An={$product_package[0][2]} La={$product_package[0][3]}");
                }

                /*
                Reorders the final package by the largest dimension, adds X cm on each dimension (for the wrapping/box),
                Rounds up each value and trasfers the values to the final variables.
                */
                sort($product_package[0]);

                $extra_wrapper = (int) absint($this->extra_wrapper);

                if (empty($extra_wrapper) || false === $extra_wrapper || $extra_wrapper < 0) {
                    $extra_wrapper = 0; // No valid value returned?
                }

                $length = ceil($product_package[0][3] + $extra_wrapper);
                $width  = ceil($product_package[0][2] + $extra_wrapper);
                $height = ceil($product_package[0][1] + $extra_wrapper);
                $product_package[0][0] = $length * $width * $height;

                write_log("FinalPackage: Kg={$weight} Vl={$product_package[0][0]} La={$length} An={$width} Al={$height}");

                // Add rate to WC
                $rates = $this->get_tarification($package, $weight, $length, $width, $height);

                $this->add_rates($rates);
            }

            /**
             * calculate_shipping_by_item method
             *
             * @param  array  $package Package data
             */
            public function calculate_shipping_by_item($package = array())
            {
                write_log('Calculate Shipping By Item');

                $weight = 0;
                $length = 0;
                $width  = 0;
                $height = 0;

                $final_tarification = array();

                foreach ($package['contents'] as $item_id => $values) {
                    $_product = $values['data'];

                    //Calculates the final package weight.
                    $weight = round($weight + $_product->get_weight(), 3);

                    //Generates the package for the current product.
                    $length = round($_product->get_length(), 1);
                    $width  = round($_product->get_width(), 1);
                    $height = round($_product->get_height(), 1);

                    //Adds the extra_wrapper to the package
                    $extra_wrapper = (int) absint($this->extra_wrapper);

                    if (empty($extra_wrapper) || false === $extra_wrapper || $extra_wrapper < 0) {
                        $extra_wrapper = 0; //No valid value returned?
                    }

                    $length = ceil($length + $extra_wrapper);
                    $width  = ceil($width + $extra_wrapper);
                    $height = ceil($height + $extra_wrapper);

                    //Get Chilexpress's tarification data
                    $result = $this->get_tarification($package, $weight, $length, $width, $height);

                    foreach ($result as $key => $value) {
                        // for every available service multiply the cost with quantity
                        $result[$key][2] = $value[2] * $values['quantity'];
                    }

                    // add the rate to the final_tarification
                    if (count($final_tarification) == 0) {
                        $final_tarification = $result;
                    } else {
                        foreach ($result as $key => $value) {
                            // for every available service add the rate of this item (already multiplied with the quantity) to the final rate
                            $final_tarification[$key][2] += $value[2];
                        }
                    }
                }

                if (true === WP_DEBUG) {
                    write_log($final_tarification);
                }

                // for every service add the Rate for the frontend and payment
                $this->add_rates($final_tarification);
            }

            /**
            * calculate_shipping_override method
            *
            * @param  array  $package Package data
            */
            public function calculate_shipping_override($package = array(), $override_packaging_heuristic = '')
            {
                write_log('Packaging heuristic to being used: ' . $override_packaging_heuristic);

                $final_tarification        = array();
                $packages_for_tarification = apply_filters('whq_wcchp_override_packages_for_tarification', array(), array( $package, $override_packaging_heuristic ));

                if (! empty($packages_for_tarification) || is_array($packages_for_tarification)) {
                    foreach ($packages_for_tarification as $tarif_package) {
                        if (count($tarif_package) >= 4 &&
                        ! empty($tarif_package['weight']) &&
                        ! empty($tarif_package['length']) &&
                        ! empty($tarif_package['width']) &&
                        ! empty($tarif_package['height']) &&
                        filter_var($tarif_package['weight'], FILTER_VALIDATE_FLOAT) !== false &&
                        filter_var($tarif_package['length'], FILTER_VALIDATE_INT) !== false &&
                        filter_var($tarif_package['width'], FILTER_VALIDATE_INT) !== false &&
                        filter_var($tarif_package['height'], FILTER_VALIDATE_INT) !== false) {
                            $weight = $tarif_package['weight'];
                            $length = $tarif_package['length'];
                            $width  = $tarif_package['width'];
                            $height = $tarif_package['height'];

                            $result = $this->get_tarification($package, $weight, $length, $width, $height);

                            // add the rate to the final_tarification
                            if (count($final_tarification) == 0) {
                                $final_tarification = $result;
                            } else {
                                foreach ($result as $key => $value) {
                                    // for every available service add the rate of this item (already multiplied with the quantity) to the final rate
                                    $final_tarification[$key][2] += $value[2];
                                }
                            }
                        }
                    }
                } else {
                    $final_tarification = false;
                }

                if (true === WP_DEBUG) {
                    write_log($final_tarification);
                }

                $this->add_rates($final_tarification);
            }

            /**
             * get_tarification method, retrieves Chilexpress's data directly from the API
             * @param  array $package Package data
             * @param  integer $weight  Package weight, in kg
             * @param  integer $length  Package length, in cm
             * @param  integer $width   Package width, in cm
             * @param  integer $height  Package height, in cm
             * @return integer          Chilexpress's tarification value
             */
            private function get_tarification($package, $weight, $length, $width, $height)
            {
                if (isset($_POST['s_city']) && ! is_null($_POST['s_city'])) {
                    $city = sanitize_text_field($_POST['s_city']);
                } else {
                    // And what about WC()->customer->get_shipping_city() ?
                    $city = $package['destination']['city'];
                }

                if (!is_null($city)) {
                    // Transform city name to city code
                    $cities = $this->get_cities();

                    if (is_array($cities)) {
                        foreach ($cities as $CodComuna => $GlsComuna) {
                            if ($city == $GlsComuna) {
                                $city = $CodComuna;
                                break;
                            }
                        }
                    }

                    $chp_cost = whq_wcchp_get_tarification($city, $this->shipping_origin, $weight, $length, $width, $height);

                    if (false === $chp_cost) {
                        $chp_estimated = 0;
                    } else {
                        $chp_estimated = $chp_cost->respValorizarCourier->Servicios;
                    }

                    $service_value = 0;

                    if (is_array($chp_estimated)) {
                        // ULTRA RÁPIDO:1
                        // OVERNIGHT:2
                        // DÍA HÁBIL SIGUIENTE:3
                        // DÍA HÁBIL SUBSIGUIENTE:4
                        // TERCER DÍA:5

                        $supported_shipments_types = $this->get_chilexpress_option('shipments_types');

                        if (false === $supported_shipments_types) {
                            // We need some default values in case the admin hasn't configured this yet
                            $supported_shipments_types = array( 2, 3, 4, 5 );
                        }

                        // https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/164
                        if (!is_array($supported_shipments_types)) {
                            $supported_shipments_types = array( $supported_shipments_types );
                        }

                        write_log($supported_shipments_types);

                        $rates = array();

                        foreach ($chp_estimated as $key => $value) {
                            write_log('Servicio: ' . '[' . $value->CodServicio . ']' . $value->GlsServicio . ', valor ' . $value->ValorServicio);

                            // We don't wan't to support other kind of shippments for now
                            if ($value->CodServicio >= 6) {
                                continue;
                            }

                            // Not supported by this store?
                            if (! in_array($value->CodServicio, $supported_shipments_types)) {
                                write_log('[' . $value->CodServicio . ']' . $value->GlsServicio . ' Not supported by the Store!');
                                continue;
                            } else {
                                write_log('[' . $value->CodServicio . ']' . $value->GlsServicio . ' is supported');
                            }

                            $service_id    = $this->id . ':' . $value->CodServicio;
                            $service_label = $this->title . ' (' . $value->GlsServicio . ')';
                            $service_value = $value->ValorServicio;

                            if (false === $service_value || empty($service_value)) {
                                $service_id    = $this->id . '_0';
                                $service_value = 0;
                            }

                            // Add extra fixed_packing_price cost per item
                            if(!empty($this->fixed_packing_price) && $this->fixed_packing_price > 0) {
                                $service_value = $service_value + absint($this->fixed_packing_price);
                            }

                            $rates[ $service_id ] = array( $service_id, $service_label, $service_value );
                        }

                        // Return outside the conditional (for filtering)
                    } else {
                        if (false === $chp_cost) {
                            $service_id    = $this->id . ':0';
                            $service_label = $this->title . ' (No Disponible)';
                            $service_value = 0;
                        } else {
                            $service_id    = $this->id . ':' . $chp_cost->respValorizarCourier->Servicios->CodServicio;
                            $service_label = $this->title . ' (' . $chp_cost->respValorizarCourier->Servicios->GlsServicio . ')';
                            $service_value = $chp_cost->respValorizarCourier->Servicios->ValorServicio;
                        }

                        // Add extra fixed_packing_price cost per item
                        if(!empty($this->fixed_packing_price) && $this->fixed_packing_price > 0) {
                            $service_value = $service_value + absint($this->fixed_packing_price);
                        }

                        $rates = array();

                        $rates[ $service_id ] = array( $service_id, $service_label, $service_value );

                        // Return outside the conditional (for filtering)
                    }

                    $rates = apply_filters('whq_wcchp_tarification_rates', $rates);

                    write_log($rates);

                    return $rates;
                }
            }

            /**
             * add_rates method, add the calculated rate to WC's cart and checkout
             * @param integer $rates Rate to add
             */
            private function add_rates($rates)
            {
                foreach ($rates as $key => $values) {
                    // ULTRA RÁPIDO:1
                    // OVERNIGHT:2
                    // DÍA HÁBIL SIGUIENTE:3
                    // DÍA HÁBIL SUBSIGUIENTE:4
                    // TERCER DÍA:5

                    if (!empty($this->shipments_types_names)) {
                        $renames = explode(',', $this->shipments_types_names);

                        if (count($renames) == 5) {
                            if (stripos($values[1], 'ultra rapido') !== false) {
                                $values[1] = $this->title . ' (' . $renames[0] . ')';
                            }

                            if (stripos($values[1], 'overnight') !== false) {
                                $values[1] = $this->title . ' (' . $renames[1] . ')';
                            }

                            if (stripos($values[1], 'dia habil siguiente') !== false) {
                                $values[1] = $this->title . ' (' . $renames[2] . ')';
                            }

                            if (stripos($values[1], 'dia habil subiguiente') !== false) {
                                $values[1] = $this->title . ' (' . $renames[3] . ')';
                            }

                            if (stripos($values[1], 'tercer dia') !== false) {
                                $values[1] = $this->title . ' (' . $renames[4] . ')';
                            }
                        }
                    }

                    $values[1] = apply_filters('whq_wcchp_rates_label', $values[1]);

                    $this->add_rate(array(
                        'id'    => $values[0], // service_id
                        'label' => $values[1], // service_label
                        'cost'  => $values[2], // service_cost
                    ));
                }
            }

            public static function create_states($states)
            {
                $regions      = $this->get_states();
                $states['CL'] = array();

                foreach ($regions as $key => $city) {
                    $states['CL'][$key] = $city;
                }

                return $states;
            }

            public static function add_cart_fee(WC_Cart $cart)
            {
                WC()->cart->calculate_shipping();
            }

            /**
             * Validate any checkbox field
             */
            public function validate_checkbox_field($key, $value)
            {
                if ($key == 'shipping_zones_support') {
                    if ($value == 1) {
                        // Update value by hand, so we can redirect our users to the next step (Setting a Shipping Zone)
                        $wcchp_options                           = get_option('woocommerce_chilexpress_settings');
                        $wcchp_options['shipping_zones_support'] = 'yes';

                        update_option('woocommerce_chilexpress_settings', $wcchp_options);

                        wp_redirect('admin.php?page=wc-settings&tab=shipping&section=');
                        die();
                    }
                }

                return ! is_null($value) ? 'yes' : 'no';
            }

            /**
             * Validate any number field
             */
            public function validate_number_field($key, $value)
            {
                // Validate the minimum and maximum cache time limit
                if ($key == 'locations_cache') {
                    if (isset($value) && $value < 7) {
                        WC_Admin_Settings::add_error(esc_html__('El caché mínimo para las localidades y regiones es de una semana (7 días).', 'whq-wcchp'));

                        $value = 7;
                    }

                    if (isset($value) && $value > 60) {
                        WC_Admin_Settings::add_error(esc_html__('El caché máximo para las localidades y regiones es de dos meses (60 días).', 'whq-wcchp'));

                        $value = 60;
                    }
                }

                // Validate the extra package wrapper
                if ($key == 'extra_wrapper') {
                    if (isset($value) && $value < 0) {
                        WC_Admin_Settings::add_error(esc_html__('El valor mínimo es 0 centímetros.', 'whq-wcchp'));

                        $value = 0;
                    }

                    if (isset($value) && $value > 30) {
                        WC_Admin_Settings::add_error(esc_html__('¿Estás seguro que necesitas 30 o más centímetros extra para la caja/embalaje?.', 'whq-wcchp')); //Will leave the value in there, just warn the user
                    }
                }

                return $value;
            }
        }
    }
}
