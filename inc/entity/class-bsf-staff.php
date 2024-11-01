<?php

/*
 * Staff
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Staff' ) ) {

	/**
	 * BSF_Staff Class.
	 */
	class BSF_Staff extends BSF_Datastore {

		/**
		 * Table
		 */
		protected $table = 'bsf_staff' ;

		/**
		 * Name
		 */
		protected $name ;

		/**
		 * Email
		 */
		protected $email ;

		/**
		 * WordPress User ID
		 */
		protected $wp_user_id ;

		/**
		 * Attachment ID
		 */
		protected $attachment_id ;

		/**
		 * Phone
		 */
		protected $phone ;

		/**
		 * Info
		 */
		protected $info ;

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
		 * Services
		 */
		protected $services = array() ;

		/**
		 * Working Hours
		 */
		protected $working_hours = array() ;

		/**
		 * schema
		 */
		protected $schema = array(
			'name'          => '%s' ,
			'email'         => '%s' ,
			'wp_user_id'    => '%d' ,
			'attachment_id' => '%d' ,
			'phone'         => '%s' ,
			'info'          => '%s' ,
			'status'        => '%s' ,
			'date'          => '%s' ,
			'position'      => '%d' ,
				) ;

		/**
		 * Get image url
		 */
		public function get_image_url() {
			$image_url = wp_get_attachment_image_src( $this->get_attachment_id() ) ;

			return ( $image_url ) ? $image_url[ 0 ] : BSF_PLUGIN_URL . '/assets/images/staff-placeholder.png' ;
		}

		/**
		 * Get Services
		 */
		public function get_services() {

			if ( ! empty( $this->services ) ) {
				return $this->services ;
			}

			$staff_service_table = BSF_Tables_Instances::get_table_by_id( 'staff_services' )->get_table_name() ;
			$query               = new BSF_Query( $staff_service_table ) ;
			$staff_services      = $query->fetchArray() ;

			if ( ! bsf_check_is_array( $staff_services ) ) {
				return $this->services ;
			}

			$services_data = array() ;
			foreach ( $staff_services as $service ) {
				$services_data[ $service[ 'service_id' ] ] = new BSF_Staff_Services( $service[ 'id' ] ) ;
			}

			$this->services = $services_data ;

			return $services_data ;
		}

		/**
		 * Get Working Hours
		 */
		public function get_working_hours() {

			if ( ! empty( $this->working_hours ) ) {
				return $this->working_hours ;
			}

			$staff_working_hours = BSF_Tables_Instances::get_table_by_id( 'staff_working_hours' )->get_table_name() ;
			$query               = new BSF_Query( $staff_working_hours ) ;
			$working_hours       = $query->where( 'staff_id' , $this->get_id() )->fetchArray() ;

			if ( ! bsf_check_is_array( $working_hours ) ) {
				return $this->working_hours ;
			}

			$working_hours_data = array() ;
			foreach ( $working_hours as $working_hour ) {
				$working_hours_data[ $working_hour[ 'day_index' ] ] = new BSF_Staff_Working_Hours( $working_hour[ 'id' ] ) ;
			}

			$this->working_hours = $working_hours_data ;

			return $working_hours_data ;
		}

		/**
		 * Set Name
		 */
		public function set_name( $value ) {

			return $this->name = $value ;
		}

		/**
		 * Set Email
		 */
		public function set_email( $value ) {

			return $this->email = $value ;
		}

		/**
		 * Set Word Press User ID
		 */
		public function set_wp_user_id( $value ) {

			return $this->wp_user_id = $value ;
		}

		/**
		 * Set Attachment ID
		 */
		public function set_attachment_id( $value ) {

			return $this->attachment_id = $value ;
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
		 * Get Email
		 */
		public function get_email() {

			return $this->email ;
		}

		/**
		 * Get Wordpress User ID
		 */
		public function get_wp_user_id() {

			return $this->wp_user_id ;
		}

		/**
		 * Get Attachment ID
		 */
		public function get_attachment_id() {

			return $this->attachment_id ;
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
	
