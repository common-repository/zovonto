<?php
/* Payment step */

if ( ! defined ( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
echo self::render_progress_bar ( 'payment' ) ;
$available_gateways = BSF ()->payment_gateways ()->get_available_payment_gateways () ;
?>
<div class="bsf-booking-form-payment-container bsf-booking-form-inner-container">
	<h2><?php echo esc_html ( get_option ( 'bsf_settings_payment_step_label' ) ) ; ?></h2>
	<p><?php echo wp_kses_post ( wptexturize ( get_option ( 'bsf_settings_payment_step_desc' ) ) ) ; ?></p>
	<div class="bsf_booking_form_msg">
	</div>
	<div class="bsf-booking-form-complete-container">
		<?php do_action ( 'bsf_before_payment_methods' ) ; ?>
		<h2><?php esc_html_e ( 'Booking Amount' , 'zovonto' ) ; ?></h2>
		<table class="bsf-booking-customer-info bsf-booking-payment-table">
			<tr>
				<td><?php esc_html_e ( 'Subtotal' , 'zovonto' ) ; ?></td>
				<td><?php echo bsf_price ( $booking_data_object->get_price () ) ; ?></td> 
			</tr>
			<?php do_action ( 'bsf_after_subtotal_rows' , $form_id , $booking_data_object ) ; ?>
			<tr>
				<td><?php esc_html_e ( 'Total' , 'zovonto' ) ; ?></td>
				<td><?php echo bsf_price ( $booking_data_object->get_total () ) ; ?></td>
			</tr>
		</table>
	</div>

	<div class="bsf-payment-methods">
		<?php
		if ( apply_filters ( 'bsf_available_payment_methods' , true ) ) {
			if ( BSF ()->payment_gateways ()->no_payment_gateways_available () ) {
				?>
				<li class="bsf_payment_method bsf_no_payment_methods_available">
					<label for="bsf_no_payment_methods_available">
						<?php esc_html_e ( 'No payment gateways available' , 'zovonto' ) ; ?>
					</label>
				</li>
				<?php
			} else {
				foreach ( $available_gateways as $gateway ) :
					?>
					<li class="bsf_payment_method bsf_payment_method_<?php echo esc_attr ( $gateway->get_id () ) ; ?>">
						<input id="bsf_payment_method_<?php echo esc_attr ( $gateway->get_id () ) ; ?>" type="radio" class="bsf_payment_method_input" name="payment_method" value="<?php echo esc_attr ( $gateway->get_id () ) ; ?>" <?php checked ( $gateway->get_id () === current ( array_keys ( $available_gateways ) ) , true ) ; ?>/>

						<label for="bsf_payment_method_<?php echo esc_attr ( $gateway->get_id () ) ; ?>">
							<?php echo esc_html ( $gateway->get_title () ) ; ?>
							<div class="bsf_payment_method_icon">
								<?php echo $gateway->get_icon () ; ?>
							</div>
						</label>
						<?php
						if ( $gateway->get_description () ) :
							?>
							<div class="bsf_payment_method_<?php echo esc_attr ( $gateway->get_id () ) ; ?>">
							<?php echo $gateway->get_description () ; ?>
							</div>
						<?php endif ; ?>
					</li>
							<?php
						endforeach ;
			}
		}
				do_action ( 'bsf_after_payment_methods' ) ;
		?>
						
	</div>
	<div class="bsf-nav-steps">
		<button type="button" class="bsf_booking_back_step bsf_booking_step_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> <?php esc_html_e ( 'Prev' , 'zovonto' ) ; ?></button>
		<button type="button" class="bsf_booking_next_step bsf_booking_step_btn"><?php esc_html_e ( 'Next' , 'zovonto' ) ; ?> <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
	</div>
</div>
<?php
