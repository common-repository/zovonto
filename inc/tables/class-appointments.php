<?php

/**
 * Appointments Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Appointments_Table' ) ) {

	/**
	 * Class BSF_Appointments_Table
	 */
	class BSF_Appointments_Table extends BSF_Abstract_Tables {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'appointments' ;
			$this->table = 'bsf_appointments' ;

			parent::__construct() ;
		}

		/*
		 * Get Query
		 */

		public function get_query() {

			$staff_table    = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;
			$service_table  = BSF_Tables_Instances::get_table_by_id( 'services' )->get_table_name() ;
			$customer_table = BSF_Tables_Instances::get_table_by_id( 'customers' )->get_table_name() ;

			return "CREATE TABLE IF NOT EXISTS {$this->get_table_name()} (
                `id`                 INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `service_id`         INT(20) UNSIGNED DEFAULT NULL,
                `staff_id`           INT(20) UNSIGNED DEFAULT NULL,
                `customer_id`        INT(20) UNSIGNED DEFAULT NULL,
                `start_date`         DATETIME NOT NULL,
                `end_date`           DATETIME NOT NULL,
                `currency`           VARCHAR(10) NOT NULL DEFAULT 'USD',
                `price`              DOUBLE NOT NULL,
                `status`             VARCHAR(20) NOT NULL,
                `created_from`       ENUM('booking','google') NOT NULL DEFAULT 'booking',
                `date`               DATETIME NOT NULL
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci" ;
		}

	}

}
