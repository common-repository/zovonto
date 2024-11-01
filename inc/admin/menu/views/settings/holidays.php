<?php
/**
 * Working Hours
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

$events = bsf_get_holiday_events() ;
?>
<div class="bsf_holiday_year">
	<div class="bsf_holiday_previous_year bsf_change_calendar_year" data-trigger=".jCal .left">
		<i class="dashicons dashicons-arrow-left-alt2"></i>
	</div>
	<input class="jcal_year" readonly type="text" value="">
	<div class="bsf_holiday_next_year bsf_change_calendar_year" data-trigger=".jCal .right">
		<i class="dashicons dashicons-arrow-right-alt2"></i>
	</div>
</div>
<input type ="hidden" class="bsf_holiday_events" value='<?php echo esc_attr( wp_json_encode( $events ) ) ; ?>' />
<input type ="hidden" class="bsf_holiday_staff_id" value="0" />
<div id="bfs_holiday_calender">
</div>
<?php
