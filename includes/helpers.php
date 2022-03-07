<?php
if (!defined('ABSPATH')) {
	die();
}

// https://stackoverflow.com/a/24622602/920648
function whq_wcchp_array_move( &$array, $old_pos, $new_pos ) {
	if( ! is_array( $array ) ) {
		return;
	}

	if ( $old_pos == $new_pos ) {
		return;
	}

	array_splice($array, max($new_pos, 0), 0, array_splice( $array, max($old_pos, 0), 1) );
}

// https://www.elegantthemes.com/blog/tips-tricks/using-the-wordpress-debug-log
if ( ! function_exists( 'write_log' ) ) {
	function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( '[WCCHP]' . print_r( $log, true ) );
			} else {
				error_log( '[WCCHP]' . $log );
			}
		}
	}
}

/**
 * Check if plugin https://codecanyon.net/item/woocommerce-shipping-calculator-on-product-page/11496815 is installed and activated
 *
 * @return mixed	1 for enabled, false or 0 for disabled or non existent
 */
function whq_wcchp_rpship_calc_options() {
	$rpship_calc_options = get_option( 'rpship-calculator-setting' );

	if( false !== $rpship_calc_options ) {
		$shipping_calc_enabled = $rpship_calc_options['enable_on_productpage'];
		$shipping_calc_type    = $rpship_calc_options['shipping_type'];

		if( $shipping_calc_type == 1 ) {
			// "radio" not supported, prefer "display all shipping with price"
			$shipping_calc_enabled = false;
		}
	} else {
		$shipping_calc_enabled = false;
	}

	return $shipping_calc_enabled;
}

/**
 * Get plugin options from WP's main options table
 */
function whq_get_chilexpress_option( $option_name = '' ) {
	$options = get_option( 'woocommerce_chilexpress_settings' );

	// TODO: detect if shipping zones is enabled, and get only this option from the global array. Others needs to be called from the respective $instance_id
	if ( $option_name == 'shipping_zones_support' ) {
		$options = get_option( 'woocommerce_chilexpress_settings' );
	}

	//https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/25
	if ( false === $options ) {
		return false;
	}

	if ( ! is_array( $options ) ) {
		return false;
	}

	if ( false === array_key_exists( $option_name, $options ) ) {
		return false;
	}

	return $options["$option_name"];
}
