<?php

/*
 * Payments
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Payment' ) ) {

	/**
	 * BSF_Payment Class.
	 */
	class BSF_Payment extends BSF_Datastore {

		/**
		 * Table
		 */
		protected $table = 'bsf_payments' ;

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
		 * Appointment ID
		 */
		protected $appointment_id ;

		/**
		 * Payment Method
		 */
		protected $payment_method ;

		/**
		 * Price
		 */
		protected $price ;

		/**
		 * Currency
		 */
		protected $currency ;

		/**
		 * Status
		 */
		protected $status ;

		/**
		 * Date
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
		 * Appointment
		 */
		protected $appointment ;

		/**
		 * schema
		 */
		protected $schema = array(
			'staff_id'       => '%d' ,
			'service_id'     => '%d' ,
			'customer_id'    => '%d' ,
			'appointment_id' => '%d' ,
			'payment_method' => '%s' ,
			'price'          => '%d' ,
			'currency'       => '%s' ,
			'status'         => '%s' ,
			'date'           => '%s' ,
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
		 * Get Appointment
		 */
		public function get_appointment() {

			if ( $this->appointment ) {
				return $this->appointment ;
			}

			$this->appointment = new BSF_Appointment( $this->get_appointment_id() ) ;

			return $this->appointment ;
		}

		/**
		 * Generates a URL for the successful payment.
		 */
		public function get_payment_received_url( $endpoint = '' ) {
			$endpoint_url = home_url() ;

			if ( $endpoint && filter_var( $endpoint , FILTER_VALIDATE_URL ) ) {
				$endpoint_url = $endpoint ;
			}

			if ( false === strpos( $endpoint_url , '?' ) ) {
				$endpoint_url = trailingslashit( $endpoint_url ) ;
			}

			if ( is_ssl() ) {
				$endpoint_url = str_replace( 'http:' , 'https:' , $endpoint_url ) ;
			}

			return apply_filters( 'bsf_get_payment_received_url' , add_query_arg( array(
				'payment_id' => $this->id ,
				'_wpnonce'   => wp_create_nonce( 'bsf-payment-received' ) ,
							) , $endpoint_url ) , $this ) ;
		}

		/**
		 * Set Customer ID
		 */
		public function set_customer_id( $value ) {

			return $this->customer_id = $value ;
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
		 * Set Appointment ID
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
		 * Set Attachment ID
		 */
		public function set_attachment_id( $value ) {

			return $this->attachment_id = $value ;
		}

		/**
		 * Set Price
		 */
		public function set_price( $value ) {

			return $this->price = $value ;
		}

		/**
		 * Set Currency
		 */
		public function set_currency( $value ) {

			return $this->currency = $value ;
		}

		/**
		 * Set Status
		 */
		public function set_status( $value ) {

			return $this->status = $value ;
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
		 * Get Appointment ID
		 */
		public function get_appointment_id() {

			return $this->appointment_id ;
		}

		/**
		 * Get Payment Method
		 */
		public function get_payment_method() {

			return $this->payment_method ;
		}

		/**
		 * Get price
		 */
		public function get_price() {

			return $this->price ;
		}

		/**
		 * Get Currency
		 */
		public function get_currency() {

			return $this->currency ;
		}

		/**
		 * Get Status
		 */
		public function get_status() {

			return $this->status ;
		}

		/**
		 * Get date
		 */
		public function get_date() {

			return $this->date ;
		}

	}

}
	
