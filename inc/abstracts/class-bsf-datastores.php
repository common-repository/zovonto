<?php

/*
 * Data Store
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Datastore' ) ) {

	/**
	 * BSF_Datastore Class.
	 */
	abstract class BSF_Datastore {

		/**
		 * ID
		 */
		protected $id = '' ;

		/**
		 * Table
		 */
		protected $table = '' ;

		/**
		 * Meta type
		 */
		protected $meta_type = '' ;

		/**
		 * Parent table
		 */
		protected $parent_table = '' ;

		/**
		 * data
		 */
		protected $data ;

		/**
		 * schema
		 */
		protected $schema = array() ;

		/**
		 * global database object
		 */
		protected static $database_object ;

		/**
		 * Class initialization.
		 */
		public function __construct( $_id = '', $populate = true ) {
			$this->id = $_id ;

			if ( self::$database_object === null ) {
				/** @var \wpdb $wpdb */
				global $wpdb ;

				self::$database_object = $wpdb ;
			}

			if ( $populate && $_id ) {
				$this->populate_data() ;
			}
		}

		/**
		 * Populate Data 
		 */
		protected function populate_data() {

			$this->load_data() ;
		}

		/**
		 * Exists
		 */
		public function exists() {

			return ! empty( $this->data ) ;
		}

		/**
		 * Table exists
		 */
		public function table_exists() {
			$table_name = $this->get_table_name() ;

			if ( self::$database_object->get_var( "SHOW TABLES LIKE '{$table_name}';" ) ) {
				return true ;
			}
			return false ;
		}

		/**
		 * Set Repeat
		 */
		public function set_id( $value ) {

			return $this->id = $value ;
		}

		/**
		 * Get ID 
		 */
		public function get_id() {

			return $this->id ;
		}

		/**
		 * Table name with wordpress prefix
		 */
		public function get_table_name() {

			return self::$database_object->prefix . $this->get_table() ;
		}

		/**
		 * Parent Table name with wordpress prefix
		 */
		public function get_parent_table_name() {

			return self::$database_object->prefix . $this->get_parent_table() ;
		}

		/**
		 * Table
		 */
		public function get_table() {

			return $this->table ;
		}

		/**
		 * May be get Parent Table
		 */
		public function get_parent_table() {

			return $this->parent_table ;
		}

		/**
		 * Meta type
		 */
		public function get_meta_type() {
			return $this->meta_type ;
		}

		/**
		 * Prepare data
		 */
		protected function load_data() {

			$query       = new BSF_Query( $this->get_table_name() ) ;
			$data_stores = $query->where( 'id' , $this->get_id() )->fetchArray() ;

			$this->data = reset( $data_stores ) ;

			if ( bsf_check_is_array( $this->data ) ) {
				foreach ( $this->data as $key => $value ) {

					if ( array_key_exists( $key , $this->schema ) ) {
						$this->$key = ( is_serialized( $value ) ) ? @unserialize( $value ) : $value ;
					}
				}
			}

			$this->load_extra_data() ;
		}

		/**
		 * Prepare extra data
		 */
		protected function load_extra_data() {
			
		}

		/**
		 * Format data
		 */
		public function format_data( $data ) {
			$formatted_data = array() ;
			$format         = array() ;
			$schema         = $this->schema ;

			foreach ( $data as $key => $value ) {
				if ( ! array_key_exists( $key , $schema ) ) {
					continue ;
				}

				$format[]               = $schema[ $key ] ;
				$formatted_data[ $key ] = $value ;
			}

			return array( $formatted_data , $format ) ;
		}

		/**
		 * Create a entity
		 */
		public function create( $data ) {

			list($values , $format) = $this->format_data( $data ) ;

			self::$database_object->insert( $this->get_table_name() , $values , $format ) ;

			return self::$database_object->insert_id ;
		}

		/**
		 * update entity
		 */
		public function update( $data, $where = false ) {
			if ( ! $this->get_id() && ! $this->get_meta_type() ) {
				return false ;
			}

			if ( ! $where ) {
				$where = array( 'id' => $this->get_id() ) ;
			}

			list($values , $format) = $this->format_data( $data ) ;

			self::$database_object->update( $this->get_table_name() , $values , $where , $format ) ;

			if ( $this->get_id() ) {
				$this->populate_data() ;
			}

			return $this->get_id() ;
		}

		/**
		 * delete entity
		 */
		public function delete( $where = false ) {
			if ( ! $where ) {
				$where = array( 'id' => $this->get_id() ) ;
			}

			self::$database_object->delete( $this->get_table_name() , $where ) ;
		}

	}

}
