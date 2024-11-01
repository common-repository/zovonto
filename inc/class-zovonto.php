<?php

/*
 * Plugin Name: Zovonto Bookings and Appointments
 * Description: Zovonto is a free Appointment Booking Plugin which allows you to run an appointment booking system in your WordPress site.
 * Version: 1.6
 * Author: Flintop
 * Author URI: https://flintop.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: zovonto
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'Zovonto' ) ) {

	/**
	 * Main Class.
	 * */
	final class Zovonto {

		/**
		 * Version.
		 * 
		 * @var string
		 * */
		private $version = '1.6' ;

		/**
		 * WP Minimum Version.
		 * 
		 * @var string
		 * */
		public static $wp_minimum_version = '4.6' ;

		/**
		 * Locale.
		 * 
		 * @var string
		 * */
		private $locale = 'zovonto' ;

		/**
		 * Folder Name.
		 * 
		 * @var string
		 * */
		private $folder_name = 'zovonto' ;

		/**
		 * Payment Gateways.
		 * 
		 * @var array
		 * */
		protected $payment_gateways ;

		/**
		 * Notifications.
		 * 
		 * @var array
		 * */
		protected $notifications ;

		/**
		 * The single instance of the class.
		 * 
		 * @var object/null
		 * */
		protected static $_instance = null ;

		/**
		 * Load Zovonto Class in Single Instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self() ;
			}
			return self::$_instance ;
		}

		/* Cloning has been forbidden */

		public function __clone() {
			_doing_it_wrong( __FUNCTION__, 'You are not allowed to perform this action!!!', '1.0' ) ;
		}

		/**
		 * Unserialize the class data has been forbidden
		 * */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, 'You are not allowed to perform this action!!!', '1.0' ) ;
		}

		/**
		 * Constructor
		 * */
		public function __construct() {
			$this->define_constants() ;
			$this->include_files() ;
			$this->init_hooks() ;
		}

		/**
		 * Load the plugin current language MO file.
		 * */
		private function load_plugin_textdomain() {
			$locale = determine_locale() ;
			/**
			 * This hook is used to alter the plugin locale.
			 * 
			 * @since 1.0
			 */
			$locale = apply_filters( 'plugin_locale', $locale, BSF_LOCALE ) ;

			// Unload the text domain if other plugins/themes loaded the same text domain by mistake.
			unload_textdomain( BSF_LOCALE ) ;

			// Load the text domain from the "wp-content" languages folder. we have handles the plugin folder in languages folder for easily handle it.
			load_textdomain( BSF_LOCALE, WP_LANG_DIR . '/' . BSF_FOLDER_NAME . '/' . BSF_LOCALE . '-' . $locale . '.mo' ) ;

			// Load the text domain from the current plugin languages folder.
			load_plugin_textdomain( BSF_LOCALE, false, dirname( plugin_basename( BSF_PLUGIN_FILE ) ) . '/languages' ) ;
		}

		/**
		 * Prepare the constants value array.
		 * */
		private function define_constants() {

			$constant_array = array(
				'BSF_VERSION'        => $this->version,
				'BSF_LOCALE'         => $this->locale,
				'BSF_FOLDER_NAME'    => $this->folder_name,
				'BSF_ABSPATH'        => dirname( BSF_PLUGIN_FILE ) . '/',
				'BSF_ADMIN_URL'      => admin_url( 'admin.php' ),
				'BSF_ADMIN_AJAX_URL' => admin_url( 'admin-ajax.php' ),
				'BSF_PLUGIN_SLUG'    => plugin_basename( BSF_PLUGIN_FILE ),
				'BSF_PLUGIN_PATH'    => untrailingslashit( plugin_dir_path( BSF_PLUGIN_FILE ) ),
				'BSF_PLUGIN_URL'     => untrailingslashit( plugins_url( '/', BSF_PLUGIN_FILE ) ),
					) ;
			/**
			 * This hook is used to alter the define constants.
			 * 
			 * @since 1.0
			 */
			$constant_array = apply_filters( 'bsf_define_constants', $constant_array ) ;

			if ( is_array( $constant_array ) && ! empty( $constant_array ) ) {
				foreach ( $constant_array as $name => $value ) {
					$this->define_constant( $name, $value ) ;
				}
			}
		}

		/**
		 * Define the Constants value.
		 * */
		private function define_constant( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value ) ;
			}
		}

		/**
		 * Include required files
		 * */
		private function include_files() {

			//function
			include_once(BSF_ABSPATH . 'inc/bsf-common-functions.php') ;

			//class
			include_once(BSF_ABSPATH . 'inc/tables/class-bsf-tables-instances.php') ;
			include_once(BSF_ABSPATH . 'inc/notifications/class-bsf-notification-instances.php') ;

			include_once(BSF_ABSPATH . 'inc/class-bsf-install.php') ;
			include_once(BSF_ABSPATH . 'inc/class-bsf-query.php') ;
			include_once(BSF_ABSPATH . 'inc/class-bsf-datetime.php') ;
			include_once(BSF_ABSPATH . 'inc/class-bsf-session.php') ;
			include_once(BSF_ABSPATH . 'inc/class-bsf-slots.php') ;
			include_once(BSF_ABSPATH . 'inc/class-bsf-data-handler.php') ;
			include_once(BSF_ABSPATH . 'inc/privacy/class-bsf-privacy.php') ;
			include_once(BSF_ABSPATH . 'inc/abstracts/class-bsf-datastores.php') ;
			include_once(BSF_ABSPATH . 'inc/frontend/class-bsf-booking-form-handler.php') ;
			include_once(BSF_ABSPATH . 'inc/class-bsf-profile-data-handler.php') ;

			//Entity
			include_once(BSF_ABSPATH . 'inc/entity/class-bsf-holidays.php') ;
			include_once(BSF_ABSPATH . 'inc/entity/class-bsf-staff.php') ;
			include_once(BSF_ABSPATH . 'inc/entity/class-bsf-services.php') ;
			include_once(BSF_ABSPATH . 'inc/entity/class-bsf-appointments.php') ;
			include_once(BSF_ABSPATH . 'inc/entity/class-bsf-customers.php') ;
			include_once(BSF_ABSPATH . 'inc/entity/class-bsf-payments.php') ;
			include_once(BSF_ABSPATH . 'inc/entity/class-bsf-staff-services.php') ;
			include_once(BSF_ABSPATH . 'inc/entity/class-bsf-staff-working-hours.php') ;

			if ( is_admin() ) {
				$this->include_admin_files() ;
			}

			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
				$this->include_frontend_files() ;
			}
		}

		/**
		 * Include admin files
		 * */
		private function include_admin_files() {
			include_once(BSF_ABSPATH . 'inc/admin/class-bsf-admin-assets.php') ;
			include_once(BSF_ABSPATH . 'inc/admin/class-bsf-admin-ajax.php') ;
			include_once(BSF_ABSPATH . 'inc/admin/menu/class-bsf-menu-management.php') ;
		}

		/**
		 * Include frontend files
		 * */
		private function include_frontend_files() {
			include_once(BSF_ABSPATH . 'inc/frontend/class-bsf-lightbox.php') ;
			include_once(BSF_ABSPATH . 'inc/frontend/class-bsf-booking-data.php') ;
			include_once(BSF_ABSPATH . 'inc/frontend/class-bsf-booking-checkout.php') ;
			include_once(BSF_ABSPATH . 'inc/frontend/class-bsf-booking-form.php') ;
			include_once(BSF_ABSPATH . 'inc/frontend/class-bsf-frontend-assets.php') ;
		}

		/**
		 * Define the hooks 
		 * */
		private function init_hooks() {
			// Init the plugin.
			add_action( 'init', array( $this, 'init' ), 0 ) ;
			// Plugins loaded.
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) ) ;
			// Register the plugin.
			register_activation_hook( BSF_PLUGIN_FILE, array( 'BSF_Install', 'install' ) ) ;
		}

		/**
		 * Init.
		 * */
		public function init() {
			$this->load_plugin_textdomain() ;
		}

		/**
		 * Plugins Loaded
		 * */
		public function plugins_loaded() {
			/**
			 * This hook is used to do extra action before plugin loaded.
			 * 
			 * @since 1.0
			 */
			do_action( 'dey_before_plugin_loaded' ) ;

			$this->notifications    = BSF_Notification_Instances::get_notifications() ;
			$this->payment_gateways = include_once(BSF_ABSPATH . 'inc/payment-gateways/class-bsf-payment-gateways.php') ;
			/**
			 * This hook is used to do extra action after plugin loaded.
			 * 
			 * @since 1.0
			 */
			do_action( 'dey_after_plugin_loaded' ) ;
		}

		/**
		 * Payment Gateways instances
		 * */
		public function payment_gateways() {
			return $this->payment_gateways ;
		}

		/**
		 * Notifications instances
		 * */
		public function notifications() {
			return $this->notifications ;
		}

	}

}

