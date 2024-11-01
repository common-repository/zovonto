<?php

/**
 * Services Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'BSF_Services_Tab' ) ) {
	return new BSF_Services_Tab() ;
}

/**
 * BSF_Services_Tab.
 */
class BSF_Services_Tab extends BSF_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'services' ;
		$this->code  = 'fa-wrench' ;
		$this->label = __( 'Services' , 'zovonto' ) ;

		add_action( $this->plugin_slug . '_admin_field_output_services' , array( $this , 'output_services' ) ) ;

		parent::__construct() ;
	}

	/**
	 * Get settings array.
	 */
	public function get_settings( $current_section = '' ) {

		return array(
			array( 'type' => 'output_services' )
				) ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the services
	 */
	public function output_services() {

		$service_table = BSF_Tables_Instances::get_table_by_id( 'services' )->get_table_name() ;
		$query         = new BSF_Query( $service_table ) ;
		$services      = $query->orderBy( 'position' )->Limit( 5 )->fetchArray() ;

		include_once BSF_PLUGIN_PATH . '/inc/admin/menu/views/services/services-edit.php' ;
	}

}

return new BSF_Services_Tab() ;
