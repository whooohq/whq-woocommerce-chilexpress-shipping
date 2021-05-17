var whq_wcchp_region_select;
var whq_wcchp_region_array;
var whq_wcchp_city_select;
var whq_wcchp_city_array;
var whq_wcchp_region_name;
var whq_wcchp_region_code;
var whq_wcchp_city_name;
var whq_wcchp_city_code;
var whq_wcchp_cities_hardcoded = false;
var whq_wcchp_object_to_array;
var whq_wcchp_chilexpress_noservice;
var whq_wcchp_chilexpress_noservice_text;

jQuery(document).ready(function( $ ) {
	//Only on WooCommerce's Cart
	if( jQuery('.woocommerce-cart').length || ( jQuery('.single-product').length && whq_wcchp_shipping_calc_on_product == '1' ) ) {
		if( whq_wcchp_jsdebug ) {
			console.log('[WCCHP] in cart');
		}

		if( jQuery('input[value^="chilexpress"]').length ) {
			//CL detection
			if( jQuery('input[value^="chilexpress"]').is(':checked') && jQuery('#calc_shipping_country').val() == 'CL' ) {
				if( whq_wcchp_jsdebug ) {
					console.log('[WCCHP] Chile detected by initial value');
				}

				whq_wcchp_cart_chile_detected();
			}
		}

		//CL detection on single product page
		if( whq_wcchp_shipping_calc_on_product == '1' && jQuery('.single-product').length ) {
			if( jQuery('#calc_shipping_country').val() == 'CL' ) {
				if( whq_wcchp_jsdebug ) {
					console.log('[WCCHP] Chile detected in single product page');
				}

				whq_wcchp_cart_chile_detected();
			}
		}

		//Fixes DOM when clicking Apply Cupon
		jQuery('body').on('click', 'input[name="apply_coupon"]', function() {
			whq_wcchp_cart_remove_chilexpress_classes();
		});

		//Fixes DOM when clicking Update Cart
		jQuery('body').on('click', 'input[name="update_cart"]', function() {
			whq_wcchp_cart_remove_chilexpress_classes();
		});

		//Fixes DOM when clicking any Shipping Method
		jQuery('body').on('click', '.shipping_method', function() {
			whq_wcchp_cart_remove_chilexpress_classes();
		});

		//Reviews settings for Chilexpress when clicking Shipping Calculator
		jQuery('body').on('click', '.shipping-calculator-button', function() {
			whq_wcchp_cart_watcher();
		});

		//Reviews settings for Chilexpress when changing country in Shipping Calculator
		jQuery('body').on('change', '#calc_shipping_country', function() {
			whq_wcchp_cart_watcher();
		});

		//Manage regions and load cities
		jQuery('body').on('change', '#calc_shipping_whq_region_select', function() {
			if( whq_wcchp_jsdebug ) {
				console.log('[WCCHP] region changed, loading cities');
			}

			whq_wcchp_region_select = jQuery('#calc_shipping_whq_region_select').val();
			whq_wcchp_region_array  = whq_wcchp_region_select.split('|');

			jQuery('#calc_shipping_state').val( whq_wcchp_region_array[1] );
			jQuery('#calc_shipping_whq_region').val( whq_wcchp_region_array[0] );

			jQuery('#calc_shipping_city_field').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			whq_wcchp_cart_load_cities( whq_wcchp_region_array[0] );
		});

		// Manage cities
		jQuery('body').on('change', '#calc_shipping_whq_city_select', function() {
			if( whq_wcchp_jsdebug ) {
				console.log('[WCCHP] change detected in #calc_shipping_whq_city_select');
			}

			whq_wcchp_city_select = jQuery('#calc_shipping_whq_city_select').val();
			whq_wcchp_city_array  = whq_wcchp_city_select.split('|');

			jQuery('#calc_shipping_city').val( whq_wcchp_city_array[1] );
			jQuery('#calc_shipping_whq_city').val( whq_wcchp_city_array[0] );
		});

		whq_wcchp_chilexpress_down = setInterval(function() {
			if( jQuery('body').hasClass('wc-chilexpress-down') && jQuery('input[value^="chilexpress"]').length ) {
				if( ! jQuery('#wc-chilexpress-verify').length ) {
					// Disables chilexpress shipping option
					jQuery('input[value^="chilexpress"]').next('label').children('.amount').remove();
					jQuery('input[value^="chilexpress"]').prop('disabled', true);

					// Adds the option to check Chilexpress availability
					jQuery('input[value^="chilexpress"]').next('label').after('<span id="wc-chilexpress-verify">: No disponible (<a class="wc-chilexpress-verify" href="#">Reintentar</a>)</span>');
				}
				// Displays an error message to the user
				if( ! jQuery('body').hasClass('wc-chilexpress-errormsg') ) {
					jQuery('form.woocommerce-cart-form').prepend('<ul class="woocommerce-error whq_wcchp_chilexpress_error"><li><strong>Chilexpress no se encuentra disponible por el momento. Por favor inténtalo más tarde.</li></ul>');
					jQuery('html, body').animate({ scrollTop: 0 }, 'normal');
					setTimeout(function() {
						jQuery('.whq_wcchp_chilexpress_error').fadeOut(500, function() {
							jQuery(this).remove();
						});
					}, 7000);
					jQuery('body').addClass('wc-chilexpress-errormsg');
				}
			}
		}, 250);

		// Retry button for Chilexpress
		jQuery('#wc-chilexpress-verify').on('click', '.wc-chilexpress-verify', function() {
			whq_wcchp_cart_chilexpress_verify();
		});

		// Restores original fields when clicking Calc Shipping
		jQuery('body').on('click', 'button[name="calc_shipping"]', function() {
			whq_wcchp_cart_inputs_restore();
		});

		// No service for certain location detection
		whq_wcchp_chilexpress_noservice = setInterval(function() {
			whq_wcchp_chilexpress_noservice_text = jQuery('input[value^="chilexpress:"]').next('label').text();
			if ( whq_wcchp_chilexpress_noservice_text.toLowerCase().indexOf('sin servicio') >= 0 ) {
				whq_wcchp_chilexpress_noservice_text = jQuery('input[value^="chilexpress:"]').next('label').text().replace('(SIN SERVICIO)', '');
				jQuery('input[value^="chilexpress"]').next('label').text(whq_wcchp_chilexpress_noservice_text);
			}
		}, 250);
	}
});

function whq_wcchp_cart_watcher() {
	if( whq_wcchp_jsdebug ) {
		console.group( '[WCCHP] whq_wcchp_cart_watcher' );
	}

	if(jQuery('#calc_shipping_country').val() != 'CL') {
		if( whq_wcchp_jsdebug ) {
			console.log( 'No Chile selected' );
		}

		whq_wcchp_cart_inputs_restore();

		return false;
	}

	if( jQuery('input[value^="chilexpress"]').length || jQuery('input[value^="chilexpress"]').is(':checked') == 'CL' ) {
		if( whq_wcchp_jsdebug ) {
			console.log( 'Chile detected, executing whq_wcchp_cart_chile_detected()' );
		}

		whq_wcchp_cart_chile_detected();
	}

	if( whq_wcchp_jsdebug ) {
		console.groupEnd();
	}
}

function whq_wcchp_cart_chile_detected() {
	if( whq_wcchp_jsdebug ) {
		console.log('[WCCHP] whq_wcchp_cart_chile_detected()');
	}

	if( jQuery('body').hasClass('wc-chilexpress-enabled') ) {
		if( whq_wcchp_jsdebug ) {
			console.log('[WCCHP] .wc-chilexpress-enabled is already set, aborting');
		}

		return;
	}

	whq_wcchp_cart_inputs_replace();

	if( whq_wcchp_jsdebug ) {
		console.log('[WCCHP] requesting regions, action: whq_wcchp_regions_ajax');
	}

	if( whq_wcchp_shipping_calc_on_product == '1' && jQuery('.single-product').length ) {
		jQuery('#rp_shipping_calculator').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
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

			/*
				To simulate a failure with Chilexpress, uncomment the following lines and comment the second line in function whq_wcchp_cart_chilexpress_verify()
			 */

			/*
			if( response.success === true && ! jQuery('body').hasClass('wc-chilexpress-errormsg') ) {
				whq_wcchp_cart_chilexpress_down();
				return;
			}
			*/

			if( response.success === false ) {
				if( whq_wcchp_jsdebug ) {
					console.log( '[WCCHP] chilexpress api down' );
				}

				//Chilexpress API is down
				whq_wcchp_cart_chilexpress_down();
			} else {
				if( whq_wcchp_jsdebug ) {
					console.log( '[WCCHP] populating regions' );
				}

				jQuery('#calc_shipping_whq_region_select').prop('disabled', false).empty().append('<option value="|"></option>');

				whq_wcchp_region_name = jQuery('#calc_shipping_state').val();
				whq_wcchp_region_code = '';

				//Loop every region
				jQuery(response.data).each(function( i ) {
					if( whq_wcchp_jsdebug ) {
						console.log( '[WCCHP] loop regions' );
					}

					if( response.data[i].GlsRegion == whq_wcchp_region_name ) {
						whq_wcchp_region_code = response.data[i].idRegion;

						jQuery('#calc_shipping_whq_region').val(whq_wcchp_region_code);
						jQuery('#calc_shipping_whq_region_select').append('<option value="' + response.data[i].idRegion + '|' + response.data[i].GlsRegion + '" selected> ' +response.data[i].GlsRegion + ' </option>');
					} else {
						jQuery('#calc_shipping_whq_region_select').append('<option value="' + response.data[i].idRegion + '|' + response.data[i].GlsRegion + '"> ' + response.data[i].GlsRegion + ' </option>');
					}

				});

				//Unblock the UI
				jQuery('#calc_shipping_state_field').unblock();

				//Load cities
				if( whq_wcchp_region_code !== '' ) {
					whq_wcchp_cart_load_cities( whq_wcchp_region_code );
				} else {
					jQuery('#calc_shipping_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');
				}
			}

			if( whq_wcchp_shipping_calc_on_product == '1' && jQuery('.single-product').length ) {
				jQuery('#rp_shipping_calculator').unblock();
			}
		}
	});
}

function whq_wcchp_cart_load_cities( region_code ) {
	if( whq_wcchp_jsdebug ) {
		console.log( '[WCCHP] whq_wcchp_cart_load_cities()' );
	}

	if(region_code === '') {
		region_code = '99'; //Bring it on!
	}

	if( whq_wcchp_jsdebug ) {
		console.log('[WCCHP] requesting cities, action: whq_wcchp_cities_ajax');
	}

	if( whq_wcchp_shipping_calc_on_product == '1' && jQuery('.single-product').length ) {
		jQuery('#rp_shipping_calculator').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
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

				//Chilexpress API is down
				whq_wcchp_cart_chilexpress_down();
			} else {
				if( whq_wcchp_jsdebug ) {
					console.log( '[WCCHP] populating cities' );
				}

				jQuery('#calc_shipping_whq_city_select').prop('disabled', false).empty().append('<option value=""></option>');

				whq_wcchp_city_name = jQuery('#calc_shipping_city').val();
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

						if( response.data[i].GlsComuna == whq_wcchp_city_name ) {
							whq_wcchp_city_code = response.data[i].CodComuna;

							jQuery('#calc_shipping_whq_city').val(whq_wcchp_city_code);
							jQuery('#calc_shipping_whq_city_select').append('<option value="' + response.data[i].CodComuna + '|' + response.data[i].GlsComuna + '" selected> ' + response.data[i].GlsComuna + ' </option>');
						} else {
							jQuery('#calc_shipping_whq_city_select').append('<option value="' + response.data[i].CodComuna + '|' + response.data[i].GlsComuna + '"> ' + response.data[i].GlsComuna + ' </option>');
						}
					});

				} else {

					//Cleanup the 2 at the end of the city's name
					if ( whq_wcchp_cities_hardcoded === false ) {
						response.data.GlsComuna = whq_wcchp_city_name_cleanup( response.data.GlsComuna );
					}

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

				jQuery('#calc_shipping_city_field').unblock();
			}

			if( whq_wcchp_shipping_calc_on_product == '1' && jQuery('.single-product').length ) {
				jQuery('#rp_shipping_calculator').unblock();
			}
		}
	});
}

function whq_wcchp_cart_inputs_replace() {
	if( whq_wcchp_jsdebug ) {
		console.group('[WCCHP] whq_wcchp_cart_inputs_replace');
	}

	if( ! jQuery('#calc_shipping_whq_city_select, #calc_shipping_whq_region_select').is('select') ) {
		//Show city field and hide postcode field
		jQuery('#calc_shipping_city_field').show();
		jQuery('#calc_shipping_postcode_field').hide();

		//Inserts new fields for manipulation and select
		jQuery("#calc_shipping_state").after('<input type="text" class="input-text" name="calc_shipping_whq_region" id="calc_shipping_whq_region" />');
		jQuery("#calc_shipping_whq_region").after('<select id="calc_shipping_whq_region_select" name="calc_shipping_whq_region_select" disabled="disabled"></select>');
		jQuery('#calc_shipping_state').hide();
		jQuery('#calc_shipping_whq_region').hide();
		jQuery("#calc_shipping_city").after('<input type="text" class="input-text" name="calc_shipping_whq_city" id="calc_shipping_whq_city" />');
		jQuery("#calc_shipping_whq_city").after('<select id="calc_shipping_whq_city_select" name="calc_shipping_whq_city_select" disabled="disabled"></select>');
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

		if( !jQuery('#calc_shipping_whq_region_select').hasClass('select2-hidden-accessible') ) {
			jQuery('#calc_shipping_whq_region_select').select2({
				placeholder: 'Selecciona la Región primero.'
			});
			jQuery('.select2-container').css('width', '100%'); //Select2 width fix
		}
		if( !jQuery('#calc_shipping_whq_city_select').hasClass('select2-hidden-accessible') ) {
			jQuery('#calc_shipping_whq_city_select').select2({
				placeholder: 'Selecciona la Comuna o Ciudad.'
			});
			jQuery('.select2-container').css('width', '100%'); //Select2 width fix
		}

		//Inserts our trigger class in body
		jQuery('body').addClass('wc-chilexpress-enabled');
	}

	if( whq_wcchp_jsdebug ) {
		console.groupEnd();
	}
}

function whq_wcchp_cart_inputs_restore() {
	if( whq_wcchp_jsdebug ) {
		console.group( '[WCCHP] whq_wcchp_cart_inputs_restore' );
	}

	if( jQuery('body').hasClass('wc-chilexpress-enabled') ) {
		if( jQuery('#calc_shipping_whq_city_select, #calc_shipping_whq_region_select').is('select') ) {
			if( whq_wcchp_jsdebug ) {
				console.log( 'restoring fields' );
			}

			//Shows all address fiels
			jQuery('#calc_shipping_state').show();
			jQuery('#calc_shipping_city').show();
			jQuery('#calc_shipping_postcode_field').show();

			//Remove inserted fields
			jQuery("#calc_shipping_whq_region").remove();
			jQuery('#calc_shipping_whq_region_select').next('.select2-container').remove();
			jQuery("#calc_shipping_whq_region_select").remove();
			jQuery("#calc_shipping_whq_city").remove();
			jQuery('#calc_shipping_whq_city_select').next('.select2-container').remove();
			jQuery("#calc_shipping_whq_city_select").remove();

			//Unblock the UI
			jQuery('#calc_shipping_city_field, #calc_shipping_state_field').unblock();
		}

		//Remove our trigger class from body
		jQuery('body').removeClass('wc-chilexpress-enabled');
	}

	if( whq_wcchp_jsdebug ) {
		console.groupEnd();
	}
}

function whq_wcchp_cart_remove_chilexpress_classes() {
	if( whq_wcchp_jsdebug ) {
		console.group( '[WCCHP] whq_wcchp_cart_remove_chilexpress_classes' );
	}

	if( jQuery('body').hasClass('wc-chilexpress-enabled') ) {
		jQuery('body').removeClass('wc-chilexpress-enabled');
	}

	if( whq_wcchp_jsdebug ) {
		console.groupEnd();
	}
}

function whq_wcchp_cart_chilexpress_down() {
	if( whq_wcchp_jsdebug ) {
		console.group( '[WCCHP] whq_wcchp_cart_inputs_restore' );
	}

	whq_wcchp_cart_inputs_restore();

	jQuery('body').addClass('wc-chilexpress-down');

	if( jQuery('input[value^="chilexpress"]').is(':checked') ) {
		jQuery('.shipping_method').not('input[value^="chilexpress"]:first').click();
	}

	if( whq_wcchp_jsdebug ) {
		console.groupEnd();
	}
}

function whq_wcchp_cart_chilexpress_verify() {
	if( whq_wcchp_jsdebug ) {
		console.group( '[WCCHP] whq_wcchp_cart_chilexpress_verify' );
	}

	jQuery('body').removeClass('wc-chilexpress-down');
	jQuery('body').removeClass('wc-chilexpress-errormsg');
	jQuery('#wc-chilexpress-verify').remove();
	jQuery('input[value^="chilexpress"]').prop('disabled', false);
	jQuery('input[value^="chilexpress"]').prop('checked', true);
	jQuery('.shipping-calculator-button').click();

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
