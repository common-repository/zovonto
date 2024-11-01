/* global bsf_booking_form_params, bsf_booking_default_params */

jQuery( function ( $ ) {
	'use strict' ;

	try {
		$( document.body ).on( 'bsf-booking-form-container-init' , function ( ) {
			var $form_container = $( 'div.bsf-booking-form-container' ) ;
			if ( !$form_container.length ) {
				return false ;
			}

			// Initialize booking
			call_service_step() ;

			/*
			 * Service Step - 1
			 */
			function call_service_step() {

				bsf_block( $form_container ) ;

				//prepare data param for render service
				var data = {
					action : 'bsf_render_service' ,
					form_id : bsf_booking_form_params.form_id ,
					default : bsf_booking_form_params ,
						bsf_security : bsf_booking_default_params.render_step_nonce ,
				} ;

				//ajax for render service
				$.post( bsf_booking_default_params.ajax_url , data , function ( res ) {
					if ( res.success == true ) {
						//display service layout
						$form_container.html( res.data.html ) ;

						//declare variable
						var service_container = $( '#bsf_select_services' , $form_container ) ,
								$date_from = $( '#bsf_date_from' , $form_container ) ,
								$week_day = $( '.bsf_week_days' , $form_container ) ,
								$from_time = $( '#bsf_select_time_from' , $form_container ) ,
								$to_time = $( '#bsf_select_time_to' , $form_container ) ,
								$next_step = $( '.bsf_booking_next_step' , $form_container ) ,
								Services = res.data.services ;

						set_select_option( service_container , Services , bsf_booking_form_params.service_id ) ;

						//set selected value in dropdown
						service_container.val( res.data.selected_service ).trigger( 'change' ) ;

						//set select options for Categories , Services and Staff
						function set_select_option( $select , $options , $selected_value ) {

							$( 'option:not([value=""])' , $select ).remove() ;

							function sortValues( obj ) {
								return Object.keys( obj ).map( function ( key ) {
									return obj[key] ;
								} ) ;
							}

							function compare( a , b ) {
								if ( parseInt( a.position ) < parseInt( b.position ) ) {
									return -1 ;
								}
								if ( parseInt( a.position ) > parseInt( b.position ) ) {
									return 1 ;
								}

								return 0 ;
							}

							// sort select by position
							$options = sortValues( $options ).sort( compare ) ;

							$.each( $options , function ( key , value ) {

								$select.append( $( "<option></option>" )
										.attr( "value" , value.id )
										.attr( "selected" , ( value.id == $selected_value ) )
										.text( value.name ) ) ;
							} ) ;

						}

						//date picker for available date
						$date_from.datepicker( {
							altField : $date_from.next( ".bsf_alter_datepicker_value" ) ,
							altFormat : 'yy-mm-dd' ,
							dateFormat : bsf_booking_default_params.date_format ,
							changeMonth : false ,
							gotoCurrent : true ,
							changeYear : false ,
							minDate : 0 ,
							maxDate : '+' + res.data.max_date - 1 ,
							firstDay : bsf_booking_default_params.start_of_week ,
						} ) ;

						// time from
						$from_time.on( 'change' , function () {

							var selected_from_time = $( this ).val() ,
									to_time = $to_time.val() ;

							$to_time.empty() ;

							$( 'option' , this ).each( function () {
								if ( $( this ).val() >= selected_from_time ) {
									$to_time.append( $( this ).clone() ) ;
								}
							} ) ;

							var to_first_value = $( 'option:first' , $to_time ).val() ;
							var $to_time_value = to_time >= to_first_value ? to_time : to_first_value ;

							$to_time.val( $to_time_value ) ;
						} ) ;

						//Next step
						$next_step.on( 'click' , function ( e ) {
							e.preventDefault( ) ;

							bsf_block( $form_container ) ;
							var week_days = [ ] ;

							$week_day.each( function () {
								if ( $( this ).is( ":checked" ) ) {
									week_days.push( $( this ).val() ) ;
								}
							} ) ;

							var save_data = {
								action : 'bsf_save_service_form_session' ,
								form_id : bsf_booking_form_params.form_id ,
								service_id : service_container.val() ,
								from_date : $date_from.next( '.bsf_alter_datepicker_value' ).val() ,
								from_time : $from_time.val() ,
								to_time : $to_time.val() ,
								week_days : week_days ,
								default : bsf_booking_form_params ,
									bsf_security : bsf_booking_default_params.render_step_nonce ,
							} ;

							$.post( bsf_booking_default_params.ajax_url , save_data , function ( res ) {
								if ( res.success == true ) {
									call_time_step() ;
								} else {
									bsf_display_error( res.data.error ) ;
								}
								bsf_unblock( $form_container ) ;
							} ) ;
						} ) ;
					}
					bsf_unblock( $form_container ) ;
				} ) ;
			}

			/*
			 * Time Step-2
			 */

			function call_time_step() {
				bsf_block( $form_container ) ;

				//prepare data param for time service
				var data = {
					action : 'bsf_render_time' ,
					form_id : bsf_booking_form_params.form_id ,
					bsf_security : bsf_booking_default_params.render_step_nonce ,
				} ;

				//ajax for render time
				$.post( bsf_booking_default_params.ajax_url , data , function ( res ) {
					if ( res.success == true ) {
						$form_container.html( res.data.html ) ;

						var $next_step = $( '.bsf_booking_next_step' , $form_container ) ,
								$back_step = $( '.bsf_booking_back_step' , $form_container ) ,
								$time_slot_container = $( 'div.bsf_time_slots' , $form_container ) ,
								$selected_time_container = $( 'input.bsf_booking_selected_time' , $form_container ) ;

						$time_slot_container.append( prepare_time_template( res.data.slot_data ) ) ;

						function prepare_time_template( slot_data ) {
							var response = '' ;
							$.each( slot_data , function ( group , group_slots ) {
								var html = '<button class="bsf_booking_day" value="' + group + '">' + group_slots.title + '</button>' ;
								$.each( group_slots.slots , function ( id , slot ) {
									var class_name = "bsf_booking_time" ;
									class_name += ( slot.data[2] == res.data.selected_date ? ' bsf_booking_active_time' : '' ) ;
									class_name += ( slot.booked ) ? " bsf_booked_slot" : "" ;
									var readonly = ( slot.booked ) ? 'disabled="disabled"' : "" ;

									html += '<button value="' + JSON.stringify( slot.data ).replace( /"/g , '&quot;' ) + '"'
											+ ' data-group="' + group + '" class="' + class_name + '" ' + readonly + ' >' +
											'<span>' + slot.text + '</span>' +
											'</button>' ;
								} ) ;
								response += html ;
							} ) ;

							return response ;
						}

						$( '.bsf_booking_time' ).on( 'click' , function ( e ) {
							e.preventDefault( ) ;

							var $selected_value = $( this ).val() ;
							$( '.bsf_booking_time' ).removeClass( 'bsf_booking_active_time' ) ;
							$selected_time_container.val( $selected_value ) ;
							$( this ).addClass( 'bsf_booking_active_time' ) ;

							$next_step.show() ;

						} ) ;

						//Next step
						$next_step.on( 'click' , function ( e ) {
							e.preventDefault( ) ;

							bsf_block( $form_container ) ;

							var save_data = {
								action : 'bsf_save_time_form_session' ,
								form_id : bsf_booking_form_params.form_id ,
								slots : $.parseJSON( $( '.bsf_booking_selected_time' ).val( ) ) ,
								bsf_security : bsf_booking_default_params.render_step_nonce ,
							} ;

							$.post( bsf_booking_default_params.ajax_url , save_data , function ( response ) {
								if ( response.success == true ) {
									call_details_step() ;
								} else {
									bsf_display_error( response.data.error ) ;
								}

								bsf_unblock( $form_container ) ;
							} ) ;
						} ) ;

						//Back step
						$back_step.on( 'click' , function ( e ) {
							e.preventDefault( ) ;

							//Back to Service Step
							call_service_step() ;

						} ) ;
					}
					bsf_unblock( $form_container ) ;
				} ) ;
			}

			/*
			 * Details Step-3
			 */
			function call_details_step( ) {
				bsf_block( $form_container ) ;
				//prepare data param for time service
				var data = {
					action : 'bsf_render_details' ,
					form_id : bsf_booking_form_params.form_id ,
					bsf_security : bsf_booking_default_params.render_step_nonce ,
				} ;

				//ajax for details
				$.post( bsf_booking_default_params.ajax_url , data , function ( res ) {
					if ( res.success == true ) {
						$form_container.html( res.data.html ) ;
						var $next_step = $( '.bsf_booking_next_step' , $form_container ) ,
								$back_step = $( '.bsf_booking_back_step' , $form_container ) ;

						//Next step
						$next_step.on( 'click' , function ( e ) {
							e.preventDefault( ) ;

							bsf_block( $form_container ) ;

							var $form_data = $( "form#bsf_booking_form_details" ).serializeArray() ;

							$form_data.push( { name : "action" , value : "bsf_save_details_form_session" } ) ;
							$form_data.push( { name : "form_id" , value : bsf_booking_form_params.form_id } ) ;
							$form_data.push( { name : "bsf_security" , value : bsf_booking_default_params.render_step_nonce } ) ;

							$.post( bsf_booking_default_params.ajax_url , $form_data , function ( res ) {
								if ( res.success == true ) {
									call_payment_step() ;
								} else {
									bsf_display_error( res.data.error ) ;
								}

								bsf_unblock( $form_container ) ;
							} ) ;
						} ) ;

						//Back step
						$back_step.on( 'click' , function ( e ) {
							e.preventDefault( ) ;

							//Back to Time Step
							call_time_step() ;

						} ) ;
					}
					bsf_unblock( $form_container ) ;
				} ) ;
			}

			/*
			 * Payment Step-5
			 */
			function call_payment_step( $message = '' ) {
				bsf_block( $form_container ) ;
				//prepare data param for time service
				var data = {
					action : 'bsf_render_payment' ,
					form_id : bsf_booking_form_params.form_id ,
					bsf_security : bsf_booking_default_params.render_step_nonce ,
				} ;
				//ajax for render time
				$.post( bsf_booking_default_params.ajax_url , data , function ( res ) {
					if ( res.success == true ) {
						$form_container.html( res.data.html ) ;
						var $next_step = $( '.bsf_booking_next_step' , $form_container ) ,
								$back_step = $( '.bsf_booking_back_step' , $form_container ) ;

						$form_container.trigger( 'payment-methods-container' ) ;

						if ( $message ) { //display success message
							bsf_display_success( $message ) ;
						}

						//Next step
						$next_step.on( 'click' , function ( e ) {
							e.preventDefault( ) ;
							var $chosenPaymentMethod = $form_container.find( 'input[name="payment_method"]:checked' ).val( )

							if ( $form_container.triggerHandler( 'submit_payment_method_' + $chosenPaymentMethod ) !== false ) {
								bsf_block( $form_container ) ;

								var $form_data = $form_container.find( 'div.bsf-payment-methods  :input' ).serializeArray( ) ;

								$form_data.push( { name : "action" , value : "bsf_process_checkout" } ) ;
								$form_data.push( { name : "form_id" , value : bsf_booking_form_params.form_id } ) ;
								$form_data.push( { name : "bsf_security" , value : bsf_booking_default_params.render_step_nonce } ) ;

								$.post( bsf_booking_default_params.ajax_url , $form_data , function ( res ) {
									if ( res.success == true ) {
										call_complete_step( ) ;
									} else {
										bsf_display_error( res.data.error ) ;
									}

									bsf_unblock( $form_container ) ;
								} ) ;
							}
						} ) ;
						//Back step
						$back_step.on( 'click' , function ( e ) {
							e.preventDefault( ) ;

							//Back to Details Step
							call_details_step( ) ;
						} ) ;
					}
					bsf_unblock( $form_container ) ;
				} ) ;
			}

			/*
			 * Complete Step
			 */
			function call_complete_step(  ) {
				bsf_block( $form_container ) ;
				//prepare data param for time service
				var data = {
					action : 'bsf_render_complete' ,
					form_id : bsf_booking_form_params.form_id ,
					bsf_security : bsf_booking_default_params.render_step_nonce ,
				} ;

				//ajax for render time
				$.post( bsf_booking_default_params.ajax_url , data , function ( res ) {
					if ( res.success == true ) {
						$form_container.html( res.data.html ) ;

						var $done_btn = $( '.bsf_booking_completed_btn' , $form_container ) ;

						$done_btn.on( 'click' , function ( e ) {
							window.location.reload() ;
						} ) ;

					}
					bsf_unblock( $form_container ) ;
				} ) ;
			}

		} ) ;

		//block ui
		function bsf_block( id ) {
			$( id ).block( {
				message : null ,
				overlayCSS : {
					background : '#fff' ,
					opacity : 0.7
				}
			} ) ;
		}

		//Unblock ui
		function bsf_unblock( id ) {
			$( id ).unblock( ) ;
		}

		//Display error
		function bsf_display_error( $message ) {
			var $error_container = $( 'div.bsf_booking_form_msg' ) ;

			$error_container.html( '<p class="bsf_error">' + $message + '</p>' ) ;

		}

		//Display Success
		function bsf_display_success( $message ) {
			var $success_container = $( 'div.bsf_booking_form_msg' ) ;

			$success_container.html( '<p class="bsf_success">' + $message + '</p>' ) ;

		}

		$( document.body ).trigger( 'bsf-booking-form-container-init' ) ;
	} catch ( err ) {
		window.console.log( err ) ;
	}

} ) ;

