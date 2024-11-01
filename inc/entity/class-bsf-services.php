<?php

/*
 * Services
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Services' ) ) {

	/**
	 * BSF_Services Class.
	 */
	class BSF_Services extends BSF_Datastore {

		/**
		 * Table
		 */
		protected $table = 'bsf_services' ;

		/**
		 * Name
		 */
		protected $name ;

		/**
		 * Color
		 */
		protected $color ;

		/**
		 * Duration
		 */
		protected $duration ;

		/**
		 * Price
		 */
		protected $price ;

		/**
		 * Info
		 */
		protected $info ;

		/**
		 * Slot Duration
		 */
		protected $slot_duration ;

		/**
		 * Status
		 */
		protected $status ;

		/**
		 * Date
		 */
		protected $date ;

		/**
		 * Position
		 */
		protected $position ;

		/**
		 * schema
		 */
		protected $schema = array(
			'name'          => '%s' ,
			'color'         => '%s' ,
			'duration'      => '%s' ,
			'price'         => '%f' ,
			'info'          => '%s' ,
			'slot_duration' => '%s' ,
			'status'        => '%s' ,
			'date'          => '%s' ,
			'position'      => '%d' ,
				) ;

		/**
		 * Set Name
		 */
		public function set_name( $value ) {

			return $this->name = $value ;
		}

		/**
		 * Set color
		 */
		public function set_color( $value ) {

			return $this->color = $value ;
		}

		/**
		 * Set duration
		 */
		public function set_duration( $value ) {

			return $this->duration = $value ;
		}

		/**
		 * Set price
		 */
		public function set_price( $value ) {

			return $this->price = $value ;
		}

		/**
		 * Set info
		 */
		public function set_info( $value ) {

			return $this->info = $value ;
		}

		/**
		 * Set slot duration
		 */
		public function set_slot_duration( $value ) {

			return $this->slot_duration = $value ;
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
		 * Set Position
		 */
		public function set_position( $value ) {

			return $this->position = $value ;
		}

		/**
		 * Get Name
		 */
		public function get_name() {

			return $this->name ;
		}

		/**
		 * Get color
		 */
		public function get_color() {

			return $this->color ;
		}

		/**
		 * Get duration
		 */
		public function get_duration() {

			return $this->duration ;
		}

		/**
		 * Get price
		 */
		public function get_price() {

			return $this->price ;
		}

		/**
		 * Get info
		 */
		public function get_info() {

			return $this->info ;
		}

		/**
		 * Get slot duration
		 */
		public function get_slot_duration() {

			return $this->slot_duration ;
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

		/**
		 * Get Position
		 */
		public function get_position() {

			return $this->position ;
		}

	}

}
	
