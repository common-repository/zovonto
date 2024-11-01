<?php

defined( 'ABSPATH' ) || exit ;

/**
 * Payment gateways class.
 */
class BSF_Payment_Gateways {

	/**
	 * Payment gateway classes.
	 *
	 * @var array
	 */
	public $payment_gateways = array() ;

	/**
	 * The single instance of the class.
	 */
	protected static $_instance = null ;

	/**
	 * Main BSF_Payment_Gateways Instance.
	 * Ensures only one instance of BSF_Payment_Gateways is loaded or can be loaded.
	 *
	 * @return BSF_Payment_Gateways Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self() ;
		}
		return self::$_instance ;
	}

	/**
	 * Initialize payment gateways.
	 */
	public function __construct() {
		add_action( 'bsf_load_payment_gateways' , array( $this , 'load_default_payment_gateways' ) , 0 ) ;

		$this->init() ;
	}

	/**
	 * Load gateways.
	 */
	public function init() {
		do_action( 'bsf_load_payment_gateways' ) ;

		$loaded_gateways = apply_filters( 'bsf_loaded_payment_gateways' , array(
			'BSF_Offline_Gateway' ,
				) ) ;

		if ( empty( $loaded_gateways ) ) {
			return ;
		}

		foreach ( $loaded_gateways as $order => $gateway ) {
			if ( is_string( $gateway ) ) {
				if ( class_exists( $gateway ) ) {
					$this->payment_gateways[ $order ] = new $gateway() ;
				}
			} elseif ( is_object( $gateway ) ) {
				$this->payment_gateways[ $order ] = $gateway ;
			}
		}
	}

	public function load_default_payment_gateways() {
		//Abstract
		if ( ! class_exists( 'BSF_Payment_Gateway' ) ) {
			include BSF_PLUGIN_PATH . '/inc/abstracts/abstract-bsf-payment-gateway.php' ;
		}

		include_once 'offline/class-bsf-offline-gateway.php' ;
	}

	/**
	 * Get gateways.
	 *
	 * @return array
	 */
	public function get_payment_gateways() {
		$gateways = array() ;

		if ( sizeof( $this->payment_gateways ) > 0 ) {
			foreach ( $this->payment_gateways as $gateway ) {
				$gateways[ $gateway->get_id() ] = $gateway ;
			}
		}
		return $gateways ;
	}

	/**
	 * Check whether no available gateways for use in frontend.
	 *
	 * @return array
	 */
	public function no_payment_gateways_available() {

		if ( sizeof( $this->payment_gateways ) > 0 ) {
			foreach ( $this->payment_gateways as $gateway ) {
				if ( $gateway->is_available() ) {
					return false ;
				}
			}
		}
		return true ;
	}

	/**
	 * Get available gateways.
	 *
	 * @return array
	 */
	public function get_available_payment_gateways() {
		$available_gateways = array() ;

		if ( sizeof( $this->payment_gateways ) > 0 ) {
			foreach ( $this->payment_gateways as $gateway ) {
				if ( $gateway->is_available() ) {
					$available_gateways[ $gateway->get_id() ] = $gateway ;
				}
			}
		}

		return apply_filters( 'bsf_get_available_payment_gateways' , $available_gateways ) ;
	}

}

return BSF_Payment_Gateways::instance() ;
