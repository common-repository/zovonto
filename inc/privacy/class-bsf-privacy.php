<?php
/*
 * GDPR Compliance
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

if ( ! class_exists( 'BSF_Privacy' ) ) :

	/**
	 * BSF_Privacy class
	 */
	class BSF_Privacy {

		/**
		 * BSF_Privacy constructor.
		 */
		public function __construct() {
			$this->init_hooks() ;
		}

		/**
		 * Register plugin
		 */
		public function init_hooks() {
			// This hook registers Booking System privacy content
			add_action( 'admin_init' , array( __CLASS__ , 'register_privacy_content' ) , 20 ) ;
		}

		/**
		 * Register Privacy Content
		 */
		public static function register_privacy_content() {
			if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
				return ;
			}

			$content = self::get_privacy_message() ;
			if ( $content ) {
				wp_add_privacy_policy_content( __( 'Zovonto' , 'zovonto' ) , $content ) ;
			}
		}

		/**
		 * Prepare Privacy Content
		 */
		public static function get_privacy_message() {

			$content = self::get_privacy_message_html() ;
			return $content ;
		}

		/**
		 * Get Privacy Content
		 */
		public static function get_privacy_message_html() {
			ob_start() ;
			?>
			<p><?php esc_html_e( 'This includes the basics of what personal data your store may be collecting, storing and sharing. Depending on what settings are enabled and which additional plugins are used, the specific information shared by your store will vary.' , 'zovonto' ); ?></p>
			<h2><?php esc_html_e( 'WHAT DOES THE PLUGIN DO?' , 'zovonto' ) ; ?></h2>
			<p><?php esc_html_e( '- Both Members(registered users) and guests can book Appointments on the site.' , 'zovonto' ) ; ?> </p>
			<p><?php esc_html_e( '- Emails can be sent to the users and Staff Members for actions such as Appointment Approved.' , 'zovonto' ) ; ?> </p>
			<h2><?php esc_html_e( 'WHAT WE COLLECT AND STORE?' , 'zovonto' ) ; ?></h2>
			<h4><?php esc_html_e( '- USER ID' , 'zovonto' ) ; ?></h4>
			<ul>
				<li>
					<?php esc_html_e( 'The User id is used for storing the Appointments made by the user.' , 'zovonto' ) ; ?>
				</li>
			</ul>
			<h4><?php esc_html_e( '- EMAIL ID' , 'zovonto' ) ; ?></h4>
			<ul>
				<li>
					<?php esc_html_e( 'The Email ID is collected for sending Appointment Email Notifications' , 'zovonto' ) ; ?>
				</li>
			</ul>
			<?php
			$contents = ob_get_contents() ;
			ob_end_clean() ;

			return $contents ;
		}

	}

	new BSF_Privacy() ;

endif;
