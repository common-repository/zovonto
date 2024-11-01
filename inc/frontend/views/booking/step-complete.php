<?php
/* Complete step */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
echo self::render_progress_bar( 'complete' ) ;
?>
<div class="bsf-booking-form-complete-container bsf-booking-form-inner-container">
	<h2><?php echo esc_html( get_option( 'bsf_settings_completion_step_label' ) ) ; ?></h2>
	<p><?php echo wp_kses_post( wptexturize( get_option( 'bsf_settings_completion_step_desc' ) ) ) ; ?></p>
	<div class="bsf_booking_form_msg">

	</div>
	<table class="bsf-booking-customer-info">
		<tr>
			<th><?php esc_html_e( 'Service' , 'zovonto' ) ; ?></th>
			<td><?php echo esc_html( $payment_object->get_services()->get_name() ) ; ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Staff' , 'zovonto' ) ; ?></th>
			<td><?php echo esc_html( $payment_object->get_staff()->get_name() ) ; ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Price' , 'zovonto' ) ; ?></th>
			<td><?php echo bsf_price( $payment_object->get_price() ) ; ?></td>
		</tr>
		<?php do_action( 'bsf_after_price_field' , $payment_object->get_appointment() ) ; ?>
		<tr>
			<th><?php esc_html_e( 'Appointment Start Time' , 'zovonto' ) ; ?></th>
			<td><?php echo esc_html( BSF_Date_Time::get_date_object_format_datetime( $payment_object->get_appointment()->get_start_date() ) ) ; ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Appointment End Time' , 'zovonto' ) ; ?></th>
			<td><?php echo esc_html( BSF_Date_Time::get_date_object_format_datetime( $payment_object->get_appointment()->get_end_date() ) ) ; ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Payment Method' , 'zovonto' ) ; ?></th>
			<td><?php echo esc_html( bsf_display_payment_method( $payment_object->get_payment_method() ) ) ; ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Payment Status' , 'zovonto' ) ; ?></th>
			<td><?php echo esc_html( ucfirst( $payment_object->get_status() ) ) ; ?></td>
		</tr>
	</table>
	<div class="bsf-nav-steps">
		<button type="button" class="bsf_booking_next_step bsf_booking_completed_btn bsf_booking_step_btn"><?php esc_html_e( 'Done' , 'zovonto' ) ; ?></button>
	</div>
</div>

<?php
