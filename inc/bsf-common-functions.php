<?php

/*
 * Common functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

include_once('bsf-layout-functions.php') ;
include_once('bsf-datastores-functions.php') ;
include_once('bsf-formatting-functions.php') ;
include_once('bsf-default-common-functions.php') ;

if ( ! function_exists( 'bsf_check_is_array' ) ) {

	/**
	 * Function to check given a variable is array and not empty
	 * */
	function bsf_check_is_array( $array ) {
		if ( is_array( $array ) && ! empty( $array ) ) {
			return true ;
		} else {
			return false ;
		}
	}

}

if ( ! function_exists( 'bsf_price' ) ) {

	function bsf_price( $price, $args = array() ) {
		$format = '%1$s%2$s' ;

		switch ( get_option( 'bsf_currency_position' , 'left' ) ) {
			case 'left':
				$format = '%1$s%2$s' ;
				break ;
			case 'right':
				$format = '%2$s%1$s' ;
				break ;
			case 'left_space':
				$format = '%1$s&nbsp;%2$s' ;
				break ;
			case 'right_space':
				$format = '%2$s&nbsp;%1$s' ;
				break ;
		}

		$args = apply_filters( 'bsf_price_args' , wp_parse_args( $args , array(
			'currency'           => '' ,
			'decimal_separator'  => stripslashes( get_option( 'bsf_currency_decimal_separator' , '.' ) ) ,
			'thousand_separator' => stripslashes( get_option( 'bsf_currency_thousand_separator' , ',' ) ) ,
			'decimals'           => absint( get_option( 'bsf_price_num_decimals' , '2' ) ) ,
			'price_format'       => $format ,
				) ) ) ;

		$unformatted_price = $price ;
		$negative          = $price < 0 ;
		$price             = floatval( $negative ? $price * -1 : $price ) ;
		$price             = number_format( $price , $args[ 'decimals' ] , $args[ 'decimal_separator' ] , $args[ 'thousand_separator' ] ) ;

		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $args[ 'price_format' ] , '<span class="bsf-Price-currencySymbol">' . get_bsf_currency_symbol( $args[ 'currency' ] ) . '</span>' , $price ) ;
		$return          = '<span class="bsf-Price-amount">' . $formatted_price . '</span>' ;

		return apply_filters( 'bsf_price' , $return , $price , $args , $unformatted_price ) ;
	}

}

if ( ! function_exists( 'bsf_get_allowed_setting_tabs' ) ) {

	function bsf_get_allowed_setting_tabs() {

		return apply_filters( 'bsf_settings_tabs_array' , array() ) ;
	}

}

if ( ! function_exists( 'bsf_get_time_slot_length' ) ) {

	function bsf_get_time_slot_length() {

		return ( int ) get_option( 'bsf_settings_time_slot_length' , 15 ) * MINUTE_IN_SECONDS ;
	}

}

if ( ! function_exists( 'bsf_get_drop_down_values' ) ) {

	/**
	 * Get dropdown values for calendar tab
	 */
	function bsf_get_drop_down_values( $index ) {
		global $wpdb ;
		switch ( $index ) {
			case 'range_type':
				$value = array(
					'1'         => 'Date Range' ,
					'2'         => 'Range of Month(s)' ,
					'3'         => 'Range of Week(s)' ,
					'4'         => 'Range of Day(s)' ,
					'5'         => 'Time Range(All Days)' ,
					'6'         => 'Date Range with Time' ,
					'Monday'    => 'Monday' ,
					'Tuesday'   => 'Tuesday' ,
					'Wednesday' => 'Wednesday' ,
					'Thursday'  => 'Thursday' ,
					'Friday'    => 'Friday' ,
					'Saturday'  => 'Saturday' ,
					'Sunday'    => 'Sunday'
						) ;
				break ;
			case 'days':
				$value = array(
					'0' => 'Sun' ,
					'1' => 'Mon' ,
					'2' => 'Tue' ,
					'3' => 'Wed' ,
					'4' => 'Thu' ,
					'5' => 'Fri' ,
					'6' => 'Sat' ,
						) ;
				break ;
			case 'months':
				$value = array(
					'1'  => 'January' ,
					'2'  => 'February' ,
					'3'  => 'March' ,
					'4'  => 'April' ,
					'5'  => 'May' ,
					'6'  => 'June' ,
					'7'  => 'July' ,
					'8'  => 'August' ,
					'9'  => 'September' ,
					'10' => 'October' ,
					'11' => 'November' ,
					'12' => 'December' ,
						) ;
				break ;
			case 'operator':
				$value = array(
					'1' => '+' ,
					'2' => '-' ,
					'3' => '*' ,
					'4' => '/' ,
						) ;
				break ;
			case 'weeks':
				for ( $i = 1 ; $i <= 52 ; $i ++ ) {
					$value[ $i ] = 'Week' . $i ;
				}
				break ;
			default:
				$value = '' ;
				break ;
		}
		return $value ;
	}

}
