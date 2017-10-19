jQuery(document).ready(function( $ ) {
	//Dismiss admin notice
	jQuery('body').on( 'click', '.whq_wcchp_incompatible_plugins > .notice-dismiss', function() {
		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: 'whq_wcchp_incompatible_plugins_dismiss_ajax'
			},
			success: function( response ) {
			}
		});
	});

	//Disable Shipping Zones support
	jQuery('body').on( 'click', '.wcchp_disable_shipping_zones_support', function() {
		var instance_id = $(this).attr('href');
		instance_id = instance_id.replace('#', '');

		jQuery.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				action: 'whq_wcchp_disable_shipping_zones_support_ajax',
				instance_id: instance_id
			},
			success: function( response ) {
				if ( response == 1 ) {
					window.location.href = 'admin.php?page=wc-settings&tab=shipping&section=chilexpress';
				}
			}
		});
	});
});
