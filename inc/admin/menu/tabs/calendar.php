<?php

/**
 * Calendar Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'BSF_Calendar_Tab' ) ) {
	return new BSF_Calendar_Tab() ;
}

/**
 * BSF_Calendar_Tab.
 */
class BSF_Calendar_Tab extends BSF_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'calendar' ;
		$this->code  = 'fa-calendar' ;
		$this->label = __( 'Calendar' , 'zovonto' ) ;

		add_action( $this->plugin_slug . '_admin_field_output_calendar' , array( $this , 'output_calendar' ) ) ;

		parent::__construct() ;
	}

	/**
	 * Argument
	 */
	private static $args = array() ;

	/**
	 * Get settings array.
	 */
	public function get_settings( $current_section = '' ) {

		return array(
			array( 'type' => 'output_calendar' )
				) ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the affiliates overview
	 */
	public function output_calendar() {
		global $current_section ;
		switch ( $current_section ) {
			case 'new':
				$this->display_new_page() ;
				break ;
			case 'edit':
				$this->display_edit_page() ;
				break ;
			default:
				$this->display_calendar() ;
				break ;
		}
	}

	/**
	 * Output the calendar
	 */
	public function display_calendar() {
		self::prepare_data() ;
		self::output_table() ;
	}

	/**
	 * Prepare calendar data
	 */
	public function prepare_data() {

		$view  = ( isset( $_REQUEST[ 'view' ] ) && sanitize_key( $_REQUEST[ 'view' ] ) == 'day' ) ? 'day' : 'month' ;
		$month = isset( $_REQUEST[ 'month' ] ) ? absint( $_REQUEST[ 'month' ] ) : date( 'n' ) ;
		$year  = isset( $_REQUEST[ 'year' ] ) ? absint( $_REQUEST[ 'year' ] ) : date( 'Y' ) ;
		$day   = isset( $_REQUEST[ 'day' ] ) ? is_scalar( $_REQUEST[ 'day' ] ) ? bsf_sanitize_text_field( $_REQUEST[ 'day' ] ) : $_REQUEST[ 'day' ] : date( 'Y-m-d' ) ;

		//get first day of month
		$start_date                 = strtotime( "$year-$month-01" ) ;
		$first_day_of_current_month = date( 'N' , $start_date ) ;
		//diff between first day and first day of week
		$start_of_week              = absint( get_option( 'start_of_week' , 1 ) ) ;
		$diff                       = $start_of_week - $first_day_of_current_month ;
		//get start time stamp and end timestamp
		$start_timestamp            = strtotime( $diff . ' days midnight' , $start_date ) ;
		$end_timestamp              = strtotime( '+35 days midnight -1 mins' , $start_timestamp ) ;

		$bookings = $this->get_bookings_data( $start_timestamp , $end_timestamp ) ;

		self::$args = array(
			'bookings'        => $bookings ,
			'month'           => $month ,
			'year'            => $year ,
			'day'             => $day ,
			'start_timestamp' => $start_timestamp ,
			'end_timestamp'   => $end_timestamp ,
				) ;
	}

	/*
	 * Get Appointments Data
	 */

	public function get_bookings_data( $start_timestamp, $end_timestamp ) {
		$appointments_table = BSF_Tables_Instances::get_table_by_id( 'appointments' )->get_table_name() ;
		$query              = new BSF_Query( $appointments_table ) ;
		$appointments       = $query->whereGte( 'start_date' , date( 'Y-m-d H:i:s' , $start_timestamp ) )->fetchArray() ;
		$appointments       = $query->whereLt( 'end_date' , date( 'Y-m-d H:i:s' , $end_timestamp ) )->fetchArray() ;

		return $appointments ;
	}

	/**
	 * Display Calendar
	 */
	public function output_table() {
		$args = self::$args ;
		extract( $args ) ;
		include BSF_PLUGIN_PATH . '/inc/admin/menu/views/calendar/month.php' ;
	}

	/**
	 * Display Action Bar
	 */
	public function display_action_bar() {
		$args = self::$args ;
		extract( $args ) ;

		include BSF_PLUGIN_PATH . '/inc/admin/menu/views/calendar/action.php' ;
	}

	/**
	 * List bookings for a day
	 */
	public function list_bookings( $date_start, $date_end ) {
		$args = self::$args ;

		if ( ! bsf_check_is_array( $args[ 'bookings' ] ) ) {
			return ;
		}

		ob_start() ;
		foreach ( $args[ 'bookings' ] as $booking ) {
			$appointment_obj = new BSF_Appointment( $booking[ 'id' ] ) ;
			if (
					( $appointment_obj->get_start_date() >= date( 'Y-m-d H:i:s' , $date_start ) && $appointment_obj->get_start_date() < date( 'Y-m-d H:i:s' , $date_end ) ) ||
					( $appointment_obj->get_start_date() < date( 'Y-m-d H:i:s' , $date_start ) && $appointment_obj->get_end_date() > date( 'Y-m-d H:i:s' , $date_end ) ) ||
					( $appointment_obj->get_end_date() > date( 'Y-m-d H:i:s' , $date_start ) && $appointment_obj->get_end_date() <= date( 'Y-m-d H:i:s' , $date_end ) )
			) {
				echo '<div class="bsf_bookings_each_booking" data-appointmentid="' . esc_attr( $appointment_obj->get_id() ) . '">'
				. '<a href="#">' ;
				echo '<strong>#' . $appointment_obj->get_id() . ' - ' ;
				if ( $product = $appointment_obj->get_services() ) {
					echo esc_attr($product->get_name()) ;
				}

				echo '</strong></a>' ;
				echo '</div>' ;
			}
		}

		$content = ob_get_clean() ;
		if ( empty( $content ) ) {
			return ;
		}

		$this->display_booking_lists( $content , $date_start ) ;
	}

	/**
	 * Display bookings for a day
	 */
	public function display_booking_lists( $content, $date_start ) {
		$overall_string_count = substr_count( $content , '</div>' ) ;

		if ( strlen( $content ) > 800 ) {
			echo '<div class="bsf_bookings_lists">' ;
			echo $content ;
			echo '</div>' ;
			// truncate string
			$string_cut            = substr( $content , 0 , 800 ) ;
			$endPoint              = strrpos( $string_cut , '</div>' ) ;
			$truncate_string       = $endPoint ? substr( $string_cut , 0 , $endPoint + 6 ) : substr( $string_cut , 0 ) ;
			$truncate_string_count = substr_count( $truncate_string , '</div>' ) ;
			$string_count          = $overall_string_count - $truncate_string_count ;
			$append_string         = '<p class="bsf_bookings_list" data-date="' . esc_attr( date( 'M-d' , $date_start ) ) . '">'
					. '<a href="#">+' . $string_count . ' ' . __( 'More' , 'zovonto' ) . ' </a>'
					. '</p>' ;
			echo '<div class="bsf_bookings">' ;
			echo $truncate_string . $append_string ;
			echo '</div>' ;
		} else {
			echo '<div class="bsf_bookings">' ;
			echo $content ;
			echo '</div>' ;
		}
	}

}

return new BSF_Calendar_Tab() ;
