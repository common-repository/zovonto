<?php

/*
 * Holidays
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Holidays' ) ) {

	/**
	 * BSF_Holidays Class.
	 */
	class BSF_Holidays extends BSF_Datastore {

		/**
		 * Table
		 */
		protected $table = 'bsf_holidays' ;

		/**
		 * Staff ID
		 */
		protected $staff_id ;

		/**
		 * Date
		 */
		protected $date ;

		/**
		 * Repeat
		 */
		protected $repeat ;

		/**
		 * schema
		 */
		protected $schema = array(
			'staff_id' => '%d' ,
			'date'     => '%s' ,
			'repeat'   => '%d'
				) ;

		/**
		 * Set Staff ID 
		 */
		public function set_staff_id( $value ) {

			return $this->staff_id = $value ;
		}

		/**
		 * Set Date
		 */
		public function set_date( $value ) {

			return $this->date = $value ;
		}

		/**
		 * Set Repeat
		 */
		public function set_repeat( $value ) {

			return $this->repeat = $value ;
		}

		/**
		 * Get Staff ID 
		 */
		public function get_staff_id() {

			return $this->staff_id ;
		}

		/**
		 * Get Date
		 */
		public function get_date() {

			return $this->date ;
		}

		/**
		 * Get Repeat
		 */
		public function get_repeat() {

			return $this->repeat ;
		}

		/**
		 * Get Holiday month
		 */
		public function get_holiday_month() {

			return ( int ) date( 'm' , strtotime( $this->get_date() ) ) ;
		}

		/**
		 * Get Holiday day
		 */
		public function get_holiday_day() {

			return ( int ) date( 'd' , strtotime( $this->get_date() ) ) ;
		}

		/**
		 * Get Holiday year
		 */
		public function get_holiday_year( $repeat = false ) {

			if ( $repeat && $this->get_repeat() ) {
				return '' ;
			}

			return ( int ) date( 'Y' , strtotime( $this->get_date() ) ) ;
		}

	}

}
