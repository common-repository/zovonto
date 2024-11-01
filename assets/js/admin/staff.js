jQuery( function ( $ ) {
	'use strict' ;
	var file_frame ;
	$( 'body' ).on( 'click' , '.bsf_upload_staff_image' , function ( e ) {

		e.preventDefault( ) ;
		var $button = $( this ) ;
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open( ) ;
			return ;
		}

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media( {
			frame : 'select' ,
			title : $button.data( 'title' ) ,
			multiple : false ,
			library : {
				type : 'image'
			} ,
			button : {
				text : $button.data( 'button' )
			}
		} ) ;
		// When an image is selected, run a callback.
		file_frame.on( 'select' , function ( ) {
			var selection = file_frame.state( ).get( 'selection' ) ;
			selection.map( function ( attachment ) {
				attachment = attachment.toJSON( ) ;
				if ( attachment.id ) {
					$button.closest( '.bsf_profile_image' ).find( 'img.bsf_profile_edit_image_url' ).attr( 'src' , attachment.url ) ;
					$button.closest( '.bsf_profile_image' ).find( '.bsf_staff_image_attachment_id' ).val( attachment.id ) ;
				}
			} ) ;
			// replace previous image with new one if selected
		} ) ;
		// Finally, open the modal
		file_frame.open( ) ;
	} ) ;
	var BSF_Staff = {
		init : function ( ) {

			$( document ).on( 'click' , '.bsf_add_staff' , this.add_staff ) ;
			$( document ).on( 'click' , '.bsf_close_staff_popup' , this.close_staff_popup ) ;
			$( document ).on( 'click' , '.bsf_add_staff_popup' , this.display_add_staff_popup ) ;
			$( document ).on( 'click' , '.bsf_delete_staff_image' , this.delete_image ) ;
			$( document ).on( 'click' , '.bsf_staff_delete_btn' , this.delete_staff ) ;
		} , display_add_staff_popup : function ( e ) {
			e.preventDefault( ) ;
			var $this = $( e.currentTarget ) ;

			var data = {
				action : 'bsf_staff_selection_popup' ,
				bsf_security : bsf_staff_params.staff_nonce ,
			} ;

			$.post( ajaxurl , data , function ( res ) {
				if ( res.success === true ) {
					$( 'div.bsf_new_staff_popup' ).remove( ) ;
					$( $this ).after( res.data.html )

					$( document.body ).trigger( 'bsf-enhanced-init' ) ;
				} else {
					window.alert( res.data.error ) ;
				}
			} ) ;
		} , close_staff_popup : function ( e ) {
			e.preventDefault( ) ;
			var $this = $( e.currentTarget ) ,
					popup = $( $this ).closest( 'div.bsf_new_staff_popup' ) ;

			popup.remove( ) ;
		} , add_staff : function ( e ) {
			e.preventDefault( ) ;
			var $this = $( e.currentTarget ) ,
					$form = $( $this ).closest( '.bsf_new_staff_form' ) ,
					$panel = $( $this ).closest( '.bsf_category_panel' ) ;

			BSF_Staff.block( $form ) ;

			var data = {
				action : 'bsf_add_staff' ,
				user_id : $form.find( '#user_id' ).val( ) ,
				category_id : $panel.data( 'category' ) ,
				bsf_security : bsf_staff_params.staff_nonce ,
			} ;

			$.post( ajaxurl , data , function ( res ) {
				if ( res.success === true ) {
					window.location.reload( ) ;
				} else {
					window.alert( res.data.error ) ;
				}
				BSF_Staff.unblock( $form ) ;
			} ) ;

		} , delete_image : function ( e ) {
			e.preventDefault( ) ;
			var $this = $( e.currentTarget ) ,
					$div = $( $this ).closest( '.bsf_profile_image' ) ;
			$div.find( '.bsf_staff_image_attachment_id' ).val( '' ) ;
			$div.find( 'img.bsf_profile_edit_image_url' ).attr( 'src' , bsf_staff_params.image_placeholder_url ) ;
		} , delete_staff : function ( e ) {
			e.preventDefault( ) ;
			if ( !confirm( bsf_staff_params.staff_delete_msg ) ) {
				return false ;
			}

			var $this = $( e.currentTarget ) ,
					$div = $( $this ).closest( '.bsf_staff_tab_footer' ) ,
					$form = $( $this ).closest( '.bsf_staff_details' ) ;

			BSF_Staff.block( $form ) ;

			var data = {
				action : 'bsf_delete_staff' ,
				staff_id : $div.find( '.bsf_staff_id' ).val( ) ,
				bsf_security : bsf_staff_params.staff_nonce ,
			} ;

			$.post( ajaxurl , data , function ( res ) {
				if ( res.success === true ) {
					window.location.reload( ) ;
				} else {
					window.alert( res.data.error ) ;
				}
				BSF_Staff.unblock( $form ) ;
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
	BSF_Staff.init( ) ;
} ) ;
