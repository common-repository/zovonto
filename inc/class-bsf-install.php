<?php

/**
 * Initialize the Plugin.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Install' ) ) {

	/**
	 * Class.
	 */
	class BSF_Install {
		/*
		 * Plugin Slug
		 */

		protected static $plugin_slug = 'bsf' ;

		/**
		 *  Class initialization.
		 */
		public static function init() {
			add_action( 'init', array( __CLASS__, 'init_plugin' ) ) ;
			add_filter( 'plugin_action_links_' . BSF_PLUGIN_SLUG, array( __CLASS__, 'settings_link' ) ) ;
		}

		/**
		 * Initialize the plugin
		 */
		public static function init_plugin() {

			//session start
			if ( session_status() == PHP_SESSION_NONE ) {
				session_start() ;
			}

			self::check_version() ;
		}

		/**
		 * Check current version of the plugin is updated when activating plugin, if not run updater.
		 */
		public static function check_version() {
			if ( version_compare( get_option( 'bsf_version' ), BSF_VERSION, '>=' ) ) {
				return ;
			}

			self::install() ;
		}

		/**
		 * Install Booking System
		 */
		public static function install() {

			BSF_Tables_Instances::create_tables() ; //create tables

			self::set_default_values() ; // default values
			self::update_version() ;
		}

		/**
		 * Update BSF version to current.
		 */
		private static function update_version() {
			update_option( 'bsf_version', BSF_VERSION ) ;
		}

		/**
		 *  Settings link. 
		 */
		public static function settings_link( $links ) {
			$setting_page_link = '<a href="admin.php?page=booking_system">' . __( 'Settings', 'zovonto' ) . '</a>' ;

			array_unshift( $links, $setting_page_link ) ;

			return $links ;
		}

		/**
		 *  Set settings default values  
		 */
		public static function set_default_values() {
			if ( ! class_exists( 'BSF_Settings' ) ) {
				include_once(BSF_PLUGIN_PATH . '/inc/admin/menu/class-bsf-settings.php') ;
			}

			//default for settings
			$settings = BSF_Settings::get_settings_pages() ;

			foreach ( $settings as $setting ) {
				$sections = $setting->get_sections() ;
				if ( ! bsf_check_is_array( $sections ) ) {
					continue ;
				}

				foreach ( $sections as $section_key => $section ) {
					if ( $section_key == 'booking_form' ) {
						$booking_form_steps = bsf_get_booking_form_steps() ;
						foreach ( $booking_form_steps as $booking_form_step => $booking_form ) {
							$settings_array = $setting->get_settings( $booking_form_step ) ;
							foreach ( $settings_array as $value ) {
								if ( isset( $value[ 'default' ] ) && isset( $value[ 'id' ] ) ) {
									if ( get_option( $value[ 'id' ] ) === false ) {
										add_option( $value[ 'id' ], $value[ 'default' ] ) ;
									}
								}
							}
						}
					} else {
						$settings_array = $setting->get_settings( $section_key ) ;
						foreach ( $settings_array as $value ) {
							if ( isset( $value[ 'default' ] ) && isset( $value[ 'id' ] ) ) {
								if ( get_option( $value[ 'id' ] ) === false ) {
									add_option( $value[ 'id' ], $value[ 'default' ] ) ;
								}
							}
						}
					}
				}
			}

			//default for notification
			$notifications = BSF_Notification_Instances::get_notifications() ;

			foreach ( $notifications as $object ) {
				$settings = $object->settings_options_array() ;

				if ( ! bsf_check_is_array( $settings ) ) {
					continue ;
				}

				foreach ( $settings as $setting ) {
					if ( isset( $setting[ 'default' ] ) && isset( $setting[ 'id' ] ) ) {
						if ( get_option( $setting[ 'id' ] ) === false ) {
							add_option( $setting[ 'id' ], $setting[ 'default' ] ) ;
						}
					}
				}
			}

			// default for working hours.
			for ( $i = 0 ; $i < 7 ; $i ++ ) {
				$day         = BSF_Date_Time::get_week_day_by_number( $i ) ;
				$option_name = 'bsf_working_hours_' . strtolower( $day ) ;

				if ( get_option( $option_name . '_start' ) === false ) {
					update_option( $option_name . '_start', '09:00' ) ;
				}

				if ( get_option( $option_name . '_end' ) === false ) {
					update_option( $option_name . '_end', '18:00' ) ;
				}
			}
		}

	}

	BSF_Install::init() ;
}
