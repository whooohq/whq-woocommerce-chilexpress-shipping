var whq_wcchp_region_select;
var whq_wcchp_region_array;
var whq_wcchp_city_select;
var whq_wcchp_city_array;
var whq_wcchp_region_name;
var whq_wcchp_region_code;
var whq_wcchp_city_name;
var whq_wcchp_city_code;

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

		//Manage regions and load cities
		jQuery('body').on('change', '#calc_shipping_whq_region_select', function() {
			whq_wcchp_region_select = jQuery('#calc_shipping_whq_region_select').val();
			whq_wcchp_region_array  = whq_wcchp_region_select.split('|');

			jQuery('#calc_shipping_state').val( whq_wcchp_region_array[1] );
			jQuery('#calc_shipping_whq_region').val( whq_wcchp_region_array[0] );

			whq_wcchp_cart_load_cities( whq_wcchp_region_array[0] );
		});

		//Manage cities
		jQuery('body').on('change', '#calc_shipping_whq_city_select', function() {
			whq_wcchp_city_select = jQuery('#calc_shipping_whq_city_select').val();
			whq_wcchp_city_array  = whq_wcchp_city_select.split('|');

			jQuery('#calc_shipping_city').val( whq_wcchp_city_array[1] );
			jQuery('#calc_shipping_whq_city').val( whq_wcchp_city_array[0] );
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
			if( response.success === false ) {
				//Chilexpress down
				whq_wcchp_cart_inputs_restore();
			} else {
				jQuery('#calc_shipping_whq_region_select').prop('disabled', false).empty().append('<option value="|"></option>');

				whq_wcchp_region_name = jQuery('#calc_shipping_state').val();
				whq_wcchp_region_code = '';

				jQuery(response.data).each(function( i ) {

					if( response.data[i].GlsRegion == whq_wcchp_region_name ) {
						whq_wcchp_region_code = response.data[i].idRegion;

						jQuery('#calc_shipping_whq_region').val(whq_wcchp_region_code);
						jQuery('#calc_shipping_whq_region_select').append('<option value="' + response.data[i].idRegion + '|' + response.data[i].GlsRegion + '" selected> ' +response.data[i].GlsRegion + ' </option>');
					} else {
						jQuery('#calc_shipping_whq_region_select').append('<option value="' + response.data[i].idRegion + '|' + response.data[i].GlsRegion + '"> ' + response.data[i].GlsRegion + ' </option>');
					}

				});

				if( !jQuery('#calc_shipping_whq_region_select').hasClass('select2-hidden-accessible') ) {
					jQuery('#calc_shipping_whq_region_select').select2();
					jQuery('.select2-container').css('width', '100%'); //Select2 width fix
					jQuery('#calc_shipping_state_field').unblock();
				}

				if( whq_wcchp_region_code !== '' ) {
					whq_wcchp_cart_load_cities( whq_wcchp_region_code );
				} else {
					jQuery('#calc_shipping_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');
				}
			}
		}
	});
}

function whq_wcchp_cart_load_cities( region_code ) {
	if(region_code === '') {
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
			if( response.success === false ) {
				//Chilexpress API down? error?
				whq_wcchp_cart_inputs_restore();
			} else {
				jQuery('#calc_shipping_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');

				var whq_wcchp_city_name = jQuery('#calc_shipping_city').val();
				var whq_wcchp_city_code = '';

				if( jQuery.isArray( response.data ) ) {

					jQuery(response.data).each(function( i ) {
						if( response.data[i].GlsComuna == whq_wcchp_city_name ) {
							whq_wcchp_city_code = response.data[i].CodComuna;

							jQuery('#calc_shipping_whq_city').val(whq_wcchp_city_code);
							jQuery('#calc_shipping_whq_city_select').append('<option value="' + response.data[i].CodComuna + '|' + response.data[i].GlsComuna + '" selected> ' + response.data[i].GlsComuna + ' </option>');
						} else {
							jQuery('#calc_shipping_whq_city_select').append('<option value="' + response.data[i].CodComuna + '|' + response.data[i].GlsComuna + '"> ' + response.data[i].GlsComuna + ' </option>');
						}
					});

				} else {

					if( response.data.GlsComuna == whq_wcchp_city_name ) {
						whq_wcchp_city_code = response.data.CodComuna;
						jQuery('#calc_shipping_whq_city').val(whq_wcchp_city_code);
						jQuery('#calc_shipping_whq_city_select').append('<option value="' + response.data.CodComuna + '|' + response.data.GlsComuna + '" selected> ' + response.data.GlsComuna + ' </option>');
					} else {
						jQuery('#calc_shipping_whq_city_select').append('<option value="' + response.data.CodComuna + '|' +response.data.GlsComuna + '"> ' + response.data.GlsComuna + ' </option>');
					}

				}

				$code_and_city = jQuery('#calc_shipping_whq_city').val() + '|' + jQuery('#calc_shipping_city').val();

				if ( $code_and_city != jQuery('#calc_shipping_whq_city_select').val() ) {
					jQuery('#calc_shipping_whq_city').val('');
					jQuery('#calc_shipping_city').val('');
				}

				if( !jQuery('#calc_shipping_whq_city_select').hasClass('select2-hidden-accessible') ) {
					jQuery('#calc_shipping_whq_city_select').select2();
					jQuery('.select2-container').css('width', '100%'); //Select2 width fix
					jQuery('#calc_shipping_city_field').unblock();
				}
			}
		}
	});
}

function whq_wcchp_cart_inputs_replace() {
	if( jQuery('#calc_shipping_city, #calc_shipping_state').is('input') ) {
		//Show city field and hide postcode field
		jQuery('#calc_shipping_city_field').show();
		jQuery('#calc_shipping_postcode_field').hide();

		//Inserts new fields for manipulation and select
		jQuery("#calc_shipping_state").after('<input type="text" class="input-text" name="calc_shipping_whq_region" id="calc_shipping_whq_region" />');
		jQuery("#calc_shipping_whq_region").after('<select id="calc_shipping_whq_region_select" name="calc_shipping_whq_region_select" disabled="disabled"></select>');
		jQuery('#calc_shipping_state').hide();
		jQuery('#calc_shipping_whq_region').hide();
		jQuery("#calc_shipping_city").after('<input type="text" class="input-text" name="calc_shipping_whq_city" id="calc_shipping_whq_city" />');
		jQuery("#calc_shipping_whq_city").after('<select id="calc_shipping_whq_city_select" name="calc_shipping_whq_city_select" disabled="disabled"><option value="">Selecciona la región primero.</option></select>');
		jQuery('#calc_shipping_city').hide();
		jQuery('#calc_shipping_whq_city').hide();

		//Block UI
		jQuery('#calc_shipping_city_field, #calc_shipping_state_field').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
	}
}

function whq_wcchp_cart_inputs_restore() {
	if( jQuery('#calc_shipping_whq_city_select, #calc_shipping_whq_region_select').is('select') ) {
		//Hide city field and show postcode
		jQuery('#calc_shipping_city_field').hide();
		jQuery('#calc_shipping_postcode_field').show();
		jQuery('#calc_shipping_state').show();

		//Remove inserted fields
		jQuery("#calc_shipping_whq_region").remove();
		jQuery('#calc_shipping_whq_region_select').next('.select2-container').remove();
		jQuery("#calc_shipping_whq_region_select").remove();
		jQuery("#calc_shipping_whq_city").remove();
		jQuery('#calc_shipping_whq_city_select').next('.select2-container').remove();
		jQuery("#calc_shipping_whq_city_select").remove();

		//Remove our trigger class from body
		jQuery('body').removeClass('wc-chilexpress-enabled');

		//Unblock the UI
		jQuery('#calc_shipping_city_field, #calc_shipping_state_field').unblock();
	}
}
