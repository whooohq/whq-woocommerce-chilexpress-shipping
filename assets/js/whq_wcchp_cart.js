var whq_wcchp_code_reg;

jQuery(document).ready(function( $ ) {
	//Only on WooCommerce's Cart
	if( jQuery('.woocommerce-cart').length ) {
		jQuery('body').on('click', '.shipping-calculator-button', function() {
			whq_wcchp_cart_watcher();

			//CL detected
			if( jQuery('#calc_shipping_country').val() == 'CL' ) {
				whq_wcchp_cart_chile_detected();
			} else {
				whq_wcchp_cart_inputs_restore();
			}
		});

		//Load cities
		jQuery('body').on('change', '#calc_shipping_state', function() {
			whq_wcchp_code_reg = jQuery('#calc_shipping_state').val();

			whq_wcchp_cart_load_cities( whq_wcchp_code_reg );
		});
	}

	//Fix DOM refresh when changing shipping method
	jQuery('body').on('click', '.shipping_method', function() {
		if( jQuery('body').hasClass('wc-chilexpress-enabled') ) {
			jQuery('body').removeClass('wc-chilexpress-enabled');
		}
	});

	//Fix Update Totals button behavior
	jQuery('body').on('click', 'button[name="calc_shipping"]', function() {
		whq_wcchp_cart_inputs_restore();
	});
});

function whq_wcchp_cart_watcher() {
	jQuery('body').on('change', '#calc_shipping_country', function() {
		//CL detected
		if( jQuery('#calc_shipping_country').val() == 'CL' ) {
			whq_wcchp_cart_chile_detected();
		} else {
			whq_wcchp_cart_inputs_restore();
		}
	});
}

function whq_wcchp_cart_chile_detected() {
	if( jQuery('body').hasClass('wc-chilexpress-enabled') ) {
		return;
	}

	jQuery('body').addClass('wc-chilexpress-enabled');

	whq_wcchp_cart_inputs_replace();

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
				whq_wcchp_cart_inputs_restore();
			} else {
				jQuery('#calc_shipping_state').prop('disabled', false).empty().append('<option value=""></option>');

				jQuery(response.data).each(function( i ) {
					jQuery('#calc_shipping_state').append('<option value="'+response.data[i]['idRegion']+'"> '+response.data[i]['GlsRegion']+' </option>');
				});

				if( !jQuery('#calc_shipping_state').hasClass('select2-hidden-accessible') ) {
					jQuery('#calc_shipping_state').select2();
					jQuery('.select2-container').css('width', '100%'); //Select2 width fix

					jQuery('#calc_shipping_state_field').unblock();
				}

				//Let user know that he/she needs to select a Region first
				jQuery('#calc_shipping_city').prop('disabled', false).empty().append('<option value="">Selecciona tu región primero, por favor.</option>');
			}
		}
	});
}

function whq_wcchp_cart_load_cities( region_code ) {
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
				jQuery('#calc_shipping_city').prop('disabled', false).empty().append('<option value=""></option>');

				if( jQuery.isArray( response.data ) ) {
					jQuery(response.data).each(function( i ) {
						jQuery('#calc_shipping_city').append('<option value="'+response.data[i]['CodComuna']+'"> '+response.data[i]['GlsComuna']+' </option>');
					});
				} else {
					jQuery('#calc_shipping_city').append('<option value="'+response.data['CodComuna']+'"> '+response.data['GlsComuna']+' </option>');
				}

				if( !jQuery('#calc_shipping_city').hasClass('select2-hidden-accessible') ) {
					jQuery('#calc_shipping_city').select2();
					jQuery('.select2-container').css('width', '100%'); //Select2 width fix

					jQuery('#calc_shipping_city_field').unblock();
				}
			}
		}
	});
}

function whq_wcchp_cart_inputs_replace() {
	if( jQuery('#calc_shipping_city, #calc_shipping_state').is('input') ) {
		//Show city field
		jQuery('#calc_shipping_city_field').show();
		jQuery('#calc_shipping_postcode_field').hide();

		//Block UI
		jQuery('#calc_shipping_city_field, #calc_shipping_state_field').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		//Replace with selects
		jQuery("#calc_shipping_state").replaceWith('<select id="calc_shipping_state" name="calc_shipping_state" disabled="disabled"></select>')
		jQuery("#calc_shipping_city").replaceWith('<select id="calc_shipping_city" name="calc_shipping_city" disabled="disabled"></select>');
	}
}

function whq_wcchp_cart_inputs_restore() {
	if( jQuery('#calc_shipping_city, #calc_shipping_state').is('select') ) {
		//Hide city field
		jQuery('#calc_shipping_city_field').hide();
		jQuery('#calc_shipping_postcode_field').show();

		//Replace with inputs
		jQuery("#calc_shipping_state").replaceWith('<input type="text" class="input-text" name="calc_shipping_state" id="calc_shipping_state" placeholder="State / County" />');
		jQuery("#calc_shipping_city").replaceWith('<input type="text" class="input-text" name="calc_shipping_city" id="calc_shipping_city" placeholder="City" />');

		//Avoid duplicated select2
		jQuery('#calc_shipping_city').next('.select2-container').remove();

		//Remove our trigger class from body
		jQuery('body').removeClass('wc-chilexpress-enabled');

		//Unblock the UI
		jQuery('#calc_shipping_city_field, #calc_shipping_state_field').unblock();
	}
}
