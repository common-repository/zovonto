/*
 * jCal calendar multi-day and multi-month datepicker plugin for jQuery
 *	version 0.3.6
 * Author: Jim Palmer
 * Released under MIT license.
 */
( function ( $ ) {
	'use strict' ;

	$.fn.jCal = function ( opt ) {
		$.jCal( this , opt ) ;
	} ;

	$.jCal = function ( target , opt ) {
		opt = $.extend( {
			day : new Date() , // date to drive first cal
			days : 1 , // default number of days user can select
			showMonths : 1 , // how many side-by-side months to show
			monthSelect : false , // show selectable month and year ranges via animated comboboxen
			dCheck : function ( day ) {
				return true ;
			} , // handler for checking if single date is valid or not
			callback : function ( day , days ) {
				return true ;
			} , // callback function for click on date
			selectedBG : 'rgb(0, 143, 214)' , // default bgcolor for selected date cell
			defaultBG : 'rgb(255, 255, 255)' , // default bgcolor for unselected date cell
			dayOffset : 0 , // 0=week start with sunday, 1=week starts with monday
			forceWeek : false , // true=force selection at start of week, false=select days out from selected day
			dow : [ 'S' , 'M' , 'T' , 'W' , 'T' , 'F' , 'S' ] , // days of week - change this to reflect your dayOffset
			ml : [ 'January' , 'February' , 'March' , 'April' , 'May' , 'June' , 'July' , 'August' , 'September' , 'October' , 'November' , 'December' ] ,
			ms : [ 'Jan' , 'Feb' , 'Mar' , 'Apr' , 'May' , 'Jun' , 'Jul' , 'Aug' , 'Sep' , 'Oct' , 'Nov' , 'Dec' ] ,
			_target : target										// target DOM element - no need to set extend this variable
		} , opt ) ;
		opt.day = new Date( opt.day.getFullYear() , opt.day.getMonth() , 1 ) ;
		if ( !$( opt._target ).data( 'days' ) ) {
			$( opt._target ).data( 'days' , opt.days ) ;
		}
		$( target ).stop().empty() ;
		for ( var sm = 0 ; sm < opt.showMonths ; sm++ ) {
			$( target ).append( '<div class="jCalMo"></div>' ) ;
		}
		opt.cID = 'c' + $( '.jCalMo' ).length ;
		$( '.jCalMo' , target ).each(
				function ( ind ) {
					drawCalControl( $( this ) , $.extend( { } , opt , { 'ind' : ind ,
						'day' : new Date( new Date( opt.day.getTime() ).setMonth( new Date( opt.day.getTime() ).getMonth() + ind ) ) }
					) ) ;
					drawCal( $( this ) , $.extend( { } , opt , { 'ind' : ind ,
						'day' : new Date( new Date( opt.day.getTime() ).setMonth( new Date( opt.day.getTime() ).getMonth() + ind ) ) }
					) ) ;
				} ) ;

	} ;
	function drawCalControl( target , opt ) {
		$( target ).append(
				'<div class="jCal">' +
				( ( opt.ind == 0 ) ? '<div class="left" />' : '' ) +
				'<div class="month">' +
				'<span class="monthName">' + opt.ml[opt.day.getMonth()] + '</span>' +
				'</div>' +
				( ( opt.ind == ( opt.showMonths - 1 ) ) ? '<div class="right" />' : '' ) +
				'</div>' ) ;

		// set current year
		$( '.jcal_year' ).val( opt.day.getFullYear() ) ;


		// left arrow
		target.find( '.jCal .left' ).on( 'click' , $.extend( { } , opt ) ,
				function ( e ) {
					if ( $( '.jCalMask' , e.data._target ).length > 0 ) {
						return false ;
					}
					$( e.data._target ).stop() ;
					var mD = { w : 0 , h : 0 } ;
					$( '.jCalMo' , e.data._target ).each( function () {
						mD.w += $( this ).width() + parseInt( $( this ).css( 'padding-left' ) ) + parseInt( $( this ).css( 'padding-right' ) ) ;
						var cH = $( this ).height() + parseInt( $( this ).css( 'padding-top' ) ) + parseInt( $( this ).css( 'padding-bottom' ) ) ;
						mD.h = ( ( cH > mD.h ) ? cH : mD.h ) ;
					} ) ;
					// save right arrow
					var right = null ;
					// create new previous 12 months
					for ( var i = 0 ; i < 12 ; i++ ) {
						$( e.data._target ).prepend( '<div class="jCalMo"></div>' ) ;
						e.data.day = new Date( $( 'div[id*=' + e.data.cID + 'd_]:first' , e.data._target ).attr( 'id' ).replace( e.data.cID + 'd_' , '' ).replace( /_/g , '/' ) ) ;
						e.data.day.setDate( 1 ) ;
						e.data.day.setMonth( e.data.day.getMonth() - 1 ) ;
						drawCalControl( $( '.jCalMo:first' , e.data._target ) , e.data ) ;
						drawCal( $( '.jCalMo:first' , e.data._target ) , e.data ) ;
						// clone right arrow
						right = $( '.right' , e.data._target ).clone( true ) ;
					}
					// and delete previous 12 month
					for ( var i = 0 ; i < 12 ; i++ ) {
						$( '.jCalMo:last' ).remove() ;
					}
					// restore left arrow
					right.appendTo( $( '.jCalMo:eq(1) .jCal' , e.data._target ) ) ;
				} ) ;

		// right arrow
		target.find( '.jCal .right' ).on( 'click' , $.extend( { } , opt ) ,
				function ( e ) {
					if ( $( '.jCalMask' , e.data._target ).length > 0 ) {
						return false ;
					}
					$( e.data._target ).stop() ;
					var mD = { w : 0 , h : 0 } ;
					$( '.jCalMo' , e.data._target ).each( function () {
						mD.w += $( this ).width() + parseInt( $( this ).css( 'padding-left' ) ) + parseInt( $( this ).css( 'padding-right' ) ) ;
						var cH = $( this ).height() + parseInt( $( this ).css( 'padding-top' ) ) + parseInt( $( this ).css( 'padding-bottom' ) ) ;
						mD.h = ( ( cH > mD.h ) ? cH : mD.h ) ;
					} ) ;
					// need save left arrow before remove first month
					var left = false ;
					// create new next 12 month
					for ( var i = 0 ; i < 12 ; i++ ) {
						$( e.data._target ).append( '<div class="jCalMo"></div>' ) ;
						e.data.day = new Date( $( 'div[id^=' + e.data.cID + 'd_]:last' , e.data._target ).attr( 'id' ).replace( e.data.cID + 'd_' , '' ).replace( /_/g , '/' ) ) ;
						e.data.day.setDate( 1 ) ;
						e.data.day.setMonth( e.data.day.getMonth() + 1 ) ;
						drawCalControl( $( '.jCalMo:last' , e.data._target ) , e.data ) ;
						drawCal( $( '.jCalMo:last' , e.data._target ) , e.data ) ;
						// clone left arrow
						left = $( '.left' , e.data._target ).clone( true ) ;
					}
					// and delete previous 12 month
					for ( var i = 0 ; i < 12 ; i++ ) {
						$( '.jCalMo:first' ).remove() ;
					}
					// restore left arrow
					left.prependTo( $( '.jCalMo:eq(1) .jCal' , e.data._target ) ) ;
				} ) ;
	}

	function drawCal( target , opt ) {
		for ( var ds = 0 , length = opt.dow.length ; ds < length ; ds++ ) {
			$( target ).append( '<div class="dow">' + opt.dow[ds] + '</div>' ) ;
		}
		var fd = new Date( new Date( opt.day.getTime() ).setDate( 1 ) ) ;
		var ldlm = new Date( new Date( fd.getTime() ).setDate( 0 ) ) ;
		var ld = new Date( new Date( new Date( fd.getTime() ).setMonth( fd.getMonth() + 1 ) ).setDate( 0 ) ) ;
		var copt = { fd : fd.getDay() , lld : ldlm.getDate() , ld : ld.getDate() } ;
		var offsetDayStart = ( ( copt.fd < opt.dayOffset ) ? ( opt.dayOffset - 7 ) : 0 ) ;
		var offsetDayEnd = ( ( ld.getDay() < opt.dayOffset ) ? ( 7 - ld.getDay() ) : ld.getDay() ) ;

		for ( var d = offsetDayStart , dE = ( copt.fd + copt.ld + ( 6 - offsetDayEnd ) ) ; d < dE ; d++ ) {
			$( target ).append(
					( ( d <= ( copt.fd - opt.dayOffset ) ) ?
							'<div id="' + opt.cID + 'd' + d + '" class="pday">' + ( copt.lld - ( ( copt.fd - opt.dayOffset ) - d ) ) + '</div>'
							: ( ( d > ( ( copt.fd - opt.dayOffset ) + copt.ld ) ) ?
									'<div id="' + opt.cID + 'd' + d + '" class="aday">' + ( d - ( ( copt.fd - opt.dayOffset ) + copt.ld ) ) + '</div>'
									: '<div id="' + opt.cID + 'd_' + ( fd.getMonth() + 1 ) + '_' + ( d - ( copt.fd - opt.dayOffset ) ) + '_' + fd.getFullYear() + '" class="' +
									( ( opt.dCheck( new Date( ( new Date( fd.getTime() ) ).setDate( d - ( copt.fd - opt.dayOffset ) ) ) ) ) ? 'day' : 'invday' ) +
									'">' + ( d - ( copt.fd - opt.dayOffset ) ) + '</div>'
									)
							)
					) ;
		}
		$( target ).find( 'div[id^=' + opt.cID + 'd]:first, div[id^=' + opt.cID + 'd]:nth-child(7n+2)' ).before( '<br style="clear:both; font-size:0.1em;" />' ) ;
		$( target ).find( 'div[id^=' + opt.cID + 'd_]:not(.invday)' ).bind( "mouseover mouseout click" , $.extend( { } , opt ) ,
				function ( e ) {
					if ( $( '.jCalMask' , e.data._target ).length > 0 ) {
						return false ;
					}
					var osDate = new Date( $( this ).attr( 'id' ).replace( /c[0-9]{1,}d_([0-9]{1,2})_([0-9]{1,2})_([0-9]{4})/ , '$1/$2/$3' ) ) ;
					if ( e.data.forceWeek ) {
						osDate.setDate( osDate.getDate() + ( e.data.dayOffset - osDate.getDay() ) ) ;
					}
					var sDate = new Date( osDate.getTime() ) ;
					if ( e.type == 'click' ) {
						$( 'div[id*=d_]' , e.data._target ).stop().removeClass( 'selectedDay' ).removeClass( 'overDay' ).css( 'backgroundColor' , '' ) ;
					}
					for ( var di = 0 , ds = $( e.data._target ).data( 'days' ) ; di < ds ; di++ ) {
						var currDay = $( e.data._target ).find( '#' + e.data.cID + 'd_' + ( sDate.getMonth() + 1 ) + '_' + sDate.getDate() + '_' + sDate.getFullYear() ) ;
						if ( currDay.length == 0 || $( currDay ).hasClass( 'invday' ) ) {
							break ;
						}
						if ( e.type == 'mouseover' ) {
							$( currDay ).addClass( 'overDay' ) ;
						} else if ( e.type == 'mouseout' ) {
							$( currDay ).stop().removeClass( 'overDay' ).css( 'backgroundColor' , '' ) ;
						} else if ( e.type == 'click' ) {
							$( currDay ).stop().addClass( 'selectedDay' ) ;
						}
						sDate.setDate( sDate.getDate() + 1 ) ;
					}
					if ( e.type == 'click' ) {
						e.data.day = osDate ;
						drawPopup( target , opt , this , osDate ) ;
						e.data.callback( osDate , di ) ;
						$( e.data._target ).data( 'day' , e.data.day ).data( 'days' , di ) ;
					}
				} ) ;

		// draw events for this month
		var events = JSON.parse( $( '.bsf_holiday_events' ).val( ) ) ;

		if ( events ) {
			drawEvents( target , fd.getMonth() + 1 , events ) ;
		}

	}

	// draw the events in calendar (called for each month)
	function drawEvents( $target , month , events ) {
		// remove old events
		$( '.bsf_holidayday' , $target ).removeClass( 'bsf_holidayday' ).data( 'id' , null ) ;
		$( '.bsf_repeatday' , $target ).removeClass( 'bsf_repeatday' ) ;

		// and add new
		for ( var i in events ) {
			if ( events.hasOwnProperty( i ) ) {
				if ( events[i].m == month ) {
					$target.find( getEventSelector( events[i] ) )
							.addClass( 'bsf_holidayday' )
							.addClass( events[i]['y'] != '' ? '' : 'bsf_repeatday' )
							.data( 'id' , i ) ;
				}
			}
		}
	}


	// create a selector string by event
	function getEventSelector( event ) {
		return 'div[id^=c12d_' + event.m + '_' + event.d + '_' + ( event.hasOwnProperty( 'y' ) ? ( event.y + ']' ) : ']' ) ;
	}

	function drawPopup( $target , opt , div , day ) {
		$( '.bsf_holiday_popup' ).remove() ;
		var $div = $( div ) ;

		var holiday_checked = $div.hasClass( 'bsf_holidayday' ) ? 'checked="checked"' : '' ;
		var repeat_checked = $div.hasClass( 'bsf_repeatday' ) ? 'checked="checked"' : '' ;
		var disabled = holiday_checked ? '' : 'disabled="disabled"' ;

		var $popup = '<div class="bsf_holiday_popup">' +
				'<p><span class="bsf_close_holiday_popup">' +
				'<i class="fa fa-times-circle" aria-hidden="true"></i></span>' +
				'<label>' + opt.holiday_label + '</label>' +
				'<input type="checkbox" class="bsf_holiday_day_selection" ' + holiday_checked + '/></p>' +
				'<p><label>' + opt.repeat_label + '</label>' +
				'<input type="checkbox" class="bsf_repeat_day_selection" ' + repeat_checked + ' ' + disabled + '/>' +
				'</p><input type="button" disabled="disabled" class="bsf_save_holiday_settings" value="' + opt.save_label + '"/>' +
				'</div>' ;

		$div.after( $popup ) ;

		$( '.bsf_save_holiday_settings' ).on( 'click' , function ( event ) {
			event.preventDefault() ;
			var $this = $( event.currentTarget ) ,
					popup = $( $this ).closest( 'div.bsf_holiday_popup' ) ;

			var data = {
				action : 'bsf_update_holidays' ,
				id : $div.data( 'id' ) ,
				holiday : popup.find( '.bsf_holiday_day_selection' ).is( ':checked' ) ,
				repeat : popup.find( '.bsf_repeat_day_selection' ).is( ':checked' ) ,
				staff_id : $( '.bsf_holiday_staff_id' ).val() ,
				day : day.getFullYear() + '-' + ( day.getMonth() + 1 ) + '-' + day.getDate() ,
				bsf_security : opt.holiday_nonce
			} ;

			$.post( ajaxurl , data , function ( response ) {
				if ( true === response.success ) {
					$( '.bsf_holiday_events' ).val( JSON.stringify( response.data.events ) ) ;
					drawEvents( $target , day.getMonth() + 1 , response.data.events ) ;
					$( popup ).remove() ;
				} else {
					window.alert( response.data.error ) ;
				}
			} ) ;
		} ) ;
	}

} )( jQuery ) ;
