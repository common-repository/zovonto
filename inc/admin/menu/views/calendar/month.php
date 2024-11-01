<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
?>

<div class = 'bsf_bookings_calendar_wrap'>
	<h2><?php esc_html_e( 'Appointments for the Month' , 'zovonto' ) ; ?></h2>
	<?php
	BSF_Calendar_Tab::display_action_bar() ;
	?>
	<table class="bsf_bookings_calendar_days">
		<thead>
			<tr>
				<?php
				$first_week = get_option( 'start_of_week' , 1 ) ;
				$days       = bsf_get_drop_down_values( 'days' ) ;
				for ( $index = 0 ; $index < 7 ; $index ++ ) :
					$i = ( $index + $first_week ) % 7 ;
					?>
					<th><?php esc_html_e( $days[ $i ] ) ; ?></th>
				<?php endfor ; ?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php
				$current_time = strtotime( 'today midnight' ) ;
				$index        = 0 ;
				for ( $timestamp = $start_timestamp ; $timestamp <= $end_timestamp ; $timestamp = strtotime( '+1 day' , $timestamp ) ) :
					$class_name        = ( date( 'n' , $timestamp ) != absint( $month ) ) ? 'bsf_bookings_calendar_diff_month' : '' ;
					$class_name        .= ( $timestamp == $current_time ) ? ' bsf_bookings_today' : '' ;
					?>
					<td width="14.285%" class="<?php echo esc_attr($class_name) ; ?>">
						<a class='bsf_bookings_day' href="#">
							<?php echo esc_html( date( 'd' , $timestamp ) ) ; ?>
						</a>
						<?php
						$start_time_object = BSF_Date_Time::get_tz_date_time_object( date( 'Y-m-d H:i:s' , $timestamp ) , false , true ) ;
						$end_time_object   = BSF_Date_Time::get_tz_date_time_object( date( 'Y-m-d H:i:s' , $timestamp ) , false , true )->modify( '+1 day' ) ;

						BSF_Calendar_Tab::list_bookings( date_timestamp_get( $start_time_object ) , date_timestamp_get( $end_time_object ) ) ;
						?>
					</td>
					<?php
					$index ++ ;
					if ( $index % 7 === 0 ) {
						echo '</tr><tr>' ;
					}

				endfor ;
				?>
			</tr>
		</tbody>
	</table>
</div>
<?php
