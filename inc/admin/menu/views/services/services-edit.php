<?php
/* All Services */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="bsf_services_wrapper">
	<div class="bsf_services_list">
		<div class="bsf_all_services_title">
			<label class="bsf_services_list_title"><?php esc_html_e( 'All Services' , 'zovonto' ) ; ?></label>
			<button class="bsf_add_service"><?php esc_html_e( 'Add Service' , 'zovonto' ) ; ?></button>
		</div>
		<div class="bsf_added_services">
			<?php foreach ( $services as $service ) : ?>
				<div class="bsf_newly_added_services bsf_newly_added_services_<?php echo esc_attr( $service[ 'id' ] ) ; ?>" data-serviceid="<?php echo esc_attr( $service[ 'id' ] ) ; ?>">
					<div class="bsf_services_name">
						<i class="fa fa-bars bsf_drag_services" aria-hidden="true"></i> <h3 class="bsf_update_services_name"><?php echo esc_html( $service[ 'name' ] == '' ? 'Untitled' : $service[ 'name' ]  ) ; ?></h3>
						<input type="checkbox" name="bsf_delete_service" data-serviceid="<?php echo esc_attr( $service[ 'id' ] ) ; ?>"/>
						<i class="fa fa-chevron-circle-down bsf_toggle_services_panel" aria-hidden="true"></i>
					</div>
					<div class="bsf_services_info">
						<div class="bsf_services_info_row">
							<label><?php esc_html_e( 'Title' , 'zovonto' ) ; ?></label>
							<input type="text" class="bsf_services_title" value="<?php echo esc_attr( $service[ 'name' ] ) ; ?>"/>
						</div>
						<div class="bsf_services_info_row">
							<label><?php esc_html_e( 'Color' , 'zovonto' ) ; ?></label>
							<input type="text" class="bsf_services_color bsf_colorpicker" value="<?php echo esc_attr( $service[ 'color' ] ) ; ?>"/>
						</div>
						<div class="bsf_services_info_row">
							<label><?php esc_html_e( 'Price' , 'zovonto' ) ; ?></label>
							<input type="number" class="bsf_services_price" value="<?php echo esc_attr( $service[ 'price' ] ) ; ?>"/>
						</div>
						<div class="bsf_services_info_row">
							<label><?php esc_html_e( 'Duration' , 'zovonto' ) ; ?></label>
							<select class="bsf_services_duration">
								<?php
								$duration_options = bsf_get_service_duration_options( $service[ 'duration' ] ) ;
								foreach ( $duration_options as $key => $name ) {
									?>
									<option value="<?php echo esc_attr( $key ) ; ?>" 
															  <?php 
																if ( $service[ 'duration' ] == $key ) {
																	?>
										selected="selected"<?php } ?>><?php esc_html_e( $name ) ; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class = "bsf_services_info_row">
							<label><?php esc_html_e( 'Time Slot Length' , 'zovonto' ) ; ?></label>
							<select class="bsf_services_time_slot">
								<?php
								$time_slot_duration_options = get_bsf_service_time_slot_length_options() ;
								foreach ( $time_slot_duration_options as $key => $name ) {
									?>
									<option value="<?php echo esc_attr( $key ) ; ?>" 
															  <?php 
																if ( $service[ 'slot_duration' ] == $key ) {
																	?>
										selected="selected"<?php } ?>><?php esc_html_e( $name ) ; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="bsf_services_info_row">
							<label><?php esc_html_e( 'Info' , 'zovonto' ) ; ?></label>
							<textarea class="bsf_services_service_info"><?php esc_html_e( $service[ 'info' ] ) ; ?></textarea>
						</div>
						<div class="bsf_add_working_hours_wrapper" >
							<?php do_action( 'bsf_after_service_form_fields' , $service[ 'id' ] ) ; ?>
						</div>
						<div class="bsf_services_info_row">
							<button class="bsf_save_services" data-serviceid="<?php echo esc_attr( $service[ 'id' ] ) ; ?>"><?php esc_html_e( 'Save' , 'zovonto' ) ; ?></button>
						</div>
					</div>
				</div>
			<?php endforeach ; ?>
		</div>
		<div class="bsf_delete_services">
			<button class="bsf_delete_services_btn"><?php esc_html_e( 'Delete' , 'zovonto' ) ; ?></button>
		</div>
	</div>
</div>
