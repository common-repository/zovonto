<?php

/**
 * Staff Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'BSF_Staff_Tab' ) ) {
	return new BSF_Staff_Tab() ;
}

/**
 * BSF_Staff_Tab.
 */
class BSF_Staff_Tab extends BSF_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'staff' ;
		$this->code  = 'fa-user' ;
		$this->label = __( 'Staff' , 'zovonto' ) ;

		add_action( $this->plugin_slug . '_admin_field_output_staff' , array( $this , 'output_staff' ) ) ;

		parent::__construct() ;
	}

	/**
	 * Get settings array.
	 */
	public function get_settings( $current_section = '' ) {
		return array(
			array( 'type' => 'output_staff' )
				) ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the staff
	 */
	public function output_staff() {
		global $bsf_staff_id ;

		$bsf_staff_id = bsf_get_default_staff_id() ;

		include_once BSF_PLUGIN_PATH . '/inc/admin/menu/views/staff/staff-edit.php' ;
	}

	/**
	 * Save settings.
	 */
	public function save() {

		if ( isset( $_POST[ 'edit_staff' ] ) && ! empty( $_POST[ 'edit_staff' ] ) ) {
			$this->update_staff() ;
		}
	}

	/*
	 * update staff
	 */

	public function update_staff() {
		check_admin_referer( $this->plugin_slug . '_edit_staff' , '_' . $this->plugin_slug . '_nonce' ) ;

		try {
			global $bsf_staff_id ;

			$data = bsf_sanitize_text_field( $_POST[ 'staff' ] ) ;

			if ( isset( $data[ 'email' ] ) && ! filter_var( $data[ 'email' ] , FILTER_VALIDATE_EMAIL ) ) {
				throw new Exception( __( 'Please enter valid a Email' , 'zovonto' ) ) ;
			}

			$bsf_staff_id = absint( $data[ 'id' ] ) ;

			$data[ 'name' ]  = bsf_sanitize_text_field( $data[ 'name' ] ) ;
			$data[ 'email' ] = bsf_sanitize_text_field( $data[ 'email' ] ) ;
			$data[ 'phone' ] = bsf_sanitize_text_field( $data[ 'phone' ] ) ;
			$data[ 'info' ]  = bsf_sanitize_text_area( $data[ 'info' ] ) ;

			bsf_update_staff( $bsf_staff_id , $data ) ;

			do_action( 'bsf_after_staff_updated' , $bsf_staff_id , $data ) ;

			unset( $_POST[ 'staff' ] ) ;

			BSF_Settings::add_message( __( 'Staff has been updated successfully.' , 'zovonto' ) ) ;
		} catch ( Exception $ex ) {
			BSF_Settings::add_error( $ex->getMessage() ) ;
		}
	}

}

return new BSF_Staff_Tab() ;
