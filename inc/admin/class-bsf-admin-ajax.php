<?php
/*
 * Admin Ajax
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'BSF_Admin_Ajax' ) ) {

	/**
	 * BSF_Admin_Ajax Class
	 */
	class BSF_Admin_Ajax {

		/**
		 * BSF_Admin_Ajax Class initialization
		 */
		public static function init() {

			$actions = array(
				'update_holidays'              => false ,
				'toggle_notifications'         => false ,
				'staff_selection_popup'        => false ,
				'user_search'                  => false ,
				'customers_search'             => false ,
				'services_search'              => false ,
				'staff_search'                 => false ,
				'add_staff'                    => false ,
				'delete_staff'                 => false ,
				'add_service'                  => false ,
				'save_service'                 => false ,
				'delete_services'              => false ,
				'update_position_for_services' => false ,
				'render_service'               => true ,
				'render_time'                  => true ,
				'render_details'               => true ,
				'render_payment'               => true ,
				'render_complete'              => true ,
				'save_service_form_session'    => true ,
				'save_time_form_session'       => true ,
				'save_details_form_session'    => true ,
				'process_checkout'             => true ,
				'display_calendar_details'     => false ,
					) ;

			foreach ( $actions as $action => $nopriv ) {
				add_action( 'wp_ajax_bsf_' . $action , array( __CLASS__ , $action ) ) ;

				if ( $nopriv ) {
					add_action( 'wp_ajax_nopriv_bsf_' . $action , array( __CLASS__ , $action ) ) ;
				}
			}
		}

		/**
		 * Update Holidays
		 */
		public static function update_holidays() {
			check_ajax_referer( 'bsf-holiday-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'day' ] ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$id      = isset( $_POST[ 'id' ] ) ? absint( $_POST[ 'id' ] ) : '' ;
				$holiday = bsf_sanitize_text_field( $_POST[ 'holiday' ] ) ;
				$repeat  = bsf_sanitize_text_field( $_POST[ 'repeat' ] ) ;
				$day     = bsf_sanitize_text_field( $_POST[ 'day' ] ) ;

				if ( $id ) {
					if ( $holiday == 'true' ) {
						bsf_update_holiday( $id , array( 'repeat' => ( int ) ( $repeat == 'true' ) ) ) ;
					} elseif ( $holiday == 'false' ) {
						bsf_delete_holiday( $id ) ;
					}
				} else {

					if ( $holiday && $day ) {
						$data = array( 'staff_id' => absint( $_POST[ 'staff_id' ] ) ,
							'repeat'   => ( int ) ( $repeat == 'true' ) ,
							'date'     => $day
								) ;

						bsf_create_new_holiday( $data ) ;
					}
				}

				wp_send_json_success( array( 'events' => bsf_get_holiday_events( absint( $_POST[ 'staff_id' ] ) ) ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( $ex->getMessage() ) ;
			}
		}

		/**
		 * Toggle notifications
		 */
		public static function toggle_notifications() {
			check_ajax_referer( 'bsf-notification-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'notification_name' ] ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$notification_object = BSF_Notification_Instances::get_notification_by_id( sanitize_key( $_REQUEST[ 'notification_name' ] ) ) ;

				if ( is_object( $notification_object ) ) {
					$value = ( bsf_sanitize_text_field( $_REQUEST[ 'enabled' ] ) == 'true' ) ? 'yes' : 'no' ;
					$notification_object->update_option( 'enabled' , $value ) ;
				}

				wp_send_json_success() ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * User search
		 */
		public static function user_search() {
			check_ajax_referer( 'bsf-search-nonce' , 'bsf_security' ) ;

			try {
				$term = isset( $_GET[ 'term' ] ) ? ( string ) wp_unslash( $_GET[ 'term' ] ) : '' ;

				if ( empty( $term ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$listofusers = array() ;
				$number      = ( strlen( $term ) > 3 ) ? '' : '20' ;

				$args           = array(
					'meta_key'     => 'bsf_staff_enabled' ,
					'meta_compare' => 'NOT EXISTS' ,
					'search'       => '*' . esc_attr( $term ) . '*' ,
					'number'       => $number ,
					'fields'       => 'all' ,
						) ;
				$search_results = get_users( $args ) ;

				if ( bsf_check_is_array( $search_results ) ) {
					foreach ( $search_results as $user ) {
						if ( ! is_object( $user ) ) {
							continue ;
						}

						$listofusers[ $user->ID ] = esc_html( $user->display_name . '(#' . absint( $user->ID ) . ' &ndash; ' . $user->user_email . ')' ) ;
					}
				}

				wp_send_json( $listofusers ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
			}
		}

		/**
		 * Service search
		 */
		public static function services_search() {
			check_ajax_referer( 'bsf-search-nonce' , 'bsf_security' ) ;

			try {
				$term = isset( $_GET[ 'term' ] ) ? ( string ) wp_unslash( $_GET[ 'term' ] ) : '' ;

				if ( empty( $term ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$listofservices = array() ;

				$service_table = BSF_Tables_Instances::get_table_by_id( 'services' )->get_table_name() ;
				$query         = new BSF_Query( $service_table ) ;
				$services      = $query->whereLike( 'name' , '%' . $term . '%' )->fetchArray() ;

				if ( bsf_check_is_array( $services ) ) {
					foreach ( $services as $service ) {
						if ( ! bsf_check_is_array( $service ) ) {
							continue ;
						}

						$listofservices[ $service[ 'id' ] ] = esc_html( $service[ 'name' ] . '(#' . absint( $service[ 'id' ] ) . ')' ) ;
					}
				}

				wp_send_json( $listofservices ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
			}
		}

		/**
		 * Customer search
		 */
		public static function customers_search() {
			check_ajax_referer( 'bsf-search-nonce' , 'bsf_security' ) ;

			try {
				$term = isset( $_GET[ 'term' ] ) ? ( string ) wp_unslash( $_GET[ 'term' ] ) : '' ;

				if ( empty( $term ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$listofcustomers = array() ;

				$customer_table = BSF_Tables_Instances::get_table_by_id( 'customers' )->get_table_name() ;
				$query          = new BSF_Query( $customer_table ) ;
				$customers      = $query->whereLike( 'first_name' , '%' . $term . '%' , 'OR' )->fetchArray() ;
				$customers      = $query->whereLike( 'last_name' , '%' . $term . '%' , 'OR' )->fetchArray() ;
				$customers      = $query->whereLike( 'email' , '%' . $term . '%' )->fetchArray() ;

				if ( bsf_check_is_array( $customers ) ) {
					foreach ( $customers as $customer ) {
						if ( ! bsf_check_is_array( $customer ) ) {
							continue ;
						}

						$listofcustomers[ $customer[ 'id' ] ] = esc_html( $customer[ 'first_name' ] . ' &ndash; ' . $customer[ 'last_name' ] . '(#' . absint( $customer[ 'id' ] ) . ' &ndash; ' . $customer[ 'email' ] . ')' ) ;
					}
				}

				wp_send_json( $listofcustomers ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
			}
		}

		/**
		 * Staff search
		 */
		public static function staff_search() {
			check_ajax_referer( 'bsf-search-nonce' , 'bsf_security' ) ;

			try {
				$term = isset( $_GET[ 'term' ] ) ? ( string ) wp_unslash( $_GET[ 'term' ] ) : '' ;

				if ( empty( $term ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$listofusers = array() ;

				$staff_table = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;
				$query       = new BSF_Query( $staff_table ) ;
				$staffs      = $query->whereLike( 'name' , '%' . $term . '%' )->fetchArray() ;

				if ( bsf_check_is_array( $staffs ) ) {
					foreach ( $staffs as $staff ) {
						if ( ! bsf_check_is_array( $staff ) ) {
							continue ;
						}

						$listofusers[ $staff[ 'id' ] ] = esc_html( $staff[ 'name' ] . '(#' . absint( $staff[ 'id' ] ) . ' &ndash; ' . $staff[ 'email' ] . ')' ) ;
					}
				}

				wp_send_json( $listofusers ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $e->getMessage() ) ) ;
			}
		}

		/**
		 * Update Position for Services List 
		 */
		public static function update_position_for_services() {
			check_ajax_referer( 'bsf-services-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'serviceid' ] ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$service_ids = bsf_sanitize_text_field( $_REQUEST[ 'serviceid' ] ) ;

				foreach ( $service_ids as $position => $service_id ) {
					bsf_update_service( $service_id , array( 'position' => $position ) ) ;
				}

				wp_send_json_success( array() ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Delete Staff 
		 */
		public static function delete_staff() {
			check_ajax_referer( 'bsf-staff-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'staff_id' ] ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$appointment_table = BSF_Tables_Instances::get_table_by_id( 'appointments' )->get_table_name() ;
				$appointment_query = new BSF_Query( $appointment_table ) ;
				$appointment       = $appointment_query->where( 'staff_id' , absint( $_REQUEST[ 'staff_id' ] ) )->fetchArray() ;

				if ( bsf_check_is_array( $appointment ) ) {
					throw new Exception( __( "The Selected Staff is linked with one or more Appointment(s). So, the staff can't be deleted" , 'zovonto' ) ) ;
				}

				$staff_services_table = BSF_Tables_Instances::get_table_by_id( 'staff_services' )->get_table_name() ;
				$query                = new BSF_Query( $staff_services_table ) ;
				$staff_services       = $query->where( 'staff_id' , absint( $_REQUEST[ 'staff_id' ] ) )->fetchArray() ;

				if ( bsf_check_is_array( $staff_services ) ) {
					foreach ( $staff_services as $staff_service ) {
						bsf_delete_staff_services( $staff_service[ 'id' ] ) ;
					}
				}

				bsf_delete_staff( absint( $_REQUEST[ 'staff_id' ] ) ) ;

				wp_send_json_success( array() ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Add Staff 
		 */
		public static function add_staff() {
			check_ajax_referer( 'bsf-staff-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'user_id' ] ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$user = get_userdata( absint( $_REQUEST[ 'user_id' ] ) ) ;

				if ( ! is_object( $user ) || ! $user->exists() ) {
					throw new exception( __( 'User not exists' , 'zovonto' ) ) ;
				}

				$data = array(
					'name'       => $user->display_name ,
					'wp_user_id' => $user->ID ,
					'email'      => $user->user_email ,
					'date'       => current_time( 'mysql' , true ) ,
						) ;

				$staff_id = bsf_create_new_staff( $data ) ;

				wp_send_json_success( array( 'id' => $staff_id ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Staff Selection Popup 
		 */
		public static function staff_selection_popup() {
			check_ajax_referer( 'bsf-staff-nonce' , 'bsf_security' ) ;

			try {
				ob_start() ;
				$staff_table = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;
				$query       = new BSF_Query( $staff_table ) ;

				if ( $query->count() ) {
					throw new Exception( __( 'Maximum 1 Staff can be created in the free version.' , 'zovonto' ) ) ;
				}
				
				?>
				<div class="bsf_new_staff_popup">
					<div class="bsf_new_staff_form">
						<span class="bsf_close_staff_popup"><i class="fa fa-window-close-o" aria-hidden="true"></i></span>
						<label><?php esc_html_e( 'Users' , 'zovonto' ) ; ?></label>
						<?php
						$user_selection_args = array(
							'id'          => 'user_id' ,
							'class'       => 'bsf_user_selection' ,
							'list_type'   => 'customers' ,
							'action'      => 'bsf_user_search' ,
							'placeholder' => __( 'Search a User' , 'zovonto' ) ,
							'multiple'    => false ,
								) ;
						bsf_select2_html( $user_selection_args ) ;
						?>
						<button type="button" class="button-primary bsf_add_staff"><?php esc_html_e( 'ADD' , 'zovonto' ) ; ?></button>
					</div>
				</div>
				<?php
				$content             = ob_get_clean() ;
				ob_end_clean() ;

				wp_send_json_success( array( 'html' => $content ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Add New Service 
		 */
		public static function add_service() {
			check_ajax_referer( 'bsf-services-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				ob_start() ;
				$service_table = BSF_Tables_Instances::get_table_by_id( 'services' )->get_table_name() ;
				$query         = new BSF_Query( $service_table ) ;

				if ( $query->count() > 4 ) {
					throw new Exception( __( 'Maximum 5 Services can be created in the free version' , 'zovonto' ) ) ;
				}

				$service_id = bsf_create_new_service( array( 'name' => '' ) ) ;

				$service_table = BSF_Tables_Instances::get_table_by_id( 'services' )->get_table_name() ;
				$query         = new BSF_Query( $service_table ) ;
				$services      = $query->where( 'id' , $service_id )->fetchArray() ;

				foreach ( $services as $service ) :
					?>
					<div class="bsf_newly_added_services bsf_newly_added_services_<?php echo esc_attr( $service[ 'id' ] ) ; ?>">
						<div class="bsf_services_name">
							<i class="fa fa-bars" aria-hidden="true"></i> <h3 class="bsf_update_services_name"><?php echo $service[ 'name' ] == '' ? 'Untitled' : esc_html( $service[ 'name' ] ) ; ?></h3>
							<input type="checkbox" name="bsf_delete_service" data-serviceid="<?php echo esc_attr( $service[ 'id' ] ) ; ?>"/>
							<i class="fa fa-chevron-circle-down bsf_toggle_services_panel" aria-hidden="true"></i>
						</div>
						<div class="bsf_services_info">
							<div class="bsf_services_info_row">
								<label><?php esc_html_e( 'Title' , 'zovonto' ); ?></label>
								<input type="text" class="bsf_services_title" value=""/>
							</div>
							<div class="bsf_services_info_row">
								<label><?php esc_html_e( 'Color' , 'zovonto' ); ?></label>
								<input type="text" class="bsf_services_color bsf_colorpicker" value=""/>
							</div>
							<div class="bsf_services_info_row">
								<label><?php esc_html_e( 'Price' , 'zovonto' ); ?></label>
								<input type="number" class="bsf_services_price" value=""/>
							</div>
							<div class="bsf_services_info_row">
								<label><?php esc_html_e( 'Duration' , 'zovonto' ); ?></label>
								<select class="bsf_services_duration">
									<?php
									$duration_options = bsf_get_service_duration_options( $service[ 'duration' ] ) ;
									foreach ( $duration_options as $key => $name ) {
										?>
										<option value="<?php echo esc_attr( $key ) ; ?>"><?php echo esc_html( $name ) ; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="bsf_services_info_row">
								<label><?php esc_html_e( 'Time Slot Length' , 'zovonto' ); ?></label>
								<select class="bsf_services_time_slot">
									<?php
									$time_slot_duration_options = get_bsf_service_time_slot_length_options() ;
									foreach ( $time_slot_duration_options as $key => $name ) {
										?>
										<option value="<?php echo esc_attr( $key ) ; ?>"><?php echo esc_html( $name ) ; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="bsf_services_info_row">
								<label><?php esc_html_e( 'Info' , 'zovonto' ); ?></label>
								<textarea class="bsf_services_service_info"></textarea>
							</div>
							<?php do_action( 'bsf_after_service_form_fields' , $service[ 'id' ] ) ; ?>

							<div class="bsf_services_info_row">
								<button class="bsf_save_services" data-serviceid="<?php echo esc_attr( $service[ 'id' ] ) ; ?>"><?php esc_html_e( 'Save' , 'zovonto' ) ; ?></button>
							</div>
						</div>
					</div>
					<?php
				endforeach ;

				$fields = ob_get_clean() ;
				ob_end_clean() ;

				wp_send_json_success( array( 'field' => $fields , 'service_id' => $service_id ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Save Service
		 */
		public static function save_service() {
			check_ajax_referer( 'bsf-services-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'servicesid' ] ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				global $wpdb ;
				$service_id = absint( $_REQUEST[ 'servicesid' ] ) ;
				$title      = bsf_sanitize_text_field( $_POST[ 'servicestitle' ] ) ;
				$price      = bsf_sanitize_text_field( $_POST[ 'servicesprice' ] ) ;

				$data = array(
					'name'          => $title ,
					'color'         => bsf_sanitize_text_field( $_POST[ 'servicescolor' ] ) ,
					'price'         => $price ,
					'duration'      => bsf_sanitize_text_field( $_POST[ 'servicesduration' ] ) ,
					'slot_duration' => bsf_sanitize_text_field( $_POST[ 'servicestimeslot' ] ) ,
					'info'          => bsf_sanitize_text_area( $_POST[ 'servicesinfo' ] ) ,
						) ;

				bsf_update_service( $service_id , $data ) ;

				// maybe create staff services 

				$staff_table = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;
				$query       = new BSF_Query( $staff_table ) ;
				$staff_id    = $query->orderBy( 'id' )->Limit( 1 )->fetchCol( 'id' ) ;

				if ( bsf_check_is_array( $staff_id ) ) {
					$wpdb->delete( $wpdb->prefix . 'bsf_staff_services' , array( 'service_id' => $service_id , 'staff_id' => current( $staff_id ) ) ) ;

					$staff_service_data = array(
						'staff_id'   => current( $staff_id ) ,
						'service_id' => $service_id ,
						'price'      => $price
							) ;

					bsf_create_new_staff_services( $staff_service_data ) ;
				}

				do_action( 'bsf_services_after_save' , $service_id , $_REQUEST ) ;

				wp_send_json_success( array( 'name' => $title ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Delete Services 
		 */
		public static function delete_services() {
			check_ajax_referer( 'bsf-services-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'servicesid' ] ) ) {
					throw new exception( __( 'Please Select atleast one Service' , 'zovonto' ) ) ;
				}

				$service_ids = bsf_sanitize_text_field( $_REQUEST[ 'servicesid' ] ) ;

				foreach ( $service_ids as $serviceid ) {
					$staff_services_table = BSF_Tables_Instances::get_table_by_id( 'staff_services' )->get_table_name() ;
					$query                = new BSF_Query( $staff_services_table ) ;
					$staff_services       = $query->where( 'service_id' , $serviceid )->fetchArray() ;

					if ( ! bsf_check_is_array( $staff_services ) ) {
						foreach ( $staff_services as $staff_service ) {
							bsf_delete_staff_services( $staff_service[ 'id' ] ) ;
						}
					}

					bsf_delete_service( $serviceid ) ;
				}

				wp_send_json_success() ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Render Service 
		 */
		public static function render_service() {
			check_ajax_referer( 'bsf-render-step-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$options = BSF_Booking_Form_Handler::render_service( $_REQUEST ) ;

				wp_send_json_success( $options ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Render Time 
		 */
		public static function render_time() {
			check_ajax_referer( 'bsf-render-step-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$options = BSF_Booking_Form_Handler::render_time( bsf_sanitize_text_field( $_REQUEST[ 'form_id' ] ) ) ;

				wp_send_json_success( $options ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Render Details 
		 */
		public static function render_details() {
			check_ajax_referer( 'bsf-render-step-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$options = BSF_Booking_Form_Handler::render_details( bsf_sanitize_text_field( $_REQUEST[ 'form_id' ] ) ) ;

				wp_send_json_success( $options ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Render Payment 
		 */
		public static function render_payment() {
			check_ajax_referer( 'bsf-render-step-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$options = BSF_Booking_Form_Handler::render_payment( bsf_sanitize_text_field( $_REQUEST[ 'form_id' ] ) ) ;

				wp_send_json_success( $options ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Render Complete 
		 */
		public static function render_complete() {
			check_ajax_referer( 'bsf-render-step-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$options = BSF_Booking_Form_Handler::render_complete( bsf_sanitize_text_field( $_REQUEST[ 'form_id' ] ) ) ;

				wp_send_json_success( $options ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Save Service form session
		 */
		public static function save_service_form_session() {
			check_ajax_referer( 'bsf-render-step-nonce' , 'bsf_security' ) ;

			try {
				$data = $_REQUEST ;
				if ( ! isset( $data ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				if ( ! $staff_id = bsf_get_default_staff_id() ) {
					throw new exception( __( 'No Staff are assign for events' , 'zovonto' ) ) ;
				}

				$week_days = isset( $data[ 'week_days' ] ) ? bsf_sanitize_text_field( $data[ 'week_days' ] ) : array() ;

				$new_data                 = array() ;
				$new_data[ 'service_id' ] = absint( $data[ 'service_id' ] ) ;
				$new_data[ 'staff_id' ]   = absint( $staff_id ) ;
				$new_data[ 'from_date' ]  = bsf_sanitize_text_field( $data[ 'from_date' ] ) ;
				$new_data[ 'week_days' ]  = $week_days ;
				$new_data[ 'from_time' ]  = bsf_sanitize_text_field( $data[ 'from_time' ] ) ;
				$new_data[ 'to_time' ]    = bsf_sanitize_text_field( $data[ 'to_time' ] ) ;

				if ( empty( $new_data[ 'service_id' ] ) ) {
					throw new Exception( __( 'Please select a Service' , 'zovonto' ) ) ;
				}

				$booking_data_object = new BSF_Booking_Data( bsf_sanitize_text_field( $data[ 'form_id' ] ) ) ;
				$booking_data_object->set_data( $new_data ) ;

				wp_send_json_success() ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Save Time form session
		 */
		public static function save_time_form_session() {
			check_ajax_referer( 'bsf-render-step-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				if ( empty( $_REQUEST[ 'slots' ] ) ) {
					throw new Exception( __( 'Please select a slot' , 'zovonto' ) ) ;
				}

				$booking_data_object = new BSF_Booking_Data( bsf_sanitize_text_field( $_REQUEST[ 'form_id' ] ) ) ;

				$booking_data_object->set_data( $_REQUEST ) ;

				wp_send_json_success() ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Save Details form session
		 */
		public static function save_details_form_session() {
			check_ajax_referer( 'bsf-render-step-nonce' , 'bsf_security' ) ;

			try {

				$data = $_REQUEST ;
				if ( ! isset( $data ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				if ( empty( $data[ 'first_name' ] ) ) {
					throw new Exception( __( 'First Name should not be empty' , 'zovonto' ) ) ;
				}

				if ( empty( $data[ 'last_name' ] ) ) {
					throw new Exception( __( 'Last Name should not be empty' , 'zovonto' ) ) ;
				}

				if ( empty( $data[ 'email' ] ) ) {
					throw new Exception( __( 'Email should not be empty' , 'zovonto' ) ) ;
				}

				if ( ! filter_var( $data[ 'email' ] , FILTER_VALIDATE_EMAIL ) ) {
					throw new Exception( __( 'Please enter a valid email' , 'zovonto' ) ) ;
				}

				if ( empty( $data[ 'phone' ] ) ) {
					throw new Exception( __( 'Phone Number should not be empty' , 'zovonto' ) ) ;
				}

				$data[ 'first_name' ] = bsf_sanitize_text_field( $data[ 'first_name' ] ) ;
				$data[ 'last_name' ]  = bsf_sanitize_text_field( $data[ 'last_name' ] ) ;
				$data[ 'email' ]      = sanitize_email( $data[ 'email' ] ) ;
				$data[ 'phone' ]      = bsf_sanitize_text_field( $data[ 'phone' ] ) ;

				do_action( 'bsf_after_form_fields_validation' , $data ) ;

				$booking_data_object = new BSF_Booking_Data( bsf_sanitize_text_field( $_REQUEST[ 'form_id' ] ) ) ;

				$booking_data_object->set_data( $data ) ;

				wp_send_json_success() ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Process checkout
		 */
		public static function process_checkout() {
			check_ajax_referer( 'bsf-render-step-nonce' , 'bsf_security' ) ;

			try {
				if ( ! isset( $_REQUEST ) ) {
					throw new Exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				if ( empty( $_REQUEST[ 'payment_method' ] ) ) {
					throw new Exception( __( 'Invalid Payment Method' , 'zovonto' ) ) ;
				}

				$booking_data_object = new BSF_Booking_Data( bsf_sanitize_text_field( $_REQUEST[ 'form_id' ] ) ) ;
				$booking_data_object->set_data( $_REQUEST ) ;

				$checkout = new BSF_Booking_Checkout( bsf_sanitize_text_field( $_REQUEST[ 'form_id' ] ) ) ;
				$result   = $checkout->process_checkout() ;

				$result = wp_parse_args( ( array ) $result , array(
					'result'      => '' ,
					'redirect'    => '' ,
					'err_message' => '' ,
						) ) ;

				if ( 'success' !== $result[ 'result' ] ) {
					if ( ! empty( $result[ 'err_message' ] ) ) {
						throw new exception( $result[ 'err_message' ] ) ;
					} else {
						throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
					}
				}

				wp_send_json_success( $result ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

		/**
		 * Display Calendar Details
		 */
		public static function display_calendar_details() {

			check_ajax_referer( 'bsf-calendar-nonce' , 'bsf_security' ) ;
			try {
				if ( ! isset( $_POST ) || ! isset( $_POST[ 'post_id' ] ) || ( absint( $_POST[ 'post_id' ] ) == '' ) ) {
					throw new exception( __( 'Invalid Request' , 'zovonto' ) ) ;
				}

				$booking_info = new BSF_Appointment( absint( $_POST[ 'post_id' ] ) ) ;
				$post_url     = admin_url( 'admin.php?page=booking_system&tab=appointments&paged=1&section=edit&id=' . $booking_info->get_id() ) ;
				ob_start() ;
				?>
				<div class="bsf_bookings_second_popup_inner_content_width">
					<div class="bsf_bookings_second_popup_inner_content_top">
						<div class="bsf_bookings_title">
							<h2><?php echo esc_html( $booking_info->get_services()->get_name() ) ; ?></h2>
						</div>
						<div class="bsf_bookings_close">
							<div class="bsf_bookings_close_image" title="<?php esc_attr_e( 'Close' , 'zovonto' ) ; ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></div>
						</div>
						<div class="bsf_bookings_popup_booking">
							<div class="bsf_bookings_popup_booking_id"><label><?php esc_html_e( 'ID' , 'zovonto' ) ; ?><span><?php echo ': ' . esc_html( $_POST[ 'post_id' ] ) ; ?></span></label></div>
							<div class="bsf_bookings_popup_booking_status"><?php echo bsf_display_status( $booking_info->get_status() ) ; ?></div>
						</div>
					</div>
					<div class="bsf_bookings_second_popup_inner_content_bottom">
						<div class="bsf_bookings_second_popup_inner_content_bottom_data">
							<p class="form-field bsf_bookings_popup_product_name"> <i class="fa fa-wrench" aria-hidden="true"></i>
								<strong><?php esc_html_e( 'Service Name' , 'zovonto' ) ; ?></strong>
								<?php echo ': ' . esc_html( $booking_info->get_services()->get_name() ) ; ?>
							</p>
							<p class="form-field bsf_bookings_popup_staff_name"> <i class="fa fa-user" aria-hidden="true"></i>
								<strong><?php esc_html_e( 'Staff Name' , 'zovonto' ) ; ?></strong>
								<?php echo ': ' . esc_html( $booking_info->get_staff()->get_name() ) ; ?>
							</p>
							<?php if ( $booking_info->get_start_date() ) { ?>
								<p class="form-field bsf_bookings_popup_start_date"> <i class="fa fa-calendar" aria-hidden="true"></i>
									<strong><?php esc_html_e( 'Start Date' , 'zovonto' ) ; ?></strong>
									<?php echo ': ' . esc_html( BSF_Date_Time::get_date_object_format_datetime( $booking_info->get_start_date() ) ) ; ?>
								</p>
								<?php
							}
							if ( $booking_info->get_end_date() ) {
								?>
								<p class="form-field bsf_bookings_popup_end_date"> <i class="fa fa-calendar" aria-hidden="true"></i>
									<strong><?php esc_html_e( 'End Date' , 'zovonto' ) ; ?></strong>
									<?php echo ': ' . esc_html( BSF_Date_Time::get_date_object_format_datetime( $booking_info->get_end_date() ) ) ; ?>
								</p>
								<?php
							}
							?>
						</div>
					</div>
				</div>
				<?php
				$content = ob_get_clean() ;
				ob_end_clean() ;

				wp_send_json_success( array( 'content' => $content ) ) ;
			} catch ( Exception $ex ) {
				wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
			}
		}

	}

	BSF_Admin_Ajax::init() ;
}
