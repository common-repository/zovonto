<?php

/**
 * Class BSF_Offline_Gateway.
 */
defined( 'ABSPATH' ) || exit ;

/**
 * Offline Gateway.
 */
class BSF_Offline_Gateway extends BSF_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id      = 'offline_payment_gateway' ;
		$this->enabled = 'yes' ; //Default
		// Load the settings.
		$this->init_settings() ;

		// Get settings.
		$this->title       = $this->get_option( 'title' , 'Pay Offline' ) ;
		$this->description = $this->get_option( 'description' , 'Payment has to be paid directly in person.' ) ;

		add_action( 'bsf_thankyou_page_' . $this->id , array( $this , 'thankyou_page' ) ) ;

		parent::__construct() ;
	}

	/**
	 * Initialize Gateway Settings Form Fields.
	 */
	public function init_form_fields() {

		$this->form_fields = array(
			array(
				'title'   => __( 'Enable/Disable' , 'zovonto' ) ,
				'type'    => 'checkbox' ,
				'id'      => $this->get_option_key( 'enabled' ) ,
				'desc'    => '' ,
				'default' => 'yes' ,
			) ,
			array(
				'title'   => __( 'Title' , 'zovonto' ) ,
				'type'    => 'text' ,
				'id'      => $this->get_option_key( 'title' ) ,
				'desc'    => __( 'Payment method description' , 'zovonto' ) ,
				'default' => 'Pay Offline' ,
			) ,
			array(
				'title'   => __( 'Description' , 'zovonto' ) ,
				'type'    => 'textarea' ,
				'id'      => $this->get_option_key( 'description' ) ,
				'desc'    => __( 'Payment method description that the customer will see on your website.' , 'zovonto' ) ,
				'default' => 'Payment has to be paid directly in person.' ,
			) ) ;
	}

	/**
	 * Process the payment
	 *
	 * @param mixed $payment BSF_Payment payment
	 * @return array
	 */
	public function process_payment( $payment, $booking_data ) {
		bsf_update_payment( $payment->get_id() , array(
			'status' => 'pending' ,
		) ) ;

		return array(
			'result' => 'success' ,
				) ;
	}

	/**
	 * Output for the payment received page.
	 */
	public function thankyou_page() {
		if ( $this->instructions ) {
			echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) ) ;
		}
	}

}
