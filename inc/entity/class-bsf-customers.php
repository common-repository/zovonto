<?php

/*
 * Customer
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Customer' ) ) {

	/**
	 * BSF_Customer Class.
	 */
	class BSF_Customer extends BSF_Datastore {

		/**
		 * Table
		 */
		protected $table = 'bsf_customers' ;

		/**
		 * Wordpress User ID
		 */
		protected $wp_user_id ;

		/**
		 * First name
		 */
		protected $first_name ;

		/**
		 * Last name
		 */
		protected $last_name ;

		/**
		 * Email
		 */
		protected $email ;

		/**
		 * Phone
		 */
		protected $phone ;

		/**
		 * Info
		 */
		protected $info ;

		/**
		 * Date
		 */
		protected $date ;

		/**
		 * Appointment count
		 */
		protected $appointment_count ;

		/**
		 * Appointment Last date 
		 */
		protected $appointment_lastdate ;

		/**
		 * schema
		 */
		protected $schema = array(
			'wp_user_id' => '%d' ,
			'first_name' => '%s' ,
			'last_name'  => '%s' ,
			'email'      => '%s' ,
			'phone'      => '%s' ,
			'info'       => '%s' ,
			'date'       => '%s' ,
				) ;

		/**
		 * Get Full name
		 */
		public function get_full_name( $sep = ' ' ) {

			return $this->get_first_name() . $sep . $this->get_last_name() ;
		}

		/**
		 * Get Appointment count
		 */
		public function get_appointment_count() {

			if ( $this->appointment_count ) {
				return $this->appointment_count ;
			}

			$appointment_table = BSF_Tables_Instances::get_table_by_id( 'appointments' )->get_table_name() ;
			$appointment_query = new BSF_Query( $appointment_table ) ;

			$this->appointment_count = $appointment_query->where( 'customer_id' , $this->get_id() )->count() ;

			return $this->appointment_count ;
		}

		/**
		 * Get Appointment last date
		 */
		public function get_appointment_lastdate() {

			if ( $this->appointment_lastdate ) {
				return $this->appointment_lastdate ;
			}

			$appointment_table = BSF_Tables_Instances::get_table_by_id( 'appointments' )->get_table_name() ;
			$appointment_query = new BSF_Query( $appointment_table , 't' ) ;
			$appointment       = $appointment_query->select( '`t`.start_date as date' )
							->where( 'customer_id' , $this->get_id() )
							->order( 'DESC' )->fetchRow() ;

			$this->appointment_lastdate = ( bsf_check_is_array( $appointment ) ) ? $appointment[ 'date' ] : '' ;

			return $this->appointment_lastdate ;
		}

		/**
		 * Set Word Press User ID
		 */
		public function set_wp_user_id( $value ) {

			return $this->wp_user_id = $value ;
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
		 * Set date
		 */
		public function set_date( $value ) {

			return $this->date = $value ;
		}

		/**
		 * Get Wordpress User ID
		 */
		public function get_wp_user_id() {

			return $this->wp_user_id ;
		}

		/**
		 * Get First name
		 */
		public function get_first_name() {

			return $this->first_name ;
		}

		/**
		 * Get Last name
		 */
		public function get_last_name() {

			return $this->last_name ;
		}

		/**
		 * Get Email
		 */
		public function get_email() {

			return $this->email ;
		}

		/**
		 * Get Phone
		 */
		public function get_phone() {

			return $this->phone ;
		}

		/**
		 * Get info
		 */
		public function get_info() {

			return $this->info ;
		}

		/**
		 * Get date
		 */
		public function get_date() {

			return $this->date ;
		}

	}

}
	
