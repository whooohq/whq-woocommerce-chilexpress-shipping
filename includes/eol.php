<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * EOL Notice
 */
function whq_wcchp_eolnotice_notice_warn() {
	if ( get_option( 'whq_wcchp_eolnotice' ) !== false ) {
		return;
	}
	?>
	<div class="notice notice-warning is-dismissible whq-chilexpress-eolnotice">
		<p><span class="dashicons dashicons-warning" style="font-size: 2rem; margin: 0 10px 0 0; line-height: 20px;"></span> Importante: Lamentamos informar el término del soporte del plugin <a href="https://wordpress.org/plugins/woo-chilexpress-shipping/" target="_blank">Chilexpress Shipping for WooCommerce</a>. El plugin será dado de baja el 1° de Enero del 2023.</p>
		<p>Te recomendamos <a href="https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/discussions/248" target="_blank">leer la siguiente información</a>, y tomar las medidas apropiadas para no interrumpir el flujo de los envíos en tu tienda.</p>
		<p>Muchas gracias por su atención.</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'whq_wcchp_eolnotice_notice_warn' );

/**
 * EOL close button
 */
function whq_wcchp_eolnotice_dismiss_notice_callback() {
	update_option( 'whq_wcchp_eolnotice', true, 'no' );

	echo '1';
	die();
}
add_action( 'wp_ajax_whq_wcchp_eolnotice_dismiss', 'whq_wcchp_eolnotice_dismiss_notice_callback' );

/**
 * EOL dismiss schedule event (weekly)
 */
function whq_wcchp_weekly_schedule_activation() {
	wp_schedule_event( time(), 'weekly', 'whq_wccchp_eolnotice_weekly_cleanup' );
}
register_activation_hook( $whq_wcchp_default['plugin_file'], 'whq_wcchp_weekly_schedule_activation' );

function whq_wcchp_weekly_schedule_deactivation() {
	wp_clear_scheduled_hook( 'whq_wccchp_eolnotice_weekly_cleanup' );
}
register_deactivation_hook( $whq_wcchp_default['plugin_file'], 'whq_wcchp_weekly_schedule_deactivation' );

function whq_wcchp_schedule_event() {
	if ( ! wp_next_scheduled( 'whq_wccchp_eolnotice_weekly_cleanup' ) ) {
		whq_wcchp_weekly_schedule_activation();
	}
}
add_action( 'admin_init', 'whq_wcchp_schedule_event' );

/**
 * EOL schedule callback
 */
function whq_wcchp_eolnotice_weekly_callback() {
	delete_option( 'whq_wcchp_eolnotice' );
}
add_action( 'whq_wccchp_eolnotice_weekly_cleanup', 'whq_wcchp_eolnotice_weekly_callback' );

/**
 * EOL new weekly schedule
 */
function whq_wcchp_weekly_cron_schedule( $schedules ) {
	$schedules[ 'weekly' ] = [
		'interval' => 60 * 60 * 24 * 7, // Seconds in a week
		'display' => __( 'Weekly' ) ];

	return $schedules;
}
add_filter( 'cron_schedules', 'whq_wcchp_weekly_cron_schedule' );
