<?php

/**
 * Payment Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'BSF_Payments_Tab' ) ) {
	return new BSF_Payments_Tab() ;
}

/**
 * BSF_Payments_Tab.
 */
class BSF_Payments_Tab extends BSF_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'payments' ;
		$this->code  = 'fa-credit-card-alt' ;
		$this->label = __( 'Payments' , 'zovonto' ) ;

		add_action( $this->plugin_slug . '_admin_field_output_payments' , array( $this , 'output_payments' ) ) ;
		parent::__construct() ;
	}

	/**
	 * Get settings array.
	 */
	public function get_settings( $current_section = '' ) {
		return array(
			array( 'type' => 'output_payments' )
				) ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the user Payment table
	 */
	public function output_payments() {

		global $current_section ;

		switch ( $current_section ) {
			case 'edit':
				$this->display_edit_page() ;
				break ;
			default:
				$this->display_table() ;
				break ;
		}
	}

	/**
	 * Output of display payment table
	 */
	public function display_table() {

		if ( ! class_exists( 'BSF_Payments_Post_Table' ) ) {
			require_once( BSF_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-bsf-payments-table.php' ) ;
		}

		echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
		echo '<h2 class="wp-heading-inline">' . __( 'Payments' , 'zovonto' ) . '</h2>' ;

		$post_table = new BSF_Payments_Post_Table() ;
		$post_table->prepare_items() ;

		if ( isset( $_REQUEST[ 's' ] ) && strlen( $_REQUEST[ 's' ] ) ) {
			/* translators: %s: search keywords */
			printf( ' <span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' , 'zovonto' ) . '</span>' , $_REQUEST[ 's' ] ) ;
		}

		$post_table->views() ;
		$post_table->search_box( __( 'Search Payments' , 'zovonto' ) , $this->plugin_slug . '_search' ) ;
		$post_table->display() ;
		echo '</div>' ;
	}

	/**
	 * Output the edit payment page
	 */
	public function display_edit_page() {
		if ( ! isset( $_GET[ 'id' ] ) ) {
			return ;
		}

		$payment_id     = absint( $_GET[ 'id' ] ) ;
		$payment_object = new BSF_Payment( $payment_id ) ;

		include_once( BSF_PLUGIN_PATH . '/inc/admin/menu/views/payments-edit.php' ) ;
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section ;

		if ( isset( $_POST[ 'edit_payament' ] ) && ! empty( $_POST[ 'edit_payament' ] ) ) {
			$this->update_payment() ;
		}
	}

	/*
	 * Create a new affiliates
	 */

	public function update_payment() {
		check_admin_referer( $this->plugin_slug . '_edit_payment' , '_' . $this->plugin_slug . '_nonce' ) ;

		try {
			$meta_data = bsf_sanitize_text_field( $_POST[ 'payment' ] ) ;

			if ( empty( $meta_data[ 'id' ] ) || $meta_data[ 'id' ] != absint( $_REQUEST[ 'id' ] ) ) {
				throw new Exception( __( 'Cannot modify Payment ID' , 'zovonto' ) ) ;
			}

			global $wpdb ;

			$payment_id = absint( $meta_data[ 'id' ] ) ;
			$status     = bsf_sanitize_text_field( $meta_data[ 'status' ] ) ;
			$payment    = new BSF_Payment( $payment_id ) ;

			$table_name = BSF_Tables_Instances::get_table_by_id( 'payments' )->get_table_name() ;

			$wpdb->update( $table_name , array( 'status' => $status ) , array( 'ID' => $payment_id ) ) ;

			do_action( 'bsf_payment_status_changed_to' , $status , $payment ) ;

			BSF_Settings::add_message( __( 'Payment has been updated successfully.' , 'zovonto' ) ) ;
		} catch ( Exception $ex ) {
			BSF_Settings::add_error( $ex->getMessage() ) ;
		}
	}

}

return new BSF_Payments_Tab() ;
