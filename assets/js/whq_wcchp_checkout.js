var whq_wcchp_chilexpress_down;
var whq_wcchp_chilexpress_down_noselect;
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
var whq_wcchp_cities_hardcoded = false;
var whq_wcchp_object_to_array;
var whq_wcchp_chilexpress_noservice;
var whq_wcchp_chilexpress_noservice_text;
var whq_wcchp_jsdebug = false;

var whq_wcchp_states_map = {
	'CL-TA': 'R1',
	'CL-AN': 'R2',
	'CL-AT': 'R3',
	'CL-CO': 'R4',
	'CL-VS': 'R5',
	'CL-LI': 'R6',
	'CL-ML': 'R7',
	'CL-BI': 'R8',
	'CL-AR': 'R9',
	'CL-RM': 'RM',
	'CL-LL': 'R10',
	'CL-AI': 'R11',
	'CL-MA': 'R12',
	'CL-LR': 'R14',
	'CL-AP': 'R15',
	'CL-NB': 'R16'
};

jQuery(document).ready(function( $ ) {
	//Only on WooCommerce's Checkout
	if( jQuery('.woocommerce-checkout').length ) {
		if( whq_wcchp_jsdebug ) {
			console.log('[WCCHP] in checkout');
		}

		//CL detection
		if(jQuery('#billing_country').val() == 'CL' || jQuery('#shipping_country').val() == 'CL') {
			if( whq_wcchp_jsdebug ) {
				console.log('[WCCHP] Chile detected by initial value');
			}

			whq_wcchp_checkout_chile_detected();
		}

		jQuery('body').on('change', '#billing_country', function() {
			if(jQuery('#billing_country').val() == 'CL') {
				if( whq_wcchp_jsdebug ) {
					console.log('[WCCHP] Chile detected by #billing_country value change');
				}

				whq_wcchp_checkout_chile_detected();
			} else {
				whq_wcchp_checkout_inputs_restore();
			}
		});

		jQuery('body').on('change', '#shipping_country', function() {
			if(jQuery('#shipping_country').val() == 'CL') {
				if( whq_wcchp_jsdebug ) {
					console.log('[WCCHP] Chile detected by #shipping_country value change');
				}

				whq_wcchp_checkout_chile_detected();
			} else {
				whq_wcchp_checkout_inputs_restore();
			}
		});

		// Manage billing regions and load cities
		jQuery('body').on('change', '#billing_state, #billing_whq_region_select', function() {
			if( whq_wcchp_jsdebug ) {
				console.log('[WCCHP] change detected in #billing_state, #billing_whq_region_select');
			}

			whq_wcchp_region_billing_select = jQuery('#billing_state').val();
			//whq_wcchp_region_billing_array  = whq_wcchp_region_billing_select.split('|');
			whq_wcchp_region_billing_value_for_chxp = whq_wcchp_states_map[whq_wcchp_region_billing_select];

			if ( whq_wcchp_jsdebug ) {
				console.log( '[WCCHP] WC region code: ' + whq_wcchp_region_billing_select );
				console.log( '[WCCHP] CHXP region code: ' + whq_wcchp_region_billing_value_for_chxp );
			}

			//jQuery('#billing_state').val( whq_wcchp_region_billing_array[1] );
			//jQuery('#billing_whq_region').val( whq_wcchp_region_billing_array[0] );

			jQuery('#billing_city_field').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			whq_wcchp_checkout_load_cities( whq_wcchp_region_billing_value_for_chxp, 'billing' );
		});

		// Manage shipping regions and load cities
		jQuery('body').on('change', '#shipping_state, #shipping_whq_region_select', function() {
			if( whq_wcchp_jsdebug ) {
				console.log('[WCCHP] change detected in #shipping_state, #shipping_whq_region_select');
			}

			whq_wcchp_region_shipping_select = jQuery('#shipping_state').val();
			//whq_wcchp_region_shipping_array  = whq_wcchp_region_shipping_select.split('|');
			whq_wcchp_region_shipping_value_for_chxp = whq_wcchp_states_map[whq_wcchp_region_shipping_select];

			if ( whq_wcchp_jsdebug ) {
				console.log( '[WCCHP] WC region code: ' + whq_wcchp_region_shipping_select );
				console.log( '[WCCHP] CHXP region code: ' + whq_wcchp_region_shipping_value_for_chxp );
			}

			// https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/151
			/*if ( jQuery('#shipping_state').length ) {
				jQuery('#shipping_state').val( whq_wcchp_region_shipping_array[1] );
				jQuery('#shipping_whq_region').val( whq_wcchp_region_shipping_array[0] );
			} else {
				jQuery('#billing_state').val( whq_wcchp_region_shipping_array[1] );
				jQuery('#billing_whq_region').val( whq_wcchp_region_shipping_array[0] );
			}*/

			jQuery('#shipping_city_field, #billing_city_field').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			whq_wcchp_checkout_load_cities( whq_wcchp_region_shipping_value_for_chxp, 'shipping' );
		});

		// Manage billing cities
		jQuery('body').on('change', '#billing_whq_city_select', function() {
			if( whq_wcchp_jsdebug ) {
				console.log('[WCCHP] change detected in #billing_whq_city_select');
			}

			whq_wcchp_city_select = jQuery('#billing_whq_city_select').val();
			whq_wcchp_city_array  = whq_wcchp_city_select.split('|');

			jQuery('#billing_city').val( whq_wcchp_city_array[1] );
			jQuery('#billing_whq_city').val( whq_wcchp_city_array[0] );
		});

		// Manage shipping cities
		jQuery('body').on('change', '#shipping_whq_city_select', function() {
			if( whq_wcchp_jsdebug ) {
				console.log('[WCCHP] change detected in #shipping_whq_city_select');
			}

			whq_wcchp_city_select = jQuery('#shipping_whq_city_select').val();
			whq_wcchp_city_array  = whq_wcchp_city_select.split('|');

			jQuery('#shipping_city').val( whq_wcchp_city_array[1] );
			jQuery('#shipping_whq_city').val( whq_wcchp_city_array[0] );
		});

		// Fix Select2 width
		jQuery('#ship-to-different-address-checkbox').click(function() {
			if ( jQuery('#ship-to-different-address-checkbox').is(':checked') ) {
				jQuery('.select2-container').css('width', '100%');
			}
		});

		// No Chilexpress API available?
		jQuery('body').on('click', '.shipping_method', function() {
			if( ! jQuery('body').hasClass('wc-chilexpress-enabled') ) {
				if( whq_wcchp_jsdebug ) {
					console.log('[WCCHP] no .wc-chilexpress-enabled, disabling chilexpress');
				}

				jQuery('input[value^="chilexpress"]').prop('disabled', true);
			}
		});

		whq_wcchp_chilexpress_down = setInterval(function() {
			if( ! jQuery('body').hasClass('wc-chilexpress-enabled') && ! jQuery('body').hasClass('wc-chilexpress-down') && jQuery('input[value^="chilexpress"]').length ) {
				jQuery('input[value^="chilexpress"]').prop('disabled', true).prop('selected', false).hide().next('label').children('.amount').text('No disponible');
				jQuery('.shipping_method').not('input[value^="chilexpress"]:first').click();

				whq_wcchp_show_error_msg();

				if( ! jQuery('body').hasClass('wc-chilexpress-down') ) {
					jQuery('body').addClass('wc-chilexpress-down');
				}
			}
		}, 250);
		//clearInterval( whq_wcchp_chilexpress_down );

		whq_wcchp_chilexpress_down_noselect = setInterval(function() {
			if( jQuery('body').hasClass('wc-chilexpress-down') && jQuery('input[value^="chilexpress"]').length ) {
				jQuery('input[value^="chilexpress"]').prop('disabled', true).prop('selected', false).hide().next('label').children('.amount').text('No disponible');

				if( jQuery('input[value^="chilexpress"]').is(':checked') ) {
					jQuery('.shipping_method').not('input[value^="chilexpress"]:first').click();
				}
			}
		}, 250);
		//clearInterval( whq_wcchp_chilexpress_down_noselect );

		//Shipping cost zero?
		//https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/13
		jQuery('body').on('click', '#place_order', function() {
			if( jQuery('body').hasClass('wc-chilexpress-enabled') && jQuery('input[value^="chilexpress"]').length ) {
				whq_wcchp_chilexpress_cost = jQuery('input[value^="chilexpress"]').next('label').children('.amount').text();

				if( ( whq_wcchp_chilexpress_cost === '' || whq_wcchp_chilexpress_cost === 'No disponible.' ) && jQuery('input[value^="chilexpress"]').is(':checked') ) {
					if( whq_wcchp_jsdebug ) {
						console.log('[WCCHP] zero cost shipping order');
					}

					jQuery('input[value^="chilexpress"]').prop('disabled', true).prop('selected', false);

					whq_wcchp_show_error_msg();

					return false;
				}
			}
		});

		//No service for certain location detection
		whq_wcchp_chilexpress_noservice = setInterval(function() {
			whq_wcchp_chilexpress_noservice_text = jQuery('input[value^="chilexpress:"]').next('label').text();

			if ( whq_wcchp_chilexpress_noservice_text.toLowerCase().indexOf('sin servicio') >= 0 ) {
				jQuery('input[value^="chilexpress:"]').prop('disabled', true);
				jQuery('.shipping_method').not('input[value^="chilexpress"]:first').click();

				clearInterval( whq_wcchp_chilexpress_noservice ); //3 or more shipping methods loop bug. Why?
			} else {
				jQuery('input[value^="chilexpress:"]').prop('disabled', false);
			}
		}, 250);

		//No service for certain location detection, on click
		jQuery('body').on('click', 'input[value^="chilexpress:"]', function() {
			if( whq_wcchp_jsdebug ) {
				console.log('[WCCHP] chilexpress input clicked');
			}

			whq_wcchp_chilexpress_noservice_text = jQuery('input[value^="chilexpress:"]').next('label').text().toLowerCase();

			if ( whq_wcchp_chilexpress_noservice_text.indexOf('sin servicio') >= 0 ) {
				jQuery('input[value^="chilexpress:"]').prop('disabled', true);
				jQuery('.shipping_method').not('input[value^="chilexpress"]:first').click();
			}
		});

		//Stop checkout post if only Chilexpress is present, and has no service
		jQuery('body').on('submit', '.woocommerce-checkout', function(e) {
			e.preventDefault();
			whq_wcchp_submit_check();
		});
		jQuery('body').on('click', '#place_order', function(e) {
			e.preventDefault();
			whq_wcchp_submit_check();
		});
	}
});

function whq_wcchp_show_error_msg() {
	jQuery('form.woocommerce-checkout').prepend('<div class="whq_wcchp_chilexpress_error woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout"><ul class="woocommerce-error"><li><strong>Chilexpress no se encuentra disponible en este momento. Por favor, selecciona otro método de envío (si existiese), o iténtalo nuevamente en unos minutos.</li></ul></div>');

	jQuery('html, body').animate({ scrollTop: 0 }, 'normal');

	setTimeout(function() {
		jQuery('.whq_wcchp_chilexpress_error').fadeOut(500, function() {
			jQuery(this).remove();
		});
	}, 9000);
}

function whq_wcchp_submit_check() {
	if( whq_wcchp_jsdebug ) {
		console.log('[WCCHP] checkout form submission attempt');
	}

	whq_wcchp_chilexpress_noservice_text = jQuery('input[value^="chilexpress:"]').parent('td').text().trim().toLowerCase();

	if ( whq_wcchp_chilexpress_noservice_text.indexOf('sin servicio') >= 0 ) {
		if( whq_wcchp_jsdebug ) {
			console.log('[WCCHP] no service, cant submit form');
		}

		whq_wcchp_show_error_msg();

		return false;
	} else {
		jQuery('form.woocommerce-checkout').submit();

		return true;
	}
}

function whq_wcchp_checkout_chile_detected() {
	if( whq_wcchp_jsdebug ) {
		console.log( '[WCCHP] whq_wcchp_checkout_chile_detected()' );
	}

	if( jQuery('body').hasClass('wc-chilexpress-enabled') ) {
		if( whq_wcchp_jsdebug ) {
			console.log('[WCCHP] .wc-chilexpress-enabled is already set, aborting');
		}

		return;
	}

	whq_wcchp_checkout_inputs_replace();

	/*if( whq_wcchp_jsdebug ) {
		console.log('[WCCHP] requesting regions, action: whq_wcchp_regions_ajax');
	}

	jQuery.ajax({
		url: woocommerce_params.ajax_url,
		data: {
			action: 'whq_wcchp_regions_ajax'
		},
		type: 'POST',
		datatype: 'application/json',
		success: function( response ) {
			if( whq_wcchp_jsdebug ) {
				console.log( '[WCCHP] whq_wcchp_regions_ajax response' );
				console.log( response );
			}

			if( response.success === false ) {
				if( whq_wcchp_jsdebug ) {
					console.log( '[WCCHP] chilexpress api down' );
				}

				// Chilexpress API down? error?
				whq_wcchp_checkout_inputs_restore();
			} else {
				if( whq_wcchp_jsdebug ) {
					console.log( '[WCCHP] populating regions' );
				}

				jQuery('#billing_whq_region_select, #shipping_whq_region_select').prop('disabled', false).empty().append('<option value=""></option>');

				whq_wcchp_region_billing_name  = jQuery('#billing_state').val();
				whq_wcchp_region_billing_code  = '';

				// https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/151
				if ( jQuery('#shipping_state').length ) {
					whq_wcchp_region_shipping_name = jQuery('#shipping_state').val();
					whq_wcchp_region_shipping_code = '';
				} else {
					whq_wcchp_region_shipping_name = jQuery('#billing_state').val();
					whq_wcchp_region_shipping_code = '';
				}

				// Loop every region
				jQuery(response.data).each(function( i ) {
					if( whq_wcchp_jsdebug ) {
						console.log( '[WCCHP] loop regions' );
					}

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

				// Load cities for billing
				if( whq_wcchp_region_billing_code !== '' ) {
					if( whq_wcchp_jsdebug ) {
						console.log( 'loading cities for billing' );
					}

					whq_wcchp_checkout_load_cities( whq_wcchp_region_billing_code, 'billing' );
				} else {
					if( whq_wcchp_jsdebug ) {
						console.log( 'no region code?' );
					}

					jQuery('#billing_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');
				}

				// Load cities for shipping
				if( whq_wcchp_region_shipping_code !== '' ) {
					if( whq_wcchp_jsdebug ) {
						console.log( 'loading cities for shipping' );
					}

					whq_wcchp_checkout_load_cities( whq_wcchp_region_shipping_code, 'shipping' );
				} else {
					if( whq_wcchp_jsdebug ) {
						console.log( 'no region code?' );
					}

					jQuery('#shipping_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');
				}

				// Unblock the UI
				jQuery('#billing_state_field, #shipping_state_field').unblock();

				//Copy values to the hidden Inputs, for Chrome auto-complete
				//https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/34
				whq_wcchp_city_select = jQuery('#billing_whq_city_select').val();
				whq_wcchp_city_array  = whq_wcchp_city_select.split('|');

				if( whq_wcchp_city_select !== false ) {
					jQuery('#billing_city').val( whq_wcchp_city_array[1] );
					jQuery('#billing_whq_city').val( whq_wcchp_city_array[0] );
				}

				whq_wcchp_region_billing_select = jQuery('#billing_whq_region_select').val();
				whq_wcchp_region_billing_array  = whq_wcchp_region_billing_select.split('|');

				if ( whq_wcchp_region_billing_select !== false ) {
					jQuery('#billing_state').val( whq_wcchp_region_billing_array[1] );
					jQuery('#billing_whq_region').val( whq_wcchp_region_billing_array[0] );
				}

				// https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/151
				if ( jQuery('#shipping_state').length ) {
					whq_wcchp_city_select = jQuery('#shipping_whq_city_select').val();
					whq_wcchp_city_array  = whq_wcchp_city_select.split('|');
				} else {
					whq_wcchp_city_select = jQuery('#billing_whq_city_select').val();
					whq_wcchp_city_array  = whq_wcchp_city_select.split('|');
				}

				if ( whq_wcchp_city_select !== false ) {
					jQuery('#shipping_city').val( whq_wcchp_city_array[1] );
					jQuery('#shipping_whq_city').val( whq_wcchp_city_array[0] );
				}

				// https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/151
				if ( jQuery('#shipping_state').length ) {
					whq_wcchp_region_shipping_select = jQuery('#shipping_whq_region_select').val();
					whq_wcchp_region_shipping_array  = whq_wcchp_region_shipping_select.split('|');
				} else {
					whq_wcchp_region_shipping_select = jQuery('#billing_whq_region_select').val();
					whq_wcchp_region_shipping_array  = whq_wcchp_region_shipping_select.split('|');
				}

				if( whq_wcchp_region_shipping_select !== false ) {
					jQuery('#shipping_state').val( whq_wcchp_region_shipping_array[1] );
					jQuery('#shipping_whq_region').val( whq_wcchp_region_shipping_array[0] );
				}
			}
		}
	});*/
}

function whq_wcchp_checkout_load_cities( region_code, billorship ) {
	if( whq_wcchp_jsdebug ) {
		console.log( '[WCCHP] whq_wcchp_checkout_load_cities()' );
	}

	if( region_code === '' ) {
		region_code = '99'; //Bring it on!
	}

	if( billorship === '' ) {
		billorship = 'billing';
	}

	if( whq_wcchp_jsdebug ) {
		console.log('[WCCHP] requesting cities, action: whq_wcchp_cities_ajax');
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
			if( whq_wcchp_jsdebug ) {
				console.log( '[WCCHP] whq_wcchp_cities_ajax response' );
				console.log( response );
			}

			if( response.success === false ) {
				if( whq_wcchp_jsdebug ) {
					console.log( '[WCCHP] chilexpress api down' );
				}

				//Chilexpress API down? error?
				whq_wcchp_checkout_inputs_restore();
			} else {
				if( whq_wcchp_jsdebug ) {
					console.log( '[WCCHP] populating cities' );
				}

				jQuery('#' + billorship + '_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');

				whq_wcchp_city_name = jQuery('#' + billorship + '_city').val();
				whq_wcchp_city_code = '';

				//Hard-coded list (Chilexpress API down?)
				if ( typeof response.data === 'object' && Object.keys(response.data).length >= 239 ) {
					whq_wcchp_cities_hardcoded   = true;
					whq_wcchp_object_to_array = Object.keys( response.data ).map( function( key ) {
						return [ key, response.data[ key ] ];
					});
					response.data = whq_wcchp_object_to_array;
				}

				if( jQuery.isArray( response.data ) ) {

					jQuery(response.data).each(function( i, value ) {
						//Map the hard-coded values back
						if( whq_wcchp_cities_hardcoded === true ) {
							response.data[i].CodComuna = response.data[i][0];
							response.data[i].GlsComuna = response.data[i][1];
						}

						//Cleanup the 2 at the end of the city's name
						if ( whq_wcchp_cities_hardcoded === false ) {
							response.data[i].GlsComuna = whq_wcchp_city_name_cleanup( response.data[i].GlsComuna );
						}

						if( response.data[i].GlsComuna  == whq_wcchp_city_name ) {
							whq_wcchp_city_code = response.data[i].CodComuna;

							jQuery('#' + billorship + '_whq_city').val(whq_wcchp_city_code);
							jQuery('#' + billorship + '_whq_city_select').append('<option value="' + response.data[i].CodComuna + '|' + response.data[i].GlsComuna + '" selected>' + response.data[i].GlsComuna + '</option>');
						} else {
							jQuery('#' + billorship + '_whq_city_select').append('<option value="' + response.data[i].CodComuna + '|' + response.data[i].GlsComuna + '">' + response.data[i].GlsComuna + '</option>');
						}
					});

				} else {

					//Cleanup the 2 at the end of the city's name
					if ( whq_wcchp_cities_hardcoded === false ) {
						response.data.GlsComuna = whq_wcchp_city_name_cleanup( response.data.GlsComuna );
					}

					if( response.data.GlsComuna  == whq_wcchp_city_name ) {
						whq_wcchp_city_code = response.data.CodComuna;

						jQuery('#' + billorship + '_whq_city').val(whq_wcchp_city_code);
						jQuery('#' + billorship + '_whq_city_select').append('<option value="' + response.data.CodComuna + '|' + response.data.GlsComuna + '" selected>' + response.data.GlsComuna + '</option>');
					} else {
						jQuery('#' + billorship + '_whq_city_select').append('<option value="' + response.data.CodComuna + '|' + response.data.GlsComuna + '">' + response.data.GlsComuna + '</option>');
					}

				}

				$code_and_city = jQuery('#' + billorship + '_whq_city').val() + '|' + jQuery('#' + billorship + '_city').val();

				if ( $code_and_city != jQuery('' + billorship + '_whq_city_select').val() ) {
					jQuery('#' + billorship + '_whq_city').val('');
					jQuery('#' + billorship + '_city').val(' ');
				}

				jQuery('#' + billorship + '_city_field').unblock();
			}
		}
	});
}

function whq_wcchp_checkout_inputs_replace() {
	if( whq_wcchp_jsdebug ) {
		console.group( '[WCCHP] whq_wcchp_checkout_inputs_replace' );
	}

	if( ! jQuery('#billing_whq_city_select, #shipping_whq_city_select').is('select') ) {
		if( whq_wcchp_jsdebug ) {
			console.log( 'replacing city' );
		}

		// Inserts new fields for manipulation and select
		jQuery("#billing_city").after('<input type="text" class="input-text" name="billing_whq_city" id="billing_whq_city" />');
		jQuery("#billing_whq_city").after('<select id="billing_whq_city_select" name="billing_whq_city_select" disabled="disabled"><option value="">Selecciona la región primero.</option></select>');
		jQuery('#billing_city').hide();
		jQuery('#billing_whq_city').hide();

		// https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/151
		if ( jQuery('#shipping_state').length ) {
			jQuery("#shipping_city").after('<input type="text" class="input-text" name="shipping_whq_city" id="shipping_whq_city" />');
			jQuery("#shipping_whq_city").after('<select id="shipping_whq_city_select" name="shipping_whq_city_select" disabled="disabled"><option value="">Selecciona la región primero.</option></select>');
			jQuery('#shipping_city').hide();
			jQuery('#shipping_whq_city').hide();
		}

		//Block UI
		jQuery('#billing_city_field, #shipping_city_field').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		// https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/151
		if( jQuery("#shipping_city").is("select") ) {
			if( whq_wcchp_jsdebug ) {
				console.log( 'City select is not compatible. Replacing...' );
			}

			jQuery("#shipping_city").remove();
			setTimeout(function(){
				jQuery("#shipping_whq_city").before('<input type="text" class="input-text" value="" placeholder="" name="shipping_city" id="shipping_city" autocomplete="address-level1" style="display: none;">')
			}, 100);
		}

		if( ! jQuery('#billing_whq_city_select, #shipping_whq_city_select').hasClass('select2-hidden-accessible') ) {
			jQuery('#billing_whq_city_select, #shipping_whq_city_select').select2({
				placeholder: 'Selecciona la Comuna o Ciudad.'
			});
		}
	}

	/*if( ! jQuery('#billing_whq_region_select, #shipping_whq_region_select').is('select') ) {
		if( whq_wcchp_jsdebug ) {
			console.log( 'replacing region' );
		}

		// Inserts new fields for manipulation and select
		jQuery("#billing_state").after('<input type="text" class="input-text" name="billing_whq_region" id="billing_whq_region" />');
		jQuery("#billing_whq_region").after('<select id="billing_whq_region_select" name="billing_whq_region_select" disabled="disabled"></select>');
		jQuery('#billing_state').hide();
		jQuery('#billing_whq_region').hide();

		// https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/151
		if( jQuery('#shipping_state').length ) {
			jQuery("#shipping_state").after('<input type="text" class="input-text" name="shipping_whq_region" id="shipping_whq_region" />');
			jQuery('#shipping_state').hide();
			jQuery("#shipping_whq_region").after('<select id="shipping_whq_region_select" name="shipping_whq_region_select" disabled="disabled"></select>');
			jQuery('#shipping_whq_region').hide();
		}

		//Block UI
		jQuery('#billing_state_field, #shipping_state_field').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		if( !jQuery('#billing_whq_region_select, #shipping_whq_region_select').hasClass('select2-hidden-accessible') ) {

			// https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/154
			jQuery('#billing_whq_region_select').next('.select2-container').remove();

			setTimeout(function() {
				jQuery('#billing_whq_region_select, #shipping_whq_region_select').select2({
					placeholder: 'Selecciona la Región primero.'
				});
			}, 100);
		}

		// https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/151
		if( jQuery("#billing_state").is("select") ) {
			if( whq_wcchp_jsdebug ) {
				console.log( 'State select is not compatible. Replacing...' );
			}

			jQuery("#billing_state").remove();

			setTimeout(function(){
				jQuery("#billing_whq_region").before('<input type="text" class="input-text" value="" placeholder="" name="billing_state" id="billing_state" autocomplete="address-level1" style="display: none;">')
			}, 100);
		}

		//Inserts our trigger class in body
		jQuery('body').addClass('wc-chilexpress-enabled');
	}*/

	//Inserts our trigger class in body
	jQuery('body').addClass('wc-chilexpress-enabled');

	if( whq_wcchp_jsdebug ) {
		console.groupEnd();
	}
}

function whq_wcchp_checkout_inputs_restore() {
	if( whq_wcchp_jsdebug ) {
		console.group( '[WCCHP] whq_wcchp_checkout_inputs_restore' );
	}

	if( jQuery('body').hasClass('wc-chilexpress-enabled') ) {
		if( whq_wcchp_jsdebug ) {
			console.log( 'restoring fields' );
		}

		// Remove inserted fields
		if( jQuery('#billing_whq_city_select, #shipping_whq_city_select').is('select') ) {
			if( whq_wcchp_jsdebug ) {
				console.log( 'Removing inserted fields, City' );
			}
			jQuery("#billing_whq_city").remove();
			jQuery("#billing_whq_city_select").next('.select2-container').remove();
			jQuery("#billing_whq_city_select").remove();
			jQuery("#shipping_whq_city").remove();
			jQuery("#shipping_whq_city_select").next('.select2-container').remove();
			jQuery("#shipping_whq_city_select").remove();
		}

		/*if( jQuery('#billing_whq_region_select, #shipping_whq_region_select').is('select') ) {
			if( whq_wcchp_jsdebug ) {
				console.log( 'Removing inserted fields, Region' );
			}
			jQuery("#billing_whq_region").remove();
			jQuery("#billing_whq_region_select").next('.select2-container').remove();
			jQuery("#billing_whq_region_select").remove();
			jQuery("#shipping_whq_region").remove();
			jQuery("#shipping_whq_region_select").next('.select2-container').remove();
			jQuery("#shipping_whq_region_select").remove();
		}*/

		// De-select Chilexpress
		jQuery('.shipping_method').not('input[value^="chilexpress"]:first').click();

		// Show the old ones
		jQuery('#billing_state, #billing_city, #shipping_state, #shipping_city').show();

		// Unblock the UI
		jQuery('#shipping_city_field, #shipping_state_field').unblock();
		jQuery('#billing_city_field, #billing_state_field').unblock();

		// Remove our trigger class from body
		jQuery('body').removeClass('wc-chilexpress-enabled');
	}

	if( whq_wcchp_jsdebug ) {
		console.groupEnd();
	}
}

//https://stackoverflow.com/a/6253616/920648
function whq_wcchp_city_name_cleanup( city_name ) {
	if( typeof city_name === 'undefined' ) {
		return city_name;
	}

	if (city_name.substring( city_name.length - 1 ) === '2' ) {
		city_name = city_name.substring( 0,  city_name.length - 1 );
	}

	return city_name;
}
