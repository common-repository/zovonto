<?php

/**
 * Frontend Assets
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'BSF_Fronend_Assets' ) ) {

	/**
	 * Class.
	 */
	class BSF_Fronend_Assets {

		/**
		 * Class Initialization.
		 */
		public static function init() {

			add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) ) ;
			add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
		}

		/**
		 * Enqueue external css files
		 */
		public static function external_css_files() {

			wp_enqueue_style( 'font-awesome' , BSF_PLUGIN_URL . '/assets/css/font-awesome.min.css' , array() , BSF_VERSION ) ;

			$form_primary_color = get_option( 'bsf_settings_booking_form_primary_color' , '#000070' ) ;

			$contents = '.bsf_booking_prograss_bar ul.bsf_booking_steps li.active:before{
              background:' . $form_primary_color . '!important;
            }
            .bsf_booking_prograss_bar ul.bsf_booking_steps li.active:after{
              background:' . $form_primary_color . '!important;
            }
            .bsf-booking-form-container .bsf-booking-form-inner-container h2{
              border-bottom:1px dashed ' . $form_primary_color . ' !important;
            }
            .bsf-booking-form-container .bsf-booking-form-inner-container .bsf-booking-form-row
            .bsf-booking-form-row-checkbox input:checked + .bsf_slider {
              background-color: ' . $form_primary_color . ' !important;
              border: 1px solid ' . $form_primary_color . ' !important;
            }
            .bsf-booking-form-container .bsf-booking-form-inner-container .bsf-nav-steps button.bsf_booking_step_btn,
            .bsf-booking-form-inner-container .bsf_coupon_display button{
              background: ' . $form_primary_color . ' !important;
            }
            ' . get_option( 'bsf_settings_booking_custom_css' , '' ) ;

			// custom css 
			wp_register_style( 'bsf-frontend-custom-styles' , false ) ;
			wp_enqueue_style( 'bsf-frontend-custom-styles' ) ;
			wp_add_inline_style( 'bsf-frontend-custom-styles' , $contents ) ;
		}

		/**
		 * Enqueue external js files
		 */
		public static function external_js_files() {

			wp_register_script( 'blockUI' , BSF_PLUGIN_URL . '/assets/js/blockUI/jquery.blockUI.js' , array( 'jquery' , 'jquery-ui-datepicker' ) , '2.70.0' ) ;
		}

	}

	BSF_Fronend_Assets::init() ;
}
