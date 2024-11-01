<?php

/**
 * Admin Assets
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'BSF_Admin_Assets' ) ) {

	/**
	 * Class.
	 */
	class BSF_Admin_Assets {

		/**
		 * Class Initialization.
		 */
		public static function init() {

			add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) ) ;
			add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
		}

		/**
		 * Enqueue external css files
		 */
		public static function external_css_files() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

			wp_enqueue_style( 'font-awesome' , BSF_PLUGIN_URL . '/assets/css/font-awesome.min.css' , array() , BSF_VERSION ) ;
			wp_enqueue_style( 'bsf-admin' , BSF_PLUGIN_URL . '/assets/css/backend/admin.css' , array() , BSF_VERSION ) ;

			$newscreenids = get_current_screen() ;
			$screen_ids   = array(
				'toplevel_page_booking_system' ,
					) ;

			$screenid = str_replace( 'edit-' , '' , $newscreenids->id ) ;

			if ( ! in_array( $screenid , $screen_ids ) ) {
				return ;
			}

			wp_enqueue_style( 'jCal' , BSF_PLUGIN_URL . '/assets/css/backend/jCal.css' , array() , BSF_VERSION ) ;
			wp_enqueue_style( 'bsf-staff' , BSF_PLUGIN_URL . '/assets/css/backend/staff.css' , array() , BSF_VERSION ) ;
			wp_enqueue_style( 'bsf-services' , BSF_PLUGIN_URL . '/assets/css/backend/services.css' , array() , BSF_VERSION ) ;
			wp_enqueue_style( 'jquery-ui' , BSF_PLUGIN_URL . '/assets/css/jquery-ui' . $suffix . '.css' , array() , BSF_VERSION ) ;
			wp_enqueue_style( 'bsf-calendar' , BSF_PLUGIN_URL . '/assets/css/backend/calender.css' , array() , BSF_VERSION ) ;
			wp_enqueue_style( 'bsf-posttable' , BSF_PLUGIN_URL . '/assets/css/backend/post-table.css' , array() , BSF_VERSION ) ;
			wp_enqueue_style( 'bsf-notification' , BSF_PLUGIN_URL . '/assets/css/backend/notification.css' , array() , BSF_VERSION ) ;
			wp_enqueue_style( 'bsf-admin-calendar' , BSF_PLUGIN_URL . '/assets/css/backend/admin-bookings-calendar.css' , array() , BSF_VERSION ) ;
		}

		/**
		 * Enqueue external js files
		 */
		public static function external_js_files() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

			wp_enqueue_script( 'bsf-admin' , BSF_PLUGIN_URL . '/assets/js/admin/admin.js' , array( 'jquery' ) , BSF_VERSION ) ;
			wp_localize_script(
					'bsf-admin' , 'bsf_admin_params' , array(
				'button_title' => __( 'Shortcodes' , 'zovonto' ) ,
				'button_image' => BSF_PLUGIN_URL
					)
			) ;

			$newscreenids = get_current_screen() ;
			$screen_ids   = array(
				'toplevel_page_booking_system' ,
					) ;

			$screenid = str_replace( 'edit-' , '' , $newscreenids->id ) ;

			$enqueue_array = array(
				'bsf-admin'   => array(
					'callable' => array( 'BSF_Admin_Assets' , 'admin' ) ,
					'restrict' => in_array( $screenid , $screen_ids ) ,
				) ,
				'bsf-select2' => array(
					'callable' => array( 'BSF_Admin_Assets' , 'select2' ) ,
					'restrict' => in_array( $screenid , $screen_ids ) ,
				) ,
					) ;

			$enqueue_array = apply_filters( 'bsf_admin_assets_array' , $enqueue_array ) ;
			if ( ! bsf_check_is_array( $enqueue_array ) ) {
				return ;
			}

			foreach ( $enqueue_array as $key => $enqueue ) {
				if ( ! bsf_check_is_array( $enqueue ) ) {
					continue ;
				}

				if ( $enqueue[ 'restrict' ] ) {
					call_user_func_array( $enqueue[ 'callable' ] , array( $suffix ) ) ;
				}
			}
		}

		/**
		 * Enqueue Admin end required JS files
		 */
		public static function admin( $suffix ) {
			global $wp_locale ;
			//media
			wp_enqueue_media() ;
			//thickbox
			add_thickbox() ;

			wp_register_script( 'blockUI' , BSF_PLUGIN_URL . '/assets/js/blockUI/jquery.blockUI.js' , array( 'jquery' ) , '2.70.0' ) ;
			wp_enqueue_script( 'bsf-settings' , BSF_PLUGIN_URL . '/assets/js/admin/settings.js' , array( 'jquery' , 'blockUI' , 'jquery-ui-datepicker' , 'iris' ) , BSF_VERSION ) ;
			wp_enqueue_script( 'bsf-staff' , BSF_PLUGIN_URL . '/assets/js/admin/staff.js' , array( 'jquery' , 'blockUI' , 'jquery-ui-sortable' ) , BSF_VERSION ) ;
			wp_enqueue_script( 'bsf-services' , BSF_PLUGIN_URL . '/assets/js/admin/services.js' , array( 'jquery' , 'blockUI' , 'jquery-ui-sortable' ) , BSF_VERSION ) ;
			wp_enqueue_script( 'bsf-calendar' , BSF_PLUGIN_URL . '/assets/js/admin/calendar.js' , array( 'jquery' , 'blockUI' ) , BSF_VERSION ) ;

			wp_localize_script(
					'bsf-settings' , 'bsf_settings_params' , array(
				'months'             => array_values( $wp_locale->month ) ,
				'days'               => array_values( $wp_locale->weekday_abbrev ) ,
				'start_of_week'      => ( int ) get_option( 'start_of_week' ) ,
				'holiday_nonce'      => wp_create_nonce( 'bsf-holiday-nonce' ) ,
				'staff_nonce'        => wp_create_nonce( 'bsf-staff-nonce' ) ,
				'search_nonce'       => wp_create_nonce( 'bsf-search-nonce' ) ,
				'notification_nonce' => wp_create_nonce( 'bsf-notification-nonce' ) ,
				'save_label'         => __( 'Save' , 'zovonto' ) ,
				'holiday_label'      => __( 'Holiday' , 'zovonto' ) ,
				'repeat_label'       => __( 'Repeat every year' , 'zovonto' ) ,
					)
			) ;

			wp_localize_script(
					'bsf-staff' , 'bsf_staff_params' , array(
				'staff_nonce'           => wp_create_nonce( 'bsf-staff-nonce' ) ,
				'image_placeholder_url' => BSF_PLUGIN_URL . '/assets/images/staff-placeholder.png' ,
				'staff_delete_msg'      => __( 'Are you sure you want to delete this Staff?' , 'zovonto' ) ,
					)
			) ;

			wp_localize_script(
					'bsf-services' , 'bsf_services_params' , array(
				'services_nonce'               => wp_create_nonce( 'bsf-services-nonce' ) ,
				'services_working_hours_nonce' => wp_create_nonce( 'bsf-service-working-hours-nonce' ) ,
				'image_placeholder_url'        => BSF_PLUGIN_URL . '/assets/images/service-placeholder.png' ,
				'services_delete_msg'          => __( 'Service(s) deleted successfully' , 'zovonto' ) ,
				'services_delete_alert_msg'    => __( 'Are you sure you want to proceed to delete the Service(s)?' , 'zovonto' ) ,
				'service_saved_msg'            => __( 'Settings saved successfully' , 'zovonto' ) ,
					)
			) ;

			wp_localize_script(
					'bsf-calendar' , 'bsf_calendar_params' , array(
				'calendar_nonce' => wp_create_nonce( 'bsf-calendar-nonce' ) ,
					)
			) ;

			wp_enqueue_script( 'jCal' , BSF_PLUGIN_URL . '/assets/js/admin/jCal.js' , array( 'jquery' ) , BSF_VERSION ) ;
		}

		/**
		 * Enqueue select2 scripts and css
		 */
		public static function select2( $suffix ) {
			wp_enqueue_style( 'select2' , BSF_PLUGIN_URL . '/assets/css/select2/select2' . $suffix . '.css' , array() , '4.0.5' ) ;

			wp_register_script( 'select2' , BSF_PLUGIN_URL . '/assets/js/select2/select2' . $suffix . '.js' , array( 'jquery' ) , '4.0.5' ) ;
			wp_enqueue_script( 'bsf-enhanced' , BSF_PLUGIN_URL . '/assets/js/bsf-enhanced.js' , array( 'jquery' , 'select2' ) , BSF_VERSION ) ;
			wp_localize_script(
					'bsf-enhanced' , 'bsf_enhanced_select_params' , array(
				'ajax_url'     => admin_url( 'admin-ajax.php' ) ,
				'search_nonce' => wp_create_nonce( 'bsf-search-nonce' ) ,
					)
			) ;
		}

	}

	BSF_Admin_Assets::init() ;
}
