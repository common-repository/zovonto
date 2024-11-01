<?php

/**
 * Staff Working Hours Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Staff_Working_Hours_Table' ) ) {

	/**
	 * Class BSF_Staff_Working_Hours_Table
	 */
	class BSF_Staff_Working_Hours_Table extends BSF_Abstract_Tables {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'staff_working_hours' ;
			$this->table = 'bsf_staff_working_hours' ;

			parent::__construct() ;
		}

		/*
		 * Get Query
		 */

		public function get_query() {

			$staff_table = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;

			return "CREATE TABLE IF NOT EXISTS {$this->get_table_name()} (
                `id`                 INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `staff_id`           INT(20) UNSIGNED NOT NULL,
                `day_index`          INT(20) UNSIGNED NOT NULL,
                `start_time`         TIME DEFAULT NULL,
                `end_time`           TIME DEFAULT NULL,
                UNIQUE KEY unique_ids (staff_id,day_index),
                CONSTRAINT
                    FOREIGN KEY (staff_id)
                    REFERENCES {$staff_table}(id)
                    ON DELETE CASCADE
            ) ENGINE = INNODB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci" ;
		}

	}

}
