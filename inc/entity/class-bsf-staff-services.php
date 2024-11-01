<?php

/*
 * Staff Services
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Staff_Services' ) ) {

	/**
	 * BSF_Staff_Services Class.
	 */
	class BSF_Staff_Services extends BSF_Datastore {

		/**
		 * Table
		 */
		protected $table = 'bsf_staff_services' ;

		/**
		 * Staff ID
		 */
		protected $staff_id ;

		/**
		 * Service ID
		 */
		protected $service_id ;

		/**
		 * Price
		 */
		protected $price ;

		/**
		 * schema
		 */
		protected $schema = array(
			'staff_id'   => '%d' ,
			'service_id' => '%d' ,
			'price'      => '%f' ,
				) ;

		/**
		 * Set Staff ID
		 */
		public function set_staff_id( $value ) {

			return $this->staff_id = $value ;
		}

		/**
		 * Set Service ID
		 */
		public function set_service_id( $value ) {

			return $this->service_id = $value ;
		}

		/**
		 * Set price
		 */
		public function set_price( $value ) {

			return $this->price = $value ;
		}

		/**
		 * Get Staff ID
		 */
		public function get_staff_id() {

			return $this->staff_id ;
		}

		/**
		 * Get Service ID
		 */
		public function get_service_id() {

			return $this->service_id ;
		}

		/**
		 * Get price
		 */
		public function get_price() {

			return $this->price ;
		}

	}

}
	
