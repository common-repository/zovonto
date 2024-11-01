<?php

/*
 * Staff Working Hours
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Staff_Working_Hours' ) ) {

	/**
	 * BSF_Staff_Working_Hours Class.
	 */
	class BSF_Staff_Working_Hours extends BSF_Datastore {

		/**
		 * Table
		 */
		protected $table = 'bsf_staff_working_hours' ;

		/**
		 * Staff ID
		 */
		protected $staff_id ;

		/**
		 * Day Index
		 */
		protected $day_index ;

		/**
		 * Start Time
		 */
		protected $start_time ;

		/**
		 * End Time
		 */
		protected $end_time ;

		/**
		 * schema
		 */
		protected $schema = array(
			'staff_id'   => '%d' ,
			'day_index'  => '%d' ,
			'start_time' => '%s' ,
			'end_time'   => '%s'
				) ;

		/**
		 * Set Staff ID
		 */
		public function set_staff_id( $value ) {

			return $this->staff_id = $value ;
		}

		/**
		 * Set day index
		 */
		public function set_day_index( $value ) {

			return $this->day_index = $value ;
		}

		/**
		 * Set start time
		 */
		public function set_start_time( $value ) {

			return $this->start_time = $value ;
		}

		/**
		 * Set end time
		 */
		public function set_end_time( $value ) {

			return $this->end_time = $value ;
		}

		/**
		 * Get Staff ID
		 */
		public function get_staff_id() {

			return $this->staff_id ;
		}

		/**
		 * Get day index
		 */
		public function get_day_index() {

			return $this->day_index ;
		}

		/**
		 * Get start time
		 */
		public function get_start_time() {

			return $this->start_time ;
		}

		/**
		 * Get end time
		 */
		public function get_end_time() {

			return $this->end_time ;
		}

	}

}
	
