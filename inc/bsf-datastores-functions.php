<?php

/*
 * Post functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! function_exists( 'bsf_create_new_staff' ) ) {

	function bsf_create_new_staff( $data ) {

		$staff    = new BSF_Staff() ;
		$staff_id = $staff->create( $data ) ;

		//default working hour for staff
		$working_hours = bsf_get_default_working_hours() ;
		foreach ( $working_hours as $day_index => $working_hour ) {
			$working_hours_data = array(
				'staff_id'   => $staff_id ,
				'day_index'  => $day_index ,
				'start_time' => $working_hour[ 'start' ] ,
				'end_time'   => $working_hour[ 'end' ]
					) ;

			bsf_create_new_staff_working_hours( $working_hours_data ) ;
		}

		//default Holiday for staff
		$holidays = bsf_get_default_holiday() ;
		foreach ( $holidays as $holiday ) {
			$holiday_data = array(
				'staff_id' => $staff_id ,
				'date'     => $holiday[ 'date' ] ,
				'repeat'   => $holiday[ 'repeat' ] ,
					) ;

			bsf_create_new_holiday( $holiday_data ) ;
		}

		update_user_meta( $data[ 'wp_user_id' ] , 'bsf_staff_enabled' , 'yes' ) ;

		return $staff_id ;
	}

}

if ( ! function_exists( 'bsf_update_staff' ) ) {

	function bsf_update_staff( $staff_id, $data ) {

		$staff    = new BSF_Staff( $staff_id ) ;
		$staff_id = $staff->update( $data ) ;

		return $staff_id ;
	}

}

if ( ! function_exists( 'bsf_create_new_staff_services' ) ) {

	function bsf_create_new_staff_services( $data ) {

		$staff_services    = new BSF_Staff_Services() ;
		$staff_services_id = $staff_services->create( $data ) ;

		return $staff_services_id ;
	}

}

if ( ! function_exists( 'bsf_update_staff_services' ) ) {

	function bsf_update_staff_services( $staff_services_id, $data ) {

		$staff_services    = new BSF_Staff_Services( $staff_services_id ) ;
		$staff_services_id = $staff_services->update( $data ) ;

		return $staff_services_id ;
	}

}

if ( ! function_exists( 'bsf_delete_staff_services' ) ) {

	function bsf_delete_staff_services( $staff_services_id, $staff_services_obj = array() ) {

		if ( ! is_a( $staff_services_obj , 'BSF_Staff_Services' ) ) {
			$staff_services_obj = new BSF_Staff_Services( $staff_services_id ) ;
		}

		$staff_services_obj->delete() ;

		return true ;
	}

}

if ( ! function_exists( 'bsf_delete_staff' ) ) {

	function bsf_delete_staff( $staff_id, $staff_object = array() ) {

		if ( ! is_a( $staff_object , 'BSF_Staff' ) ) {
			$staff_object = new BSF_Staff( $staff_id ) ;
		}

		$staff_object->delete() ;

		delete_user_meta( $staff_object->get_wp_user_id() , 'bsf_staff_enabled' ) ; // remove meta from user

		return true ;
	}

}

if ( ! function_exists( 'bsf_create_new_service' ) ) {

	function bsf_create_new_service( $data ) {

		$services   = new BSF_Services() ;
		$service_id = $services->create( $data ) ;

		$staff_table = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;
		$query       = new BSF_Query( $staff_table ) ;
		$staffs      = $query->orderBy( 'position' )->fetchArray() ;

		foreach ( $staffs as $staff ) {
			$staff_service_data = array(
				'staff_id'   => $staff[ 'id' ] ,
				'service_id' => $service_id ,
					) ;

			bsf_create_new_staff_services( $staff_service_data ) ;
		}

		return $service_id ;
	}

}

if ( ! function_exists( 'bsf_update_service' ) ) {

	function bsf_update_service( $service_id, $data ) {

		$services = new BSF_Services( $service_id ) ;
		$services->update( $data ) ;
	}

}
if ( ! function_exists( 'bsf_delete_service' ) ) {

	function bsf_delete_service( $service_id, $service_object = array() ) {

		if ( ! is_a( $service_object , 'BSF_Services' ) ) {
			$service_object = new BSF_Services( $service_id ) ;
		}

		$service_object->delete() ;

		return true ;
	}

}

if ( ! function_exists( 'bsf_create_new_customer' ) ) {

	function bsf_create_new_customer( $data ) {

		$customer_object = new BSF_Customer() ;
		$customer_id     = $customer_object->create( $data ) ;

		return $customer_id ;
	}

}

if ( ! function_exists( 'bsf_update_customer' ) ) {

	function bsf_update_customer( $customer_id, $data ) {

		$customer_object = new BSF_Customer( $customer_id ) ;
		$customer_object->update( $data ) ;
	}

}
if ( ! function_exists( 'bsf_delete_customer' ) ) {

	function bsf_delete_customer( $customer_id, $customer_object = array() ) {

		if ( ! is_a( $customer_object , 'BSF_Customer' ) ) {
			$customer_object = new BSF_Customer( $customer_id ) ;
		}

		$customer_object->delete() ;

		return true ;
	}

}

if ( ! function_exists( 'bsf_create_new_payment' ) ) {

	function bsf_create_new_payment( $data ) {

		$payment_object = new BSF_Payment() ;
		$payment_id     = $payment_object->create( $data ) ;

		return $payment_id ;
	}

}

if ( ! function_exists( 'bsf_update_payment' ) ) {

	function bsf_update_payment( $payment_id, $data ) {

		$payment_object = new BSF_Payment( $payment_id ) ;
		$payment_object->update( $data ) ;
	}

}
if ( ! function_exists( 'bsf_delete_payment' ) ) {

	function bsf_delete_payment( $payment_id, $payment_object = array() ) {

		if ( ! is_a( $payment_object , 'BSF_Payment' ) ) {
			$payment_object = new BSF_Payment( $payment_id ) ;
		}

		$payment_object->delete() ;

		return true ;
	}

}

if ( ! function_exists( 'bsf_create_new_appointment' ) ) {

	function bsf_create_new_appointment( $data ) {

		$appointment_object = new BSF_Appointment() ;
		$appointment_id     = $appointment_object->create( $data ) ;

		do_action( 'bsf_appointments_after_saved' , $appointment_id , $data ) ;

		do_action( 'bsf_status_changed_' . $data[ 'status' ] , $appointment_id ) ;

		return $appointment_id ;
	}

}

if ( ! function_exists( 'bsf_update_appointment' ) ) {

	function bsf_update_appointment( $appointment_id, $data ) {

		$appointment_object = new BSF_Appointment( $appointment_id ) ;
		$appointment_object->update( $data ) ;

		do_action( 'bsf_status_changed_' . $appointment_object->get_status() , $appointment_id ) ;
	}

}
if ( ! function_exists( 'bsf_delete_appointment' ) ) {

	function bsf_delete_appointment( $appointment_id, $appointment_object = array() ) {

		if ( ! is_a( $appointment_object , 'BSF_Payment' ) ) {
			$appointment_object = new BSF_Appointment( $appointment_id ) ;
		}

		$appointment_object->delete() ;

		return true ;
	}

}

if ( ! function_exists( 'bsf_create_new_holiday' ) ) {

	function bsf_create_new_holiday( $data ) {

		$holiday    = new BSF_Holidays() ;
		$holiday_id = $holiday->create( $data ) ;

		return $holiday_id ;
	}

}

if ( ! function_exists( 'bsf_update_holiday' ) ) {

	function bsf_update_holiday( $holiday_id, $data ) {

		$holiday = new BSF_Holidays( $holiday_id ) ;
		$holiday->update( $data ) ;
	}

}

if ( ! function_exists( 'bsf_delete_holiday' ) ) {

	function bsf_delete_holiday( $holiday_id, $holiday_object = array() ) {

		if ( ! is_a( $holiday_object , 'BSF_Holidays' ) ) {
			$holiday_object = new BSF_Holidays( $holiday_id ) ;
		}

		$holiday_object->delete() ;

		return true ;
	}

}

if ( ! function_exists( 'bsf_create_new_staff_working_hours' ) ) {

	function bsf_create_new_staff_working_hours( $data ) {

		$staff_working_hours   = new BSF_Staff_Working_Hours() ;
		$staff_working_hour_id = $staff_working_hours->create( $data ) ;

		return $staff_working_hour_id ;
	}

}

if ( ! function_exists( 'bsf_update_staff_working_hours' ) ) {

	function bsf_update_staff_working_hours( $staff_working_hour_id, $data ) {

		$staff_working_hours = new BSF_Staff_Working_Hours( $staff_working_hour_id ) ;
		$staff_working_hours->update( $data ) ;
	}

}

if ( ! function_exists( 'bsf_delete_staff_working_hours' ) ) {

	function bsf_delete_staff_working_hours( $staff_working_hour_id, $staff_object = array() ) {

		if ( ! is_a( $staff_object , 'BSF_Staff_Working_Hours' ) ) {
			$staff_working_hours_object = new BSF_Staff_Working_Hours( $staff_working_hour_id ) ;
		}

		$staff_working_hours_object->delete() ;

		return true ;
	}

}

if ( ! function_exists( 'bsf_get_holiday_events' ) ) {

	function bsf_get_holiday_events( $staff_id = false ) {
		if ( ! $staff_id ) {
			$staff_id = 0 ;
		}

		$holidays_table = BSF_Tables_Instances::get_table_by_id( 'holidays' )->get_table_name() ;
		$query          = new BSF_Query( $holidays_table ) ;
		$event_ids      = $query->where( 'staff_id' , $staff_id )->fetchCol( '`t`.id' ) ;

		if ( ! bsf_check_is_array( $event_ids ) ) {
			return array() ;
		}

		$events = array() ;
		foreach ( $event_ids as $event_id ) {
			$holiday_object = new BSF_Holidays( $event_id ) ;

			if ( ! $holiday_object->exists() ) {
				continue ;
			}

			$events[ $event_id ] = array(
				'staff_id' => $holiday_object->get_staff_id() ,
				'm'        => $holiday_object->get_holiday_month() ,
				'd'        => $holiday_object->get_holiday_day() ,
				'y'        => $holiday_object->get_holiday_year( true )
					) ;
		}

		return $events ;
	}

}

if ( ! function_exists( 'bsf_get_default_working_hours' ) ) {

	function bsf_get_default_working_hours() {
		$start_of_week = ( int ) get_option( 'start_of_week' ) ;
		for ( $i = 0 ; $i < 7 ; $i ++ ) {
			$day_index   = ( $i + $start_of_week ) % 7 ;
			$day         = BSF_Date_Time::get_week_day_by_number( $day_index ) ;
			$option_name = 'bsf_working_hours_' . strtolower( $day ) ;

			$start_time = get_option( $option_name . '_start' , null ) ;
			$end_time   = get_option( $option_name . '_end' ) ;

			$working_hours[ $day_index ] = array( 'start' => ( $start_time == '' ) ? null : $start_time , 'end' => $end_time ) ;
		}

		return $working_hours ;
	}

}

if ( ! function_exists( 'bsf_get_default_holiday' ) ) {

	function bsf_get_default_holiday() {
		$holidays_table = BSF_Tables_Instances::get_table_by_id( 'holidays' )->get_table_name() ;
		$query          = new BSF_Query( $holidays_table ) ;
		$holiday        = $query->where( 'staff_id' , 0 )->fetchArray() ;

		if ( ! bsf_check_is_array( $holiday ) ) {
			return array() ;
		}

		return $holiday ;
	}

}

if ( ! function_exists( 'bsf_get_default_staff_id' ) ) {

	function bsf_get_default_staff_id() {
		$staff_table = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;
		$query       = new BSF_Query( $staff_table ) ;
		$_staff_id   = $query->orderBy( 'id' )->Limit( 1 )->fetchCol( 'id' ) ;

		if ( ! bsf_check_is_array( $_staff_id ) ) {
			return null ;
		}

		return current( $_staff_id ) ;
	}

}
