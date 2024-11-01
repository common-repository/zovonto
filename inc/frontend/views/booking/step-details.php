<?php
/* Details step */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
echo self::render_progress_bar( 'details' ) ;
?>
<div class="bsf-booking-form-details-container bsf-booking-form-inner-container">
	<h2><?php echo esc_html( get_option( 'bsf_settings_user_details_step_label' ) ) ; ?></h2>
	<p><?php echo wp_kses_post( wptexturize( get_option( 'bsf_settings_user_details_step_desc' ) ) ) ; ?></p>
	<div class="bsf_booking_form_msg">

	</div>
	<div class="bsf-booking-form-row">
		<p class="bsf-booking-form-details-info"><small><?php echo wp_kses_post( wptexturize( $info_text ) ) ; ?></small></p>
	</div>
	<form method="POST" id="bsf_booking_form_details">
		<div class="bsf-booking-form-row">
			<label><?php echo esc_html( get_option( 'bsf_settings_first_name_label' ) ) ; ?> <span>*</span></label>
			<input type="text" name="first_name" value="<?php echo esc_attr( $booking_data_object->get_first_name() ); ?>" placeholder="<?php echo esc_attr( get_option( 'bsf_settings_first_name_placeholder' ) ) ; ?>"/>
		</div>
		<div class="bsf-booking-form-row">
			<label><?php echo esc_html( get_option( 'bsf_settings_last_name_label' ) ) ; ?> <span>*</span></label>
			<input type="text" name="last_name" value="<?php echo esc_attr( $booking_data_object->get_last_name() ); ?>" placeholder="<?php echo esc_attr( get_option( 'bsf_settings_last_name_placeholder' ) ) ; ?>"/>
		</div>
		<div class="bsf-booking-form-row">
			<label><?php echo esc_html( get_option( 'bsf_settings_email_label' ) ) ; ?> <span>*</span></label>
			<input type="text" name="email" value="<?php echo esc_attr( $booking_data_object->get_email() ); ?>" placeholder="<?php echo esc_attr( get_option( 'bsf_settings_email_placeholder' ) ) ; ?>"/>
		</div>
		<div class="bsf-booking-form-row">
			<label><?php echo esc_html( get_option( 'bsf_settings_phone_label' ) ) ; ?> <span>*</span></label>
			<input type="text" name="phone" value="<?php echo esc_attr( $booking_data_object->get_phone() ); ?>" placeholder="<?php echo esc_attr( get_option( 'bsf_settings_phone_placeholder' ) ) ; ?>"/>
		</div>
		<?php if ( get_option( 'bsf_settings_display_notes_field' , 'yes' ) != 'no' ) { ?>
			<div class="bsf-booking-form-row">
				<label><?php echo esc_html( get_option( 'bsf_settings_notes_field_label' ) ) ; ?></label>
				<textarea name="info" id="bsf_customer_notes"><?php echo esc_html( $booking_data_object->get_info() ); ?></textarea>
			</div>
		<?php } ?>
		<?php do_action( 'bsf_after_booking_fields' , $booking_data_object ) ; ?>

		<div class="bsf-nav-steps">
			<button type="button" class="bsf_booking_back_step bsf_booking_step_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> <?php esc_html_e( 'Prev' , 'zovonto' ) ; ?></button>
			<button type="button" class="bsf_booking_next_step bsf_booking_step_btn"><?php esc_html_e( 'Next' , 'zovonto' ) ; ?> <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
		</div>
	</form>
</div>
<?php
