<?php

/**
 * Customers Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Customers_Table' ) ) {

	/**
	 * Class BSF_Customers_Table
	 */
	class BSF_Customers_Table extends BSF_Abstract_Tables {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'customers' ;
			$this->table = 'bsf_customers' ;

			parent::__construct() ;
		}

		/*
		 * Get Query
		 */

		public function get_query() {

			return "CREATE TABLE IF NOT EXISTS {$this->get_table_name()} (
                `id`                 INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `wp_user_id`         INT(20) UNSIGNED DEFAULT NULL,
                `first_name`         VARCHAR(60) NOT NULL DEFAULT '',
                `last_name`          VARCHAR(60) NOT NULL DEFAULT '',
                `email`              VARCHAR(100) NOT NULL DEFAULT '',
                `phone`              VARCHAR(30) NOT NULL DEFAULT '',
                `info`               LONGTEXT NOT NULL DEFAULT '',
                `date`               DATETIME NOT NULL
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci" ;
		}

	}

}
