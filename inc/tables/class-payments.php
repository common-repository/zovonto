<?php

/**
 * Payments Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Payments_Table' ) ) {

	/**
	 * Class BSF_Payments_Table
	 */
	class BSF_Payments_Table extends BSF_Abstract_Tables {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'payments' ;
			$this->table = 'bsf_payments' ;

			parent::__construct() ;
		}

		/*
		 * Get Query
		 */

		public function get_query() {

			return "CREATE TABLE IF NOT EXISTS {$this->get_table_name()} (
                `id`                 INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `service_id`         INT(20) UNSIGNED DEFAULT NULL,
                `staff_id`           INT(20) UNSIGNED DEFAULT NULL,
                `customer_id`        INT(20) UNSIGNED DEFAULT NULL,
                `appointment_id`     INT(20) UNSIGNED DEFAULT NULL,
                `payment_method`     VARCHAR(50) NOT NULL DEFAULT '',
                `currency`           VARCHAR(10) NOT NULL DEFAULT 'USD',
                `price`              DOUBLE NOT NULL,
                `status`             VARCHAR(20) NOT NULL,
                `date`               DATETIME NOT NULL
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci" ;
		}

	}

}
