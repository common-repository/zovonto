<?php

/**
 * Abstract Notifications Class
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Notifications' ) ) {

	/**
	 * BSF_Notifications Class
	 */
	class BSF_Notifications {
		/*
		 * ID
		 */

		protected $id ;

		/*
		 * Title
		 */
		protected $title ;

		/*
		 * Message
		 */
		protected $message = '' ;

		/*
		 * Subject
		 */
		protected $subject = '' ;

		/*
		 * Placeholders
		 */
		protected $placeholders = array() ;

		/*
		 * Plugin slug
		 */
		protected $plugin_slug = 'bsf' ;

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->enabled       = $this->get_option( 'enabled' , 'no' ) ;
			$this->email_enabled = $this->get_option( 'email_enabled' , 'no' ) ;

			if ( empty( $this->placeholders ) ) {
				$this->placeholders = array(
					'{site_name}' => $this->get_blogname() ,
						) ;
			}
		}

		/*
		 * Get id
		 */

		public function get_id() {
			return $this->id ;
		}

		/*
		 * Get title
		 */

		public function get_title() {
			return $this->title ;
		}

		/*
		 * Get settings link
		 */

		public function settings_link() {
			return null ;
		}

		/*
		 * is enabled
		 */

		public function is_enabled() {

			return 'yes' === $this->enabled ;
		}

		/*
		 * is email enabled
		 */

		public function is_email_enabled() {

			return $this->is_enabled() && 'yes' === $this->email_enabled ;
		}

		/*
		 * Default Subject
		 */

		public function get_default_subject() {

			return $this->subject ;
		}

		/*
		 * Default Message
		 */

		public function get_default_message() {

			return $this->message ;
		}

		/**
		 * Get subject.
		 */
		public function get_subject() {

			return $this->format_string( $this->get_option( 'subject' , $this->get_default_subject() ) ) ;
		}

		/**
		 * Get Message.
		 */
		public function get_message() {
			$string = $this->format_string( $this->get_option( 'message' , $this->get_default_message() ) ) ;
			$string = wpautop( $string ) ;

			return $string ;
		}

		/**
		 * Get email headers.
		 */
		public function get_headers() {
			$header = 'Content-Type: ' . $this->get_content_type() . "\r\n" ;

			return $header ;
		}

		/**
		 * Get WordPress blog name.
		 */
		public function get_blogname() {
			return wp_specialchars_decode( get_option( 'blogname' ) , ENT_QUOTES ) ;
		}

		/**
		 * Get valid recipients.
		 */
		public function get_recipient() {
			$recipients = array_map( 'trim' , explode( ',' , $this->recipient ) ) ;
			$recipients = array_filter( $recipients , 'is_email' ) ;

			return implode( ', ' , $recipients ) ;
		}

		/**
		 * Format String
		 */
		public function format_string( $string ) {
			$find    = array_keys( $this->placeholders ) ;
			$replace = array_values( $this->placeholders ) ;

			$string = str_replace( $find , $replace , $string ) ;

			return $string ;
		}

		/**
		 * Send an email.
		 */
		public function send_email( $to, $subject, $message, $headers = false, $attachments = array() ) {
			if ( ! $headers ) {
				$headers = $this->get_headers() ;
			}

			add_filter( 'wp_mail_from' , array( $this , 'get_from_address' ) ) ;
			add_filter( 'wp_mail_from_name' , array( $this , 'get_from_name' ) ) ;
			add_filter( 'wp_mail_content_type' , array( $this , 'get_content_type' ) ) ;

			$return = wp_mail( $to , $subject , $message , $headers , $attachments ) ;

			remove_filter( 'wp_mail_from' , array( $this , 'get_from_address' ) ) ;
			remove_filter( 'wp_mail_from_name' , array( $this , 'get_from_name' ) ) ;
			remove_filter( 'wp_mail_content_type' , array( $this , 'get_content_type' ) ) ;

			return $return ;
		}

		/**
		 * Get the from name
		 */
		public function get_from_name() {
			$from_name = get_option( 'bsf_settings_email_from_name' ) ;

			return wp_specialchars_decode( esc_html( $from_name ) , ENT_QUOTES ) ;
		}

		/**
		 * Get the from address
		 */
		public function get_from_address() {
			$from_address = get_option( 'bsf_settings_email_from_address' ) ;

			return sanitize_email( $from_address ) ;
		}

		/*
		 * Get Attachments
		 */

		public function get_attachments() {
			array() ;
		}

		/*
		 * Get settings options array
		 */

		public function settings_options_array() {
			array() ;
		}

		/**
		 * Get content type.
		 */
		public function get_content_type() {

			return 'text/html' ;
		}

		/*
		 * Update Option
		 */

		public function update_option( $key, $value ) {
			$field_key = $this->get_field_key( $key ) ;

			return update_option( $field_key , $value ) ;
		}

		/*
		 * Prepare Options
		 */

		public function prepare_options() {
			$default_data = $this->data ;

			foreach ( $default_data as $key => $value ) {

				$this->$key = $this->get_option( $key , $value ) ;
			}
		}

		/*
		 * Get Option
		 */

		public function get_option( $key, $value = false ) {
			$field_key = $this->get_field_key( $key ) ;

			return get_option( $field_key , $value ) ;
		}

		/*
		 * Get field key
		 */

		public function get_field_key( $key ) {
			return sanitize_key( $this->plugin_slug . '_' . $this->id . '_' . $key ) ;
		}

		/*
		 * Extra Fields
		 */

		public function extra_fields() {
			
		}

	}

}
