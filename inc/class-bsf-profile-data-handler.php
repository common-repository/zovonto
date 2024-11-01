<?php

/**
 * Profile Data Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Profile_Data_Handler' ) ) {

	/**
	 * Class.
	 */
	class BSF_Profile_Data_Handler {

		/**
		 *  Class initialization.
		 */
		public static function init() {
			add_action( 'profile_update' , array( __CLASS__ , 'update_customer_details' ) , 10 , 2 ) ;
		}

		/*
		 * Update User details to customer
		 */

		public static function update_customer_details( $user_id, $old_user_data ) {

			$first_name = $old_user_data->first_name ? $old_user_data->first_name : '' ;
			$last_name  = $old_user_data->last_name ? $old_user_data->last_name : '' ;
			$user_email = $old_user_data->user_email ? $old_user_data->user_email : '' ;

			$args = array(
				'first_name' => $first_name ,
				'last_name'  => $last_name ,
				'email'      => $user_email
					) ;

			bsf_update_customer( $user_id , $args ) ;
		}

	}

	BSF_Profile_Data_Handler::init() ;
}
