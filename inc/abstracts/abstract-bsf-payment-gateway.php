<?php

/**
 * Abstract payment gateway
 *
 * Handles generic payment gateway functionality which is extended by payment gateways.
 */
defined( 'ABSPATH' ) || exit ;

if ( ! class_exists( 'BSF_Settings_Page' ) ) {
	include_once BSF_PLUGIN_PATH . '/inc/abstracts/class-bsf-settings-page.php' ;
}

/**
 * Payment Gateway.
 *
 * Extended by individual payment gateways to handle payments.
 */
abstract class BSF_Payment_Gateway extends BSF_Settings_Page {

	/**
	 * Whether the Gateway is enabled.
	 *
	 * @var string
	 */
	public $enabled = 'no' ;

	/**
	 * Gateway title.
	 *
	 * @var string
	 */
	public $title = '' ;

	/**
	 * Gateway description.
	 *
	 * @var string
	 */
	public $description = '' ;

	/**
	 * Icon for the gateway.
	 *
	 * @var string
	 */
	public $icon ;

	/**
	 * Supported features such as 'refunds'.
	 *
	 * @var array
	 */
	public $supports = array() ;

	/**
	 * Form option fields.
	 *
	 * @var array
	 */
	public $form_fields = array() ;

	/**
	 * URL to view a transaction.
	 *
	 * @var string
	 */
	public $transaction_url = '' ;

	/**
	 * Constructor for the extented gateway.
	 */
	public function __construct() {
		add_filter( $this->plugin_slug . '_get_settings_settings' , array( $this , 'render_form_fields' ) , 11 , 2 ) ;

		if ( $this->is_valid() ) {
			add_action( 'admin_enqueue_scripts' , array( $this , 'admin_scripts' ) ) ;
			add_action( 'wp_enqueue_scripts' , array( $this , 'frontend_scripts' ) ) ;
		}
	}

	/**
	 * Return the gateway ID
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id ;
	}

	/**
	 * Return the title for admin screens.
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->title ;
	}

	/**
	 * Return the description for admin screens.
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description ;
	}

	/**
	 * Init settings for gateways.
	 */
	public function init_settings() {
		$this->enabled = $this->get_option( 'enabled' , $this->enabled ) ;
	}

	/**
	 * Check whether the gateway is enabled
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return 'yes' === $this->get_option( 'enabled' , $this->enabled ) ;
	}

	/**
	 * Check whether the gateway is valid to use
	 *
	 * @return bool
	 */
	public function is_valid() {
		return true ;
	}

	/**
	 * Check whether the gateway is available for use by customers
	 *
	 * @return bool
	 */
	public function is_available() {
		return $this->is_valid() && $this->is_enabled() ;
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		
	}

	/**
	 * Admin Panel Options.
	 */
	public function admin_options() {
		
	}

	/**
	 * Load admin scripts.
	 */
	public function admin_scripts() {
		
	}

	/**
	 * Load frontend scripts.
	 */
	public function frontend_scripts() {
		
	}

	/**
	 * Render extended Gateway Settings Form Fields.
	 */
	public function render_form_fields( $settings, $current_section ) {
		if ( $this->id !== $current_section ) {
			return $settings ;
		}

		$this->admin_options() ;

		if ( ! $this->is_valid() ) {
			return $this->form_fields ;
		}

		$this->init_form_fields() ;

		$this->form_fields = array_merge( array(
			array(
				'type'  => 'title' ,
				'title' => $this->get_option( 'title' , $this->title ) ,
				'id'    => $this->get_option_key( 'payment_settings' ) ,
			) ) , $this->form_fields , array(
			array(
				'type' => 'sectionend' ,
				'id'   => $this->get_option_key( 'payment_settings' ) ,
			) ) ) ;

		return $this->form_fields ;
	}

	/**
	 * Get option from DB.
	 *
	 * @param  string $key Option key.
	 * @param  mixed  $default Value when empty.
	 * @return string The value specified for the option or a default value for the option.
	 */
	public function get_option( $key, $default = null ) {
		return get_option( $this->get_option_key( $key ) , $default ) ;
	}

	/**
	 * Get the return url.
	 *
	 * @param mixed $payment BSF_Payment payment
	 * @return string
	 */
	public function get_return_url( $payment, $endpoint = '' ) {
		$return_url = $payment->get_payment_received_url( $endpoint ) ;

		if ( is_ssl() ) {
			$return_url = str_replace( 'http:' , 'https:' , $return_url ) ;
		}

		return apply_filters( 'bsf_get_return_url' , $return_url , $payment ) ;
	}

	/**
	 * Get a link to the transaction on the 3rd party gateway size (if applicable).
	 *
	 * @param mixed $payment BSF_Payment payment
	 * @return string transaction URL, or empty string.
	 */
	public function get_transaction_url( $payment ) {
		$return_url     = '' ;
		$transaction_id = $payment->get_transaction_id() ;

		if ( ! empty( $this->transaction_url ) && ! empty( $transaction_id ) ) {
			$return_url = sprintf( $this->transaction_url , $transaction_id ) ;
		}

		return apply_filters( 'bsf_get_transaction_url' , $return_url , $payment , $this ) ;
	}

	/**
	 * Force https for urls.
	 *
	 * @param mixed $content
	 * @return string
	 */
	public function force_https_url( $content ) {
		if ( is_ssl() ) {
			if ( is_array( $content ) ) {
				$content = array_map( array( $this , 'force_https_url' ) , $content ) ;
			} else {
				$content = str_replace( 'http:' , 'https:' , $content ) ;
			}
		}
		return $content ;
	}

	/**
	 * Return the gateway's icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		$icon = $this->icon ? '<img src="' . $this->force_https_url( $this->icon ) . '" alt="' . esc_attr( $this->get_title() ) . '" />' : '' ;

		return apply_filters( 'bsf_payment_gateway_icon' , $icon , $this->id ) ;
	}

	/**
	 * Process the payment
	 *
	 * @param mixed $payment BSF_Payment payment
	 * @return array
	 */
	public function process_payment( $payment, $booking_data ) {
		return array() ;
	}

	/**
	 * Validate frontend fields.
	 *
	 * Validate payment fields on the frontend.
	 *
	 * @return bool
	 */
	public function validate_fields() {
		return true ;
	}

	/**
	 * If There are no payment fields show the description if set.
	 * Override this in your gateway if you have some.
	 */
	public function payment_fields() {
		if ( $description = $this->get_description() ) {
			echo wpautop( wptexturize( $description ) ) ;
		}
	}

	/**
	 * Check if a gateway supports a given feature.
	 */
	public function supports( $feature ) {
		return apply_filters( 'bsf_payment_gateway_supports' , in_array( $feature , $this->supports ) , $feature , $this ) ;
	}

}
