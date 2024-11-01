<?php
/**
 * Staff - Appointment Approved Notification
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Staff_Appointment_Approved_Notification' ) ) {

	/**
	 * Class BSF_Staff_Appointment_Approved_Notification
	 */
	class BSF_Staff_Appointment_Approved_Notification extends BSF_Notifications {

		/**
		 * Class Constructor
		 */
		public function __construct() {
			$this->id    = 'staff_appointment_approved' ;
			$this->title = __( 'Staff - Appointment Approved' , 'zovonto' ) ;

			// Triggers for this email.
			add_action( 'bsf_status_changed_approved' , array( $this , 'trigger' ) , 10 , 1 ) ;
			add_action( $this->plugin_slug . '_admin_field_staff_appointment_approved_shortcodes_table' , array( $this , 'staff_appointment_approved_shortcodes_table' ) ) ;

			parent::__construct() ;
		}

		/*
		 * Default Subject
		 */

		public function get_default_subject() {

			return '{site_name} – New Appointment' ;
		}

		/*
		 * Default Message
		 */

		public function get_default_message() {

			return 'Hi {staff_name},

You have one new Appointment. The details are as follows.

{appointment_details}

Thanks. ' ;
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
				$this->recipient                               = $appointment->get_staff()->get_email() ;
				$this->placeholders[ '{customer_name}' ]       = $appointment->get_customer()->get_full_name() ;
				$this->placeholders[ '{customer_email}' ]      = $appointment->get_customer()->get_email() ;
				$this->placeholders[ '{customer_phone}' ]      = $appointment->get_customer()->get_phone() ;
				$this->placeholders[ '{service_name}' ]        = $appointment->get_services()->get_name() ;
				$this->placeholders[ '{staff_name}' ]          = $appointment->get_staff()->get_email() ;
				$this->placeholders[ '{appointment_details}' ] = $this->prpeare_appointment_info( $appointment ) ;
				$this->placeholders[ '{appointment_date}' ]    = $appointment->get_formatted_datetime( 'date' ) ;
				$this->placeholders[ '{appointment_time}' ]    = $appointment->get_formatted_datetime( 'time' ) ;
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
					<th><?php esc_html_e( 'Appointment Date' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointment->get_formatted_datetime( 'date' ) ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Appointment Time' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointment->get_formatted_datetime( 'time' ) ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Customer Name' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointment->get_customer()->get_full_name() ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Customer Phone' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointment->get_customer()->get_phone() ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Customer Email' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointment->get_customer()->get_email() ) ; ?></td>
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
				'id'    => 'staff_appointment_approved_shortcodes' ,
					) ;
			$settings[] = array(
				'type' => 'staff_appointment_approved_shortcodes_table'
					) ;

			$settings[] = array(
				'type' => 'sectionend' ,
				'id'   => 'staff_appointment_approved_shortcodes' ,
					) ;

			$settings[] = array(
				'type'  => 'title' ,
				'title' => __( 'Email Settings' , 'zovonto' ) ,
				'id'    => 'staff_appointment_approved_notifications_options' ,
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
				'id'   => 'staff_appointment_approved_notifications_options' ,
					) ;

			return $settings ;
		}

		/**
		 * Output the booking shortcodes table
		 */
		public function staff_appointment_approved_shortcodes_table() {
			$shortcodes_info = array(
				'{appointment_details}' => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Appointment Details' , 'zovonto' )
				) ,
				'{appointment_date}'    => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Appointment Date' , 'zovonto' )
				) ,
				'{appointment_time}'    => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Appointment Time' , 'zovonto' )
				) ,
				'{service_name}'        => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Service Name' , 'zovonto' )
				) ,
				'{staff_name}'          => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Staff Name' , 'zovonto' )
				) ,
				'{customer_name}'       => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Customer Name' , 'zovonto' )
				) ,
				'{customer_phone}'      => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Customer Phone' , 'zovonto' )
				) ,
				'{customer_email}'      => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Customer Email' , 'zovonto' )
				) ,
				'{site_name}'           => array( 'where' => __( 'Email' , 'zovonto' ) ,
					'usage' => __( 'Displays the Site Name' , 'zovonto' )
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
