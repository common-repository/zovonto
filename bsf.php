<?php

/*
 * Plugin Name: Zovonto Bookings and Appointments
 * Description: Zovonto is a free Appointment Booking Plugin which allows you to run an appointment booking system in your WordPress site.
 * Version: 1.6
 * Author: Flintop
 * Author URI: https://flintop.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: zovonto
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
/* Include once will help to avoid fatal error by load the files when you call init hook */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ;

// Include main class file
if ( ! class_exists( 'Zovonto' ) ) {
	include_once('inc/class-zovonto.php') ;
}

if ( ! function_exists( 'bsf_is_plugin_active' ) ) {

	/**
	 * Is plugin active?
	 * 
	 * @since 1.6
	 * @return bool
	 */
	function bsf_is_plugin_active() {
		if ( bsf_is_valid_wordpress_version() ) {
			return true ;
		}

		add_action( 'admin_notices', 'bsf_display_warning_message' ) ;

		return false ;
	}

}

if ( ! function_exists( 'bsf_is_valid_wordpress_version' ) ) {

	/**
	 * Is valid WordPress version?
	 * 
	 * @since 1.6
	 * @return bool
	 */
	function bsf_is_valid_wordpress_version() {
		if ( version_compare( get_bloginfo( 'version' ), Zovonto::$wp_minimum_version, '<' ) ) {
			return false ;
		}

		return true ;
	}

}

if ( ! function_exists( 'bsf_display_warning_message' ) ) {

	/**
	 * Display the warning message.
	 * 
	 * @since 1.6
	 */
	function bsf_display_warning_message() {
		if ( bsf_is_valid_wordpress_version() ) {
			return ;
		}

		echo '<div class="error">' ;
		echo '<p>' . wp_kses_post( sprintf( 'This version of Zovonto Bookings and Appointments requires WordPress %1s or newer.', Zovonto::$wp_minimum_version ) ) . '</p>' ;
		echo '</div>' ;
	}

}

// Return if the plugin is not active.
if ( ! bsf_is_plugin_active() ) {
	return ;
}

//Define constant
if ( ! defined( 'BSF_PLUGIN_FILE' ) ) {
	define( 'BSF_PLUGIN_FILE', __FILE__ ) ;
}

//return zovonto class object
if ( ! function_exists( 'BSF' ) ) {

	function BSF() {
		return Zovonto::instance() ;
	}

}

//Initialize the plugin. 
BSF() ;
