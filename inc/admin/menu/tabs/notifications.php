<?php

/**
 * Notifications Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'BSF_Notifications_Tab' ) ) {
	return new BSF_Notifications_Tab() ;
}

/**
 * BSF_Notifications_Tab.
 */
class BSF_Notifications_Tab extends BSF_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'notifications' ;
		$this->code  = 'fa-bell' ;
		$this->label = __( 'Notifications' , 'zovonto' ) ;

		add_action( $this->plugin_slug . '_admin_field_output_notifications' , array( $this , 'output_notifications' ) ) ;

		parent::__construct() ;
	}

	/**
	 * Get settings array.
	 */
	public function get_settings( $current_section = '' ) {

		return array(
			array( 'type' => 'output_notifications' )
				) ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		global $current_section ;

		if ( $current_section ) {
			BSF_Settings::output_buttons() ;
		}
	}

	/**
	 * Output the notifications
	 */
	public function output_notifications() {
		global $current_section ;

		if ( $current_section ) {
			$notification_object = BSF_Notification_Instances::get_notification_by_id( $current_section ) ;
			if ( is_object( $notification_object ) ) {
				BSF_Settings::output_fields( $notification_object->settings_options_array() ) ;
			}
		} else {
			include_once( BSF_PLUGIN_PATH . '/inc/notifications/views/layout.php' ) ;
		}
	}

	/**
	 * Output the notifications
	 */
	public function save() {
		global $current_section ;

		if ( empty( $_POST[ 'save' ] ) ) {
			return ;
		}

		if ( ! $current_section ) {
			return ;
		}

		$notification_object = BSF_Notification_Instances::get_notification_by_id( $current_section ) ;
		if ( is_object( $notification_object ) ) {
			BSF_Settings::save_fields( $notification_object->settings_options_array() ) ;
		}

		BSF_Settings::add_message( __( 'Your settings have been saved.' , 'zovonto' ) ) ;
	}

	/**
	 * Reset settings.
	 */
	public function reset() {
		global $current_section ;

		if ( empty( $_POST[ 'reset' ] ) ) {
			return ;
		}

		if ( ! $current_section ) {
			return ;
		}

		$notification_object = BSF_Notification_Instances::get_notification_by_id( $current_section ) ;
		if ( is_object( $notification_object ) ) {
			BSF_Settings::reset_fields( $notification_object->settings_options_array() ) ;
		}

		BSF_Settings::add_message( __( 'Your settings have been reset.' , 'zovonto' ) ) ;
	}

}

return new BSF_Notifications_Tab() ;
