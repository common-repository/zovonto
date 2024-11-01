<?php
/**
 * Pro Version Tab
 */
if ( ! defined ( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists ( 'BSF_PRO_Version_Tab' ) ) {
	return new BSF_PRO_Version_Tab() ;
}

/**
 * BSF_PRO_Version_Tab.
 */
class BSF_PRO_Version_Tab extends BSF_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'pro-version' ;
		$this->code  = 'fa-diamond' ;
		$this->label = __ ( 'Pro Version' , 'zovonto' ) ;

		add_action ( $this->plugin_slug . '_admin_field_pro-version-keys' , array ( $this , 'pro_version_keys' ) ) ;

		parent::__construct () ;
	}

	/**
	 * Get settings array.
	 */
	public function get_settings( $current_section = '' ) {
		return array (
			array ( 'type' => 'pro-version-keys' )
				) ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the help content
	 */
	public function pro_version_keys() {
		$pro_version_keys = array (
			'Services'                  => __ ( 'You can create unlimited services using the Pro version.' , 'zovonto' ) ,
			'Staffs'                    => __ ( 'You can create any number of Staffs using the Pro version.' , 'zovonto' ) ,
			'Booking Slots'             => __ ( 'Working Hours and Days Off can be configured for each staff and hence, based on the availability of each staff, available booking slots will be displayed.' , 'zovonto' ) ,
			'Customers'                 => __ ( "Customer's data will be captured when they book an appointment. The user information and number of appointments made by the user will be listed in a single place." , 'zovonto' ) ,
			'Payments'                  => __ ( 'In addition to Offline Payment, Pro version supports PayPal and Stripe payment gateways.' , 'zovonto' ) ,
			'View Appointments'         => __ ( 'Supports Calendar and List View for admin to see the appointments made by the users.' , 'zovonto' ) ,
			'Notifications'             => __ ( 'Email and SMS notifications can be sent to Staffs and Customers for Appointment Approval, Cancellation, etc.' , 'zovonto' ) ,
			'Account Management'        => __ ( 'Customers can signup for an account using the Signup form that comes up with the plugin. ' , 'zovonto' ) ,
			'Customer Dashboard'        => __ ( 'Customers can view their appointments, cancel their appointments from their dashboard after they login to the site.' , 'zovonto' ) ,
			'Customizable Booking Form' => __ ( 'The frontend booking form is highly customizable.' , 'zovonto' ) ,
			'WooCommerce'               => __ ( 'Pro version supports WooCommerce Integration using which booking process will go through WooCommerce and Payments will be handled by WooCommerce.' , 'zovonto' ) ,
			'Customer Defined Duration' => __ ( 'Using this feature, customers can choose the duration for their bookings.' , 'zovonto' ) ,
			'Persons'                   => __ ( 'This feature allows customers to book for multiple persons in a single booking.' , 'zovonto' ) ,
			'Service Extra'             => __ ( 'If you are planning to offer additional services as a part of the booking process to your customers, then using Service Extra it is possible.' , 'zovonto' ) ,
			'Important Days'            => __ ( 'Important Days allows setting up special days for each staff. Special days will be given higher priority for the staffs.' , 'zovonto' ) ,
			'Custom Fields'             => __ ( 'To get more information from the customer at the time of booking, custom fields can be used.' , 'zovonto' ) ,
			'Staff Cabinet'             => __ ( 'Staff Cabinet allows the Staff to manage/edit their appointments, availability, etc from frontend.' , 'zovonto' ) ,
			'Coupons'                   => __ ( 'Site admin can create and offer coupons to their customers. Customers can use these coupon codes to get a discount in their appointment.' , 'zovonto' ) ,
			'PayPal Payouts'            => __ ( 'Pro version supports PayPal Payouts using which site admin can pay their staffs directly to their PayPal account.' , 'zovonto' ) ,
				) ;
		?>
		<div class="bsf_pro_content">
			<div class="bsf-Pro-botton-top"><a target="blank" href="https://flintop.com/zovonto-bookings-and-appointments/"><?php echo __ ( 'Buy Pro Version' , 'zovonto' ) ; ?></a></div>
			<p> <?php echo __ ( 'Pro Version of Zovonto has the following features' , 'zovonto' ); ?></a></p>   

			<?php foreach ( $pro_version_keys as $key_title => $key_desc ) { ?>
				<h4><?php echo $key_title ; ?></h4>
				<p> <?php echo $key_desc ; ?></p>
			<?php } ?>

			<div class="bsf-Pro-botton-bottom"><a target="blank" href="https://flintop.com/zovonto-bookings-and-appointments/"><?php echo __ ( 'Buy Pro Version' , 'zovonto' ) ; ?></a></div>
		</div>
		<?php
	}

}

return new BSF_PRO_Version_Tab() ;
