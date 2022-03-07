<?php
if (!defined('ABSPATH')) {
	die();
}

/**
 * Scripts enqueue
 */
add_action( 'wp_enqueue_scripts', 'whq_wcchp_enqueue_scripts' );
function whq_wcchp_enqueue_scripts() {
	global $whq_wcchp_default;

	//$whq_wcchp_active = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );
	$whq_wcchp_active = whq_get_chilexpress_option( 'enabled' );

	if( false === $whq_wcchp_active ) {
		$whq_wcchp_active == 'no';
	}

	if( $whq_wcchp_active == 'yes' ) {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( is_admin() ) {
			wp_enqueue_script( 'whq_wcchp_admin', $whq_wcchp_default['plugin_url'] . 'assets/js/whq_wcchp_admin.js', array( 'jquery' ), $whq_wcchp_default['plugin_version'], true );
		}

		if ( is_cart() || ( false !== whq_wcchp_rpship_calc_options() && is_product() ) ) {
			wp_enqueue_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full' . $suffix . '.js', array( 'jquery' ), '4.0.3' );
			wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css' );

			wp_enqueue_script( 'whq_wcchp_cart', $whq_wcchp_default['plugin_url'] . 'assets/js/whq_wcchp_cart.js', array('jquery', 'woocommerce', 'jquery-blockui', 'select2'), $whq_wcchp_default['plugin_version'], true );
		}

		// https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/104
		if( is_checkout() && ! is_wc_endpoint_url( 'order-pay' ) ) {
			wp_enqueue_script( 'whq_wcchp_checkout', $whq_wcchp_default['plugin_url'] . 'assets/js/whq_wcchp_checkout.js', array('jquery', 'woocommerce', 'jquery-blockui', 'select2'), $whq_wcchp_default['plugin_version'], true );
		}
	}
}

add_action( 'admin_enqueue_scripts', 'whq_wcchp_admin_enqueue_scripts' );
function whq_wcchp_admin_enqueue_scripts() {
	global $whq_wcchp_default;

	//$whq_wcchp_active = WC_WHQ_Chilexpress_Shipping::get_chilexpress_option( 'enabled' );
	$whq_wcchp_active = whq_get_chilexpress_option( 'enabled' );

	if( false === $whq_wcchp_active ) {
		$whq_wcchp_active == 'no';
	}

	if( $whq_wcchp_active == 'yes' ) {
		wp_enqueue_script( 'whq_wcchp_admin', $whq_wcchp_default['plugin_url'] . 'assets/js/whq_wcchp_admin.js', array( 'jquery' ), $whq_wcchp_default['plugin_version'], true );
	}
}

add_action( 'wp_footer', 'whq_wcchp_debug' );
function whq_wcchp_debug() {
	if ( true === WP_DEBUG ) {
		$jsdebug = true;
	} else {
		$jsdebug = false;
	}

	$shipping_calc_enabled = whq_wcchp_rpship_calc_options();
	?>
		<script type="text/javascript">
			var whq_wcchp_jsdebug                  = '<?php echo $jsdebug; ?>';
			var whq_wcchp_shipping_calc_on_product = '<?php echo $shipping_calc_enabled; ?>';
		</script>
	<?php
}
