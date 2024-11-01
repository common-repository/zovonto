<?php

/**
 * Date Time
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'BSF_Date_Time' ) ) {

	/**
	 * Class.
	 */
	class BSF_Date_Time {
		/*
		 * Wordpress Timezone
		 */

		private static $wp_timezone ;

		/*
		 * Week Days
		 */
		private static $week_days = array(
			0 => 'Sunday' ,
			1 => 'Monday' ,
			2 => 'Tuesday' ,
			3 => 'Wednesday' ,
			4 => 'Thursday' ,
			5 => 'Friday' ,
			6 => 'Saturday' ,
				) ;

		/**
		 *  Get WordPress TimeZone
		 */
		public static function get_wp_timezone() {
			if ( static::$wp_timezone ) {
				return static::$wp_timezone ;
			}

			if ( ! ( static::$wp_timezone = get_option( 'timezone_string' ) ) ) {
				$gmt_offset          = get_option( 'gmt_offset' ) ;
				static::$wp_timezone = sprintf( '%s%02d:%02d' , $gmt_offset >= 0 ? '+' : '-' , abs( $gmt_offset ) , abs( $gmt_offset ) * 60 % 60 ) ;
			}

			return static::$wp_timezone ;
		}

		/**
		 *  Create Date/Time Object TimeZone
		 */
		public static function get_tz_date_time_object( $date, $tz = false, $utc = false ) {

			if ( ! $tz ) {
				$tz = self::get_wp_timezone() ;
			}

			$date_object = date_create( $date , timezone_open( $tz ) ) ;

			if ( $utc ) {
				$date_object->setTimezone( timezone_open( 'UTC' ) ) ;
			}

			return $date_object ;
		}

		/**
		 *  Create Date/Time Object
		 */
		public static function get_date_time_object( $date, $wp_zone = true ) {
			$date_object = date_create( $date ) ;

			if ( $wp_zone ) {
				$date_object->setTimezone( timezone_open( self::get_wp_timezone() ) ) ;
			}

			return $date_object ;
		}

		/**
		 * Format date time based on WordPress
		 */
		public static function get_date_object_format_datetime( $date, $format = false, $wp_zone = true, $separator = ' ' ) {

			$date_object = self::get_date_time_object( $date , $wp_zone ) ;

			switch ( $format ) {
				case 'date':
					return $date_object->format( get_option( 'date_format' ) ) ;
					break ;
				case 'time':
					return $date_object->format( get_option( 'time_format' ) ) ;
					break ;
				default:
					return $date_object->format( get_option( 'date_format' ) . $separator . get_option( 'time_format' ) ) ;
					break ;
			}
		}

		/**
		 *  Format date
		 */
		public static function format_date( $date ) {
			return date_i18n( get_option( 'date_format' ) , $date ) ;
		}

		/**
		 *  Format time
		 */
		public static function format_time( $time ) {
			return date_i18n( get_option( 'time_format' ) , $time ) ;
		}

		/**
		 *  Format date time
		 */
		public static function format_datetime( $strtotime, $separator = ' ' ) {
			return self::format_date( $strtotime ) . $separator . self::format_time( $strtotime ) ;
		}

		/**
		 *  Get week day by number
		 */
		public static function get_week_day_by_number( $number ) {
			return isset( self::$week_days[ $number ] ) ? self::$week_days[ $number ] : '' ;
		}

		/**
		 *  Convert seconds to Time
		 */
		public static function seconds_to_time( $seconds, $show_seconds = true ) {
			$hours   = ( int ) ( $seconds / 3600 ) ;
			$seconds -= ( ( int ) $hours ) * 3600 ;
			$minutes = ( int ) ( $seconds / 60 ) ;
			$seconds -= ( ( int ) $minutes ) * 60 ;

			return $show_seconds ? sprintf( '%02d:%02d:%02d' , $hours , $minutes , $seconds ) : sprintf( '%02d:%02d' , $hours , $minutes ) ;
		}

		/**
		 *  Convert seconds to Time
		 */
		public static function seconds_to_time_format( $seconds, $format = false ) {
			$strtotime = strtotime( 'today midnight' ) + $seconds ;

			if ( ! $format ) {
				$format = 'h:i A' ;
			}

			return date( $format , $strtotime ) ;
		}

		/**
		 * Convert time to seconds.
		 */
		public static function time_to_seconds( $time ) {
			$converted_seconds = 0 ;
			$seconds           = 3600 ;
			$time_array        = explode( ':' , $time ) ;

			foreach ( $time_array as $part ) {
				$converted_seconds += ( int ) $part * $seconds ;
				$seconds           /= 60 ;
			}

			return $converted_seconds ;
		}

		/**
		 *  Convert seconds into string
		 */
		public static function seconds_to_string( $duration ) {
			$duration         = ( int ) $duration ;
			$month_in_seconds = 30 * DAY_IN_SECONDS ;
			$years            = ( int ) ( $duration / YEAR_IN_SECONDS ) ;
			$months           = ( int ) ( ( $duration % YEAR_IN_SECONDS ) / $month_in_seconds ) ;
			$weeks            = ( int ) ( ( ( $duration % YEAR_IN_SECONDS ) % $month_in_seconds ) / WEEK_IN_SECONDS ) ;
			$days             = ( int ) ( ( ( ( $duration % YEAR_IN_SECONDS ) % $month_in_seconds ) % WEEK_IN_SECONDS ) / DAY_IN_SECONDS ) ;
			$hours            = ( int ) ( ( ( ( $duration % YEAR_IN_SECONDS ) % $month_in_seconds ) % DAY_IN_SECONDS ) / HOUR_IN_SECONDS ) ;
			$minutes          = ( int ) ( ( ( ( $duration % YEAR_IN_SECONDS ) % $month_in_seconds ) % HOUR_IN_SECONDS ) / MINUTE_IN_SECONDS ) ;

			$parts = array() ;

			if ( $years > 0 ) {
				$parts[] = esc_html( sprintf( _n( '%d year' , '%d years' , $years , 'zovonto' ) , $years ) ) ;
			}
			if ( $months > 0 ) {
				$parts[] = esc_html( sprintf( _n( '%d month' , '%d months' , $months , 'zovonto' ) , $months ) ) ;
			}
			if ( $weeks > 0 ) {
				$parts[] = esc_html( sprintf( _n( '%d week' , '%d weeks' , $weeks , 'zovonto' ) , $weeks ) ) ;
			}
			if ( $days > 0 ) {
				$parts[] = esc_html( sprintf( _n( '%d day' , '%d days' , $days , 'zovonto' ) , $days ) ) ;
			}
			if ( $hours > 0 ) {
				$parts[] = esc_html( sprintf( __( '%d h' , 'zovonto' ) , $hours ) ) ;
			}
			if ( $minutes > 0 ) {
				$parts[] = esc_html( sprintf( __( '%d min' , 'zovonto' ) , $minutes ) ) ;
			}

			return implode( ' ' , $parts ) ;
		}

		/**
		 * Convert WordPress date and time format into requested JS format.
		 */
		public static function convert_wp_to_js_format( $source_format ) {
			switch ( $source_format ) {
				case 'date':
					$php_format = get_option( 'date_format' , 'Y-m-d' ) ;
					break ;
				case 'time':
					$php_format = get_option( 'time_format' , 'H:i' ) ;
					break ;
				case 'datetime':
					$php_format = get_option( 'date_format' , 'Y-m-d' ) . ' ' . get_option( 'time_format' , 'H:i' ) ;
					break ;
				default:
					$php_format = $source_format ;
			}

			$replacements = array(
				// Day
				'd'  => 'dd' , '\d' => '\'d\'' ,
				'j'  => 'd' , '\j' => 'j' ,
				'l'  => 'DD' , '\l' => 'l' ,
				'D'  => 'D' , '\D' => '\'D\'' ,
				'z'  => 'o' , '\z' => 'z' ,
				// Month
				'm'  => 'mm' , '\m' => '\'m\'' ,
				'n'  => 'm' , '\n' => 'n' ,
				'F'  => 'MM' , '\F' => 'F' ,
				// Year
				'Y'  => 'yy' , '\Y' => 'Y' ,
				'y'  => 'y' , '\y' => '\'y\'' ,
				// Others
				'S'  => '' , '\S' => 'S' ,
				'o'  => 'yy' , '\o' => '\'o\'' ,
				'\\' => '' ,
					) ;

			return str_replace( '\'\'' , '' , strtr( $php_format , $replacements ) ) ;
		}

	}

}
