<?php

/*
 * User Booking Data
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Booking_Data' ) ) {

	/**
	 * BSF_Booking_Data Class.
	 */
	class BSF_Booking_Data {

		/**
		 * Form ID
		 */
		protected $form_id ;

		/**
		 * Service ID
		 */
		protected $service_id ;

		/**
		 * Staff ID
		 */
		protected $staff_id ;

		/**
		 * Appointment ID
		 */
		protected $appointment_id ;

		/**
		 * Customer ID
		 */
		protected $customer_id ;

		/**
		 * Payment ID
		 */
		protected $payment_id ;

		/**
		 * Slots
		 */
		protected $slots = array() ;

		/**
		 * From date
		 */
		protected $from_date ;

		/**
		 * Week days
		 */
		protected $week_days = array() ;

		/**
		 * From Time
		 */
		protected $from_time ;

		/**
		 * To time
		 */
		protected $to_time ;

		/**
		 * First Name
		 */
		protected $first_name ;

		/**
		 * Last Name
		 */
		protected $last_name ;

		/**
		 * Email
		 */
		protected $email ;

		/**
		 * Phone Number
		 */
		protected $phone ;

		/**
		 * Payment Method
		 */
		protected $payment_method ;

		/**
		 * Price
		 */
		protected $price ;

		/**
		 * Total
		 */
		protected $total ;

		/**
		 * Info
		 */
		protected $info ;

		/**
		 * Properties
		 */
		protected $properties = array(
			'service_id' ,
			'staff_id' ,
			'from_date' ,
			'from_time' ,
			'to_time' ,
			'total' ,
			'price' ,
			'week_days' ,
			'first_name' ,
			'last_name' ,
			'email' ,
			'phone' ,
			'appointment_id' ,
			'customer_id' ,
			'payment_id' ,
			'payment_method' ,
			'info' ,
			'slots' ,
				) ;

		/**
		 * Class initialization
		 */
		public function __construct( $form_id, $populate = true ) {

			$this->form_id = $form_id ;

			if ( $populate ) {
				$this->populate_data() ;
			}
		}

		/**
		 * Populate data
		 */
		public function populate_data() {

			$this->prepare_user_details() ;

			$data = BSF_Session::get_form_data( $this->form_id ) ;

			foreach ( $data as $key => $value ) {
				if ( ! in_array( $key , $this->properties ) ) {
					continue ;
				}

				$this->{$key} = $value ;
			}

			$this->prepare_data() ;
		}

		/**
		 * Prepare data
		 */
		public function prepare_data() {
			$staff_services_table = BSF_Tables_Instances::get_table_by_id( 'staff_services' )->get_table_name() ;
			$staff_services_query = new BSF_Query( $staff_services_table , 't' ) ;
			$data                 = $staff_services_query->select( '`t`.price' )
							->where( 'service_id' , $this->get_service_id() )
							->where( 'staff_id' , $this->get_staff_id() )->fetchRow() ;

			$this->set_price( isset( $data[ 'price' ] ) ? $data[ 'price' ] : 0  ) ;
		}

		/**
		 * Prepare user details
		 */
		public function prepare_user_details() {
			$current_user = wp_get_current_user() ;

			if ( is_object( $current_user ) && ! empty( $current_user->ID ) ) {
				$customer_table = BSF_Tables_Instances::get_table_by_id( 'customers' )->get_table_name() ;
				$customer_query = new BSF_Query( $customer_table ) ;
				$customer       = $customer_query->where( 'wp_user_id' , $current_user->ID )->fetchRow() ;

				if ( bsf_check_is_array( $customer ) ) {
					$this->set_customer_id( $customer[ 'id' ] ) ;
					$this->set_first_name( $customer[ 'last_name' ] ) ;
					$this->set_last_name( $customer[ 'first_name' ] ) ;
					$this->set_email( $customer[ 'email' ] ) ;
					$this->set_phone( $customer[ 'phone' ] ) ;
				} else {

					$this->set_first_name( $current_user->user_firstname ) ;
					$this->set_last_name( $current_user->user_lastname ) ;
					$this->set_email( $current_user->user_email ) ;
				}
			}
		}

		/**
		 * Set all data
		 */
		public function save() {
			$form_data  = array() ;
			$properties = $this->properties ;

			foreach ( $properties as $key ) {
				$form_data[ $key ] = $this->$key ;
			}

			BSF_Session::set_form_data( $this->form_id , $form_data ) ;
		}

		/**
		 * Set all data
		 */
		public function set_data( $data ) {

			$form_data = BSF_Session::get_form_data( $this->form_id ) ;

			foreach ( $data as $key => $value ) {
				if ( ! in_array( $key , $this->properties ) ) {
					continue ;
				}

				$form_data[ $key ] = $value ;
			}

			BSF_Session::set_form_data( $this->form_id , $form_data ) ;

			$this->populate_data() ;
		}

		/**
		 * Get all data
		 */
		public function get_data() {
			$form_data = array() ;
			$data      = BSF_Session::get_form_data( $this->form_id ) ;

			foreach ( $data as $key => $value ) {
				if ( ! in_array( $key , $this->properties ) ) {
					continue ;
				}

				$form_data[ $key ] = $value ;
			}

			$form_data[ 'slot_date' ]      = $this->slot_info( 'date' ) ;
			$form_data[ 'slot_time' ]      = $this->slot_info( 'time' ) ;
			$form_data[ 'slot_date_time' ] = $this->slot_info( 'datetime' ) ;
			$form_data[ 'price' ]          = $this->get_total() ;

			return $form_data ;
		}

		/**
		 * Get Slots info
		 */
		public function slot_info( $type, $wp_zone = false ) {
			$slots = $this->get_slots() ;
			if ( ! bsf_check_is_array( $slots ) ) {
				return false ;
			}

			$date = BSF_Date_Time::get_date_time_object( $slots[ 2 ] , $wp_zone ) ;

			switch ( $type ) {
				case 'service':
					return $slots[ 0 ] ;
					break ;
				case 'staff':
					return $slots[ 1 ] ;
					break ;
				case 'date':
					return $date->format( 'Y-m-d' ) ;
					break ;
				case 'time':
					return $date->format( 'H:i:s' ) ;
					break ;
				case 'datetime':
					return $date->format( 'Y-m-d H:i:s' ) ;
					break ;
				case 'qty' ;
					return $slots[ 3 ] ;
					break ;
				default:
					return false ;
					break ;
			}
		}

		/**
		 * Get Service
		 */
		public function get_service() {

			return new BSF_Services( $this->get_service_id() ) ;
		}

		/**
		 * Get Staff
		 */
		public function get_staff() {

			return new BSF_Staff( $this->get_staff_id() ) ;
		}

		/**
		 * Get Customer
		 */
		public function get_customer() {

			return new BSF_Customer( $this->get_customer_id() ) ;
		}

		/**
		 * Get Booking duration
		 */
		public function get_booking_duration() {

			return $this->get_service()->get_duration() ;
		}

		/*         * ************************************************************************
		 *                      UserData Getters & Setters                        *
		 * *********************************************************************** */

		/**
		 * Set service id
		 */
		public function set_service_id( $value ) {

			return $this->service_id = $value ;
		}

		/**
		 * Set appointment id
		 */
		public function set_appointment_id( $value ) {

			return $this->appointment_id = $value ;
		}

		/**
		 * Set Payment Method
		 */
		public function set_payment_method( $value ) {

			return $this->payment_method = $value ;
		}

		/**
		 * Set payment id
		 */
		public function set_payment_id( $value ) {

			return $this->payment_id = $value ;
		}

		/**
		 * Set Price
		 */
		public function set_price( $value ) {

			return $this->price = $value ;
		}

		/**
		 * Set Total
		 */
		public function set_total( $value ) {

			return $this->total = $value ;
		}

		/**
		 * Set customer id
		 */
		public function set_customer_id( $value ) {

			return $this->customer_id = $value ;
		}

		/**
		 * Set slots
		 */
		public function set_slots( $value ) {

			return $this->slots = $value ;
		}

		/**
		 * Set staff id
		 */
		public function set_staff_id( $value ) {

			return $this->staff_id = $value ;
		}

		/**
		 * Set from date
		 */
		public function set_from_date( $value ) {

			return $this->from_date = $value ;
		}

		/**
		 * Set from time
		 */
		public function set_from_time( $value ) {

			return $this->from_time = $value ;
		}

		/**
		 * Set to time
		 */
		public function set_to_time( $value ) {

			return $this->to_time = $value ;
		}

		/**
		 * Set week days
		 */
		public function set_week_days( $value ) {

			return $this->week_days = $value ;
		}

		/**
		 * Set first name
		 */
		public function set_first_name( $value ) {

			return $this->first_name = $value ;
		}

		/**
		 * Set last name
		 */
		public function set_last_name( $value ) {

			return $this->last_name = $value ;
		}

		/**
		 * Set Email
		 */
		public function set_email( $value ) {

			return $this->email = $value ;
		}

		/**
		 * Set Phone
		 */
		public function set_phone( $value ) {

			return $this->phone = $value ;
		}

		/**
		 * Set info
		 */
		public function set_info( $value ) {

			return $this->info = $value ;
		}

		/**
		 * Get service id
		 */
		public function get_service_id() {

			return $this->service_id ;
		}

		/**
		 * Get appointment id
		 */
		public function get_appointment_id() {

			return $this->appointment_id ;
		}

		/**
		 * Get customer id
		 */
		public function get_customer_id() {

			return $this->customer_id ;
		}

		/**
		 * Get Price
		 */
		public function get_price() {

			return $this->price ;
		}

		/**
		 * Get Total
		 */
		public function get_total() {
			return $this->get_price() ;
		}

		/**
		 * Get payment id
		 */
		public function get_payment_id() {

			return $this->payment_id ;
		}

		/**
		 * Get Slots
		 */
		public function get_slots() {

			return $this->slots ;
		}

		/**
		 * Get staff id
		 */
		public function get_staff_id() {

			return $this->staff_id ;
		}

		/**
		 * Get form date
		 */
		public function get_from_date() {

			return $this->from_date ;
		}

		/**
		 * Get from time
		 */
		public function get_from_time() {

			return $this->from_time ;
		}

		/**
		 * Get to time
		 */
		public function get_to_time() {

			return $this->to_time ;
		}

		/**
		 * Get Week days
		 */
		public function get_week_days() {

			return $this->week_days ;
		}

		/**
		 * Get first name
		 */
		public function get_first_name() {

			return $this->first_name ;
		}

		/**
		 * Get last name
		 */
		public function get_last_name() {

			return $this->last_name ;
		}

		/**
		 * Get email
		 */
		public function get_email() {

			return $this->email ;
		}

		/**
		 * Get phone
		 */
		public function get_phone() {

			return $this->phone ;
		}

		/**
		 * Get Payment Method
		 */
		public function get_payment_method() {

			return $this->payment_method ;
		}

		/**
		 * Get info
		 */
		public function get_info() {

			return $this->info ;
		}

	}

}
