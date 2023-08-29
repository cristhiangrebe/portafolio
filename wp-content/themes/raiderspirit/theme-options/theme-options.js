/* global jQuery:false */
/* global RAIDERSPIRIT_STORAGE:false */

//-------------------------------------------
// Theme Options fields manipulations
//-------------------------------------------
jQuery(document).ready(function() {
	"use strict";

	// Toggle inherit button and cover
	jQuery('#raiderspirit_options_tabs').on('click', '.raiderspirit_options_inherit_lock,.raiderspirit_options_inherit_cover', function (e) {
		var parent = jQuery(this).parents('.raiderspirit_options_item');
		var inherit = parent.hasClass('raiderspirit_options_inherit_on');
		if (inherit) {
			parent.removeClass('raiderspirit_options_inherit_on').addClass('raiderspirit_options_inherit_off');
			parent.find('.raiderspirit_options_inherit_cover').fadeOut().find('input[type="hidden"]').val('');
		} else {
			parent.removeClass('raiderspirit_options_inherit_off').addClass('raiderspirit_options_inherit_on');
			parent.find('.raiderspirit_options_inherit_cover').fadeIn().find('input[type="hidden"]').val('inherit');
			
		}
		e.preventDefault();
		return false;
	});

	// Refresh linked field
	jQuery('#raiderspirit_options_tabs').on('change', '[data-linked] select,[data-linked] input', function (e) {
		var chg_name     = jQuery(this).parent().data('param');
		var chg_value    = jQuery(this).val();
		var linked_name  = jQuery(this).parent().data('linked');
		var linked_data  = jQuery('#raiderspirit_options_tabs [data-param="'+linked_name+'"]');
		var linked_field = linked_data.find('select');
		var linked_field_type = 'select';
		if (linked_field.length == 0) {
			linked_field = linked_data.find('input');
			linked_field_type = 'input';
		}
		var linked_lock = linked_data.parent().parent().find('.raiderspirit_options_inherit_lock').addClass('raiderspirit_options_wait');
		// Prepare data
		var data = {
			action: 'raiderspirit_get_linked_data',
			nonce: RAIDERSPIRIT_STORAGE['ajax_nonce'],
			chg_name: chg_name,
			chg_value: chg_value
		};
		jQuery.post(RAIDERSPIRIT_STORAGE['ajax_url'], data, function(response) {
			var rez = {};
			try {
				rez = JSON.parse(response);
			} catch (e) {
				rez = { error: RAIDERSPIRIT_STORAGE['ajax_error_msg'] };
				console.log(response);
			}
			if (rez.error === '') {
				if (linked_field_type == 'select') {
					var opt_list = '';
					for (var i in rez.list) {
						opt_list += '<option value="'+i+'">'+rez.list[i]+'</option>';
					}
					linked_field.html(opt_list);
				} else {
					linked_field.val(rez.value);
				}
				linked_lock.removeClass('raiderspirit_options_wait');
			}
		});
		e.preventDefault();
		return false;
	});
	
	
	// Check for dependencies
	//-----------------------------------------------------------------------------
	function raiderspirit_options_start_check_dependencies() {
		jQuery( '.raiderspirit_options .raiderspirit_options_section' ).each(
			function () {
				raiderspirit_options_check_dependencies( jQuery( this ) );
			}
		);
	}

	// Check all inner dependencies
		jQuery( document ).ready( raiderspirit_options_start_check_dependencies );
	// Check external dependencies (for example, "Page template" in the page edit mode)
		jQuery( window ).load( raiderspirit_options_start_check_dependencies );
	// Check dependencies on any field change
	jQuery( '.raiderspirit_options .raiderspirit_options_item_field [name^="raiderspirit_options_field_"]' ).on(
		'change', function () {
			raiderspirit_options_check_dependencies( jQuery( this ).parents( '.raiderspirit_options_section' ) );
		}
	);
	

	// Return value of the field
	function raiderspirit_options_get_field_value(fld, num) {
		var ctrl = fld.parents('.raiderspirit_options_item_field');
		var val = fld.attr('type')=='checkbox' || fld.attr('type')=='radio' 
					? (ctrl.find('[name^="raiderspirit_options_field_"]:checked').length > 0
						? (num === true
							? ctrl.find('[name^="raiderspirit_options_field_"]:checked').parent().index()+1
							: (ctrl.find('[name^="raiderspirit_options_field_"]:checked').val()!=''
								&& ''+ctrl.find('[name^="raiderspirit_options_field_"]:checked').val()!='0'
									? ctrl.find('[name^="raiderspirit_options_field_"]:checked').val()
									: 1
								)
							)
						: 0
						)
					: (num === true ? fld.find(':selected').index()+1 : fld.val());
		if (val===undefined || val===null) val = '';
		return val;
	}

	// Check for dependencies
	function raiderspirit_options_check_dependencies(cont) {
		cont.find( '.raiderspirit_options_item_field' ).each(
			function() {
				var ctrl = jQuery( this ), id = ctrl.data( 'param' );
				if (id === undefined) {
					return;
				}
				var depend = false;
				for (var fld in raiderspirit_dependencies) {
					if (fld == id) {
						depend = raiderspirit_dependencies[id];
						break;
					}
				}
				if (depend) {
					var dep_cnt    = 0, dep_all = 0;
					var dep_cmp    = typeof depend.compare != 'undefined' ? depend.compare.toLowerCase() : 'and';
					var dep_strict = typeof depend.strict != 'undefined';
					var fld        = null, val = '', name = '', subname = '';
					var parts      = '', parts2 = '';
					for (var i in depend) {
						if (i == 'compare' || i == 'strict') {
							continue;
						}
						dep_all++;
						name    = i;
						subname = '';
						if (name.indexOf( '[' ) > 0) {
							parts   = name.split( '[' );
							name    = parts[0];
							subname = parts[1].replace( ']', '' );
						}
						if (name.charAt( 0 ) == '#' || name.charAt( 0 ) == '.') {
							fld = jQuery( name );
							if (fld.length > 0 && ! fld.hasClass( 'raiderspirit_inited' )) {
								fld.addClass( 'raiderspirit_inited' ).on(
									'change', function () {
										jQuery( '.raiderspirit_options .raiderspirit_options_section' ).each(
											function () {
												raiderspirit_options_check_dependencies( jQuery( this ) );
											}
										);
									}
								);
							}
						} else {
							fld = cont.find( '[name="raiderspirit_options_field_' + name + '"]' );
						}
						if (fld.length > 0) {
							val = raiderspirit_options_get_field_value( fld );
							if (subname !== '') {
								parts = val.split( '|' );
								for (var p = 0; p < parts.length; p++) {
									parts2 = parts[p].split( '=' );
									if (parts2[0] == subname) {
										val = parts2[1];
									}
								}
							}
							for (var j in depend[i]) {
								if (
									(depend[i][j] == 'not_empty' && val !== '')   // Main field value is not empty - show current field
									|| (depend[i][j] == 'is_empty' && val === '') // Main field value is empty - show current field
									|| (val !== '' && ( ! isNaN( depend[i][j] )   // Main field value equal to specified value - show current field
												? val == depend[i][j]
												: (dep_strict
													? val == depend[i][j]
													: ('' + val).indexOf( depend[i][j] ) == 0
											)
										)
									)
									|| (val !== '' && ("" + depend[i][j]).charAt( 0 ) == '^' && ('' + val).indexOf( depend[i][j].substr( 1 ) ) == -1)
								// Main field value not equal to specified value - show current field
								) {
									dep_cnt++;
									break;
								}
							}
						} else {
							dep_all--;
						}
						if (dep_cnt > 0 && dep_cmp == 'or') {
							break;
						}
					}
					if (((dep_cnt > 0 || dep_all == 0) && dep_cmp == 'or') || (dep_cnt == dep_all && dep_cmp == 'and')) {
						ctrl.parents( '.raiderspirit_options_item' ).show().removeClass( 'raiderspirit_options_no_use' );
					} else {
						ctrl.parents( '.raiderspirit_options_item' ).hide().addClass( 'raiderspirit_options_no_use' );
					}
				}

				// Individual dependencies
				//------------------------------------

				// Remove 'false' to disable color schemes less then main scheme!
				// This behavious is not need for the version with sorted schemes (leave false)
				if (false && id == 'color_scheme') {
					fld = ctrl.find( '[name="raiderspirit_options_field_' + id + '"]' );
					if (fld.length > 0) {
						val     = raiderspirit_options_get_field_value( fld );
						var num = raiderspirit_options_get_field_value( fld, true );
						cont.find( '.raiderspirit_options_item_field' ).each(
							function() {
								var ctrl2 = jQuery( this ), id2 = ctrl2.data( 'param' );
								if (id2 == undefined) {
									return;
								}
								if (id2 == id || id2.substr( -7 ) != '_scheme') {
									return;
								}
								var fld2 = ctrl2.find( '[name="raiderspirit_options_field_' + id2 + '"]' ),
									val2     = raiderspirit_options_get_field_value( fld2 );
								if (fld2.attr( 'type' ) != 'radio') {
									fld2 = fld2.find( 'option' );
								}
								fld2.each(
									function(idx2) {
										var dom_obj      = jQuery( this ).get( 0 );
										dom_obj.disabled = idx2 != 0 && idx2 < num;
										if (dom_obj.disabled) {
											if (jQuery( this ).val() == val2) {
												if (fld2.attr( 'type' ) == 'radio') {
													fld2.each(
														function(idx3) {
															jQuery( this ).get( 0 ).checked = idx3 == 0;
														}
													);
												} else {
													fld2.each(
														function(idx2) {
															jQuery( this ).get( 0 ).selected = idx3 == 0;
														}
													);
												}
											}
										}
									}
								);
							}
						);
					}
				}
			}
		);
	}

});