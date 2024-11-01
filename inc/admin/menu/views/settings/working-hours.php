<?php
/**
 * Working Hours
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
$start_of_week = ( int ) get_option( 'start_of_week' ) ;
?>
<div class="bsf_working_hours_wrap">
	<p><?php esc_html_e( 'These Working Hours will be used as the default Work timings of a Staff. If you want to customize the work timings of individual staff, then you can do so by customizing the same in Staff Settings.' , 'zovonto' ) ; ?></p>
	<?php
	for ( $i = 0 ; $i < 7 ; $i ++ ) {

		$day         = BSF_Date_Time::get_week_day_by_number( ( $i + $start_of_week ) % 7 ) ;
		$option_name = 'bsf_working_hours_' . strtolower( $day ) ;
		?>
		<div class="bsf_working_hours_rows">
			<h3><?php echo esc_html( $day ) ; ?></h3>
			<div class="bsf_working_hours_select">
				<?php echo bsf_get_working_hours_select_html( array( 'option_name' => $option_name . '_start' ) ); ?>
			</div>
			<div class="bsf_working_hours_label"><span><?php esc_html_e( 'to' , 'zovonto' ) ; ?></span></div>
			<div class="bsf_working_hours_select"> 
				<?php echo bsf_get_working_hours_select_html( array( 'option_name' => $option_name . '_end' , 'is_start' => false ) ); ?>
			</div>
		</div>
	<?php } ?>
</div>
<?php
