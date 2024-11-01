jQuery( function ( $ ) {
	'use strict' ;

	try {
		$( document.body ).on( 'bsf-enhanced-init' , function () {
			if ( $( 'select.bsf_select2' ).length ) {
				//Select2 with customization
				$( 'select.bsf_select2' ).each( function () {
					var select2_args = {
						allowClear : $( this ).data( 'allow_clear' ) ? true : false ,
						placeholder : $( this ).data( 'placeholder' ) ,
						minimumResultsForSearch : 10 ,
					} ;
					$( this ).select2( select2_args ) ;
				} ) ;
			}
			if ( $( 'select.bsf_select2_search' ).length ) {
				//Multiple select with ajax search
				$( 'select.bsf_select2_search' ).each( function () {
					var select2_args = {
						allowClear : $( this ).data( 'allow_clear' ) ? true : false ,
						placeholder : $( this ).data( 'placeholder' ) ,
						minimumInputLength : $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : 3 ,
						escapeMarkup : function ( m ) {
							return m ;
						} ,
						ajax : {
							url : bsf_enhanced_select_params.ajax_url ,
							dataType : 'json' ,
							delay : 250 ,
							data : function ( params ) {
								return {
									term : params.term ,
									action : $( this ).data( 'action' ) ? $( this ).data( 'action' ) : '' ,
									bsf_security : $( this ).data( 'nonce' ) ? $( this ).data( 'nonce' ) : bsf_enhanced_select_params.search_nonce ,
								} ;
							} ,
							processResults : function ( data ) {
								var terms = [ ] ;
								if ( data ) {
									$.each( data , function ( id , term ) {
										terms.push( {
											id : id ,
											text : term
										} ) ;
									} ) ;
								}
								return {
									results : terms
								} ;
							} ,
							cache : true
						}
					} ;

					$( this ).select2( select2_args ) ;
				} ) ;
			}

			if ( $( '#bsf_from_date' ).length ) {
				$( '#bsf_from_date' ).each( function ( ) {

					$( this ).datepicker( {
						altField : $( this ).next( ".bsf_alter_datepicker_value" ) ,
						altFormat : 'yy-mm-dd' ,
						changeMonth : true ,
						changeYear : true ,
						onClose : function ( selectedDate ) {
							var maxDate = new Date( Date.parse( selectedDate ) ) ;
							maxDate.setDate( maxDate.getDate() + 1 ) ;
							$( '#bsf_to_date' ).datepicker( 'option' , 'minDate' , maxDate ) ;
						}
					} ) ;

				} ) ;
			}

			if ( $( '#bsf_to_date' ).length ) {
				$( '#bsf_to_date' ).each( function ( ) {

					$( this ).datepicker( {
						altField : $( this ).next( ".bsf_alter_datepicker_value" ) ,
						altFormat : 'yy-mm-dd' ,
						changeMonth : true ,
						changeYear : true ,
						onClose : function ( selectedDate ) {
							$( '#bsf_from_date' ).datepicker( 'option' , 'maxDate' , selectedDate ) ;
						}
					} ) ;

				} ) ;
			}

			if ( $( '.bsf_datepicker' ).length ) {
				$( '.bsf_datepicker' ).each( function ( ) {
					$( this ).datepicker( {
						altField : $( this ).next( ".bsf_alter_datepicker_value" ) ,
						altFormat : 'yy-mm-dd' ,
						changeMonth : true ,
						changeYear : true
					} ) ;
				} ) ;
			}

			if ( $( '.bsf_colorpicker' ).length ) {
				$( '.bsf_colorpicker' ).each( function ( ) {

					$( this ).iris( {
						change : function ( event , ui ) {
							$( this ).css( { backgroundColor : ui.color.toString( ) } ) ;
						} ,
						hide : true ,
						border : true
					} ) ;

					$( this ).css( 'background-color' , $( this ).val() ) ;
				} ) ;

				$( document ).on( 'click' , function ( e ) {
					if ( !$( e.target ).is( ".bsf_colorpicker, .iris-picker, .iris-picker-inner" ) ) {
						$( '.bsf_colorpicker' ).iris( 'hide' ) ;
					}
				} ) ;

				$( '.bsf_colorpicker' ).on( 'click' , function ( e ) {
					$( '.bsf_colorpicker' ).iris( 'hide' ) ;
					$( this ).iris( 'show' ) ;
				} ) ;
			}
		} ) ;

		$( document.body ).trigger( 'bsf-enhanced-init' ) ;
	} catch ( err ) {
		window.console.log( err ) ;
	}

} ) ;
