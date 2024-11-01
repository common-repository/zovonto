<?php
/* Edit Customers Page */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_payments_edit">
	<h2><?php esc_html_e( 'Edit Payment' , 'zovonto' ) ; ?></h2>
	<table class="form-table bsf_block">
		<tbody>
			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Payment Method' , 'zovonto' ) ; ?></label>
				</th>
				<td>
					<label><?php echo esc_html( bsf_display_payment_method( $payment_object->get_payment_method() ) ) ; ?></label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Service' , 'zovonto' ) ; ?></label>
				</th>
				<td>
					<label><?php echo esc_html( $payment_object->get_services()->get_name() ) ; ?></label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Customer' , 'zovonto' ) ; ?></label>
				</th>
				<td>
					<label><?php echo esc_html( $payment_object->get_customer()->get_full_name() ) ; ?></label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Appointment Date' , 'zovonto' ) ; ?></label>
				</th>
				<td>
					<label><?php echo esc_html( BSF_Date_Time::get_date_object_format_datetime( $payment_object->get_appointment()->get_start_date() ) ) ; ?></label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Payment Date' , 'zovonto' ) ; ?></label>
				</th>
				<td>
					<label><?php echo esc_html( BSF_Date_Time::get_date_object_format_datetime( $payment_object->get_date() ) ) ; ?></label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Amount' , 'zovonto' ) ; ?></label>
				</th>
				<td>
					<label><?php echo bsf_price( $payment_object->get_price() ) ; ?></label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label><?php esc_html_e( 'Status' , 'zovonto' ) ; ?></label>
				</th>
				<td>
					<select name='payment[status]'>
						<?php
						$payment_status = array(
							'pending'   => __( 'Pending' , 'zovonto' ) ,
							'completed' => __( 'Completed' , 'zovonto' ) ,
							'cancelled' => __( 'Cancelled' , 'zovonto' ) ,
								) ;

						foreach ( $payment_status as $payment_status_key => $payment_status_value ) {
							?>
							<option value="<?php echo esc_attr( $payment_status_key ) ; ?>" <?php selected( $payment_object->get_status() , $payment_status_key ) ; ?>><?php echo esc_html( $payment_status_value ) ; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input name='<?php echo esc_attr( $this->plugin_slug ) ; ?>_save' class='button-primary <?php echo esc_attr( $this->plugin_slug ) ; ?>_save_btn' type='submit' value="<?php esc_attr_e( 'Update Payment' , 'zovonto' ) ; ?>" />
		<input type="hidden" name="payment[id]" value="<?php echo esc_attr( $payment_id ) ; ?>"/>
		<input type="hidden" name="edit_payament" value="add-edit"/>

		<?php wp_nonce_field( $this->plugin_slug . '_edit_payment' , '_' . $this->plugin_slug . '_nonce' , false , true ) ; ?>
	</p>
</div>
