<?php

/**
 * Data Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Data_Handler' ) ) {

	/**
	 * Class.
	 */
	class BSF_Data_Handler {

		/**
		 *  Prepare drop down values for services. 
		 */
		public static function get_dropdown_values() {

			//services 
			$services_options = array() ;

			$services_table = BSF_Tables_Instances::get_table_by_id( 'services' )->get_table_name() ;
			$services_query = new BSF_Query( $services_table , 's' ) ;

			$services_options = $services_query->select( '`s`.*' )
					->where( '`s`.status' , 'public' )
					->orderBy( '`s`.position' )
					->Limit( 5 )
					->fetchArray() ;

			return compact( 'services_options' ) ;
		}

		/**
		 *  Prepare days and times for Front End service step
		 */
		public static function get_service_days_times() {

			$start_time = null ;
			$end_time   = null ;
			$day_index  = array() ;

			$working_hour_table = BSF_Tables_Instances::get_table_by_id( 'staff_working_hours' )->get_table_name() ;
			$staff_table        = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;
			$query              = new BSF_Query( $working_hour_table , 'wt' ) ;

			$day_times = $query->select( '`wt`.`day_index`, MIN(`wt`.`start_time`) AS `start_time`, MAX(`wt`.`end_time`) AS `end_time`' )
					->leftJoin( $staff_table , 's' , '`s`.`id` = `wt`.`staff_id`' )
					->whereNot( '`wt`.start_time' , null )
					->where( '`s`.status' , 'public' )
					->groupBy( '`wt`.day_index' )
					->fetchArray() ;

			//prepare day_index , minimum start time and maximum end time
			foreach ( $day_times as $day_time ) {
				$day_index[] = $day_time[ 'day_index' ] ;

				$start_time = ( ! $start_time ) ? $day_time[ 'start_time' ] : $start_time ;
				$end_time   = ( ! $end_time ) ? $day_time[ 'end_time' ] : $end_time ;

				$start_time = ( $start_time > $day_time[ 'start_time' ] ) ? $day_time[ 'start_time' ] : $start_time ;
				$end_time   = ( $end_time < $day_time[ 'end_time' ] ) ? $day_time[ 'end_time' ] : $end_time ;
			}

			$days  = self::get_service_days( $day_index ) ;
			$times = self::get_service_times( $start_time , $end_time ) ;

			return array( $times , $days ) ;
		}

		/**
		 *  get service days
		 */
		public static function get_service_days( $day_index ) {
			global $wp_locale ;
			$days          = array() ;
			$start_of_week = ( int ) get_option( 'start_of_week' ) ;
			$week_days     = array_values( $wp_locale->weekday_abbrev ) ;
			for ( $i = 0 ; $i < 7 ; $i ++ ) {
				$r = ( $i + $start_of_week ) % 7 ;
				if ( ! in_array( $r , $day_index ) ) {
					continue ;
				}

				$days[ $r ] = $week_days[ $r ] ;
			}

			return $days ;
		}

		/**
		 *  get Service times
		 */
		public static function get_service_times( $start_time, $end_time ) {
			$times              = array() ;
			$time_slot_length   = bsf_get_time_slot_length() ;
			$start_time_seconds = BSF_Date_Time::time_to_seconds( $start_time ) ;
			$end_time_seconds   = BSF_Date_Time::time_to_seconds( $end_time ) ;

			while ( $start_time_seconds <= $end_time_seconds ) {
				$value              = BSF_Date_Time::seconds_to_time( $start_time_seconds , false ) ;
				$times[ $value ]    = BSF_Date_Time::format_time( $start_time_seconds ) ;
				$start_time_seconds += $time_slot_length ;
			}

			return $times ;
		}

	}

}
