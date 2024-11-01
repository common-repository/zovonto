<?php
/*
 * Layout functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! function_exists( 'bsf_select2_html' ) ) {

	/**
	 * Function to display Select2 HTML
	 * */
	function bsf_select2_html( $args, $echo = true ) {
		$args = wp_parse_args( $args , array(
			'class'             => '' ,
			'id'                => '' ,
			'name'              => '' ,
			'list_type'         => '' ,
			'action'            => '' ,
			'placeholder'       => '' ,
			'custom_attributes' => '' ,
			'multiple'          => true ,
			'allow_clear'       => true ,
			'selected'          => true ,
			'options'           => array() ,
				)
				) ;

		$multiple = $args[ 'multiple' ] ? 'multiple="multiple"' : '' ;
		$name     = esc_attr( '' !== $args[ 'name' ] ? $args[ 'name' ] : $args[ 'id' ] ) . '[]' ;

		// Custom attribute handling.
		$custom_attributes = bsf_get_select2_custom_attributes( $args ) ;

		ob_start() ;
		?><select <?php echo esc_attr( $multiple ) ; ?> 
			name="<?php echo esc_attr( $name ) ; ?>" 
			id="<?php echo esc_attr( $args[ 'id' ] ) ; ?>" 
			data-action="<?php echo esc_attr( $args[ 'action' ] ) ; ?>" 
			class="bsf_select2_search <?php echo esc_attr( $args[ 'class' ] ) ; ?>" 
			data-placeholder="<?php echo esc_attr( $args[ 'placeholder' ] ) ; ?>" 
			<?php echo implode( ' ' , $custom_attributes ) ; ?>
			<?php echo $args[ 'allow_clear' ] ? 'data-allow_clear="true"' : ''; ?> >
				<?php
				if ( is_array( $args[ 'options' ] ) ) {
					foreach ( $args[ 'options' ] as $option_id ) {
						$option_value = '' ;
						switch ( $args[ 'list_type' ] ) {

							case 'customers':
								if ( $user = get_user_by( 'id' , $option_id ) ) {
									$option_value = $user->display_name . '(#' . absint( $user->ID ) . ' &ndash; ' . $user->user_email . ')' ;
								}
								break ;
							case 'staff':
								$staff_table = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;
								$query       = new BSF_Query( $staff_table ) ;
								$staffs      = $query->where( 'id' , $option_id )->fetchArray() ;

								if ( bsf_check_is_array( $staffs ) ) {
									foreach ( $staffs as $staff ) {
										if ( ! bsf_check_is_array( $staff ) ) {
											continue ;
										}

										$option_value = $staff[ 'name' ] . '(#' . absint( $staff[ 'id' ] ) . ' &ndash; ' . $staff[ 'email' ] . ')' ;
									}
								}
								break ;
							case 'services':
								$services_table = BSF_Tables_Instances::get_table_by_id( 'services' )->get_table_name() ;
								$query          = new BSF_Query( $services_table ) ;
								$services       = $query->where( 'id' , $option_id )->fetchArray() ;

								if ( bsf_check_is_array( $services ) ) {
									foreach ( $services as $service ) {
										if ( ! bsf_check_is_array( $service ) ) {
											continue ;
										}

										$option_value = $service[ 'name' ] . '(#' . absint( $service[ 'id' ] ) . ')' ;
									}
								}
								break ;
							case 'bsfcustomers':
								$customer_table = BSF_Tables_Instances::get_table_by_id( 'customers' )->get_table_name() ;
								$query          = new BSF_Query( $customer_table ) ;
								$customers      = $query->where( 'id' , $option_id )->fetchArray() ;

								if ( bsf_check_is_array( $customers ) ) {
									foreach ( $customers as $customer ) {
										if ( ! bsf_check_is_array( $customer ) ) {
											continue ;
										}

										$option_value = $customer[ 'first_name' ] . ' &ndash; ' . $customer[ 'last_name' ] . '(#' . absint( $customer[ 'id' ] ) . ' &ndash; ' . $customer[ 'email' ] . ')' ;
									}
								}
								break ;
							case 'post':
								$option_value = get_the_title( $option_id ) ;
								break ;
						}

						if ( $option_value ) {
							?>
						<option value="<?php echo esc_attr( $option_id ) ; ?>" <?php echo esc_attr( $args[ 'selected' ] ) ? 'selected="selected"' : ''; ?>><?php echo esc_html( $option_value ) ; ?></option>
							<?php
						}
					}
				}
				?>
			</select>
			<?php
			$html = ob_get_clean() ;

			if ( $echo ) {
				echo $html ;
			}

			return $html ;
	}

}

if ( ! function_exists( 'bsf_get_select2_custom_attributes' ) ) {

	function bsf_get_select2_custom_attributes( $value ) {
		$custom_attributes = array() ;

		if ( ! empty( $value[ 'custom_attributes' ] ) && is_array( $value[ 'custom_attributes' ] ) ) {
			foreach ( $value[ 'custom_attributes' ] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '=' . esc_attr( $attribute_value ) . '' ;
			}
		}

		return $custom_attributes ;
	}

}

if ( ! function_exists( 'bsf_get_datepicker_html' ) ) {

	function bsf_get_datepicker_html( $args, $echo = true ) {
		$args = wp_parse_args( $args , array(
			'class'             => '' ,
			'id'                => '' ,
			'name'              => '' ,
			'placeholder'       => '' ,
			'custom_attributes' => '' ,
			'value'             => '' ,
			'wp_zone'           => true ,
				) ) ;

		$name = ( '' !== $args[ 'name' ] ) ? $args[ 'name' ] : $args[ 'id' ] ;

		// Custom attribute handling.
		$custom_attributes = bsf_get_select2_custom_attributes( $args ) ;
		$value             = ! empty( $args[ 'value' ] ) ? BSF_Date_Time::get_date_object_format_datetime( $args[ 'value' ] , 'date' , $args[ 'wp_zone' ] ) : '' ;
		ob_start() ;
		?>
		<input type = "text" 
			   id="<?php echo esc_attr( $args[ 'id' ] ) ; ?>"
			   value = "<?php echo esc_attr( $value ) ; ?>"
			   class="bsf_datepicker <?php echo esc_attr( $args[ 'class' ] ) ; ?>" 
			   placeholder="<?php echo esc_attr( $args[ 'placeholder' ] ) ; ?>" 
			   <?php echo implode( ' ' , $custom_attributes ) ; ?>
			   />

		<input type = "hidden" 
			   class="bsf_alter_datepicker_value" 
			   name="<?php echo esc_attr( $name ) ; ?>"
			   value = "<?php echo esc_attr( $args[ 'value' ] ) ; ?>"
			   /> 
		<?php
		$html              = ob_get_clean() ;

		if ( $echo ) {
			echo $html ;
		}

		return $html ;
	}

}

if ( ! function_exists( 'bsf_get_working_hours_select_html' ) ) {

	function bsf_get_working_hours_select_html( $args ) {

		$default_args = array( 'option_name'    => '' ,
			'is_start'       => true ,
			'class'          => array() ,
			'selected_value' => 0 ,
			'show_seconds'   => false
				) ;

		$args = wp_parse_args( $args , $default_args ) ;

		extract( $args ) ;

		$time_slot_length = bsf_get_time_slot_length() ;
		$time_output      = 0 ;
		$time_end         = DAY_IN_SECONDS ;
		$selected_value   = get_option( $option_name ) ;

		$class_name = $is_start ? 'bsf_select_start' : 'bsf_select_end' ;
		$class_name = $class_name . ' ' . implode( ' ' , $class ) ;

		$output = "<select class='" . esc_attr($class_name) . "' name='" . esc_attr($option_name) . "'>" ;

		if ( $is_start ) {
			$output   .= '<option value="">' . __( 'OFF' , 'zovonto' ) . '</option>' ;
			$time_end -= $time_slot_length ;
		}

		$selected_seconds = BSF_Date_Time::time_to_seconds( $selected_value ) ;

		$value_added = false ;
		while ( $time_output <= $time_end ) {
			if ( ! $value_added ) {
				if ( $selected_seconds == $time_output ) {
					$value_added = true ;
				} elseif ( $selected_seconds < $time_output ) {
					$output      .= sprintf( '<option value="%s" selected="selected">%s</option>' , esc_attr($selected_value) , BSF_Date_Time::format_time( $selected_seconds ) ) ;
					$value_added = true ;
				}
			}

			$value       = BSF_Date_Time::seconds_to_time( $time_output , false ) ;
			$option_name = BSF_Date_Time::format_time( $time_output ) ;
			$output      .= "<option value='{$value}'" . selected( $value , $selected_value , false ) . ">{$option_name}</option>" ;
			$time_output += $time_slot_length ;
		}

		$output .= '</select>' ;

		return $output ;
	}

}


if ( ! function_exists( 'bsf_get_service_working_hours_select_html' ) ) {

	function bsf_get_service_working_hours_select_html( $args ) {

		$default_args = array( 'option_name'    => '' ,
			'is_start'       => true ,
			'class'          => array() ,
			'selected_value' => 0 ,
			'show_seconds'   => true ,
			'time_start'     => 0 ,
			'time_end'       => DAY_IN_SECONDS ,
				) ;

		$args = wp_parse_args( $args , $default_args ) ;

		extract( $args ) ;

		$time_slot_length = bsf_get_time_slot_length() ;
		$class_name       = implode( ' ' , $class ) ;

		$output = "<select class='" . esc_attr($class_name) . "' name='" . esc_attr($option_name) . "'>" ;

		if ( $is_start ) {
			$output   .= '<option value="">' . __( 'OFF' , 'zovonto' ) . '</option>' ;
			$time_end -= $time_slot_length ;
		}

		$selected_seconds = BSF_Date_Time::time_to_seconds( $selected_value ) ;

		$value_added = false ;
		while ( $time_start <= $time_end ) {
			if ( ! $value_added ) {
				if ( $selected_seconds == $time_start ) {
					$value_added = true ;
				} elseif ( $selected_seconds && $selected_seconds < $time_start ) {
					$output      .= sprintf( '<option value="%s" selected="selected">%s</option>' , esc_attr($selected_value) , BSF_Date_Time::format_time( $selected_seconds ) ) ;
					$value_added = true ;
				}
			}
			$value       = BSF_Date_Time::seconds_to_time( $time_start , $show_seconds ) ;
			$option_name = BSF_Date_Time::format_time( $time_start ) ;
			$output      .= "<option value='{$value}'" . selected( $value , $selected_value , false ) . ">{$option_name}</option>" ;
			$time_start  += $time_slot_length ;
		}

		$output .= '</select>' ;

		return $output ;
	}

}

if ( ! function_exists( 'get_bsf_time_slot_length_options' ) ) {

	/**
	 * Get time slot length options
	 */
	function get_bsf_time_slot_length_options() {
		static $time_slots ;
		if ( ! isset( $time_slots ) ) {
			$time_slots = array_unique(
					apply_filters(
							'bsf_time_slot_length_options' , array(
				'5'   => __( '5 min' , 'zovonto' ) ,
				'10'  => __( '10 min' , 'zovonto' ) ,
				'12'  => __( '12 min' , 'zovonto' ) ,
				'15'  => __( '15 min' , 'zovonto' ) ,
				'20'  => __( '20 min' , 'zovonto' ) ,
				'30'  => __( '30 min' , 'zovonto' ) ,
				'45'  => __( '45 min' , 'zovonto' ) ,
				'60'  => __( '1 h' , 'zovonto' ) ,
				'90'  => __( '1 h 30 min' , 'zovonto' ) ,
				'120' => __( '2 h' , 'zovonto' ) ,
				'180' => __( '3 h' , 'zovonto' ) ,
				'240' => __( '4 h' , 'zovonto' ) ,
				'300' => __( '5 h' , 'zovonto' ) ,
				'360' => __( '6 h' , 'zovonto' ) ,
							)
					)
					) ;
		}

		return $time_slots ;
	}

}

if ( ! function_exists( 'get_bsf_service_time_duration_options' ) ) {

	/**
	 * Get service time duration options
	 */
	function get_bsf_service_time_duration_options() {
		static $service_time_duration ;
		if ( ! isset( $service_time_duration ) ) {
			$service_time_duration = array_unique(
					apply_filters(
							'bsf_service_time_duration_options' , array(
				'5'   => __( '5 min' , 'zovonto' ) ,
				'10'  => __( '10 min' , 'zovonto' ) ,
				'12'  => __( '12 min' , 'zovonto' ) ,
				'15'  => __( '15 min' , 'zovonto' ) ,
				'20'  => __( '20 min' , 'zovonto' ) ,
				'30'  => __( '30 min' , 'zovonto' ) ,
				'45'  => __( '45 min' , 'zovonto' ) ,
				'60'  => __( '1 h' , 'zovonto' ) ,
				'90'  => __( '1 h 30 min' , 'zovonto' ) ,
				'120' => __( '2 h' , 'zovonto' ) ,
				'180' => __( '3 h' , 'zovonto' ) ,
				'240' => __( '4 h' , 'zovonto' ) ,
				'300' => __( '5 h' , 'zovonto' ) ,
				'360' => __( '6 h' , 'zovonto' ) ,
							)
					)
					) ;
		}

		return $service_time_duration ;
	}

}

if ( ! function_exists( 'get_bsf_service_time_slot_length_options' ) ) {

	/**
	 * Get service time slot length options
	 */
	function get_bsf_service_time_slot_length_options() {
		static $service_time_slots ;
		if ( ! isset( $service_time_slots ) ) {
			$default_time_slots = get_bsf_time_slot_length_options() ;
			$default_args       = array(
				'default' => __( 'Default' , 'zovonto' ) ,
				'slot'    => __( 'Slot Length as Service Duration' , 'zovonto' ) ,
					) ;

			$service_time_slots = array_unique( apply_filters( 'bsf_service_time_slot_length_options' , $default_args + $default_time_slots ) ) ;
		}

		return $service_time_slots ;
	}

}

if ( ! function_exists( 'bsf_get_service_duration_options' ) ) {

	function bsf_get_service_duration_options( $duration ) {
		$time_interval = get_option( 'bsf_settings_time_slot_length' , 15 ) ;
		$duration      = ( int ) $duration ;

		$options = array() ;

		for ( $j = $time_interval ; $j <= 720 ; $j += $time_interval ) {

			if ( ( $duration / 60 > $j - $time_interval ) && ( $duration / 60 < $j ) ) {
				$options[ $duration ] = BSF_Date_Time::seconds_to_string( $duration ) ;
			}

			$options[ $j * 60 ] = BSF_Date_Time::seconds_to_string( $j * 60 ) ;
		}

		return $options ;
	}

}

if ( ! function_exists( 'bsf_get_staff_edit_panels' ) ) {

	function bsf_get_staff_edit_panels( $staff_id ) {
		$staff = new BSF_Staff( $staff_id ) ;

		if ( ! $staff->exists() ) {
			return false ;
		}

		include_once BSF_PLUGIN_PATH . '/inc/admin/menu/views/staff/panels.php' ;
	}

}

if ( ! function_exists( 'bsf_display_action' ) ) {

	function bsf_display_action( $status, $id, $current_url ) {
		switch ( $status ) {
			case 'cancelled':
				$status_name = __( 'Cancel' , 'zovonto' ) ;
				break ;
			case 'approved':
				$status_name = __( 'Approve' , 'zovonto' ) ;
				break ;
			case 'edit':
				$status_name = __( 'Edit' , 'zovonto' ) ;
				break ;
			case 'rejected':
				$status_name = __( 'Reject' , 'zovonto' ) ;
				break ;
			default:
				$status_name = __( 'Delete Permanently' , 'zovonto' ) ;
				break ;
		}

		if ( $status == 'edit' ) {
			return '<a href="' . esc_url( add_query_arg( array( 'section' => $status , 'id' => $id ) , $current_url ) ) . '">' . $status_name . '</a>' ;
		} elseif ( $status == 'delete' ) {
			return '<a class="bsf_delete_data" href="' . esc_url( add_query_arg( array( 'action' => $status , 'id' => $id ) , $current_url ) ) . '">' . $status_name . '</a>' ;
		} else {
			return '<a href="' . esc_url( add_query_arg( array( 'action' => $status , 'id' => $id ) , $current_url ) ) . '">' . $status_name . '</a>' ;
		}
	}

}


if ( ! function_exists( 'bsf_display_status' ) ) {

	function bsf_display_status( $status, $html = true ) {

		switch ( $status ) {
			case 'rejected':
				$class_name  = 'bsf_rejected_status' ;
				$status_name = __( 'Rejected' , 'zovonto' ) ;
				break ;
			case 'cancelled':
				$class_name  = 'bsf_cancelled_status' ;
				$status_name = __( 'Cancelled' , 'zovonto' ) ;
				break ;
			case 'pending':
				$class_name  = 'bsf_pending_status' ;
				$status_name = __( 'Pending' , 'zovonto' ) ;
				break ;
			case 'completed':
				$class_name  = 'bsf_completed_status' ;
				$status_name = __( 'Completed' , 'zovonto' ) ;
				break ;
			case 'paid': //bsf_paid
				$class_name  = 'bsf_paid_status' ;
				$status_name = __( 'Paid' , 'zovonto' ) ;
				break ;
			case 'unpaid': //bsf_unpaid
				$class_name  = 'bsf_unpaid_status' ;
				$status_name = __( 'Unpaid' , 'zovonto' ) ;
			default:
				$class_name  = 'bsf_approved_status' ;
				$status_name = __( 'Approved' , 'zovonto' ) ;
				break ;
		}

		return $html ? '<span class="bsf_status_btn ' . $class_name . '">' . $status_name . '</span>' : $status_name ;
	}

}


if ( ! function_exists( 'bsf_display_payment_method' ) ) {

	function bsf_display_payment_method( $payment_method ) {
		$payment_gateways = BSF()->payment_gateways()->get_payment_gateways() ;

		if ( isset( $payment_gateways[ $payment_method ] ) ) {
			return $payment_gateways[ $payment_method ]->get_title() ;
		}

		return $payment_method ;
	}

}

if ( ! function_exists( 'bsf_get_booking_form_steps' ) ) {

	function bsf_get_booking_form_steps() {

		return apply_filters( 'bsf_booking_form_steps' , array(
			'service_selection' => __( 'Service Selection' , 'zovonto' ) ,
			'time_selection'    => __( 'Time Selection' , 'zovonto' ) ,
			'user_details'      => __( 'User Details' , 'zovonto' ) ,
			'payment'           => __( 'Payment' , 'zovonto' ) ,
			'completion'        => __( 'Completion' , 'zovonto' ) ,
				) ) ;
	}

}
