<?php

/*
 * Menu Management
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Menu_Management' ) ) {

	include_once('class-bsf-settings.php') ;

	/**
	 * BSF_Menu_Management Class.
	 */
	class BSF_Menu_Management {

		/**
		 * Affiliates slug
		 */
		private static $menu_slug = 'booking_system' ;

		/**
		 * Plugin slug.
		 */
		protected static $plugin_slug = 'bsf' ;

		/**
		 * Class initialization.
		 */
		public static function init() {
			add_action( 'admin_menu' , array( __CLASS__ , 'add_menu_page' ) ) ;
		}

		/**
		 * Add menu pages
		 */
		public static function add_menu_page() {

			$dash_icon_url = BSF_PLUGIN_URL . '/assets/images/dash-icon.png' ;
			$settings_page = add_menu_page( __( 'Zovonto' , 'zovonto' ) , __( 'Zovonto' , 'zovonto' ) , 'manage_options' , self::$menu_slug , array( __CLASS__ , 'settings_page' ) , $dash_icon_url ) ;

			add_action( 'load-' . $settings_page , array( __CLASS__ , 'settings_page_init' ) ) ;
		}

		/**
		 * Settings page init
		 */
		public static function settings_page_init() {
			global $current_tab , $current_section , $current_sub_section ;

			// Include settings pages.
			$settings = BSF_Settings::get_settings_pages() ;

			$tabs = bsf_get_allowed_setting_tabs() ;

			// Get current tab/section.
			$current_tab = ( empty( $_GET[ 'tab' ] ) || ! array_key_exists( $_GET[ 'tab' ] , $tabs ) ) ? key( $tabs ) : sanitize_title( wp_unslash( $_GET[ 'tab' ] ) ) ;

			$section = isset( $settings[ $current_tab ] ) ? $settings[ $current_tab ]->get_sections() : array() ;

			$current_section     = empty( $_REQUEST[ 'section' ] ) ? key( $section ) : sanitize_title( wp_unslash( $_REQUEST[ 'section' ] ) ) ;
			$current_sub_section = empty( $_REQUEST[ 'subsection' ] ) ? '' : sanitize_title( wp_unslash( $_REQUEST[ 'subsection' ] ) ) ;

			do_action( sanitize_key( self::$plugin_slug . '_settings_save_' . $current_tab ) , $current_section ) ;
			do_action( sanitize_key( self::$plugin_slug . '_settings_reset_' . $current_tab ) , $current_section ) ;
		}

		/**
		 * Settings page output
		 */
		public static function settings_page() {
			BSF_Settings::output() ;
		}

	}

	BSF_Menu_Management::init() ;
}
