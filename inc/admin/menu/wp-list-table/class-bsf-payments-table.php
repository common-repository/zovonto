<?php

/**
 * Affiliates Referrals Post Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( ! class_exists( 'BSF_Payments_Post_Table' ) ) {

	/**
	 * BSF_Payments_Post_Table Class.
	 * */
	class BSF_Payments_Post_Table extends WP_List_Table {

		/**
		 * Total Count of Table
		 * */
		private $total_items ;

		/**
		 * Per page count
		 * */
		private $perpage ;

		/**
		 * Offset
		 * */
		private $offset ;

		/**
		 * Order BY
		 * */
		private $orderby = 'ORDER BY id DESC' ;

		/**
		 * Table
		 * */
		private $table ;

		/**
		 * Base URL
		 * */
		private $base_url ;

		/**
		 * Current URL
		 * */
		private $current_url ;

		/**
		 * Plugin slug.
		 */
		protected $plugin_slug = 'bsf' ;

		/**
		 * Prepare the table Data to display table based on pagination.
		 * */
		public function prepare_items() {
			$this->table    = BSF_Tables_Instances::get_table_by_id( 'payments' )->get_table_name() ;
			$this->base_url = add_query_arg( array( 'page' => 'booking_system' , 'tab' => 'payments' ) , BSF_ADMIN_URL ) ;

			add_filter( $this->table_slug . '_query_where' , array( $this , 'query_for_filters' ) , 10 , 1 ) ;

			$this->prepare_current_url() ;
			$this->process_bulk_action() ;
			$this->get_perpage_count() ;
			$this->get_current_pagenum() ;
			$this->get_current_page_items() ;
			$this->prepare_pagination_args() ;
			//display header columns
			$this->prepare_column_headers() ;
		}

		/**
		 * get per page count
		 * */
		private function get_perpage_count() {

			$this->perpage = 20 ;
		}

		/**
		 * Prepare pagination
		 * */
		private function prepare_pagination_args() {

			$this->set_pagination_args( array(
				'total_items' => $this->total_items ,
				'per_page'    => $this->perpage
			) ) ;
		}

		/**
		 * get current page number
		 * */
		private function get_current_pagenum() {
			$this->offset = 20 * ( $this->get_pagenum() - 1 ) ;
		}

		/**
		 * Prepare header columns
		 * */
		private function prepare_column_headers() {
			$columns               = $this->get_columns() ;
			$hidden                = $this->get_hidden_columns() ;
			$sortable              = $this->get_sortable_columns() ;
			$this->_column_headers = array( $columns , $hidden , $sortable ) ;
		}

		/**
		 * Initialize the columns
		 * */
		public function get_columns() {
			$columns = array(
				'cb'               => '<input type="checkbox" />' , //Render a checkbox instead of text
				'id'               => __( 'ID' , 'zovonto' ) ,
				'staff'            => __( 'Staff' , 'zovonto' ) ,
				'payment_method'   => __( 'Payment Method' , 'zovonto' ) ,
				'service'          => __( 'Service' , 'zovonto' ) ,
				'customer'         => __( 'Customer' , 'zovonto' ) ,
				'appointment_date' => __( 'Appointment Date' , 'zovonto' ) ,
				'date'             => __( 'Payment Date' , 'zovonto' ) ,
				'price'            => __( 'Amount' , 'zovonto' ) ,
				'status'           => __( 'Status' , 'zovonto' ) ,
				'action'           => __( 'Actions' , 'zovonto' ) ,
					) ;

			return $columns ;
		}

		/**
		 * Initialize the hidden columns
		 * */
		public function get_hidden_columns() {
			return array() ;
		}

		/**
		 * Initialize the bulk actions
		 * */
		protected function get_bulk_actions() {
			$action = array() ;

			$action             = apply_filters( $this->plugin_slug . '_list_of_action_for_payments' , $action ) ;
			$action[ 'delete' ] = __( 'Delete' , 'zovonto' ) ;

			return $action ;
		}

		/**
		 * Display the list of views available on this table.
		 * */
		public function get_views() {
			$args        = array() ;
			$status_link = array() ;

			$status_link_array = array(
				''          => __( 'All' , 'zovonto' ) ,
				'pending'   => __( 'Pending' , 'zovonto' ) ,
				'completed' => __( 'Completed' , 'zovonto' ) ,
				'cancelled' => __( 'Cancelled' , 'zovonto' ) ,
					) ;

			foreach ( $status_link_array as $status_name => $status_label ) {
				$status_count = $this->get_total_item_for_status( $status_name ) ;

				if ( ! $status_count ) {
					continue ;
				}

				if ( $status_name ) {
					$args[ 'status' ] = $status_name ;
				}

				$label                       = $status_label . ' (' . $status_count . ')' ;
				$class                       = ( isset( $_GET[ 'status' ] ) && sanitize_key( $_GET[ 'status' ] ) == $status_name ) ? 'current' : '' ;
				$class                       = ( ! isset( $_GET[ 'status' ] ) && '' == $status_name ) ? 'current' : $class ;
				$status_link[ $status_name ] = $this->get_edit_link( $args , $label , $class ) ;
			}

			return $status_link ;
		}

		/**
		 * Edit link for status
		 * */
		private function get_edit_link( $args, $label, $class = '' ) {
			$url        = add_query_arg( $args , $this->base_url ) ;
			$class_html = '' ;
			if ( ! empty( $class ) ) {
				$class_html = sprintf(
						' class="%s"' , esc_attr( $class )
						) ;
			}

			return sprintf(
					'<a href="%s"%s>%s</a>' , esc_url( $url ) , $class_html , $label
					) ;
		}

		/**
		 * get current url
		 * */
		private function prepare_current_url() {
			//Build row actions
			if ( isset( $_GET[ 'status' ] ) ) {
				$args[ 'status' ] = sanitize_key( $_GET[ 'status' ] ) ;
			}

			$pagenum         = $this->get_pagenum() ;
			$args[ 'paged' ] = $pagenum ;
			$url             = add_query_arg( $args , $this->base_url ) ;

			$this->current_url = $url ;
		}

		/**
		 * bulk action functionality
		 * */
		public function process_bulk_action() {

			$ids = isset( $_REQUEST[ 'id' ] ) ? bsf_sanitize_text_field( $_REQUEST[ 'id' ] ) : array() ;
			$ids = ! is_array( $ids ) ? explode( ',' , $ids ) : $ids ;

			if ( ! bsf_check_is_array( $ids ) ) {
				return ;
			}

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die( '<p class="bsf_warning_notice">' . esc_html__( 'Sorry, you are not allowed to edit this item.' , 'zovonto' ) . '</p>' ) ;
			}

			$action = $this->current_action() ;

			foreach ( $ids as $id ) {

				if ( 'delete' === $action ) {
					bsf_delete_payment( $id ) ;
				}
			}

			do_action( $this->plugin_slug . '_admin_field_payments' , $ids , $action ) ;

			wp_safe_redirect( $this->current_url ) ;
			exit() ;
		}

		/**
		 * Prepare cb column data
		 * */
		protected function column_cb( $item ) {
			return sprintf(
					'<input type="checkbox" name="id[]" value="%s" />' , $item->get_id()
					) ;
		}

		/**
		 * Prepare each column data
		 * */
		protected function column_default( $item, $column_name ) {


			switch ( $column_name ) {
				case 'id':
					return esc_html( '#' . $item->get_id() ) ;
					break ;
				case 'staff':
					return esc_html( $item->get_staff()->get_name() ) ;
					break ;
				case 'payment_method':
					$payment_method = $item->get_payment_method() ;
					return bsf_display_payment_method( $payment_method ) ;
					break ;
				case 'service':
					return esc_html( $item->get_services()->get_name() ) ;
					break ;
				case 'customer':
					return esc_html( $item->get_customer()->get_full_name() ) ;
					break ;
				case 'appointment_date':
					return esc_html( $item->get_appointment()->get_start_date() ) ;
					break ;
				case 'date':
					return BSF_Date_Time::get_date_object_format_datetime( $item->get_date() ) ;
					break ;
				case 'status':
					return bsf_display_status( $item->get_status() ) ;
					break ;
				case 'price':
					return bsf_price( $item->get_price() , array( 'currency' => $item->get_currency() ) ) ;
					break ;
				case 'action':
					$actions             = array() ;
					$actions[ 'edit' ]   = bsf_display_action( 'edit' , $item->get_id() , $this->current_url ) ;
					$actions[ 'delete' ] = bsf_display_action( 'delete' , $item->get_id() , $this->current_url ) ;

					end( $actions ) ;

					$last_key = key( $actions ) ;
					foreach ( $actions as $key => $action ) {
						echo $action ;

						if ( $last_key == $key ) {
							break ;
						}

						echo ' | ' ;
					}

					break ;
			}
		}

		/**
		 * Initialize the columns
		 * */
		private function get_current_page_items() {
			global $wpdb ;

			$status  = isset( $_GET[ 'status' ] ) ? ' and status IN("' . sanitize_key( $_GET[ 'status' ] ) . '")' : '' ;
			$where   = ' WHERE 1=1' . $status ;
			$where   = apply_filters( $this->table_slug . '_query_where' , $where ) ;
			$limit   = apply_filters( $this->table_slug . '_query_limit' , $this->perpage ) ;
			$offset  = apply_filters( $this->table_slug . '_query_offset' , $this->offset ) ;
			$orderby = apply_filters( $this->table_slug . '_query_orderby' , $this->orderby ) ;

			$count_items       = $wpdb->get_results( 'SELECT id FROM ' . $this->table . " $where $orderby" ) ;
			$this->total_items = count( $count_items ) ;

			$prepare_query = $wpdb->prepare( 'SELECT id FROM ' . $this->table . " $where $orderby LIMIT %d,%d" , $offset , $limit ) ;
			$items         = $wpdb->get_results( $prepare_query , ARRAY_A ) ;

			$this->prepare_item_object( $items ) ;
		}

		/**
		 * Prepare item Object
		 * */
		private function prepare_item_object( $items ) {
			$prepare_items = array() ;
			if ( bsf_check_is_array( $items ) ) {
				foreach ( $items as $item ) {
					$prepare_items[] = new BSF_Payment( $item[ 'id' ] ) ;
				}
			}

			$this->items = $prepare_items ;
		}

		/**
		 * get total item from status
		 * */
		private function get_total_item_for_status( $status = '' ) {
			global $wpdb ;
			if ( $status ) {
				$status = "WHERE status='" . $status . "'" ;
			}
			$data = $wpdb->get_results( 'SELECT id FROM ' . $this->table . " $status" , ARRAY_A ) ;

			return count( $data ) ;
		}

		/**
		 * Filters Functionality
		 * */
		public function query_for_filters( $where ) {

			$where = $this->custom_search( $where ) ;

			return $where ;
		}

		/**
		 * Custom Search
		 * */
		public function custom_search( $where ) {
			global $wpdb ;
			if ( isset( $_REQUEST[ 's' ] ) ) {

				$search_ids = array() ;
				$terms      = explode( ',' , wp_unslash( $_REQUEST[ 's' ] ) ) ;

				foreach ( $terms as $term ) {
					$term           = $wpdb->esc_like( ( $term ) ) ;
					$payment_table  = BSF_Tables_Instances::get_table_by_id( 'payments' )->get_table_name() ;
					$service_table  = BSF_Tables_Instances::get_table_by_id( 'services' )->get_table_name() ;
					$staff_table    = BSF_Tables_Instances::get_table_by_id( 'staff' )->get_table_name() ;
					$customer_table = BSF_Tables_Instances::get_table_by_id( 'customers' )->get_table_name() ;
					$query          = new BSF_Query( $payment_table ) ;

					$payment_ids = $query->select( '`t`.`id`' )
									->InnerJoin( $staff_table , 'st' , '`st`.`id` = `t`.`staff_id`' )
									->whereLike( '`st` .`name`' , '%' . $term . '%' , 'OR' )
									->whereLike( '`t` .`id`' , '%' . $term . '%' , 'OR' )->fetchArray() ;

					$payment_ids = $query->select( '`t`.`id`' )
									->InnerJoin( $service_table , 'se' , '`se`.`id` = `t`.`service_id`' )
									->whereLike( '`se` .`name`' , '%' . $term . '%' , 'OR' )->fetchArray() ;

					$payment_ids = $query->select( '`t`.`id`' )
									->InnerJoin( $customer_table , 'cu' , '`cu`.`id` = `t`.`customer_id`' )
									->whereLike( '`cu` .`first_name`' , '%' . $term . '%' , 'OR' )
									->whereLike( '`cu` .`last_name`' , '%' . $term . '%' , 'OR' )
									->whereLike( '`cu` .`email`' , '%' . $term . '%' , 'OR' )->fetchArray() ;
				}
				$payment_ids = array_column( $payment_ids , 'id' ) ;
				$payment_ids = array_filter( array_unique( array_merge( $payment_ids ) ) ) ;
				$search_ids  = bsf_check_is_array( $payment_ids ) ? $payment_ids : array( 0 ) ;

				$where .= ' AND (id IN (' . implode( ',' , $search_ids ) . '))' ;
			}

			return $where ;
		}

	}

}
