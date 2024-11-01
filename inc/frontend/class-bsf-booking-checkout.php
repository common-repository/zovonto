<?php

/**
 *  Booking Checkout
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'BSF_Booking_Checkout' ) ) {

	/**
	 * Class
	 */
	class BSF_Booking_Checkout {
		/*
		 * Booking Data
		 */

		protected $booking_data ;

		/**
		 * Class Initialization
		 */
		public function __construct( $form_id ) {
			$this->booking_data = new BSF_Booking_Data( $form_id ) ;
		}

		/**
		 * Process the checkout 
		 */
		public function process_checkout() {

			$this->create_customer() ;
			$this->create_appointment() ;
			$this->create_payment() ;

			$this->booking_data->save() ;

			return $this->process_payment() ;
		}

		/**
		 * Create the customer 
		 */
		protected function process_payment() {

			try {
				$payment = new BSF_Payment( $this->booking_data->get_payment_id() ) ;

				if ( is_numeric( $payment->get_price() ) && $payment->get_price() ) {
					$payment_method     = $payment->get_payment_method() ;
					$available_gateways = BSF()->payment_gateways()->get_available_payment_gateways() ;

					if ( ! isset( $available_gateways[ $payment_method ] ) ) {
						throw new Exception( __( 'Unable to make payment.' , 'zovonto' ) ) ;
					}

					// Process Payment.
					$result = $available_gateways[ $payment_method ]->process_payment( $payment , $this->booking_data ) ;

					// Redirect to success page.
					return $result ;
				} else {
					//Complete Payment
					return array(
						'result' => 'success' ,
							) ;
				}
			} catch ( Exception $e ) {
				if ( ! empty( $e ) ) {
					return array(
						'result'      => 'failure' ,
						'err_message' => $e->getMessage() ,
							) ;
				}
			}

			return array(
				'result' => 'failure' ,
					) ;
		}

		/**
		 * Create the customer 
		 */
		protected function create_customer() {
			$user_id = get_current_user_id() ;

			$customer_table = BSF_Tables_Instances::get_table_by_id( 'customers' )->get_table_name() ;
			$customer_query = new BSF_Query( $customer_table , 't' ) ;
			$customer       = $customer_query->select( '`t`.id' )->where( 'email' , $this->booking_data->get_email() )->fetchRow() ;

			if ( ! bsf_check_is_array( $customer ) ) {
				$customer_data = array(
					'wp_user_id' => $user_id ,
					'first_name' => $this->booking_data->get_first_name() ,
					'last_name'  => $this->booking_data->get_last_name() ,
					'email'      => $this->booking_data->get_email() ,
					'phone'      => $this->booking_data->get_phone() ,
					'info'       => $this->booking_data->get_info() ,
					'date'       => current_time( 'mysql' , true )
						) ;
				$customer_id   = bsf_create_new_customer( $customer_data ) ;
			} else {
				$customer_id = $customer[ 'id' ] ;
			}

			$this->booking_data->set_customer_id( $customer_id ) ;
		}

		/**
		 * Create the payment 
		 */
		protected function create_payment() {

			$payment_data = array(
				'service_id'     => $this->booking_data->get_service_id() ,
				'staff_id'       => $this->booking_data->get_staff_id() ,
				'customer_id'    => $this->booking_data->get_customer_id() ,
				'payment_method' => $this->booking_data->get_payment_method() ,
				'appointment_id' => $this->booking_data->get_appointment_id() ,
				'price'          => ( float ) $this->booking_data->get_total() ,
				'currency'       => get_bsf_currency() ,
				'status'         => 'pending' ,
				'date'           => current_time( 'mysql' , true )
					) ;

			$payment_id = bsf_create_new_payment( $payment_data ) ;

			$this->booking_data->set_payment_id( $payment_id ) ;

			do_action( 'bsf_after_payment_created' , $payment_id , $this->booking_data ) ;
		}

		/**
		 * Create the appointment 
		 */
		protected function create_appointment() {
			$services = new BSF_Services( $this->booking_data->get_service_id() ) ;
			$end_date = date( 'Y-m-d H:i:s' , strtotime( $this->booking_data->slot_info( 'datetime' ) ) + $this->booking_data->get_booking_duration() ) ;

			$appointment_data = array(
				'service_id'  => $this->booking_data->get_service_id() ,
				'staff_id'    => $this->booking_data->get_staff_id() ,
				'customer_id' => $this->booking_data->get_customer_id() ,
				'start_date'  => $this->booking_data->slot_info( 'datetime' ) ,
				'end_date'    => $end_date ,
				'currency'    => get_bsf_currency() ,
				'price'       => ( float ) $this->booking_data->get_total() ,
				'status'      => 'approved' ,
				'date'        => current_time( 'mysql' , true ) ,
					) ;

			$appointment_id = bsf_create_new_appointment( $appointment_data ) ;

			$this->booking_data->set_appointment_id( $appointment_id ) ;

			do_action( 'bsf_after_appointment_created' , $appointment_id , $this->booking_data ) ;
		}

	}

}
