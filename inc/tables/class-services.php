<?php

/**
 * Services Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Services_Table' ) ) {

	/**
	 * Class BSF_Services_Table
	 */
	class BSF_Services_Table extends BSF_Abstract_Tables {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'services' ;
			$this->table = 'bsf_services' ;

			parent::__construct() ;
		}

		/*
		 * Get Query
		 */

		public function get_query() {

			return "CREATE TABLE IF NOT EXISTS {$this->get_table_name()} (
                `id`                 INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `name`               VARCHAR(255) DEFAULT '',
                `color`              VARCHAR(255) NOT NULL DEFAULT '#F55B11',
                `duration`           INT(20) NOT NULL DEFAULT 900,
                `price`              DOUBLE NOT NULL,
                `info`               TEXT NOT NULL DEFAULT '',
                `slot_duration`      VARCHAR(255) NOT NULL DEFAULT 'default',
                `date`               DATETIME NOT NULL,
                `status`             ENUM('public','private','archive') NOT NULL DEFAULT 'public',
                `position`           INT(20) NOT NULL DEFAULT 9999
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci" ;
		}

	}

}
