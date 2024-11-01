<?php
/**
 * Display Appointment details
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Light_Box' ) ) {

	/**
	 * BSF_Light_Box Class.
	 */
	class BSF_Light_Box {

		/**
		 * BSF_Light_Box Class initialization.
		 */
		public static function init() {
			add_action( 'wp_head' , array( __CLASS__ , 'view_appointments' ) ) ;
		}

		/**
		 * View Appointments details
		 */
		public static function view_appointments() {

			if ( ! isset( $_GET[ 'appointment_id' ] ) || empty( $_GET[ 'appointment_id' ] ) ) {
				return ;
			}

			//Verify the nonce.
			if ( ! isset( $_GET[ 'bsf_nonce' ] ) || ! wp_verify_nonce( $_GET[ 'bsf_nonce' ] , 'bsf_lightbox_nonce' ) ) {
				return ;
			}

			$appointment_id = absint( $_GET[ 'appointment_id' ] ) ;
			$appointments   = new BSF_Appointment( $appointment_id ) ;

			if ( ! $appointments->exists() ) {
				return ;
			}
			?>
			<table class="bsf-booking-customer-info">
				<tr>
					<th><?php esc_html_e( 'Appointment Start Time' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( BSF_Date_Time::get_date_object_format_datetime( $appointments->get_start_date() ) ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Appointment End Time' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( BSF_Date_Time::get_date_object_format_datetime( $appointments->get_end_date() ) ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Duration' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointments->get_duration_label() ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Service' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointments->get_services()->get_name() ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Staff' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointments->get_staff()->get_name() ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Customer' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointments->get_customer()->get_full_name() ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Phone Number' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( $appointments->get_customer()->get_phone() ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Payment' , 'zovonto' ) ; ?></th>
					<td><?php echo bsf_price( $appointments->get_price() ) ; ?></td>
				</tr>
				<?php do_action( 'bsf_after_price_field' , $appointments ) ; ?>
				<tr>
					<th><?php esc_html_e( 'Status' , 'zovonto' ) ; ?></th>
					<td><?php echo bsf_display_status( $appointments->get_status() ) ; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Date' , 'zovonto' ) ; ?></th>
					<td><?php echo esc_html( BSF_Date_Time::get_date_object_format_datetime( $appointments->get_date() ) ) ; ?></td>
				</tr>
			</table>
			<?php
			exit() ;
		}

	}

	BSF_Light_Box::init() ;
}
