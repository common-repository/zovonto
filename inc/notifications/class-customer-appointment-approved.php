<?php
/**
 * Customer - Appointment Approved Notification
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Customer_Appointment_Approved_Notification' ) ) {

	/**
	 * Class BSF_Customer_Appointment_Approved_Notification
	 */
	class BSF_Customer_Appointment_Approved_Notification extends BSF_Notifications {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'customer_appointment_approved' ;
			$this->title = __( 'Customer - Appointment Approved' , 'zovonto' ) ;

			// Triggers for this email.
			add_action( 'bsf_status_changed_approved' , array( $this , 'trigger' ) , 10 , 1 ) ;
			add_action( $this->plugin_slug . '_admin_field_customer_appointment_approved_shortcodes_table' , array( $this , 'customer_appointment_approved_shortcodes_table' ) ) ;

			parent::__construct() ;
		}

		/*
		 * Default Subject
		 */

		public function get_default_subject() {

			return '{site_name} – Appointment Approved' ;
		}

		/*
		 * Default Message
		 */

		public function get_default_message() {

			return 'Hi {customer_name},

Your Appointment for {service_name} on {appointment_date_time} has been successfully scheduled.

Your Appointment details are as follows.

{appointment_details}

Thanks. 

{company_name}
{company_phone}
{company_website}' ;
		}

		/*
		 * Get settings link
		 */

		public function settings_link() {
			return add_query_arg( array( 'page' => 'booking_system' , 'tab' => 'notifications' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
		}

		/**
		 * Trigger the sending of this email.
		 */
		public function trigger( $appointment_id, $appointment = false ) {
			if ( $appointment_id && ! is_a( $appointment , 'BSF_Appointment' ) ) {
				$appointment = new BSF_Appointment( $appointment_id ) ;
			}

			if ( is_a( $appointment , 'BSF_Appointment' ) ) {
				$this->recipient                                 = $appointment->get_customer()->get_email() ;
				$this->placeholders[ '{customer_name}' ]         = $appointment->get_customer()->get_full_name() ;
				$this->placeholders[ '{service_name}' ]          = $appointment->get_services()->get_name() ;
				$this->placeholders[ '{appointment_date_time}' ] = $appointment->get_start_date() ;
				$this->placeholders[ '{staff_name}' ]            = $appointment->get_staff()->get_name() ;
				$this->placeholders[ '{appointment_details}' ]   = $this->prpeare_appointment_info( $appointment ) ;
				$this->placeholders[ '{appointment_date}' ]      = $appointment->get_formatted_datetime( 'date' ) ;
				$this->placeholders[ '{appointment_time}' ]      = $appointment->get_formatted_datetime( 'time' ) ;
				$this->placeholders[ '{company_address}' ]       = get_option( 'bsf_settings_company_address' ) ;
				$this->placeholders[ '{company_name}' ]          = get_option( 'bsf_settings_company_name' ) ;
				$this->placeholders[ '{company_phone}' ]         = get_option( 'bsf_settings_company_phone' ) ;
				$this->placeholders[ '{company_website}' ]       = get_option( 'bsf_settings_company_website' ) ;
			}

			if ( $this->is_email_enabled() && $this->get_recipient() ) {
				$this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_message() , $this->get_headers() , $this->get_attachments() ) ;
			}
		}

		/*
		 * Prpeare Appointment Info 
		 */

		public function prpeare_appointment_info( $appointment ) {
			ob_start() ;
			?>
			<table>
				<tr>
					<th><?php esc_html_e( 'Service Name' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointment->get_services()->get_name() ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Staff Name' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointment->get_staff()->get_name() ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Appointment Date' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointment->get_formatted_datetime( 'date' ) ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Appointment Time' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointment->get_formatted_datetime( 'time' ) ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Address' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( get_option( 'bsf_settings_company_address' ) ) ; ?></td>
				</tr>
			</table>
			<?php
			$contents = ob_get_contents() ;
			ob_end_clean() ;

			return $contents ;
		}

		/*
		 * Get settings options array
		 */

		public function settings_options_array() {
			$settings = array() ;

			$settings[] = array(
				'type'  => 'title' ,
				'title' => __( 'Shortcodes' , 'zovonto' ) ,
				'id'    => 'customer_appointment_approved_shortcodes' ,
					) ;
			$settings[] = array(
				'type' => 'customer_appointment_approved_shortcodes_table'
					) ;

			$settings[] = array(
				'type' => 'sectionend' ,
				'id'   => 'customer_appointment_approved_shortcodes' ,
					) ;

			$settings[] = array(
				'type'  => 'title' ,
				'title' => __( 'Email Settings' , 'zovonto' ) ,
				'id'    => 'customer_appointment_approved_notifications_options' ,
					) ;
			$settings[] = array(
				'title'   => __( 'Send Email' , 'zovonto' ) ,
				'id'      => $this->get_field_key( 'email_enabled' ) ,
				'type'    => 'checkbox' ,
				'default' => '' ,
					) ;
			$settings[] = array(
				'title'   => __( 'Subject' , 'zovonto' ) ,
				'id'      => $this->get_field_key( 'subject' ) ,
				'type'    => 'text' ,
				'default' => $this->get_default_subject() ,
					) ;
			$settings[] = array(
				'title'   => __( 'Message' , 'zovonto' ) ,
				'id'      => $this->get_field_key( 'message' ) ,
				'type'    => 'wpeditor' ,
				'default' => $this->get_default_message() ,
					) ;
			$settings[] = array(
				'type' => 'sectionend' ,
				'id'   => 'customer_appointment_approved_notifications_options' ,
					) ;

			return $settings ;
		}

		/**
		 * Output the booking shortcodes table
		 */
		public function customer_appointment_approved_shortcodes_table() {
			$shortcodes_info = array(
				'{customer_name}'         => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Customer Name' , 'zovonto' )
				) ,
				'{appointment_date_time}' => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Appointment Date and Time' , 'zovonto' )
				) ,
				'{appointment_details}'   => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Appointment Details' , 'zovonto' )
				) ,
				'{appointment_date}'      => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Appointment Date' , 'zovonto' )
				) ,
				'{appointment_time}'      => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Appointment Time' , 'zovonto' )
				) ,
				'{service_name}'          => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Service Name' , 'zovonto' )
				) ,
				'{staff_name}'            => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Staff Name' , 'zovonto' )
				) ,
				'{company_address}'       => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Company Address' , 'zovonto' )
				) ,
				'{company_name}'          => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Company Name' , 'zovonto' )
				) ,
				'{company_phone}'         => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Company Phone' , 'zovonto' )
				) ,
				'{company_website}'       => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Company Website' , 'zovonto' )
				) ,
					) ;
			?>
			<table class="notification_shortcode_table">
				<thead>
					<tr>
						<th>
							<?php esc_html_e( 'Shortcode' , 'zovonto' ) ; ?>
						</th>
						<th>
							<?php esc_html_e( 'Context where Shortcode is valid' , 'zovonto' ) ; ?>
						</th>
						<th>
							<?php esc_html_e( 'Purpose' , 'zovonto' ) ; ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( bsf_check_is_array( $shortcodes_info ) ) {
						foreach ( $shortcodes_info as $shortcode => $s_info ) {
							?>
							<tr>
								<td>
									<?php echo $shortcode ; ?>
								</td>
								<td>
									<?php echo $s_info[ 'where' ] ; ?>
								</td>
								<td>
									<?php echo $s_info[ 'usage' ] ; ?>
								</td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
			<?php
		}

	}

}
