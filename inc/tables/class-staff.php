<?php

/**
 * Staff Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Staff_Table' ) ) {

	/**
	 * Class BSF_Staff_Table
	 */
	class BSF_Staff_Table extends BSF_Abstract_Tables {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'staff' ;
			$this->table = 'bsf_staff' ;

			parent::__construct() ;
		}

		/*
		 * Get Query
		 */

		public function get_query() {

			return "CREATE TABLE IF NOT EXISTS {$this->get_table_name()} (
                `id`                 INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `wp_user_id`         BIGINT(20) UNSIGNED DEFAULT NULL,
                `attachment_id`      BIGINT(20) UNSIGNED DEFAULT NULL,
                `name`               VARCHAR(100) DEFAULT '',
                `email`              VARCHAR(100) DEFAULT NULL,
                `phone`              VARCHAR(50) DEFAULT NULL,
                `date`               DATETIME NOT NULL,
                `info`               TEXT NOT NULL,
                `position`           INT(20) NOT NULL DEFAULT 9999,
                `status`             ENUM('public','private','archive') NOT NULL DEFAULT 'public'
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci" ;
		}

	}

}
