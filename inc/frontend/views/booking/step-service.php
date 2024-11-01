<?php
/* Service step */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

echo self::render_progress_bar( 'service' , false ) ;
$from_date = ( $booking_data_object->get_from_date() !== null ) ? $booking_data_object->get_from_date() : date( 'Y-m-d' , time() ) ;
?>
<div class="bsf-booking-form-service-container bsf-booking-form-inner-container">
	<h2><?php echo esc_html( get_option( 'bsf_settings_service_selection_step_label' ) ) ; ?></h2>
	<p><?php echo wp_kses_post( wptexturize( get_option( 'bsf_settings_service_selection_step_desc' ) ) ) ; ?></p>
	<div class="bsf_booking_form_msg">

	</div>
	<div class="bsf-booking-form-row">
		<label><?php echo esc_html( get_option( 'bsf_settings_service_selector_label' ) ) ; ?> <span>*</span></label>

		<select id="bsf_select_services">
			<option value=""><?php esc_html_e( 'Select a Service' , 'zovonto' ) ; ?></option>
		</select>
	</div>
	<div class="bsf-booking-form-row">
		<label><?php echo esc_html( get_option( 'bsf_settings_calendar_selector_label' ) ) ; ?></label>
		<?php
		bsf_get_datepicker_html( array(
			'id'    => 'bsf_date_from' ,
			'value' => $from_date ,
		) ) ;
		?>
	</div>
	<div class="bsf-booking-form-row">
		<?php
		foreach ( $days as $day => $day_name ) {
			$checked = 'checked="checked"' ;
			if ( $booking_data_object->get_week_days() ) {
				$checked = ( in_array( $day , $booking_data_object->get_week_days() ) ) ? $checked : '' ;
			}
			?>

			<div class="bsf-booking-form-row-checkbox">
				<strong class="bsf-booking-form-days"><?php echo esc_html( $day_name ) ; ?></strong>
				<label class="bsf_switch">
					<input type="checkbox" class="bsf_week_days" value="<?php echo esc_attr( $day ) ; ?>" <?php echo esc_attr( $checked ) ; ?>/>
					<span class="bsf_slider bsf_round"></span>
				</label>
			</div>    
		<?php } ?>
	</div>
	<div class="bsf-booking-form-row">
		<div class="bsf-booking-form-row-td">
			<label><?php echo esc_html( get_option( 'bsf_settings_start_time_label' ) ) ; ?></label>
			<select id="bsf_select_time_from">
				<?php foreach ( $times as $time_value => $time_label ) { ?>
					<option value="<?php echo esc_attr( $time_value ) ; ?>" <?php selected( $booking_data_object->get_from_time() , $time_value ) ; ?>><?php echo esc_html( $time_label ) ; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="bsf-booking-form-row-td bsf-booking-form-row-right-td">
			<label><?php echo esc_html( get_option( 'bsf_settings_end_time_label' ) ) ; ?></label>
			<select id="bsf_select_time_to">
				<?php foreach ( $times as $time_value => $time_label ) { ?>
					<option value="<?php echo esc_attr( $time_value ) ; ?>" <?php selected( $booking_data_object->get_to_time() , $time_value ) ; ?>><?php echo esc_html( $time_label ) ; ?></option>
				<?php } ?>
			</select>
		</div>    
	</div>

	<div class="bsf-nav-steps">
		<button type="button" class="bsf_booking_next_step bsf_booking_step_btn"><?php esc_html_e( 'Next' , 'zovonto' ) ; ?> <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
	</div>
</div>

<?php
