<?php

/**
 * Session
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Session' ) ) {

	/**
	 * BSF_Session Class.
	 */
	class BSF_Session {

		/**
		 *  Plugin Key
		 */
		public static $plugin_key = 'bsf_booking' ;

		/**
		 * Preference to safely stored the data in session
		 */
		public static function set( $key, $value ) {
			$_SESSION[ self::$plugin_key ][ $key ] = $value ;
		}

		/**
		 * Preference to retrieve stored the data from session
		 */
		public static function get( $key, $default = null ) {
			if ( self::has( $key ) ) {
				return $_SESSION[ self::$plugin_key ][ $key ] ;
			}

			return $default ;
		}

		/**
		 * Check if session key is valid
		 */
		public static function has() {

			return isset( $_SESSION[ self::$plugin_key ][ $key ] ) ;
		}

		/**
		 * Destroy the session
		 */
		public static function destroy( $key ) {

			return isset( $_SESSION[ self::$plugin_key ][ $key ] ) ;
		}

		/**
		 * Preference to safely stored the data in form session
		 */
		public static function set_form_data( $form_id, $value ) {

			return $_SESSION[ self::$plugin_key ][ 'forms' ][ $form_id ] = $value ;
		}

		/**
		 * Preference to retrieve stored the data from form session
		 */
		public static function get_form_data( $form_id, $default = array() ) {

			if ( self::has_form_data( $form_id ) ) {
				return $_SESSION[ self::$plugin_key ][ 'forms' ][ $form_id ] ;
			}

			return $default ;
		}

		/**
		 * Check if form session key is valid
		 */
		public static function has_form_data( $form_id ) {

			return isset( $_SESSION[ self::$plugin_key ][ 'forms' ][ $form_id ] ) ;
		}

		/**
		 * Destroy the session form data
		 */
		public static function destroy_form_data( $form_id ) {

			unset( $_SESSION[ self::$plugin_key ][ 'forms' ][ $form_id ] ) ;
		}

		/**
		 * Preference to retrieve stored the all form data from session
		 */
		public static function get_all_form_data( $default = array() ) {

			if ( isset( $_SESSION[ self::$plugin_key ][ 'forms' ] ) ) {
				return $_SESSION[ self::$plugin_key ][ 'forms' ] ;
			}

			return $default ;
		}

		/**
		 * Destroy the session all form data
		 */
		public static function destroy_all_form_data() {

			unset( $_SESSION[ self::$plugin_key ][ 'forms' ] ) ;
		}

	}

}
