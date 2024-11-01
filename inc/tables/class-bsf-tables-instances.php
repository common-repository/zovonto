<?php

/**
 * Tables Instances Class
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Tables_Instances' ) ) {

	/**
	 * Class BSF_Tables_Instances
	 */
	class BSF_Tables_Instances {
		/*
		 * Tables
		 */

		private static $tables = array() ;

		/*
		 * Get Tables
		 */

		public static function get_tables() {
			if ( ! self::$tables ) {
				self::load_tables() ;
			}

			return self::$tables ;
		}

		/*
		 * Load all Notifications
		 */

		public static function load_tables() {

			if ( ! class_exists( 'BSF_Abstract_Tables' ) ) {
				include BSF_PLUGIN_PATH . '/inc/abstracts/class-bsf-abstract-tables.php' ;
			}

			$default_table_classes = array(
				'holidays'            => 'BSF_Holidays_Table' ,
				'staff'               => 'BSF_Staff_Table' ,
				'services'            => 'BSF_Services_Table' ,
				'staff-services'      => 'BSF_Staff_Services_Table' ,
				'staff-working-hours' => 'BSF_Staff_Working_Hours_Table' ,
				'customers'           => 'BSF_Customers_Table' ,
				'appointments'        => 'BSF_Appointments_Table' ,
				'payments'            => 'BSF_Payments_Table' ,
					) ;

			foreach ( $default_table_classes as $file_name => $table_class ) {

				// include file
				include 'class-' . $file_name . '.php' ;

				//add table
				self::add_table( new $table_class() ) ;
			}
		}

		/**
		 * Add a Module
		 */
		public static function add_table( $table ) {

			self::$tables[ $table->get_id() ] = $table ;

			return new self() ;
		}

		/**
		 * Get table by id
		 */
		public static function get_table_by_id( $table_id ) {
			$tables = self::get_tables() ;

			return isset( $tables[ $table_id ] ) ? $tables[ $table_id ] : false ;
		}

		/*
		 * Create Tables
		 */

		public static function create_tables() {

			$tables = self::get_tables() ;

			foreach ( $tables as $table ) {

				if ( ! is_object( $table ) ) {
					continue ;
				}

				$table->create_table() ;
			}
		}

	}

}
	
