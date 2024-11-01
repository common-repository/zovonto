<?php
/* Progress Bar */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

$step_array = apply_filters( 'bsf_booking_steps_progress_bar' , array(
	'service'  => get_option( 'bsf_settings_service_selection_step_label' ) ,
	'time'     => get_option( 'bsf_settings_time_selection_step_label' ) ,
	'details'  => get_option( 'bsf_settings_user_details_step_label' ) ,
	'payment'  => get_option( 'bsf_settings_payment_step_label' ) ,
	'complete' => get_option( 'bsf_settings_completion_step_label' ) ,
		) ) ;

$active_class = 'active' ;
?>
<div class="bsf_booking_prograss_bar_container">
	<div class="bsf_booking_prograss_bar">
		<ul class="bsf_booking_steps">
			<?php
			foreach ( $step_array as $step_key => $step_name ) {
				?>
				<li class="bsf_booking_step <?php echo esc_attr( $active_class ) ; ?>"><?php echo esc_html( $step_name ) ; ?></li>
					<?php
					if ( $step_key == $selected_step ) {
						$active_class = '' ;
					}
			}
			?>
		</ul>
	</div>
</div>
<?php
