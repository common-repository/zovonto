<?php

/**
 * Booking Form
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Booking_Form' ) ) {

	/**
	 * Class.
	 */
	class BSF_Booking_Form {

		public static function init() {
			add_shortcode( 'bsf_booking_form' , array( __CLASS__ , 'form' ) , 10 , 3 ) ;
		}

		/**
		 *  Display Booking Form 
		 */
		public static function form( $atts, $content, $tag ) {
			//return if shortcode is not bsf_booking_form
			if ( $tag != 'bsf_booking_form' ) {
				return $content ;
			}

			ob_start() ;

			//enqueue booking form style
			self::enqueue_style() ;

			// Generate unique form id.
			$form_id = uniqid() ;

			//prpeare booking form options
			$booking_options = array( 'bsf_booking_form_params' => array(
					'form_id'   => $form_id ,
					'from_date' => date( 'Y-m-d' , time() ) ,
					'week_days' => array( 0 , 1 , 2 , 3 , 4 , 5 , 6 ) ,
					'from_time' => '00:00' ,
					'to_time'   => '23:59' ,
				) ) ;

			//enqueue booking form script
			self::enqueue_script( $booking_options ) ;

			//enqueue booking form style
			self::enqueue_style() ;

			include_once 'views/booking/form.php' ;

			$html = ob_get_contents() ;
			ob_end_clean() ;

			return $html ;
		}

		/**
		 * Function that enqueue style
		 */
		public static function enqueue_style() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

			wp_enqueue_style( 'select2' , BSF_PLUGIN_URL . '/assets/css/select2/select2' . $suffix . '.css' , array() , '4.0.5' ) ;
			wp_enqueue_style( 'jquery-ui-style' , BSF_PLUGIN_URL . '/assets/css/jquery-ui.min.css' , array() , BSF_VERSION ) ;
			wp_enqueue_style( 'bsf-booking-form' , BSF_PLUGIN_URL . '/assets/css/frontend/booking-form.css' , array() , BSF_VERSION ) ;
		}

		/**
		 * Function that enqueue script
		 */
		public static function enqueue_script( $options ) {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

			$default_options = array( 'bsf_booking_default_params' => array(
					'start_of_week'     => ( int ) get_option( 'start_of_week' ) ,
					'render_step_nonce' => wp_create_nonce( 'bsf-render-step-nonce' ) ,
					'ajax_url'          => BSF_ADMIN_AJAX_URL ,
					'date_format'       => BSF_Date_Time::convert_wp_to_js_format( 'date' ) ,
				)
					) ;

			$js_block = 'var bsf_booking_default_params=' . wp_json_encode( reset( $default_options ) ) . ';' ;
			$js_block .= 'var bsf_booking_form_params=' . wp_json_encode( reset( $options ) ) . ';' ;

			wp_register_script( 'select2' , BSF_PLUGIN_URL . '/assets/js/select2/select2' . $suffix . '.js' , array( 'jquery' ) , '4.0.5' ) ;
			wp_enqueue_script( 'bsf-booking-form' , BSF_PLUGIN_URL . '/assets/js/frontend/booking-form.js' , array( 'jquery' , 'blockUI' , 'select2' ) , BSF_VERSION ) ;

			wp_add_inline_script( 'bsf-booking-form' , $js_block ) ;
		}

	}

	BSF_Booking_Form::init() ;
}
