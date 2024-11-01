/* global bsf_calendar_params */
jQuery( function ( $ ) {
	'use strict' ;

	var BSF_Bookings_Calendar = {
		init : function () {
			$( document ).on( 'mouseup' , this.bsf_bookings_hide_popup ) ;
			$( document ).on( 'click' , 'p.bsf_bookings_list' , this.bsf_bookings_list_popup ) ;
			$( document ).on( 'click' , 'div.bsf_bookings_close' , this.bsf_bookings_close_popup ) ;
			$( document ).on( 'click' , 'div.bsf_bookings_popup_close_image' , this.bsf_bookings_close_list_popup ) ;
			$( document ).on( 'click' , 'li.bsf_bookings_each_booking, div.bsf_bookings_each_booking' , this.bsf_bookings_popup ) ;
		} ,
		bsf_bookings_popup : function ( event ) {
			event.preventDefault() ;
			var $this = $( event.currentTarget ) ;
			BSF_Bookings_Calendar.block( $this ) ;
			var popup = $( '.bsf_bookings_second_popup' ) ;
			var data = {
				action : 'bsf_display_calendar_details' ,
				post_id : $( $this ).data( 'appointmentid' ) ,
				bsf_security : bsf_calendar_params.calendar_nonce
			} ;
			$.post( ajaxurl , data , function ( response ) {
				if ( true === response.success ) {
					popup.html( response.data.content ) ;
					popup.css( "display" , 'block' ) ;
					BSF_Bookings_Calendar.bsf_bookings_set_offset( event , $this , popup ) ;
				} else {
					window.alert( response.data.error ) ;
				}
				BSF_Bookings_Calendar.unblock( $this ) ;
			} ) ;
		} ,
		bsf_bookings_list_popup : function ( event ) {
			event.preventDefault() ;
			var $this = $( event.currentTarget ) ,
					date = $( $this ).data( 'date' ) ,
					popup = $( '.bsf_bookings_first_popup' ) ,
					list = $( $this ).closest( 'td' ).find( 'div.bsf_bookings_lists' ).html() ;

			$( 'div.bsf_bookings_popup__title h2' ).html( date ) ;
			$( '.bsf_bookings_first_popup_inner_content_bottom_data' ).html( list ) ;

			popup.css( 'display' , 'block' ) ;

			BSF_Bookings_Calendar.bsf_bookings_set_offset( event , $this , popup ) ;
		} ,
		bsf_bookings_close_popup : function ( event ) {
			$( '.bsf_bookings_second_popup' ).css( "display" , "none" ) ;
		} ,
		bsf_bookings_close_list_popup : function ( event ) {
			$( '.bsf_bookings_first_popup' ).css( "display" , "none" ) ;
		} ,
		bsf_bookings_hide_popup : function ( e ) {
			var second_popup = $( '.bsf_bookings_second_popup' ) ;
			var first_popup = $( '.bsf_bookings_first_popup' ) ;
			if ( !$( '.bsf_bookings_each_booking' ).is( e.target ) && second_popup.has( e.target ).length === 0 && !second_popup.is( e.target ) ) {
				second_popup.css( "display" , "none" ) ;
			}
			if ( !$( '.bsf_bookings_list' ).is( e.target ) && first_popup.has( e.target ).length === 0 && !first_popup.is( e.target ) ) {
				first_popup.css( "display" , "none" ) ;
			}
		} ,
		bsf_bookings_set_offset : function ( event , element , popup ) {
			var pos = $( element ).offset() ;
			var width = $( element ).width() ;
			var popup_width = popup.width() ;
			var popup_height = popup.height() ;
			var window_width = $( document ).width() ;
			var window_height = $( document ).height() ;
			var leftVal = width - ( event.pageX - pos.left ) + 10 ;
			var topVal = event.pageY ;
			if ( event.pageY + popup_height > window_height ) {
				var $height = ( event.pageY + popup_height ) - window_height ;
				topVal = event.pageY - $height ;
			}

			if ( event.pageX + leftVal + popup_width > window_width ) {
				popup.offset( { top : topVal , left : pos.left - ( popup_width + 10 ) } ) ;
			} else {
				popup.offset( { top : topVal , left : event.pageX + leftVal } ) ;
			}
		} , block : function ( id ) {
			$( id ).block( {
				message : null ,
				overlayCSS : {
					background : '#fff' ,
					opacity : 0.7
				}
			} ) ;
		} , unblock : function ( id ) {
			$( id ).unblock() ;
		} ,
	} ;
	BSF_Bookings_Calendar.init() ;
} ) ;
