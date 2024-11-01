jQuery( function ( $ ) {
	'use strict' ;

	$( document.body ).on( 'bsf-edit-staff-init' , function () {
		BSF_Settings.enhanced_calender( ) ;
	} ) ;

	var BSF_Settings = {
		init : function ( ) {

			this.trigger_on_page_load( ) ;

			$( document ).on( 'click' , '.bsf_close_holiday_popup' , this.close_holiday_popup ) ;
			$( document ).on( 'click' , '.bsf_close_staff_selection_popup' , this.close_staff_selection_popup ) ;
			$( document ).on( 'click' , 'body' , this.close_holiday_popup_when_outside_click ) ;
			$( document ).on( 'click' , '.bsf_change_calendar_year' , this.change_calendar_year ) ;
			$( document ).on( 'change' , '.bsf_notifications_enabled' , this.toggle_notifications_enabled ) ;
			$( document ).on( 'change' , 'select.bsf_selection_option' , this.toggle_selection_option ) ;
			$( document ).on( 'change' , '.bsf_repeat_day_selection, .bsf_holiday_day_selection' , this.toggle_popup_toggle ) ;
			$( document ).on( 'click' , 'i.bsf_save_close' , this.save_msg_close ) ;

		} , trigger_on_page_load : function ( ) {
			this.get_selection_option( 'select.bsf_selection_option' ) ;

			$( document.body ).trigger( 'bsf-edit-staff-init' ) ;
			$( document.body ).trigger( 'bsf-enhanced-init' ) ;
		} , enhanced_calender : function ( ) {

			//return if class not exists
			var d = new Date() ;
			$( '#bfs_holiday_calender' ).jCal( {
				day : new Date( d.getFullYear() , 0 , 1 ) ,
				days : 1 ,
				showMonths : 12 ,
				monthSelect : true ,
				scrollSpeed : 350 ,
				dayOffset : parseInt( bsf_settings_params.start_of_week ) ,
				dow : bsf_settings_params.days ,
				ml : bsf_settings_params.months ,
				holiday_nonce : bsf_settings_params.holiday_nonce ,
				save_label : bsf_settings_params.save_label ,
				holiday_label : bsf_settings_params.holiday_label ,
				repeat_label : bsf_settings_params.repeat_label ,
				close : bsf_settings_params.close
			} ) ;


		} , close_holiday_popup_when_outside_click : function ( e ) {

			var $target = $( e.target ) ;
			if ( !$target.hasClass( 'day' ) && $target.closest( 'div.bsf_holiday_popup' ).length == 0 ) {
				$( 'div.bsf_holiday_popup' ).remove() ;
			}
		} , close_holiday_popup : function ( event ) {

			$( 'div.bsf_holiday_popup' ).remove() ;
		} , close_staff_selection_popup : function ( event ) {

			$( 'div.bsf_staff_selection_popup' ).remove() ;
		} , change_calendar_year : function ( event ) {
			event.preventDefault() ;
			var $this = $( event.currentTarget ) ,
					trigger = $( $this ).data( 'trigger' ) ;

			$( '#bfs_holiday_calender' ).find( $( trigger ) ).trigger( 'click' ) ;
		} , toggle_selection_option : function ( event ) {
			event.preventDefault() ;
			var $this = $( event.currentTarget ) ;

			BSF_Settings.get_selection_option( $this ) ;
		} , get_selection_option : function ( $this ) {
			if ( $( $this ).val() == '2' ) {
				$( '.bsf_selected_options' ).closest( 'tr' ).show() ;
			} else {
				$( '.bsf_selected_options' ).closest( 'tr' ).hide() ;
			}
		} , toggle_popup_toggle : function ( event ) {
			event.preventDefault() ;
			var $this = $( event.currentTarget ) ,
					popup = $( $this ).closest( 'div.bsf_holiday_popup' ) ,
					holiday = popup.find( '.bsf_holiday_day_selection' ) ,
					repeat = popup.find( '.bsf_repeat_day_selection' ) ,
					save_button = popup.find( '.bsf_save_holiday_settings' ) ;

			if ( holiday.is( ':checked' ) ) {
				repeat.prop( 'disabled' , false )
			} else {
				repeat.prop( 'checked' , false ).prop( 'disabled' , true ) ;
			}

			save_button.prop( 'disabled' , false ) ;
		} , toggle_notifications_enabled : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ,
					type = $( $this ).is( ':checked' ) ,
					closest = $( $this ).closest( 'div.bsf_notifications_grid' ) ,
					name = closest.find( '.bsf_notification_name' ).val( ) ,
					grid_inner = closest.find( '.bsf_notifications_grid_inner' ) ;

			var data = {
				action : 'bsf_toggle_notifications' ,
				enabled : type ,
				notification_name : name ,
				bsf_security : bsf_settings_params.notification_nonce
			} ;
			$.post( ajaxurl , data , function ( res ) {

				if ( res.success === true ) {
					if ( type ) {
						closest.find( '.bsf_settings_link' ).show( ) ;
						grid_inner.removeClass( 'bsf_notification_inactive' ).addClass( 'bsf_notification_active' )
					} else {
						closest.find( '.bsf_settings_link' ).hide( ) ;
						grid_inner.removeClass( 'bsf_notification_active' ).addClass( 'bsf_notification_inactive' )
					}

				} else {
					window.alert( res.data.error ) ;
				}

			} ) ;
		} , save_msg_close : function (  ) {

			$( 'div.bsf_save_msg' ).hide() ;

		} , block : function ( id ) {
			$( id ).block( {
				message : null ,
				overlayCSS : {
					background : '#fff' ,
					opacity : 0.7
				}
			} ) ;
		} ,
		unblock : function ( id ) {
			$( id ).unblock() ;
		} ,
	} ;
	BSF_Settings.init( ) ;
} ) ;
