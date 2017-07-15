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
