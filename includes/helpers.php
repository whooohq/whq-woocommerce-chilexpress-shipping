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

