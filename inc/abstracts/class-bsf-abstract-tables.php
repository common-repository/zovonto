<?php

/*
 * Tables
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Abstract_Tables' ) ) {

	/**
	 * BSF_Abstract_Tables Class.
	 */
	abstract class BSF_Abstract_Tables {

		/**
		 * id
		 */
		protected $id = '' ;

		/**
		 * Table
		 */
		protected $table = '' ;

		/**
		 * Query
		 */
		protected $query = array() ;

		/**
		 * global database object
		 */
		protected static $database_object ;

		/**
		 * Class initialization.
		 */
		public function __construct() {

			if ( self::$database_object === null ) {
				/** @var \wpdb $wpdb */
				global $wpdb ;

				self::$database_object = $wpdb ;
			}
		}

		/**
		 * get ID
		 */
		public function get_id() {

			return $this->id ;
		}

		/**
		 * Table
		 */
		public function get_table() {

			return $this->table ;
		}

		/**
		 * Table name with wordpress prefix
		 */
		public function get_table_name() {

			return self::$database_object->prefix . $this->get_table() ;
		}

		/**
		 * Get Query
		 */
		public function get_query() {

			return '' ;
		}

		/**
		 * Create table
		 */
		public function create_table() {

			if ( ! $this->get_query() ) {
				return false ;
			}

			self::$database_object->query( $this->get_query() ) ;

			return true ;
		}

	}

}
