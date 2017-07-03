<?php
if (!defined('ABSPATH')) {
	die();
}

add_filter( 'plugin_action_links_' . $whq_wcchp_default['plugin_basename'], 'whq_wcchp_add_action_links' );
function whq_wcchp_add_action_links ( $links ) {
	$whq_wcchp_link = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=chilexpress' ) . '">Ajustes</a>',
	);

	return array_merge( $whq_wcchp_link, $links );
}
