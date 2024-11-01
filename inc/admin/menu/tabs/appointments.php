<?php

/**
 * Appointments Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'BSF_Appointments_Tab' ) ) {
	return new BSF_Appointments_Tab() ;
}

/**
 * BSF_Appointments_Tab.
 */
class BSF_Appointments_Tab extends BSF_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'appointments' ;
		$this->code  = 'fa-calendar-check-o' ;
		$this->label = __( 'Appointments' , 'zovonto' ) ;

		add_action( $this->plugin_slug . '_admin_field_output_appointments' , array( $this , 'output_appointments' ) ) ;

		parent::__construct() ;
	}

	/**
	 * Get settings array.
	 */
	public function get_settings( $current_section = '' ) {

		return array(
			array( 'type' => 'output_appointments' )
				) ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the Customer Appointments
	 */
	public function output_appointments() {
		if ( ! class_exists( 'BSF_Appointments_Post_Table' ) ) {
			require_once( BSF_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-bsf-appointments-table.php' ) ;
		}

		$post_table = new BSF_Appointments_Post_Table() ;
		$post_table->prepare_items() ;

		$new_section_url = add_query_arg( array( 'page' => 'booking_system' , 'tab' => 'appointments' , 'section' => 'new' ) , BSF_ADMIN_URL ) ;
		echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
		echo '<h2 class="wp-heading-inline">' . __( 'Appointments' , 'zovonto' ) . '</h2>' ;

		if ( isset( $_REQUEST[ 's' ] ) && strlen( $_REQUEST[ 's' ] ) ) {
			/* translators: %s: search keywords */
			printf( ' <span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' , 'zovonto' ) . '</span>' , $_REQUEST[ 's' ] ) ;
		}

		$post_table->views() ;
		$post_table->search_box( __( 'Search Appointments' , 'zovonto' ) , $this->plugin_slug . '_search' ) ;
		$post_table->display() ;
		echo '</div>' ;
	}

}

return new BSF_Appointments_Tab() ;
