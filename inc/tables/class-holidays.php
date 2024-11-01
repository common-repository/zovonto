<?php

/**
 * Holidays Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Holidays_Table' ) ) {

	/**
	 * Class BSF_Holidays_Table
	 */
	class BSF_Holidays_Table extends BSF_Abstract_Tables {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'holidays' ;
			$this->table = 'bsf_holidays' ;

			parent::__construct() ;
		}

		/*
		 * Get Query
		 */

		public function get_query() {

			return "CREATE TABLE IF NOT EXISTS {$this->get_table_name()} (
                `id`                 INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `staff_id`           INT(20) UNSIGNED DEFAULT NULL,
                `date`               DATE NOT NULL,
                `repeat`             TINYINT(1) NOT NULL DEFAULT 0
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci" ;
		}

	}

}
