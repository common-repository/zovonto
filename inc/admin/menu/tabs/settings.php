<?php

/**
 * Settings Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'BSF_Settings_Tab' ) ) {
	return new BSF_Settings_Tab() ;
}

/**
 * BSF_Settings_Tab.
 */
class BSF_Settings_Tab extends BSF_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'settings' ;
		$this->code  = 'fa-cog' ;
		$this->label = __( 'Settings', 'zovonto' ) ;

		add_action( $this->plugin_slug . '_admin_field_output_booking_form', array( $this, 'output_booking_form' ) ) ;
		add_action( $this->plugin_slug . '_admin_field_output_working_hours', array( $this, 'output_working_hours' ) ) ;
		add_action( $this->plugin_slug . '_admin_field_output_holidays', array( $this, 'output_holidays' ) ) ;
		add_action( $this->plugin_slug . '_admin_field_output_payment_methods_settings', array( $this, 'output_payment_methods_settings' ) ) ;

		parent::__construct() ;
	}

	/**
	 * Get sections.
	 */
	public function get_sections() {
		$sections = array(
			'general'          => array(
				'label' => __( 'General Settings', 'zovonto' ),
				'code'  => 'fa-cogs'
			),
			'working_hours'    => array(
				'label' => __( 'Working Hours', 'zovonto' ),
				'code'  => 'fa-clock-o'
			),
			'holidays'         => array(
				'label' => __( 'Holidays', 'zovonto' ),
				'code'  => 'fa-calendar-o'
			),
			'company'          => array(
				'label' => __( 'Company', 'zovonto' ),
				'code'  => 'fa-building-o'
			),
			'payment_settings' => array(
				'label' => __( 'Payment Settings', 'zovonto' ),
				'code'  => 'fa-wrench'
			),
			'booking_form'     => array(
				'label' => __( 'Booking Form', 'zovonto' ),
				'code'  => 'fa-list-alt'
			),
				) ;

		return apply_filters( $this->plugin_slug . '_get_sections_' . $this->id, $sections ) ;
	}

	/**
	 * Get settings array.
	 */
	public function get_settings( $current_section = '' ) {
		$settings = array() ;
		$function = $current_section . '_section_array' ;

		if ( method_exists( $this, $function ) ) {
			$settings = $this->$function() ;
		}

		return apply_filters( $this->plugin_slug . '_get_settings_' . $this->id, $settings, $current_section ) ;
	}

	/**
	 * Get settings general section array.
	 */
	public function general_section_array() {

		$section_fields = array() ;

		$cancel_duration           = get_bsf_time_slot_length_options() ;
		$cancel_duration[ '720' ]  = __( '12 h', 'zovonto' ) ;
		$cancel_duration[ '1440' ] = __( '1 day', 'zovonto' ) ;
		$cancel_duration[ '2880' ] = __( '2 days', 'zovonto' ) ;
		$cancel_duration[ '4320' ] = __( '3 days', 'zovonto' ) ;

		$section_fields[] = array(
			'type'  => 'title',
			'title' => __( 'General Settings', 'zovonto' ),
			'id'    => 'bsf_general_options',
				) ;
		$section_fields[] = array(
			'title'   => __( 'Time Slot Interval', 'zovonto' ),
			'type'    => 'select',
			'default' => '15',
			'desc'    => __( 'All the backend Time Slots will be displayed based on the value set in this option.', 'zovonto' ),
			'id'      => $this->get_option_key( 'time_slot_length' ),
			'options' => get_bsf_time_slot_length_options()
				) ;
		$section_fields[] = array(
			'title'   => __( 'Advanced Booking Period', 'zovonto' ),
			'type'    => 'number',
			'default' => '30',
			'desc'    => __( 'The maximum number of days a user can book for an appointment in advance', 'zovonto' ),
			'id'      => $this->get_option_key( 'advanced_booking_period' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Booking Form Primary Color', 'zovonto' ),
			'type'    => 'colorpicker',
			'default' => '#000070',
			'id'      => $this->get_option_key( 'booking_form_primary_color' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Custom CSS', 'zovonto' ),
			'type'    => 'textarea',
			'default' => '',
			'id'      => $this->get_option_key( 'custom_css' ),
				) ;
		$section_fields[] = array(
			'type' => 'sectionend',
			'id'   => 'bsf_general_options',
				) ;
		$section_fields[] = array(
			'type'  => 'title',
			'title' => __( 'Email Settings', 'zovonto' ),
			'id'    => 'bsf_email_options',
				) ;
		$section_fields[] = array(
			'title'   => __( 'From Name', 'zovonto' ),
			'type'    => 'text',
			'default' => esc_attr( get_bloginfo( 'name', 'display' ) ),
			'desc'    => __( 'This name will be used as the From Name for all Booking Related Emails.', 'zovonto' ),
			'id'      => $this->get_option_key( 'email_from_name' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'From Email', 'zovonto' ),
			'type'    => 'text',
			'default' => esc_attr( get_bloginfo( 'admin_email', 'display' ) ),
			'desc'    => __( 'This email will be used as the From Email for all Booking Related Emails.', 'zovonto' ),
			'id'      => $this->get_option_key( 'email_from_address' ),
				) ;
		$section_fields[] = array(
			'type' => 'sectionend',
			'id'   => 'bsf_email_options',
				) ;
		return $section_fields ;
	}

	/**
	 * Get working hours settings form section array.
	 */
	public function working_hours_section_array() {

		$section_fields[] = array(
			'type' => 'output_working_hours',
				) ;

		return $section_fields ;
	}

	/**
	 * Get holiday settings form section array.
	 */
	public function holidays_section_array() {

		$section_fields[] = array(
			'type' => 'output_holidays',
				) ;

		return $section_fields ;
	}

	/**
	 * Get settings company section array.
	 */
	public function company_section_array() {

		$section_fields = array() ;

		$section_fields[] = array(
			'type'  => 'title',
			'title' => __( 'Company Settings', 'zovonto' ),
			'id'    => 'bsf_company_options',
				) ;

		$section_fields[] = array(
			'title'   => __( 'Name', 'zovonto' ),
			'type'    => 'text',
			'default' => '',
			'id'      => $this->get_option_key( 'company_name' ),
				) ;

		$section_fields[] = array(
			'title'   => __( 'Address', 'zovonto' ),
			'type'    => 'textarea',
			'default' => '',
			'id'      => $this->get_option_key( 'company_address' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Phone', 'zovonto' ),
			'type'    => 'text',
			'default' => '',
			'id'      => $this->get_option_key( 'company_phone' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Website', 'zovonto' ),
			'type'    => 'text',
			'default' => '',
			'id'      => $this->get_option_key( 'company_website' ),
				) ;
		$section_fields[] = array(
			'type' => 'sectionend',
			'id'   => 'bsf_general_options',
				) ;

		return $section_fields ;
	}

	/**
	 * Get payment setting section array.
	 */
	public function payment_settings_section_array() {
		$section_fields = array() ;
		$currencies     = get_bsf_currencies() ;

		$section_fields[] = array(
			'type'  => 'title',
			'title' => __( 'Currency Settings', 'zovonto' ),
			'id'    => 'bsf_currency_options',
				) ;

		$section_fields[] = array(
			'title'   => __( 'Currency', 'zovonto' ),
			'type'    => 'select',
			'default' => 'USD',
			'options' => $currencies,
			'id'      => 'bsf_currency',
				) ;
		$section_fields[] = array(
			'title'   => __( 'Currency Symbol Position', 'zovonto' ),
			'type'    => 'select',
			'default' => 'left',
			'options' => array(
				'left'        => __( 'Left', 'zovonto' ),
				'right'       => __( 'Right', 'zovonto' ),
				'left_space'  => __( 'Left After a Space', 'zovonto' ),
				'right_space' => __( 'Right After a Space', 'zovonto' )
			),
			'id'      => 'bsf_currency_position',
				) ;
		$section_fields[] = array(
			'title'   => __( 'Decimal Separator', 'zovonto' ),
			'type'    => 'text',
			'default' => '.',
			'id'      => 'bsf_currency_decimal_separator',
				) ;
		$section_fields[] = array(
			'title'   => __( 'Number of Decimals', 'zovonto' ),
			'type'    => 'text',
			'default' => '2',
			'id'      => 'bsf_price_num_decimals',
				) ;
		$section_fields[] = array(
			'title'   => __( 'Thousand Separator', 'zovonto' ),
			'type'    => 'text',
			'default' => ',',
			'id'      => 'bsf_currency_thousand_separator',
				) ;
		$section_fields[] = array(
			'type' => 'sectionend',
			'id'   => 'bsf_currency_options',
				) ;
		$section_fields[] = array(
			'type' => 'output_payment_methods_settings',
				) ;

		return $section_fields ;
	}

	/**
	 * Get settings form section array.
	 */
	public function booking_form_section_array() {

		$section_fields[] = array(
			'type' => 'output_booking_form',
				) ;

		return $section_fields ;
	}

	/**
	 * Get settings service customization section array.
	 */
	public function service_selection_section_array() {
		$section_fields = array() ;

		$section_fields[] = array(
			'type'  => 'title',
			'title' => __( 'Service Selection Customization', 'zovonto' ),
			'id'    => 'bsf_service_customization_options',
				) ;
		$section_fields[] = array(
			'title'   => __( 'Booking Step Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Service',
			'id'      => $this->get_option_key( 'service_selection_step_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Booking Step Description', 'zovonto' ),
			'type'    => 'textarea',
			'default' => 'Please Select a Service to proceed with the Booking',
			'id'      => $this->get_option_key( 'service_selection_step_desc' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Service Selector Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Service',
			'id'      => $this->get_option_key( 'service_selector_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Calendar Selection Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Date',
			'id'      => $this->get_option_key( 'calendar_selector_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Start Time Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Start Time',
			'id'      => $this->get_option_key( 'start_time_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'End Time Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'End Time',
			'id'      => $this->get_option_key( 'end_time_label' ),
				) ;
		$section_fields[] = array(
			'type' => 'sectionend',
			'id'   => 'bsf_service_customization_options',
				) ;

		return $section_fields ;
	}

	/**
	 * Get settings time selection customization section array.
	 */
	public function time_selection_section_array() {
		$section_fields = array() ;

		$section_fields[] = array(
			'type'  => 'title',
			'title' => __( 'Time Selection Customization', 'zovonto' ),
			'id'    => 'bsf_time_selection_customization_options',
				) ;

		$section_fields[] = array(
			'title'   => __( 'Booking Step Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Time Selection',
			'id'      => $this->get_option_key( 'time_selection_step_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Booking Step Description', 'zovonto' ),
			'type'    => 'textarea',
			'default' => 'Please Select a Time slot',
			'id'      => $this->get_option_key( 'time_selection_step_desc' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Time Slots Unavailable Error Message', 'zovonto' ),
			'type'    => 'textarea',
			'default' => 'No empty time slots available. Please select a different Service/Date/Time.',
			'id'      => $this->get_option_key( 'time_selection_no_items_message' ),
				) ;
		$section_fields[] = array(
			'type' => 'sectionend',
			'id'   => 'bsf_time_selection_customization_options',
				) ;

		return $section_fields ;
	}

	/**
	 * Get settings user details customization section array.
	 */
	public function user_details_section_array() {
		$section_fields = array() ;

		$section_fields[] = array(
			'type'  => 'title',
			'title' => __( 'Time Selection Customization', 'zovonto' ),
			'id'    => 'bsf_user_details_customization_options',
				) ;

		$section_fields[] = array(
			'title'   => __( 'Booking Step Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'User Details',
			'id'      => $this->get_option_key( 'user_details_step_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Booking Step Description', 'zovonto' ),
			'type'    => 'textarea',
			'default' => 'Please Fill in your Details',
			'id'      => $this->get_option_key( 'user_details_step_desc' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'First Name Field Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'First Name',
			'id'      => $this->get_option_key( 'first_name_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Last Name Field Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Last Name',
			'id'      => $this->get_option_key( 'last_name_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Phone Field Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Phone Number',
			'id'      => $this->get_option_key( 'phone_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Email Field Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Email',
			'id'      => $this->get_option_key( 'email_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'First Name Field Placeholder', 'zovonto' ),
			'type'    => 'text',
			'default' => 'First Name',
			'id'      => $this->get_option_key( 'first_name_placeholder' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Last Name Field Placeholder', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Last Name',
			'id'      => $this->get_option_key( 'last_name_placeholder' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Phone Field Placeholder', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Phone Number',
			'id'      => $this->get_option_key( 'phone_placeholder' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Email Field Placeholder', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Email',
			'id'      => $this->get_option_key( 'email_placeholder' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Display Notes Field', 'zovonto' ),
			'type'    => 'checkbox',
			'default' => 'yes',
			'id'      => $this->get_option_key( 'display_notes_field' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Notes Field Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Notes',
			'id'      => $this->get_option_key( 'notes_field_label' ),
				) ;
		$section_fields[] = array(
			'type' => 'sectionend',
			'id'   => 'bsf_user_details_customization_options',
				) ;

		return $section_fields ;
	}

	/**
	 * Get settings payment customization section array.
	 */
	public function payment_section_array() {
		$section_fields = array() ;

		$section_fields[] = array(
			'type'  => 'title',
			'title' => __( 'Payment Selection Customization', 'zovonto' ),
			'id'    => 'bsf_payment_customization_options',
				) ;

		$section_fields[] = array(
			'title'   => __( 'Booking Step Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Payment',
			'id'      => $this->get_option_key( 'payment_step_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Booking Step Description', 'zovonto' ),
			'type'    => 'textarea',
			'default' => 'Please Proceed to Make the Payment',
			'id'      => $this->get_option_key( 'payment_step_desc' ),
				) ;
		$section_fields[] = array(
			'type' => 'sectionend',
			'id'   => 'bsf_payment_customization_options',
				) ;

		return $section_fields ;
	}

	/**
	 * Get settings completion customization section array.
	 */
	public function completion_section_array() {
		$section_fields = array() ;

		$section_fields[] = array(
			'type'  => 'title',
			'title' => __( 'Booking Completion', 'zovonto' ),
			'id'    => 'bsf_completion_customization_options',
				) ;
		$section_fields[] = array(
			'title'   => __( 'Booking Step Label', 'zovonto' ),
			'type'    => 'text',
			'default' => 'Complete',
			'id'      => $this->get_option_key( 'completion_step_label' ),
				) ;
		$section_fields[] = array(
			'title'   => __( 'Booking Step Description', 'zovonto' ),
			'type'    => 'textarea',
			'default' => 'Your Booking has been completed Successfully',
			'id'      => $this->get_option_key( 'completion_step_desc' ),
				) ;
		$section_fields[] = array(
			'type' => 'sectionend',
			'id'   => 'bsf_completion_customization_options',
				) ;

		return $section_fields ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		global $current_section, $current_sub_section ;

		if ( ! in_array( $current_section, array( 'holidays', 'booking_form' ) ) ) {
			BSF_Settings::output_buttons() ;
		} elseif ( $current_section == 'booking_form' && $current_sub_section != '' ) {
			BSF_Settings::output_buttons() ;
		}
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section, $current_sub_section ;

		parent::save() ;

		if ( empty( $_POST[ 'save' ] ) ) {
			return ;
		}

		if ( $current_section == 'working_hours' ) {
			global $wpdb ;
			$start_of_week       = ( int ) get_option( 'start_of_week' ) ;
			$staff_id            = bsf_get_default_staff_id() ;
			$staff_working_hours = array() ;

			if ( $staff_id ) {
				$staff_working_hours_table = BSF_Tables_Instances::get_table_by_id( 'staff_working_hours' )->get_table_name() ;
				$staff_working_hours       = $wpdb->query( $wpdb->prepare( "DELETE FROM $staff_working_hours_table WHERE staff_id=%d", $staff_id ) ) ;
			}

			for ( $i = 0 ; $i < 7 ; $i ++ ) {
				$day_index   = ( $i + $start_of_week ) % 7 ;
				$day         = BSF_Date_Time::get_week_day_by_number( $day_index ) ;
				$option_name = 'bsf_working_hours_' . strtolower( $day ) ;

				$start_value = isset( $_POST[ $option_name . '_start' ] ) ? $_POST[ $option_name . '_start' ] : '' ;
				$end_value   = isset( $_POST[ $option_name . '_end' ] ) ? $_POST[ $option_name . '_end' ] : '' ;

				update_option( $option_name . '_start', $start_value ) ;
				update_option( $option_name . '_end', $end_value ) ;

				if ( ! $staff_id ) {
					continue ;
				}

				$working_hours_data = array(
					'staff_id'   => $staff_id,
					'day_index'  => $day_index,
					'start_time' => $start_value,
					'end_time'   => $end_value
						) ;

				bsf_create_new_staff_working_hours( $working_hours_data ) ;
			}
		} else if ( $current_section == 'payment_settings' && ! $current_sub_section ) {
			$payment_gateways = array(
				'offline_payment_gateway',
					) ;

			foreach ( $payment_gateways as $gateway_id ) {
				$enabled = ! empty( $_POST[ "bsf_{$gateway_id}_enabled" ] ) ? 'yes' : 'no' ;
				update_option( "bsf_{$gateway_id}_enabled", $enabled ) ;
			}
		}
	}

	/**
	 * Reset settings.
	 */
	public function reset() {
		global $current_section ;

		if ( $current_section == 'working_hours' ) {
			if ( empty( $_POST[ 'reset' ] ) ) {
				return ;
			}

			for ( $i = 0 ; $i < 7 ; $i ++ ) {
				$day         = BSF_Date_Time::get_week_day_by_number( $i ) ;
				$option_name = 'bsf_working_hours_' . strtolower( $day ) ;

				update_option( $option_name . '_start', '09:00' ) ;
				update_option( $option_name . '_end', '18:00' ) ;
			}
		}

		parent::reset() ;
	}

	/*
	 * Output Working hours
	 */

	public function output_working_hours() {
		include_once (BSF_PLUGIN_PATH . '/inc/admin/menu/views/settings/working-hours.php') ;
	}

	/*
	 * Output Holidays
	 */

	public function output_holidays() {
		include_once (BSF_PLUGIN_PATH . '/inc/admin/menu/views/settings/holidays.php') ;
	}

	/*
	 * Output Booking Form
	 */

	public function output_booking_form() {

		$booking_form_steps = bsf_get_booking_form_steps() ;

		echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
		echo '<h2 class="wp-heading-inline">' . __( 'Booking Form', 'zovonto' ) . '</h2>' ;
		echo '<table class="bsf_table bsf_booking_form_table">' ;
		echo '<thead><tr><th>' . __( 'Step Name', 'zovonto' ) . '</th>' ;
		echo '<th>' . __( 'Manage', 'zovonto' ) . '</th></tr>' ;
		echo '</tr></thead><tbody>' ;

		$settings_url = add_query_arg( array( 'page' => 'booking_system', 'tab' => 'settings', 'section' => 'booking_form' ), admin_url( 'admin.php' ) ) ;
		foreach ( $booking_form_steps as $booking_step_key => $booking_form_step ) {

			echo '<tr>' ;
			echo '<td>' . $booking_form_step . '</td>' ;
			echo '<td><a class="bsf_manage_btn" href="' . esc_url( add_query_arg( array( 'subsection' => $booking_step_key ), $settings_url ) ) . '">' . __( 'Manage', 'zovonto' ) . '</a></td>' ;
			echo '</tr>' ;
		}
		echo '</tbody></table>' ;
		echo '</div>' ;
	}

	/*
	 * Output Payment Methods
	 */

	public function output_payment_methods_settings() {
		$columns = array(
			'payment_methods' => __( 'Payment Gateway', 'zovonto' ),
			'enabled'         => __( 'Enabled', 'zovonto' ),
			'manage'          => __( 'Settings', 'zovonto' )
				) ;

		echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
		echo '<h2 class="wp-heading-inline">' . __( 'Payment Gateways', 'zovonto' ) . '</h2>' ;
		echo '<table class="bsf_table bsf_payment_gateways_table">' ;
		echo '<thead><tr>' ;
		foreach ( $columns as $class => $column ) {
			echo '<th class="' . esc_attr( $class ) . '">' . esc_html( $column ) . '</th>' ;
		}
		echo '</tr></thead><tbody>' ;
		foreach ( BSF()->payment_gateways()->get_payment_gateways() as $gateway ) {
			echo '<tr>' ;
			echo '<td>' . esc_html( $gateway->get_title() ) . '</td>' ;
			echo '<td><input type="checkbox" name="bsf_' . esc_attr( $gateway->get_id() ) . '_enabled" value="yes" ' . checked( $gateway->is_enabled(), true, false ) . '></td>' ;
			echo '<td><a class="bsf_manage_btn" href="' . esc_url( add_query_arg( array( 'page' => 'booking_system', 'tab' => 'settings', 'section' => $gateway->get_id() ), admin_url( 'admin.php' ) ) ) . '">' . __( 'Settings', 'zovonto' ) . '</a></td>' ;
			echo '</tr>' ;
		}
		echo '</tbody></table>' ;
		echo '</div>' ;
	}

}

return new BSF_Settings_Tab() ;
