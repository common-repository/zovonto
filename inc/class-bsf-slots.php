<?php

/*
 * Slots
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Slots' ) ) {

	/**
	 * BSF_Slots Class.
	 */
	class BSF_Slots {

		/**
		 * WordPress Start Date
		 */
		protected $wp_start_date ;

		/**
		 * WordPress End Date
		 */
		protected $wp_end_date ;

		/**
		 * Start Date
		 */
		protected $start_date ;

		/**
		 * End Date
		 */
		protected $end_date ;

		/**
		 * Max Date
		 */
		protected $max_date ;

		/**
		 * Booking Data
		 */
		public $booking_data ;

		/**
		 * Slots data
		 */
		protected $slots_data = array() ;

		/**
		 * Slot Length
		 */
		protected $slot_length ;

		/**
		 * Display Booking Slots
		 */
		protected $display_booking_slots ;

		/**
		 * Service
		 */
		protected $service ;

		/**
		 * Staff
		 */
		protected $staff ;

		/**
		 * Holidays
		 */
		protected $holidays = array() ;

		/**
		 * Appointments
		 */
		protected $appointments = array() ;

		/**
		 * Working hours
		 */
		protected $working_hours = array() ;

		/**
		 * Class Initialization
		 */
		public function __construct( $booking_data ) {
			$this->booking_data          = $booking_data ;
			$this->max_date              = ( int ) get_option( 'bsf_settings_advanced_booking_period' , 30 ) ;
			$this->display_booking_slots = 'no' ;

			$this->prpeare_data() ;
			$this->prepare_slots() ;
		}

		/*
		 * Prepare Data
		 */

		protected function prpeare_data() {
			$this->service     = $this->booking_data->get_service() ;
			$this->staff       = $this->booking_data->get_staff() ;
			$this->slot_length = $this->get_slot_duration() ;

			//WordPress Time Zone
			$this->wp_start_date = BSF_Date_Time::get_tz_date_time_object( $this->booking_data->get_from_date() ) ;
			$this->wp_end_date   = BSF_Date_Time::get_tz_date_time_object( $this->booking_data->get_from_date() )->modify( '+' . $this->max_date . 'days' ) ;

			//Default Time Zone
			$this->start_date = BSF_Date_Time::get_tz_date_time_object( $this->booking_data->get_from_date() , false , true ) ;
			$this->end_date   = BSF_Date_Time::get_tz_date_time_object( $this->booking_data->get_from_date() , false , true )->modify( '+' . $this->max_date . 'days' ) ;

			$this->prepare_holidays() ;
			$this->prepare_appointments() ;
			$this->prepare_working_hours() ;

			do_action( 'bsf_additional_slot_data_prepare' , $this ) ;
		}

		/*
		 * Prepare Holidays
		 */

		protected function prepare_holidays() {
			$table = BSF_Tables_Instances::get_table_by_id( 'holidays' )->get_table_name() ;
			$query = new BSF_Query( $table , 'h' ) ;

			$holidays = $query->where( 'h.staff_id' , $this->booking_data->get_staff_id() )
							->whereGt( 'h.date' , $this->start_date->format( 'Y-m-d' ) )->where( 'h.repeat' , 1 , 'OR' )->fetchArray() ;

			foreach ( $holidays as $holiday ) {
				$this->holidays[ $holiday[ 'date' ] ] = $holiday[ 'repeat' ] ;
			}
		}

		/*
		 * Prepare Appointments
		 */

		protected function prepare_appointments() {
			$table = BSF_Tables_Instances::get_table_by_id( 'appointments' )->get_table_name() ;
			$query = new BSF_Query( $table , 'a' ) ;

			$appointments = $query->where( 'a.staff_id' , $this->booking_data->get_staff_id() )
					->whereGte( 'a.start_date' , $this->start_date->format( 'Y-m-d H:i:s' ) )
					->whereLt( 'a.end_date' , $this->end_date->format( 'Y-m-d H:i:s' ) )
					->where( 'a.status' , 'approved' )
					->fetchArray() ;

			foreach ( $appointments as $appointment ) {

				$this->appointments[ $appointment[ 'start_date' ] ] = $appointment ;
			}
		}

		/*
		 * Prepare Working Hours
		 */

		protected function prepare_working_hours() {
			$table = BSF_Tables_Instances::get_table_by_id( 'staff_working_hours' )->get_table_name() ;
			$query = new BSF_Query( $table , 'whb' ) ;

			$working_hours = $query->where( 'whb.staff_id' , $this->booking_data->get_staff_id() )
					->whereNot( 'whb.start_time' , null )
					->whereIN( 'whb.day_index' , $this->booking_data->get_week_days() )
					->fetchArray() ;

			foreach ( $working_hours as $working_hour ) {
				$this->working_hours[ $working_hour[ 'day_index' ] ] = $working_hour ;
			}
		}

		/**
		 *  get slot duration
		 */
		protected function get_slot_duration() {

			switch ( $this->service->get_slot_duration() ) {
				case 'default':
					return bsf_get_time_slot_length() ;
					break ;
				case 'slot' ;
					return $this->service->get_duration() ;
					break ;
			}

			return $this->service->get_slot_duration() * MINUTE_IN_SECONDS ;
		}

		/**
		 * get slots
		 */
		public static function get_slots( $booking_data ) {
			$object = new self( $booking_data ) ;

			return $object->slots_data ;
		}

		/**
		 * slots
		 */
		protected function prepare_slots() {

			//convert time format to seconds
			$selected_start_ts    = BSF_Date_Time::time_to_seconds( $this->booking_data->get_from_time() ) ;
			$selected_end_ts      = BSF_Date_Time::time_to_seconds( $this->booking_data->get_to_time() ) ;
			$start_date_object    = $this->start_date ;
			$wp_start_date_object = $this->wp_start_date ;
			$current_time         = BSF_Date_Time::get_tz_date_time_object( 'now' , false , true ) ;

			while ( $start_date_object < $this->end_date ) {
				$week_day   = $wp_start_date_object->format( 'w' ) ;
				$start_date = $wp_start_date_object->format( 'Y-m-d' ) ;

				//check current date is holiday
				if ( array_key_exists( $start_date , $this->holidays ) ) {
					$start_date_object->modify( '+1 days' ) ;
					$wp_start_date_object->modify( '+1 days' ) ;
					continue ;
				}

				// check current day is not working day
				if ( ! array_key_exists( $week_day , $this->working_hours ) ) {
					$start_date_object->modify( '+1 days' ) ;
					$wp_start_date_object->modify( '+1 days' ) ;
					continue ;
				}

				$staff_start_ts = BSF_Date_Time::time_to_seconds( $this->working_hours[ $week_day ][ 'start_time' ] ) ;
				$staff_end_ts   = BSF_Date_Time::time_to_seconds( $this->working_hours[ $week_day ][ 'end_time' ] ) ;

				$start_ts = ( $staff_start_ts < $selected_start_ts ) ? $selected_start_ts : $staff_start_ts ;
				$end_ts   = ( $staff_end_ts > $selected_end_ts ) ? $selected_end_ts : $staff_end_ts ;

				list($start_ts , $end_ts) = apply_filters( 'bsf_get_slot_time' , array( $start_ts , $end_ts ) , $start_ts , $end_ts , $start_date , $week_day , $this ) ;

				if ( $start_ts > $end_ts ) {
					$start_date_object->modify( '+1 days' ) ;
					$wp_start_date_object->modify( '+1 days' ) ;
					continue ;
				}

				$slots = array() ;

				//prepare slots
				while ( $start_ts < $end_ts ) {
					$group    = array() ;
					$duration = $start_ts + $this->booking_data->get_booking_duration() ;
					$time     = BSF_Date_Time::seconds_to_time( $start_ts ) ;
					$date     = clone $start_date_object ;
					$date->modify( '+' . $start_ts . ' seconds' ) ;

					//Check time is greater than current time
					//Check booking duration not exists end time
					if ( $current_time > $date || $duration > $end_ts ) {
						$start_ts += $this->slot_length ;
						continue ;
					}

					$group[ 'booked' ] = self::check_appointment_duration( $date ) ;

					//Need to show booking slots
					if ( $this->display_booking_slots != 'yes' && $group[ 'booked' ] ) {
						$start_ts += $this->slot_length ;
						continue ;
					}

					$group[ 'data' ] = array(
						$this->booking_data->get_service_id() ,
						$this->booking_data->get_staff_id() ,
						$date->format( 'Y-m-d H:i:s' ) ,
						0
							) ;

					$group[ 'text' ] = BSF_Date_Time::seconds_to_time_format( $start_ts ) ;
					$slots[]         = $group ;
					$start_ts        += $this->slot_length ;
				}

				if ( ! bsf_check_is_array( $slots ) ) {
					$start_date_object->modify( '+1 days' ) ;
					$wp_start_date_object->modify( '+1 days' ) ;
					continue ;
				}

				$this->slots_data[ $start_date ] = array(
					'title' => $wp_start_date_object->format( get_option( 'date_format' ) ) ,
					'slots' => $slots
						) ;

				$start_date_object->modify( '+1 days' ) ;
				$wp_start_date_object->modify( '+1 days' ) ;
			}
		}

		/**
		 *  Check current time is already Booked
		 */
		protected function check_appointment_duration( $date ) {
			$date_object = clone $date ;
			$date_object->modify( '+' . $this->booking_data->get_booking_duration() . ' seconds' ) ;

			foreach ( $this->appointments as $appointment ) {

				if ( ( $appointment[ 'start_date' ] < $date_object->format( 'Y-m-d H:i:s' ) ) && ( $appointment[ 'end_date' ] > $date->format( 'Y-m-d H:i:s' ) ) ) {
					return apply_filters( 'bsf_validate_booked_slots' , true , $date , $this->booking_data , $appointment ) ;
				}
			}

			return false ;
		}

		/**
		 * Set WordPress Start date
		 */
		public function set_wp_start_date( $value ) {

			return $this->wp_start_date = $value ;
		}

		/**
		 * Set WordPress End date
		 */
		public function set_wp_end_date( $value ) {

			return $this->wp_start_date = $value ;
		}

		/**
		 * Set Start date
		 */
		public function set_start_date( $value ) {

			return $this->start_date = $value ;
		}

		/**
		 * Set End date
		 */
		public function set_end_date( $value ) {

			return $this->end_date = $value ;
		}

		/**
		 * Set Max date
		 */
		public function set_max_date( $value ) {

			return $this->max_date = $value ;
		}

		/**
		 * Set Booking data
		 */
		public function set_booking_data( $value ) {

			return $this->booking_data = $value ;
		}

		/**
		 * Set Slot data
		 */
		public function set_slots_data( $value ) {

			return $this->slots_data = $value ;
		}

		/**
		 * Set Slot length
		 */
		public function set_slot_length( $value ) {

			return $this->slot_length = $value ;
		}

		/**
		 * Set display booking slots
		 */
		public function set_display_booking_slots( $value ) {

			return $this->display_booking_slots = $value ;
		}

		/**
		 * Set Service
		 */
		public function set_service( $value ) {

			return $this->service = $value ;
		}

		/**
		 * Set Staff
		 */
		public function set_staff( $value ) {

			return $this->staff = $value ;
		}

		/**
		 * Set holidays
		 */
		public function set_holidays( $value ) {

			return $this->holidays = $value ;
		}

		/**
		 * Set Appointments
		 */
		public function set_appointments( $value ) {

			return $this->appointments = $value ;
		}

		/**
		 * Set Working Hours
		 */
		public function set_working_hours( $value ) {

			return $this->working_hours = $value ;
		}

		/**
		 * Set Breaks
		 */
		public function set_breaks( $value ) {

			return $this->breaks = $value ;
		}

		/**
		 * Get WordPress Start date
		 */
		public function get_wp_start_date() {

			return $this->wp_start_date ;
		}

		/**
		 * Get WordPress End date
		 */
		public function get_wp_end_date() {

			return $this->wp_end_date ;
		}

		/**
		 * Get Start date
		 */
		public function get_start_date() {

			return $this->start_date ;
		}

		/**
		 * Get End date
		 */
		public function get_end_date() {

			return $this->end_date ;
		}

		/**
		 * Get Max date
		 */
		public function get_max_date() {

			return $this->max_date ;
		}

		/**
		 * Get Booking Data
		 */
		public function get_booking_data() {

			return $this->booking_data ;
		}

		/**
		 * Get Slot data
		 */
		public function get_slots_data() {

			return $this->slots_data ;
		}

		/**
		 * Get Slot length
		 */
		public function get_slot_length() {

			return $this->slot_length ;
		}

		/**
		 * Get display booking slots
		 */
		public function get_display_booking_slots() {

			return $this->display_booking_slots ;
		}

		/**
		 * Get Service
		 */
		public function get_service() {

			return $this->service ;
		}

		/**
		 * Get Staff
		 */
		public function get_staff() {

			return $this->staff ;
		}

		/**
		 * Get holidays
		 */
		public function get_holidays() {

			return $this->holidays ;
		}

		/**
		 * get Appointments
		 */
		public function get_appointments() {

			return $this->appointments ;
		}

		/**
		 * Get Working Hours
		 */
		public function get_working_hours() {

			return $this->working_hours ;
		}

		/**
		 * Get Breaks
		 */
		public function get_breaks() {

			return $this->breaks ;
		}

	}

}
