<?php

/*
 * Appointments
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Appointment' ) ) {

	/**
	 * BSF_Appointment Class.
	 */
	class BSF_Appointment extends BSF_Datastore {

		/**
		 * Table
		 */
		protected $table = 'bsf_appointments' ;

		/**
		 * Service ID
		 */
		protected $service_id ;

		/**
		 * Staff ID
		 */
		protected $staff_id ;

		/**
		 * Customer ID
		 */
		protected $customer_id ;

		/**
		 * Start date
		 */
		protected $start_date ;

		/**
		 * End date
		 */
		protected $end_date ;

		/**
		 * Price
		 */
		protected $price ;

		/**
		 * Status
		 */
		protected $status ;

		/**
		 * Created From
		 */
		protected $created_from ;

		/**
		 * date
		 */
		protected $date ;

		/**
		 * Services
		 */
		protected $services ;

		/**
		 * Staff
		 */
		protected $staff ;

		/**
		 * Customer
		 */
		protected $customer ;

		/**
		 * schema
		 */
		protected $schema = array(
			'service_id'   => '%d' ,
			'staff_id'     => '%d' ,
			'customer_id'  => '%d' ,
			'start_date'   => '%s' ,
			'end_date'     => '%s' ,
			'currency'     => '%s' ,
			'price'        => '%d' ,
			'status'       => '%s' ,
			'created_from' => '%s' ,
			'date'         => '%s' ,
				) ;

		/**
		 * Get Services
		 */
		public function get_services() {

			if ( $this->services ) {
				return $this->services ;
			}

			$this->services = new BSF_Services( $this->get_service_id() ) ;

			return $this->services ;
		}

		/**
		 * Get Customer
		 */
		public function get_customer() {

			if ( $this->customer ) {
				return $this->customer ;
			}

			$this->customer = new BSF_Customer( $this->get_customer_id() ) ;

			return $this->customer ;
		}

		/**
		 * Get Staff
		 */
		public function get_staff() {

			if ( $this->staff ) {
				return $this->staff ;
			}

			$this->staff = new BSF_Staff( $this->get_staff_id() ) ;

			return $this->staff ;
		}

		/**
		 * Get duration label
		 */
		public function get_duration_label() {
			$duration = strtotime( $this->get_end_date() ) - strtotime( $this->get_start_date() ) ;

			return BSF_Date_Time::seconds_to_string( $duration ) ;
		}

		/**
		 * Get duration label
		 */
		public function get_formatted_price() {
			return bsf_price( $this->get_price() , array( 'currency' => $this->get_currency() ) ) ;
		}

		/**
		 * Get date
		 */
		public function get_formatted_datetime( $format = 'date', $end_date = false ) {
			$date = ( $end_date ) ? $this->get_end_date() : $this->get_start_date() ;

			$date_object = BSF_Date_Time::get_date_time_object( $date ) ;

			switch ( $format ) {
				case 'date' ;
					$formatted = $date_object->format( 'Y-m-d' ) ;
					break ;
				case 'time' ;
					$formatted = $date_object->format( 'H:i:s' ) ;
					break ;
				default:
					$date      = $date_object->format( 'Y-m-d H:i:s' ) ;
					break ;
			}

			return $formatted ;
		}

		/**
		 * Set Service ID
		 */
		public function set_service_id( $value ) {

			return $this->service_id = $value ;
		}

		/**
		 * Set Staff ID
		 */
		public function set_staff_id( $value ) {

			return $this->staff_id = $value ;
		}

		/**
		 * Set Customer ID
		 */
		public function set_customer_id( $value ) {

			return $this->customer_id = $value ;
		}

		/**
		 * Set Start date
		 */
		public function set_start_date( $value ) {

			return $this->start_date = $value ;
		}

		/**
		 * Set end date
		 */
		public function set_end_date( $value ) {

			return $this->end_date = $value ;
		}

		/**
		 * Set Currency
		 */
		public function set_currency( $value ) {

			return $this->currency = $value ;
		}

		/**
		 * Set Price
		 */
		public function set_price( $value ) {

			return $this->price = $value ;
		}

		/**
		 * Set Status
		 */
		public function set_status( $value ) {

			return $this->status = $value ;
		}

		/**
		 * Set created from
		 */
		public function set_created_from( $value ) {

			return $this->created_from = $value ;
		}

		/**
		 * Set date
		 */
		public function set_date( $value ) {

			return $this->date = $value ;
		}

		/**
		 * Get Service ID
		 */
		public function get_service_id() {

			return $this->service_id ;
		}

		/**
		 * Get Staff ID
		 */
		public function get_staff_id() {

			return $this->staff_id ;
		}

		/**
		 * Get Customer ID
		 */
		public function get_customer_id() {

			return $this->customer_id ;
		}

		/**
		 * Get Start date
		 */
		public function get_start_date() {

			return $this->start_date ;
		}

		/**
		 * Get End Date
		 */
		public function get_end_date() {

			return $this->end_date ;
		}

		/**
		 * Get Currency
		 */
		public function get_currency() {

			return $this->currency ;
		}

		/**
		 * Get Price
		 */
		public function get_price() {

			return $this->price ;
		}

		/**
		 * Get Status
		 */
		public function get_status() {

			return $this->status ;
		}

		/**
		 * Get Created form
		 */
		public function get_created_from() {

			return $this->created_from ;
		}

		/**
		 * Get date
		 */
		public function get_date() {

			return $this->date ;
		}

	}

}
	
