var whq_wcchp_location;
var whq_wcchp_code_reg;

jQuery(document).ready(function( $ ) {
	//Only on WooCommerce's checkout
	if( jQuery('.woocommerce-checkout').length ) {
		//CL detected
		if(jQuery('#billing_country').val() == 'CL' || jQuery('#shipping_country').val() == 'CL') {
			whq_wcchp_chile_detected();
		}

		//CL detected
		jQuery('body').on('change', '#billing_country', function() {
			if(jQuery('#billing_country').val() == 'CL') {
				whq_wcchp_chile_detected();
			} else {
				whq_wcchp_inputs_restore();
			}
		});

		//CL detected
		jQuery('body').on('change', '#shipping_country', function() {
			if(jQuery('#shipping_country').val() == 'CL') {
				whq_wcchp_chile_detected();
			} else {
				whq_wcchp_inputs_restore();
			}
		});

		//Load cities
		/*jQuery('body').on('change', '#billing_state', function() {
			whq_wcchp_code_reg = jQuery('#billing_state').val();
			whq_wcchp_load_cities( whq_wcchp_code_reg );
		});*/

		//Load cities
		/*jQuery('body').on('change', '#shipping_state', function() {
			whq_wcchp_code_reg = jQuery('#shipping_state').val();
			whq_wcchp_load_cities( whq_wcchp_code_reg );
		});*/

		//Mark Chilexpress as preferred shipping method
		jQuery('body').on('change', '#billing_city, #shipping_city', function() {
			if( jQuery('#ship-to-different-address-checkbox').is(':checked') ) {
				whq_wcchp_location = jQuery('#shipping_city').val();
			} else {
				whq_wcchp_location = jQuery('#billing_city').val();
			}

			if( whq_wcchp_location !== '' ) {
				var whq_wcchp_select_chilexpress = setInterval(function() {
					if( jQuery('#shipping_method_0_chilexpress').length ) {
						jQuery('#shipping_method_0_chilexpress').click();
						clearInterval( whq_wcchp_select_chilexpress );
					}
				}, 500);
			}
		});
	}
});

function whq_wcchp_chile_detected() {
	whq_wcchp_inputs_replace();

	jQuery.ajax({
		url: woocommerce_params.ajax_url,
		data: {
			action: 'whq_wcchp_regions_ajax'
		},
		type: 'POST',
		datatype: 'application/json',
		success: function( response ) {
			whq_wcchp_inputs_replace();

			jQuery('#billing_state').prop('disabled', false).empty().append('<option value=""></option>');
			jQuery('#shipping_state').prop('disabled', false).empty().append('<option value=""></option>');

			jQuery(response).each(function( i ) {
				jQuery('#billing_state').append('<option value="'+response[i]['idRegion']+'"> '+response[i]['GlsRegion']+' </option>');
				jQuery('#shipping_state').append('<option value="'+response[i]['idRegion']+'"> '+response[i]['GlsRegion']+' </option>');
			});

			if( jQuery('#ship-to-different-address-checkbox').is(':checked') ) {
				whq_wcchp_code_reg = jQuery('#shipping_state').val();
			} else {
				whq_wcchp_code_reg = jQuery('#billing_state').val();
			}

			whq_wcchp_load_cities( whq_wcchp_code_reg );
		}
	});
}

function whq_wcchp_load_cities( whq_wcchp_code_reg ) {
	jQuery.ajax({
		url: woocommerce_params.ajax_url,
		data: {
			action: 'whq_wcchp_cities_ajax',
			codregion: '99',
			codtipocobertura: '2'
		},
		type: 'POST',
		datatype: 'application/json',
		success: function( response ) {
			jQuery('#billing_city').prop('disabled', false).empty().append('<option value=""></option>');
			jQuery('#shipping_city').prop('disabled', false).empty().append('<option value=""></option>');

			jQuery(response).each(function( i ) {
				jQuery('#billing_city').append('<option value="'+response[i]['CodComuna']+'"> '+response[i]['GlsComuna']+' </option>');
				jQuery('#shipping_city').append('<option value="'+response[i]['CodComuna']+'"> '+response[i]['GlsComuna']+' </option>');
			});
		}
	});
}

function whq_wcchp_inputs_replace() {
	jQuery("#billing_city").replaceWith('<select id="billing_city" name="billing_city" disabled="disabled"></select>');
	jQuery("#billing_state").replaceWith('<select id="billing_state" name="billing_state" disabled="disabled"></select>');
	jQuery("#shipping_city").replaceWith('<select id="shipping_city" name="shipping_city" disabled="disabled"></select>');
	jQuery("#shipping_state").replaceWith( '<select id="shipping_state" name="shipping_state" disabled="disabled"></select>' );
}

function whq_wcchp_inputs_restore() {
	jQuery("#billing_city").replaceWith('<input type="text" class="input-text " name="billing_city" id="billing_city" placeholder=""  value="" autocomplete="address-level2" />');
	jQuery("#billing_state").replaceWith('<input type="text" class="input-text " value=""  placeholder="" name="billing_state" id="billing_state" autocomplete="address-level1" />');
	jQuery("#shipping_city").replaceWith('<input type="text" class="input-text " name="shipping_city" id="shipping_city" placeholder=""  value="" autocomplete="address-level2" />');
	jQuery("#shipping_state").replaceWith('<input type="text" class="input-text " value=""  placeholder="" name="shipping_state" id="shipping_state" autocomplete="address-level1" />');
}
