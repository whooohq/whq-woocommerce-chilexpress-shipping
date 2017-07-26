var whq_wcchp_location;
var whq_wcchp_code_reg;
var whq_wcchp_chilexpress_down;
var whq_wcchp_chilexpress_cost;

jQuery(document).ready(function( $ ) {
	//Only on WooCommerce's Checkout
	if( jQuery('.woocommerce-checkout').length ) {
		//CL detection
		if(jQuery('#billing_country').val() == 'CL' || jQuery('#shipping_country').val() == 'CL') {
			whq_wcchp_checkout_chile_detected();
		}
		jQuery('body').on('change', '#billing_country', function() {
			if(jQuery('#billing_country').val() == 'CL') {
				whq_wcchp_checkout_chile_detected();
			} else {
				whq_wcchp_checkout_restore();
			}
		});
		jQuery('body').on('change', '#shipping_country', function() {
			if(jQuery('#shipping_country').val() == 'CL') {
				whq_wcchp_checkout_chile_detected();
			} else {
				whq_wcchp_checkout_restore();
			}
		});

		//Manage billing regions and load cities
		jQuery('body').on('change', '#billing_whq_regopt', function() {
			var whq_wcchp_regbil_option = jQuery('#billing_whq_regopt').val();
			var whq_wcchp_regbil_array = whq_wcchp_regbil_option.split('|');
			jQuery('#billing_state').val(whq_wcchp_regbil_array[1]);
			jQuery('#billing_whq_region').val(whq_wcchp_regbil_array[0]);
			whq_wcchp_checkout_load_cities_billing( whq_wcchp_regbil_array[0] );
		});
		//Manage shipping regions and load cities
		jQuery('body').on('change', '#shipping_whq_regopt', function() {
			var whq_wcchp_regshi_option = jQuery('#shipping_whq_regopt').val();
			var whq_wcchp_regshi_array = whq_wcchp_regshi_option.split('|');
			jQuery('#shipping_state').val(whq_wcchp_regshi_array[1]);
			jQuery('#shipping_whq_region').val(whq_wcchp_regshi_array[0]);
			whq_wcchp_checkout_load_cities_shipping( whq_wcchp_regshi_array[0] );
		});
		//Manage billing cities
		jQuery('body').on('change', '#billing_whq_citopt', function() {
			var whq_wcchp_city_option = jQuery('#billing_whq_citopt').val();
			var whq_wcchp_city_array = whq_wcchp_city_option.split('|');
			jQuery('#billing_city').val(whq_wcchp_city_array[1]);
			jQuery('#billing_whq_city').val(whq_wcchp_city_array[0]);
		});
		//Manage shipping cities
		jQuery('body').on('change', '#shipping_whq_citopt', function() {
			var whq_wcchp_city_option = jQuery('#shipping_whq_citopt').val();
			var whq_wcchp_city_array = whq_wcchp_city_option.split('|');
			jQuery('#shipping_city').val(whq_wcchp_city_array[1]);
			jQuery('#shipping_whq_city').val(whq_wcchp_city_array[0]);
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

		whq_wcchp_chilexpress_down = setInterval(function() {
			if( ! jQuery('body').hasClass('wc-chilexpress-enabled') && jQuery('#shipping_method_0_chilexpress').length ) {
				jQuery('#shipping_method_0_chilexpress').prop('disabled', true).prop('selected', false);

				jQuery('form.woocommerce-checkout').prepend('<div class="whq_wcchp_chilexpress_error woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout"><ul class="woocommerce-error"><li><strong>Chilexpress no se encuentra disponible por el momento. Por favor, iténtelo más tarde.</li></ul></div>');
				jQuery('html, body').animate({ scrollTop: 0 }, 'normal');

				setTimeout(function() {
					jQuery('.whq_wcchp_chilexpress_error').fadeOut(500, function() {
						jQuery(this).remove();
					});
				}, 5000);
			}
		}, 250);

		//Shipping cost zero? https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/13
		jQuery('body').on('click', '#place_order', function() {
			if( jQuery('body').hasClass('wc-chilexpress-enabled') && jQuery('#shipping_method_0_chilexpress').length ) {
				whq_wcchp_chilexpress_cost = jQuery('#shipping_method_0_chilexpress').next('label').children('.amount').text();

				if( whq_wcchp_chilexpress_cost == '' && jQuery('#shipping_method_0_chilexpress').is(':checked') ) {
					jQuery('#shipping_method_0_chilexpress').prop('disabled', true).prop('selected', false);

					jQuery('form.woocommerce-checkout').prepend('<div class="whq_wcchp_chilexpress_error woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout"><ul class="woocommerce-error"><li><strong>Chilexpress no se encuentra disponible en este momento. Por favor, iténtalo en unos minutos.</li></ul></div>');
					jQuery('html, body').animate({ scrollTop: 0 }, 'normal');

					setTimeout(function() {
						jQuery('.whq_wcchp_chilexpress_error').fadeOut(500, function() {
							jQuery(this).remove();
						});
					}, 5000);

					return false;
				}
			}
		});
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
				//whq_wcchp_checkout_inputs_replace(); //Why WooCommerce override the first one?
				jQuery('#billing_whq_regopt, #shipping_whq_regopt').prop('disabled', false).empty().append('<option value=""></option>');

				var whq_wcchp_regbil_name = jQuery('#billing_state').val();
				var whq_wcchp_regbil_code = '';
				var whq_wcchp_regshi_name = jQuery('#shipping_state').val();
				var whq_wcchp_regshi_code = '';
				jQuery(response.data).each(function( i ) {
					if( response.data[i]['GlsRegion'] == whq_wcchp_regbil_name ) {
						whq_wcchp_regbil_code = response.data[i]['idRegion'];
						jQuery('#billing_whq_region').val(whq_wcchp_regbil_code);
						jQuery('#billing_whq_regopt').append('<option value="'+response.data[i]['idRegion']+'|'+response.data[i]['GlsRegion']+'" selected> '+response.data[i]['GlsRegion']+' </option>');
					} else {
						jQuery('#billing_whq_regopt').append('<option value="'+response.data[i]['idRegion']+'|'+response.data[i]['GlsRegion']+'"> '+response.data[i]['GlsRegion']+' </option>');
					}
					if( response.data[i]['GlsRegion'] == whq_wcchp_regshi_name ) {
						whq_wcchp_regshi_code = response.data[i]['idRegion'];
						jQuery('#shipping_whq_region').val(whq_wcchp_regshi_code);
						jQuery('#shipping_whq_regopt').append('<option value="'+response.data[i]['idRegion']+'|'+response.data[i]['GlsRegion']+'" selected> '+response.data[i]['GlsRegion']+' </option>');
					} else {
						jQuery('#shipping_whq_regopt').append('<option value="'+response.data[i]['idRegion']+'|'+response.data[i]['GlsRegion']+'"> '+response.data[i]['GlsRegion']+' </option>');
					}
				});

				if( !jQuery('#billing_whq_regopt, #shipping_whq_regopt').hasClass('select2-hidden-accessible') ) {
					jQuery('#billing_whq_regopt, #shipping_whq_regopt').select2();
					jQuery('#billing_state_field, #shipping_state_field').unblock();
				}

				if( whq_wcchp_regbil_code != '' ) {
					whq_wcchp_checkout_load_cities_billing( whq_wcchp_regbil_code );
				} else {
					jQuery('#billing_whq_citopt').prop('disabled', false).empty().append('<option value="">Selecciona la región primero.</option>');
				}
				if( whq_wcchp_regshi_code != '' ) {
					whq_wcchp_checkout_load_cities_shipping( whq_wcchp_regshi_code );
				} else {
					jQuery('#shipping_whq_citopt').prop('disabled', false).empty().append('<option value="">Selecciona la región primero.</option>');
				}
			}
		}
	});
}

function whq_wcchp_checkout_load_cities_billing( region_code ) {
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
				jQuery('#billing_whq_citopt').prop('disabled', false).empty().append('<option value=""></option>');
				var whq_wcchp_city_name = jQuery('#billing_city').val();
				var whq_wcchp_city_code = '';
				if( jQuery.isArray( response.data ) ) {
					jQuery(response.data).each(function( i ) {
						if( response.data[i]['GlsComuna'] == whq_wcchp_city_name ) {
							whq_wcchp_city_code = response.data[i]['CodComuna'];
							jQuery('#billing_whq_city').val(whq_wcchp_city_code);
							jQuery('#billing_whq_citopt').append('<option value="'+response.data[i]['CodComuna']+'|'+response.data[i]['GlsComuna']+'" selected> '+response.data[i]['GlsComuna']+' </option>');
						} else {
							jQuery('#billing_whq_citopt').append('<option value="'+response.data[i]['CodComuna']+'|'+response.data[i]['GlsComuna']+'"> '+response.data[i]['GlsComuna']+' </option>');
						}
					});
				} else {
					if( response.data['GlsComuna'] == whq_wcchp_city_name ) {
						whq_wcchp_city_code = response.data['CodComuna'];
						jQuery('#billing_whq_city').val(whq_wcchp_city_code);
						jQuery('#billing_whq_citopt').append('<option value="'+response.data['CodComuna']+'|'+response.data['GlsComuna']+'" selected> '+response.data['GlsComuna']+' </option>');
					} else {
						jQuery('#billing_whq_citopt').append('<option value="'+response.data['CodComuna']+'|'+response.data['GlsComuna']+'"> '+response.data['GlsComuna']+' </option>');
					}
				}
				$code_and_city = jQuery('#billing_whq_city').val() + '|' + jQuery('#billing_city').val();
				if ( $code_and_city != jQuery('#billing_whq_citopt').val() ) {
					jQuery('#billing_whq_city').val('');
					jQuery('#billing_city').val('');
				}
				if( !jQuery('#billing_whq_citopt').hasClass('select2-hidden-accessible') ) {
					jQuery('#billing_whq_citopt').select2();
					jQuery('#billing_city_field').unblock();
				}
			}
		}
	});
}

function whq_wcchp_checkout_load_cities_shipping( region_code ) {
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
				jQuery('#shipping_whq_citopt').prop('disabled', false).empty().append('<option value=""></option>');
				var whq_wcchp_city_name = jQuery('#shipping_city').val();
				var whq_wcchp_city_code = '';
				if( jQuery.isArray( response.data ) ) {
					jQuery(response.data).each(function( i ) {
						if( response.data[i]['GlsComuna'] == whq_wcchp_city_name ) {
							whq_wcchp_city_code = response.data[i]['CodComuna'];
							jQuery('#shipping_whq_city').val(whq_wcchp_city_code);
							jQuery('#shipping_whq_citopt').append('<option value="'+response.data[i]['CodComuna']+'|'+response.data[i]['GlsComuna']+'" selected> '+response.data[i]['GlsComuna']+' </option>');
						} else {
							jQuery('#shipping_whq_citopt').append('<option value="'+response.data[i]['CodComuna']+'|'+response.data[i]['GlsComuna']+'"> '+response.data[i]['GlsComuna']+' </option>');
						}
					});
				} else {
					if( response.data['GlsComuna'] == whq_wcchp_city_name ) {
						whq_wcchp_city_code = response.data['CodComuna'];
						jQuery('#shipping_whq_city').val(whq_wcchp_city_code);
						jQuery('#shipping_whq_citopt').append('<option value="'+response.data['CodComuna']+'|'+response.data['GlsComuna']+'" selected> '+response.data['GlsComuna']+' </option>');
					} else {
						jQuery('#shipping_whq_citopt').append('<option value="'+response.data['CodComuna']+'|'+response.data['GlsComuna']+'"> '+response.data['GlsComuna']+' </option>');
					}
				}
				$code_and_city = jQuery('#shipping_whq_city').val() + '|' + jQuery('#shipping_city').val();
				if ( $code_and_city != jQuery('#shipping_whq_citopt').val() ) {
					jQuery('#shipping_whq_city').val('');
					jQuery('#shipping_city').val('');
				}
				if( !jQuery('#shipping_whq_citopt').hasClass('select2-hidden-accessible') ) {
					jQuery('#shipping_whq_citopt').select2();
					jQuery('#shipping_city_field').unblock();
				}
			}
		}
	});
}

function whq_wcchp_checkout_inputs_replace() {
	if( jQuery('#billing_city, #shipping_city').is('input') ) {
		//Inserts new fields for manipulation and select
		jQuery("#billing_city").after('<input type="text" class="input-text" name="billing_whq_city" id="billing_whq_city" />');
		jQuery("#billing_whq_city").after('<select id="billing_whq_citopt" name="billing_whq_citopt" disabled="disabled"></select>');
		jQuery('#billing_city').hide();
		jQuery('#billing_whq_city').hide();
		jQuery("#shipping_city").after('<input type="text" class="input-text" name="shipping_whq_city" id="shipping_whq_city" />');
		jQuery("#shipping_whq_city").after('<select id="shipping_whq_citopt" name="shipping_whq_citopt" disabled="disabled"></select>');
		jQuery('#shipping_city').hide();
		jQuery('#shipping_whq_city').hide();
		jQuery('#billing_city_field, #shipping_city_field').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
	}
	if( jQuery('#billing_state, #shipping_state').is('input') ) {
		//Inserts new fields for manipulation and select
		jQuery("#billing_state").after('<input type="text" class="input-text" name="billing_whq_region" id="billing_whq_region" />');
		jQuery("#billing_whq_region").after('<select id="billing_whq_regopt" name="billing_whq_regopt" disabled="disabled"></select>');
		jQuery('#billing_state').hide();
		jQuery('#billing_whq_region').hide();
		jQuery("#shipping_state").after('<input type="text" class="input-text" name="shipping_whq_region" id="shipping_whq_region" />');
		jQuery("#shipping_whq_region").after('<select id="shipping_whq_regopt" name="shipping_whq_regopt" disabled="disabled"></select>');
		jQuery('#shipping_state').hide();
		jQuery('#shipping_whq_region').hide();
		jQuery('#billing_state_field, #shipping_state_field').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
	}
}

function whq_wcchp_checkout_restore() {
	//Remove inserted fields
	if( jQuery('#billing_whq_citopt, #shipping_whq_citopt').is('select') ) {
		jQuery("#billing_whq_city").remove();
		jQuery("#billing_whq_citopt").next('.select2-container').remove();
		jQuery("#billing_whq_citopt").remove();
		jQuery("#shipping_whq_city").remove();
		jQuery("#shipping_whq_citopt").next('.select2-container').remove();
		jQuery("#shipping_whq_citopt").remove();
	}
	if( jQuery('#billing_whq_regopt, #shipping_whq_regopt').is('select') ) {
		jQuery("#billing_whq_region").remove();
		jQuery("#billing_whq_regopt").next('.select2-container').remove();
		jQuery("#billing_whq_regopt").remove();
		jQuery("#shipping_whq_region").remove();
		jQuery("#shipping_whq_regopt").next('.select2-container').remove();
		jQuery("#shipping_whq_regopt").remove();
	}
	//Remove our trigger class from body
	jQuery('body').removeClass('wc-chilexpress-enabled');
}
