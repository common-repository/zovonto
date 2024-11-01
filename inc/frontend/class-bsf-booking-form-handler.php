<?php

/**
 *  Handles Booking Form
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'BSF_Booking_Form_Handler' ) ) {

	/**
	 * Class
	 */
	class BSF_Booking_Form_Handler {

		/**
		 * Render Service - Step 1
		 */
		public static function render_service( $data ) {
			$booking_data_object = new BSF_Booking_Data( bsf_sanitize_text_field( $data[ 'form_id' ] ) ) ;

			//Dropdown values for categories, staff and services.
			$dropdown_values = BSF_Data_Handler::get_dropdown_values() ;

			// Dropdown values for times and day values
			list($times , $days) = BSF_Data_Handler::get_service_days_times() ;

			ob_start() ;

			include_once 'views/booking/step-service.php' ;

			$contents = ob_get_contents() ;
			ob_end_clean() ;

			$options = array(
				'html'             => $contents ,
				'services'         => $dropdown_values[ 'services_options' ] ,
				'selected_service' => $booking_data_object->get_service_id() ,
				'max_date'         => ( int ) get_option( 'bsf_settings_advanced_booking_period' , 30 ) ,
					) ;

			return $options ;
		}

		/**
		 * Render Time - Step 3
		 */
		public static function render_time( $form_id ) {

			$booking_data_object = new BSF_Booking_Data( $form_id ) ;

			$slots     = BSF_Slots::get_slots( $booking_data_object ) ;
			$info_text = self::prepare_time_step_info_text( $booking_data_object ) ;

			$selected_slot = $booking_data_object->get_slots() ;
			$selected_date = isset( $selected_slot[ 2 ] ) ? $selected_slot[ 2 ] : null ;
			ob_start() ;

			include_once 'views/booking/step-time.php' ;

			$contents = ob_get_contents() ;
			ob_end_clean() ;

			$options = array(
				'html'          => $contents ,
				'slot_data'     => $slots ,
				'selected_date' => $selected_date
					) ;

			return $options ;
		}

		/**
		 * Render Details - Step 4
		 */
		public static function render_details( $form_id ) {
			$booking_data_object = new BSF_Booking_Data( $form_id ) ;
			$info_text           = self::prepare_detail_step_info_text( $booking_data_object ) ;

			ob_start() ;

			include_once 'views/booking/step-details.php' ;

			$contents = ob_get_contents() ;
			ob_end_clean() ;

			$options = array(
				'html' => $contents ,
					) ;

			return $options ;
		}

		/**
		 * Render Payment - Step 5
		 */
		public static function render_payment( $form_id ) {
			global $bsf_form_id ;
			$bsf_form_id = $form_id ;

			$booking_data_object = new BSF_Booking_Data( $form_id ) ;
			ob_start() ;

			include_once 'views/booking/step-payment.php' ;

			$contents = ob_get_contents() ;
			ob_end_clean() ;

			$options = array(
				'html' => $contents ,
					) ;

			return $options ;
		}

		/**
		 * Render Complete - Step 6
		 */
		public static function render_complete( $form_id ) {
			$booking_data_object = new BSF_Booking_Data( $form_id ) ;

			$payment_object = new BSF_Payment( $booking_data_object->get_payment_id() ) ;

			ob_start() ;

			include_once 'views/booking/step-complete.php' ;

			$contents = ob_get_contents() ;
			ob_end_clean() ;

			$options = array(
				'html' => $contents ,
					) ;

			return $options ;
		}

		/**
		 * Render Progress bar 
		 */
		public static function render_progress_bar( $selected_step ) {
			ob_start() ;

			include_once 'views/booking/progress-bar.php' ;

			$contents = ob_get_contents() ;
			ob_end_clean() ;

			return $contents ;
		}

		/**
		 * Prepare text for details step
		 */
		public static function prepare_detail_step_info_text( $booking_data_object ) {

			$service_object = new BSF_Services( $booking_data_object->slot_info( 'service' ) ) ;
			$staff_object   = new BSF_Staff( $booking_data_object->slot_info( 'staff' ) ) ;

			$time_duration = BSF_Date_Time::time_to_seconds( $booking_data_object->slot_info( 'time' , true ) ) ;
			$booking_time  = BSF_Date_Time::seconds_to_time_format( $time_duration ) ;

			return sprintf( __( 'Your selected booking details are as follows %s by %s at %s on %s.The price for the booking is %s.'
							. 'Please fill in the details in order to proceed with the booking.' , 'zovonto' ) , '<b>' . $service_object->get_name() . '</b>' , '<b>' . $staff_object->get_name() . '</b>' , '<b>' . $booking_time . '</b>' , '<b>' . $booking_data_object->slot_info( 'date' , true ) . '</b>' , '<b>' . bsf_price( $booking_data_object->get_price() ) . '</b>'
					) ;
		}

		/**
		 * Prepare text for time step
		 */
		public static function prepare_time_step_info_text( $booking_data_object ) {

			$service_object = new BSF_Services( $booking_data_object->get_service_id() ) ;
			$staff_object   = new BSF_Staff( $booking_data_object->get_staff_id() ) ;

			return sprintf( __( 'Below you can find a list of available time slots for %s by %s.'
							. 'Click on a time slot to proceed with booking.' , 'zovonto' ) , '<b>' . $service_object->get_name() . '</b>' , '<b>' . $staff_object->get_name() . '</b>' ) ;
		}

	}

}
