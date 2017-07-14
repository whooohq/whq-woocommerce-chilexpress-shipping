var whq_wcchp_location;
var whq_wcchp_code_reg;

jQuery(document).ready(function( $ ) {
	//Only on WooCommerce's Checkout
	if( jQuery('.woocommerce-checkout').length ) {
		//CL detected
		if(jQuery('#billing_country').val() == 'CL' || jQuery('#shipping_country').val() == 'CL') {
			whq_wcchp_checkout_chile_detected();
		}

		//CL detected
		jQuery('body').on('change', '#billing_country', function() {
			if(jQuery('#billing_country').val() == 'CL') {
				whq_wcchp_checkout_chile_detected();
			} else {
				whq_wcchp_checkout_restore();
			}
		});

		//CL detected
		jQuery('body').on('change', '#shipping_country', function() {
			if(jQuery('#shipping_country').val() == 'CL') {
				whq_wcchp_checkout_chile_detected();
			} else {
				whq_wcchp_checkout_restore();
			}
		});

		//Load cities
		jQuery('body').on('change', '#billing_state, #shipping_state', function() {
			if( jQuery('#ship-to-different-address-checkbox').is(':checked') ) {
				whq_wcchp_code_reg = jQuery('#shipping_state').val();
			} else {
				whq_wcchp_code_reg = jQuery('#billing_state').val();
			}

			whq_wcchp_checkout_load_cities( whq_wcchp_code_reg );
		});

		//Fix Select2 width
		jQuery('#ship-to-different-address-checkbox').click(function() {
			if ( jQuery('#ship-to-different-address-checkbox').is(':checked') ) {
				jQuery('.select2-container').css('width', '100%');
			}
		});

		//No Chilexpress API available?
		jQuery('body').on('click', '.shipping_method', function() {
			if( !jQuery('body').hasClass('wc-chilexpress-enabled') ) {
				jQuery('#shipping_method_0_chilexpress').prop('disabled', true);
			}
		});

		var whq_wcchp_chilexpress_down = setInterval(function() {
			if( ! jQuery('body').hasClass('wc-chilexpress-enabled') && jQuery('#shipping_method_0_chilexpress').length ) {
				jQuery('#shipping_method_0_chilexpress').prop('disabled', true);
				jQuery('label[for="shipping_method_0_chilexpress"]').html('Chilexpress no se encuentra disponible por el momento. Por favor, inténtelo más tarde.');
			}
		}, 250);
	}
});

function whq_wcchp_checkout_chile_detected() {
	if( jQuery('body').hasClass('wc-chilexpress-enabled') ) {
		return;
	}

	jQuery('body').addClass('wc-chilexpress-enabled');

	whq_wcchp_checkout_inputs_replace();

	jQuery.ajax({
		url: woocommerce_params.ajax_url,
		data: {
			action: 'whq_wcchp_regions_ajax'
		},
		type: 'POST',
		datatype: 'application/json',
		success: function( response ) {
			if(response.success === false) {
				//Chilexpress down
				whq_wcchp_checkout_restore();

				jQuery('#shipping_city_field, #shipping_state_field').unblock();
				jQuery('#billing_city_field, #billing_state_field').unblock();
			} else {
				whq_wcchp_checkout_inputs_replace(); //Why WooCommerce override the first one?

				jQuery('#billing_state, #shipping_state').prop('disabled', false).empty().append('<option value=""></option>');

				jQuery(response.data).each(function( i ) {
					jQuery('#billing_state, #shipping_state').append('<option value="'+response.data[i]['idRegion']+'"> '+response.data[i]['GlsRegion']+' </option>');
				});

				if( !jQuery('#billing_state, #shipping_state').hasClass('select2-hidden-accessible') ) {
					jQuery('#billing_state, #shipping_state').select2();
					jQuery('#billing_state_field, #shipping_state_field').unblock();
				}
			}
		}
	});
}

function whq_wcchp_checkout_load_cities( region_code ) {
	if(region_code == '') {
		region_code = '99'; //Bring it on!
	}

	jQuery.ajax({
		url: woocommerce_params.ajax_url,
		data: {
			action: 'whq_wcchp_cities_ajax',
			codregion: region_code,
			codtipocobertura: '2'
		},
		type: 'POST',
		datatype: 'application/json',
		success: function( response ) {
			if(response.success === true) {
				jQuery('#billing_city, #shipping_city').prop('disabled', false).empty().append('<option value=""></option>');

				if( jQuery.isArray( response.data ) ) {
					jQuery(response.data).each(function( i ) {
						jQuery('#billing_city, #shipping_city').append('<option value="'+response.data[i]['CodComuna']+'"> '+response.data[i]['GlsComuna']+' </option>');
					});
				} else {
					jQuery('#billing_city, #shipping_city').append('<option value="'+response.data['CodComuna']+'"> '+response.data['GlsComuna']+' </option>');
				}

				jQuery('#billing_city, #shipping_city').select2();
				jQuery('#billing_city_field, #billing_state_field').unblock();
			}
		}
	});
}

function whq_wcchp_checkout_inputs_replace() {
	if( jQuery('#billing_city, #shipping_city').is('input') ) {
		jQuery('#billing_city_field, #billing_state_field').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		jQuery("#billing_city").replaceWith('<select id="billing_city" name="billing_city" disabled="disabled"></select>')
		jQuery("#shipping_city").replaceWith('<select id="shipping_city" name="shipping_city" disabled="disabled"></select>');
	}

	if( jQuery('#billing_state, #shipping_state').is('input') ) {
		jQuery('#shipping_city_field, #shipping_state_field').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		jQuery("#billing_state").replaceWith('<select id="billing_state" name="billing_state" disabled="disabled"></select>');
		jQuery("#shipping_state").replaceWith( '<select id="shipping_state" name="shipping_state" disabled="disabled"></select>' );
	}
}

function whq_wcchp_checkout_restore() {
	if( jQuery('#billing_city, #shipping_city').is('select') ) {
		jQuery("#billing_city").replaceWith('<input type="text" class="input-text" name="billing_city" id="billing_city" placeholder=""  value="" autocomplete="address-level2" />');
		jQuery("#shipping_city").replaceWith('<input type="text" class="input-text" name="shipping_city" id="shipping_city" placeholder=""  value="" autocomplete="address-level2" />');
	}

	if( jQuery('#billing_state, #shipping_state').is('select') ) {
		jQuery("#billing_state").replaceWith('<input type="text" class="input-text" value=""  placeholder="" name="billing_state" id="billing_state" autocomplete="address-level1" />');
		jQuery("#shipping_state").replaceWith('<input type="text" class="input-text" value=""  placeholder="" name="shipping_state" id="shipping_state" autocomplete="address-level1" />');
	}

	jQuery('body').removeClass('wc-chilexpress-enabled');
}
