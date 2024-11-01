jQuery( function ( $ ) {
	'use strict' ;

	var BSF_Service = {
		init : function ( ) {

			this.trigger_on_page_load( ) ;

			$( document ).on( 'click' , '.bsf_add_service' , this.add_service ) ;
			$( document ).on( 'click' , '.bsf_save_services' , this.save_service ) ;
			$( document ).on( 'click' , '.bsf_delete_services_btn' , this.delete_services ) ;
			$( document ).on( 'click' , '.bsf_toggle_services_panel' , this.toggle_service_panel ) ;

		} , trigger_on_page_load : function ( e ) {

			$( '.bsf_added_services' ).sortable( {
				axis : 'y' ,
				handle : '.bsf_drag_services' ,
				update : this.drag_services_list }
			) ;

		} , add_service : function ( e ) {
			e.preventDefault( ) ;
			var $this = $( e.currentTarget ) ,
					$form = $( $this ).closest( '.bsf_add_service' ) ;

			BSF_Service.block( $form ) ;

			var data = {
				action : 'bsf_add_service' ,
				bsf_security : bsf_services_params.services_nonce ,
			} ;

			$.post( ajaxurl , data , function ( res ) {
				if ( res.success === true ) {
					$( '.bsf_added_services' ).append( res.data.field ) ;
					$( document.body ).trigger( 'bsf-enhanced-init' ) ;
				} else {
					window.alert( res.data.error ) ;
				}

				BSF_Service.unblock( $form ) ;

			} ) ;
		} , toggle_service_panel : function ( e ) {
			e.preventDefault( ) ;
			var $this = $( e.currentTarget ) ,
					container = $( $this ).closest( '.bsf_newly_added_services' ) ;

			container.find( '.bsf_services_info' ).toggle( ) ;
		} , save_service : function ( e ) {
			e.preventDefault( ) ;
			var $this = $( e.currentTarget ) ,
					$form = $( $this ).closest( '.bsf_newly_added_services' ) ;

			BSF_Service.block( $form ) ;

			var data = {
				action : 'bsf_save_service' ,
				servicesid : $form.find( '.bsf_save_services' ).attr( 'data-serviceid' ) ,
				servicestitle : $form.find( '.bsf_services_title' ).val( ) ,
				servicescolor : $form.find( '.bsf_services_color' ).val( ) ,
				servicesprice : $form.find( '.bsf_services_price' ).val( ) ,
				servicesduration : $form.find( '.bsf_services_duration' ).val( ) ,
				servicestimeslot : $form.find( '.bsf_services_time_slot' ).val( ) ,
				servicesinfo : $form.find( '.bsf_services_service_info' ).val( ) ,
				bsf_security : bsf_services_params.services_nonce ,
			} ;

			$.post( ajaxurl , data , function ( res ) {
				if ( res.success === true ) {
					$form.find( '.bsf_update_services_name' ).text( res.data.name ) ;

					window.alert( bsf_services_params.service_saved_msg ) ;
				} else {
					window.alert( res.data.error ) ;
				}
				BSF_Service.unblock( $form ) ;
			} ) ;
		} , delete_services : function ( e ) {
			e.preventDefault( ) ;
			var $this = $( e.currentTarget ) ,
					$form = $( $this ).closest( '.bsf_delete_services_btn' ) ;

			if ( !confirm( bsf_services_params.services_delete_alert_msg ) ) {
				return false ;
			}

			BSF_Service.block( $form ) ;
			var selectedservices = [ ] ;

			$.each( $( "input[name='bsf_delete_service']:checked" ) , function ( ) {
				selectedservices.push( $( this ).attr( 'data-serviceid' ) ) ;
			} ) ;

			var data = {
				action : 'bsf_delete_services' ,
				servicesid : selectedservices ,
				bsf_security : bsf_services_params.services_nonce ,
			} ;

			$.post( ajaxurl , data , function ( res ) {
				if ( res.success === true ) {
					$.each( $( "input[name='bsf_delete_service']:checked" ) , function ( ) {
						var serviceid = $( this ).attr( 'data-serviceid' ) ;
						$( '.bsf_newly_added_services_' + serviceid ).remove( ) ;
					} ) ;
					window.alert( bsf_services_params.services_delete_msg ) ;
				} else {
					window.alert( res.data.error ) ;
				}
				BSF_Service.unblock( $form ) ;
			} ) ;
		} , drag_services_list : function () {
			var serviceid = [ ] ;
			$( '.bsf_added_services' ).children( 'div' ).each( function ( ) {
				serviceid.push( $( this ).data( 'serviceid' ) ) ;
			} ) ;

			var data = {
				action : 'bsf_update_position_for_services' ,
				serviceid : serviceid ,
				bsf_security : bsf_services_params.services_nonce ,
			} ;

			$.post( ajaxurl , data , function ( res ) {
				if ( res.success === true ) {

				} else {
					window.alert( res.data.error ) ;
				}
			} ) ;

		} , block : function ( id ) {
			$( id ).block( {
				message : null ,
				overlayCSS : {
					background : '#fff' ,
					opacity : 0.7
				}
			} ) ;
		} , unblock : function ( id ) {
			$( id ).unblock( ) ;
		} ,
	} ;
	BSF_Service.init( ) ;

} ) ;
