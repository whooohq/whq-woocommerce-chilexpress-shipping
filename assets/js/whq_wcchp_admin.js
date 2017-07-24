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
});
