<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}

$next_month = $month + 1 ;
$prev_month = $month - 1 ;

$next_year = ( $next_month > 12 ) ? ( $year + 1 ) : $year ;
$prev_year = ( $prev_month < 1 ) ? ( $year - 1 ) : $year ;

$next_month  = ( $next_month > 12 ) ? 1 : $next_month ;
$prev_month  = ( $prev_month < 1 ) ? 12 : $prev_month ;
?>
<form method="POST" id="mainform" enctype="multipart/form-data" class="bsf_bookings_calendar_form">
	<input type="hidden" name="page" value="bsf_bookings_calendar"/>
	<div class="bsf_date_header_field">
		<div class="date_selector">
			<a  class="prev bsf_prev" href="<?php echo esc_url( add_query_arg( array( 'year' => $year , 'month' => $month - 1 ) ) ) ; ?>"> <i class="fa fa-chevron-left" aria-hidden="true"></i> </a>
			<a  class="next bsf_next" href="<?php echo esc_url( add_query_arg( array( 'year' => $year , 'month' => $month + 1 ) ) ) ; ?>"> <i class="fa fa-chevron-right" aria-hidden="true"></i> </a>
			<div class="bsf_date_header_select_field">
				<select name="month">
					<?php
					$month_array = bsf_get_drop_down_values( 'months' ) ;
					foreach ( $month_array as $month_id => $month_name ) {
						?>
						<option value="<?php echo esc_attr( $month_id ) ; ?>" <?php selected( $month , $month_id ) ; ?>><?php echo esc_html( $month_name ) ; ?></option>
					<?php } ; ?>
				</select>
			</div>
			<div class="bsf_date_header_select_field">
				<select name="year">
					<?php
					for ( $i = ( $year - 4 ) ; $i <= ( $year + 5 ) ; $i ++ ) :
						?>
						<option value="<?php echo $i ; ?>" <?php selected( $year , $i ) ; ?>><?php echo $i ; ?></option>
					<?php endfor ; ?>
				</select>
			</div>

			<button type="submit" title='<?php esc_attr_e( 'Submit' , 'zovonto' ) ; ?>'class="bsf_bookings_submit"> <i class="fa fa-play-circle-o" aria-hidden="true"></i> </button>
			<a class="prev bsf_today_btn"  href="<?php echo esc_url( add_query_arg( array( 'page' => 'booking_system' ) , BSF_ADMIN_URL ) ) ; ?>"><?php esc_html_e( 'Today' , 'zovonto' ) ; ?></a>
		</div>
		<div class="bsf_bookings_second_popup"> </div>
		<div class="bsf_bookings_first_popup">
			<div class="bsf_bookings_first_popup_inner_content_width">
				<div class="bsf_bookings_first_popup_inner_content_top">
					<div class="bsf_bookings_popup__title"><h2></h2></div>
					<div class="bsf_bookings_popup_close_image" title="<?php esc_attr_e( 'Close' , 'zovonto' ) ; ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></div>
				</div>
				<div class="bsf_bookings_first_popup_inner_content_bottom">
					<div class="bsf_bookings_first_popup_inner_content_bottom_data"></div>
				</div>
			</div>
		</div>
	</div>
</form>
