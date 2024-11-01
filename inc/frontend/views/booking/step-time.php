<?php
/* Time step */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
echo self::render_progress_bar( 'time' ) ;
$step_show = ( bsf_check_is_array( $booking_data_object->get_slots() ) ) ? 'bsf_show' : 'bsf_hide' ;
?>
<div class="bsf-booking-form-time-container bsf-booking-form-inner-container">
	<h2><?php echo esc_html( get_option( 'bsf_settings_time_selection_step_label' ) ) ; ?></h2>
	<p><?php echo wp_kses_post( wptexturize( get_option( 'bsf_settings_time_selection_step_desc' ) ) ) ; ?></p>
	<div class="bsf_booking_form_msg">

	</div>
	<div class="bsf-booking-form-row">
		<p class="bsf-booking-form-details-info"><small><?php echo wp_kses_post( wptexturize( $info_text ) ) ; ?></small></p>
	</div>
	<?php if ( ! empty( $slots ) ) : ?>
		<div class="bsf_time_slots">
			<input type="hidden" class="bsf_booking_selected_time" value='<?php echo esc_attr( wp_json_encode( $booking_data_object->get_slots() ) ) ; ?>'/>
		</div>
	<?php else : ?>
		<div class="bsf_time_slots">
			<?php echo esc_html( get_option( 'bsf_settings_time_selection_no_items_message' ) ) ; ?>
		</div>
	<?php endif ; ?>
	<div class="bsf-nav-steps">
		<button type="button" class="bsf_booking_back_step bsf_booking_step_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> <?php esc_html_e( 'Prev' , 'zovonto' ) ; ?></button>
		<button type="button" class="bsf_booking_next_step bsf_booking_step_btn <?php echo esc_attr( $step_show ) ; ?>" ><?php esc_html_e( 'Next' , 'zovonto' ) ; ?> <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
	</div>
</div>
<?php
