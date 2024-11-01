<?php

/**
 * Staff Services Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Staff_Services_Table' ) ) {

	/**
	 * Class BSF_Staff_Services_Table
	 */
	class BSF_Staff_Services_Table extends BSF_Abstract_Tables {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'staff_services' ;
			$this->table = 'bsf_staff_services' ;

			parent::__construct() ;
		}

		/*
		 * Get Query
		 */

		public function get_query() {

			$staff_table   = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;
			$service_table = BSF_Tables_Instances::get_table_by_id( 'services' )->get_table_name() ;

			return "CREATE TABLE IF NOT EXISTS {$this->get_table_name()} (
                `id`                 INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `staff_id`           INT(20) UNSIGNED NOT NULL,
                `service_id`         INT(20) UNSIGNED NOT NULL,
                `price`              DOUBLE NOT NULL DEFAULT 0,
                UNIQUE KEY unique_ids (staff_id, service_id),
                CONSTRAINT
                    FOREIGN KEY (staff_id)
                    REFERENCES {$staff_table}(id)
                    ON DELETE CASCADE,
                CONSTRAINT
                    FOREIGN KEY (service_id)
                    REFERENCES {$service_table}(id)
                    ON DELETE CASCADE
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci" ;
		}

	}

}
