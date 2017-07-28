var whq_wcchp_chilexpress_down;
var whq_wcchp_chilexpress_cost;
var whq_wcchp_region_billing_select;
var whq_wcchp_region_billing_array;
var whq_wcchp_region_shipping_select;
var whq_wcchp_region_shipping_array;
var whq_wcchp_city_select;
var whq_wcchp_city_array;
var whq_wcchp_region_billing_name;
var whq_wcchp_region_billing_code;
var whq_wcchp_region_shipping_name;
var whq_wcchp_region_shipping_code;
var whq_wcchp_city_name;
var whq_wcchp_city_code;

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
				whq_wcchp_checkout_inputs_restore();
			}
		});

		jQuery('body').on('change', '#shipping_country', function() {
			if(jQuery('#shipping_country').val() == 'CL') {
				whq_wcchp_checkout_chile_detected();
			} else {
				whq_wcchp_checkout_inputs_restore();
			}
		});

		//Manage billing regions and load cities
		jQuery('body').on('change', '#billing_whq_region_select', function() {
			whq_wcchp_region_billing_select = jQuery('#billing_whq_region_select').val();
			whq_wcchp_region_billing_array  = whq_wcchp_region_billing_select.split('|');

			jQuery('#billing_state').val(whq_wcchp_region_billing_array[1]);
			jQuery('#billing_whq_region').val(whq_wcchp_region_billing_array[0]);

			whq_wcchp_checkout_load_cities_billing( whq_wcchp_region_billing_array[0] );
		});

		//Manage shipping regions and load cities
		jQuery('body').on('change', '#shipping_whq_region_select', function() {
			whq_wcchp_region_shipping_select = jQuery('#shipping_whq_region_select').val();
			whq_wcchp_region_shipping_array  = whq_wcchp_region_shipping_select.split('|');

			jQuery('#shipping_state').val(whq_wcchp_region_shipping_array[1]);
			jQuery('#shipping_whq_region').val(whq_wcchp_region_shipping_array[0]);

			whq_wcchp_checkout_load_cities_shipping( whq_wcchp_region_shipping_array[0] );
		});

		//Manage billing cities
		jQuery('body').on('change', '#billing_whq_city_select', function() {
			whq_wcchp_city_select = jQuery('#billing_whq_city_select').val();
			whq_wcchp_city_array  = whq_wcchp_city_select.split('|');

			jQuery('#billing_city').val(whq_wcchp_city_array[1]);
			jQuery('#billing_whq_city').val(whq_wcchp_city_array[0]);
		});

		//Manage shipping cities
		jQuery('body').on('change', '#shipping_whq_city_select', function() {
			whq_wcchp_city_select = jQuery('#shipping_whq_city_select').val();
			whq_wcchp_city_array  = whq_wcchp_city_select.split('|');

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
			if( ! jQuery('body').hasClass('wc-chilexpress-enabled') ) {
				jQuery('#shipping_method_0_chilexpress').prop('disabled', true);
			}
		});

		whq_wcchp_chilexpress_down = setInterval(function() {
			if( ! jQuery('body').hasClass('wc-chilexpress-enabled') && ! jQuery('body').hasClass('wc-chilexpress-down') && jQuery('#shipping_method_0_chilexpress').length ) {
				jQuery('#shipping_method_0_chilexpress').prop('disabled', true).prop('selected', false);

				jQuery('form.woocommerce-checkout').prepend('<div class="whq_wcchp_chilexpress_error woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout"><ul class="woocommerce-error"><li><strong>Chilexpress no se encuentra disponible por el momento. Por favor, inténtelo más tarde.</li></ul></div>');

				if( ! jQuery('body').hasClass('wc-chilexpress-down') ) {
					jQuery('html, body').animate({ scrollTop: 0 }, 'normal');

					jQuery('body').addClass('wc-chilexpress-down');
				}

				setTimeout(function() {
					jQuery('.whq_wcchp_chilexpress_error').fadeOut(500, function() {
						jQuery(this).remove();
					});
				}, 10000);
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
			if( response.success === false ) {
				//Chilexpress API down? error?
				whq_wcchp_checkout_inputs_restore();
			} else {
				jQuery('#billing_whq_region_select, #shipping_whq_region_select').prop('disabled', false).empty().append('<option value=""></option>');

				whq_wcchp_region_billing_name = jQuery('#billing_state').val();
				whq_wcchp_region_billing_code = '';
				whq_wcchp_region_shipping_name = jQuery('#shipping_state').val();
				whq_wcchp_region_shipping_code = '';

				jQuery(response.data).each(function( i ) {
					if( response.data[i].GlsRegion == whq_wcchp_region_billing_name ) {
						whq_wcchp_region_billing_code = response.data[i].idRegion;

						jQuery('#billing_whq_region').val(whq_wcchp_region_billing_code);
						jQuery('#billing_whq_region_select').append('<option value="' + response.data[i].idRegion + '|' + response.data[i].GlsRegion + '" selected> ' + response.data[i].GlsRegion + '</option>');
					} else {
						jQuery('#billing_whq_region_select').append('<option value="' + response.data[i].idRegion + '|' + response.data[i].GlsRegion + '"> ' + response.data[i].GlsRegion + '</option>');
					}
					if( response.data[i].GlsRegion == whq_wcchp_region_shipping_name ) {
						whq_wcchp_region_shipping_code = response.data[i].idRegion;

						jQuery('#shipping_whq_region').val(whq_wcchp_region_shipping_code);
						jQuery('#shipping_whq_region_select').append('<option value="' + response.data[i].idRegion + '|' + response.data[i].GlsRegion + '" selected> ' + response.data[i].GlsRegion + '</option>');
					} else {
						jQuery('#shipping_whq_region_select').append('<option value="' + response.data[i].idRegion + '|' + response.data[i].GlsRegion + '"> ' + response.data[i].GlsRegion + '</option>');
					}
				});

				if( !jQuery('#billing_whq_region_select, #shipping_whq_region_select').hasClass('select2-hidden-accessible') ) {
					jQuery('#billing_whq_region_select, #shipping_whq_region_select').select2();
					jQuery('#billing_state_field, #shipping_state_field').unblock();
				}

				if( whq_wcchp_region_billing_code !== '' ) {
					whq_wcchp_checkout_load_cities_billing( whq_wcchp_region_billing_code );
				} else {
					jQuery('#billing_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');
				}
				if( whq_wcchp_region_shipping_code !== '' ) {
					whq_wcchp_checkout_load_cities_shipping( whq_wcchp_region_shipping_code );
				} else {
					jQuery('#shipping_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');
				}
			}
		}
	});
}

function whq_wcchp_checkout_load_cities_billing( region_code ) {
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
				whq_wcchp_checkout_inputs_restore();
			} else {
				jQuery('#billing_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');

				whq_wcchp_city_name = jQuery('#billing_city').val();
				whq_wcchp_city_code = '';

				if( jQuery.isArray( response.data ) ) {
					jQuery(response.data).each(function( i ) {
						if( response.data[i].GlsComuna  == whq_wcchp_city_name ) {
							whq_wcchp_city_code = response.data[i].CodComuna;

							jQuery('#billing_whq_city').val(whq_wcchp_city_code);
							jQuery('#billing_whq_city_select').append('<option value="' + response.data[i].CodComuna + '|' + response.data[i].GlsComuna + '" selected>' + response.data[i].GlsComuna + '</option>');
						} else {
							jQuery('#billing_whq_city_select').append('<option value="' + response.data[i].CodComuna + '|' + response.data[i].GlsComuna + '">' + response.data[i].GlsComuna + '</option>');
						}
					});
				} else {
					if( response.data.GlsComuna  == whq_wcchp_city_name ) {
						whq_wcchp_city_code = response.data.CodComuna;

						jQuery('#billing_whq_city').val(whq_wcchp_city_code);
						jQuery('#billing_whq_city_select').append('<option value="' + response.data.CodComuna + '|' + response.data.GlsComuna + '" selected>' + response.data.GlsComuna + '</option>');
					} else {
						jQuery('#billing_whq_city_select').append('<option value="' + response.data.CodComuna + '|' + response.data.GlsComuna + '">' + response.data.GlsComuna + '</option>');
					}
				}

				$code_and_city = jQuery('#billing_whq_city').val() + '|' + jQuery('#billing_city').val();

				if ( $code_and_city != jQuery('#billing_whq_city_select').val() ) {
					jQuery('#billing_whq_city').val('');
					jQuery('#billing_city').val(' ');
				}

				if( ! jQuery('#billing_whq_city_select').hasClass('select2-hidden-accessible') ) {
					jQuery('#billing_whq_city_select').select2();
					jQuery('#billing_city_field').unblock();
				}
			}
		}
	});
}

function whq_wcchp_checkout_load_cities_shipping( region_code ) {
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
				whq_wcchp_checkout_inputs_restore();
			} else {
				jQuery('#shipping_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');

				whq_wcchp_city_name = jQuery('#shipping_city').val();
				whq_wcchp_city_code = '';

				if( jQuery.isArray( response.data ) ) {
					jQuery(response.data).each(function( i ) {
						if( response.data[i].GlsComuna  == whq_wcchp_city_name ) {
							whq_wcchp_city_code = response.data[i].CodComuna;

							jQuery('#shipping_whq_city').val(whq_wcchp_city_code);
							jQuery('#shipping_whq_city_select').append('<option value="' + response.data[i].CodComuna + '|' + response.data[i].GlsComuna + '" selected>'+response.data[i].GlsComuna + '</option>');
						} else {
							jQuery('#shipping_whq_city_select').append('<option value="' + response.data[i].CodComuna + '|' + response.data[i].GlsComuna + '">' + response.data[i].GlsComuna + '</option>');
						}
					});
				} else {
					if( response.data.GlsComuna  == whq_wcchp_city_name ) {
						whq_wcchp_city_code = response.data.CodComuna;

						jQuery('#shipping_whq_city').val(whq_wcchp_city_code);
						jQuery('#shipping_whq_city_select').append('<option value="' + response.data.CodComuna + '|' + response.data.GlsComuna + '" selected>' + response.data.GlsComuna + '</option>');
					} else {
						jQuery('#shipping_whq_city_select').append('<option value="' + response.data.CodComuna + '|' + response.data.GlsComuna + '">' + response.data.GlsComuna + '</option>');
					}
				}

				$code_and_city = jQuery('#shipping_whq_city').val() + '|' + jQuery('#shipping_city').val();

				if ( $code_and_city != jQuery('#shipping_whq_city_select').val() ) {
					jQuery('#shipping_whq_city').val('');
					jQuery('#shipping_city').val(' ');
				}

				if( ! jQuery('#shipping_whq_city_select').hasClass('select2-hidden-accessible') ) {
					jQuery('#shipping_whq_city_select').select2();
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
		jQuery("#billing_whq_city").after('<select id="billing_whq_city_select" name="billing_whq_city_select" disabled="disabled"><option value="">Selecciona la región primero.</option></select>');
		jQuery('#billing_city').hide();
		jQuery('#billing_whq_city').hide();
		jQuery("#shipping_city").after('<input type="text" class="input-text" name="shipping_whq_city" id="shipping_whq_city" />');
		jQuery("#shipping_whq_city").after('<select id="shipping_whq_city_select" name="shipping_whq_city_select" disabled="disabled"><option value="">Selecciona la región primero.</option></select>');
		jQuery('#shipping_city').hide();
		jQuery('#shipping_whq_city').hide();

		//Block UI
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
		jQuery("#billing_whq_region").after('<select id="billing_whq_region_select" name="billing_whq_region_select" disabled="disabled"></select>');
		jQuery('#billing_state').hide();
		jQuery('#billing_whq_region').hide();
		jQuery("#shipping_state").after('<input type="text" class="input-text" name="shipping_whq_region" id="shipping_whq_region" />');
		jQuery("#shipping_whq_region").after('<select id="shipping_whq_region_select" name="shipping_whq_region_select" disabled="disabled"></select>');
		jQuery('#shipping_state').hide();
		jQuery('#shipping_whq_region').hide();

		//Block UI
		jQuery('#billing_state_field, #shipping_state_field').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
	}
}

function whq_wcchp_checkout_inputs_restore() {
	//Remove inserted fields
	if( jQuery('#billing_whq_city_select, #shipping_whq_city_select').is('select') ) {
		jQuery("#billing_whq_city").remove();
		jQuery("#billing_whq_city_select").next('.select2-container').remove();
		jQuery("#billing_whq_city_select").remove();
		jQuery("#shipping_whq_city").remove();
		jQuery("#shipping_whq_city_select").next('.select2-container').remove();
		jQuery("#shipping_whq_city_select").remove();
	}

	if( jQuery('#billing_whq_region_select, #shipping_whq_region_select').is('select') ) {
		jQuery("#billing_whq_region").remove();
		jQuery("#billing_whq_region_select").next('.select2-container').remove();
		jQuery("#billing_whq_region_select").remove();
		jQuery("#shipping_whq_region").remove();
		jQuery("#shipping_whq_region_select").next('.select2-container').remove();
		jQuery("#shipping_whq_region_select").remove();
	}

	//Show the old ones
	jQuery('#billing_state, #billing_city, #shipping_state, #shipping_city').show();

	//Unblock the UI
	jQuery('#shipping_city_field, #shipping_state_field').unblock();
	jQuery('#billing_city_field, #billing_state_field').unblock();

	//Remove our trigger class from body
	jQuery('body').removeClass('wc-chilexpress-enabled');
}
