<?php
/* Staff General Tab */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="bsf_staff_general_tab_content">
	<div class="bsf_profile_image_edit">
		<div class="bsf_profile_image">
			<input type="hidden" class="bsf_staff_image_attachment_id" name="staff[attachment_id]" value="<?php echo esc_attr( $staff->get_attachment_id() ) ; ?>"/>
			<div class="bsf_upload_image">
				<img src="<?php echo esc_url( $staff->get_image_url() ) ; ?>" class="bsf_profile_edit_image_url" alt="<?php esc_html_e( 'Upload Image' , 'zovonto' ) ; ?>" title="<?php esc_html_e( 'Upload Image' , 'zovonto' ) ; ?>"/>
			</div>
			<div class="bsf_profile_image_upload">
				<i class="fa fa-upload bsf_upload_staff_image" aria-hidden="true" title="<?php esc_html_e( 'Upload' , 'zovonto' ); ?>"></i>
				<i class="fa fa-trash bsf_delete_staff_image" aria-hidden="true" title="<?php esc_html_e( 'Delete' , 'zovonto' ); ?>"></i>
			</div>
		</div>
		<div class="bsf_profile_staff_name">
			<h3><?php echo esc_html( $staff->get_name() ) ; ?></h3>
		</div>
	</div>
	<table>
		<tr class="form-control">
			<th><?php esc_html_e( 'Fullname' , 'zovonto' ) ; ?></th>
			<td><input type="text" name="staff[name]" value="<?php echo esc_attr( $staff->get_name() ) ; ?>"/></td>
		</tr>
		<tr class="form-control">
			<th><?php esc_html_e( 'Email' , 'zovonto' ) ; ?></th>
			<td><input type="text" name="staff[email]" value="<?php echo esc_attr( $staff->get_email() ) ; ?>"/></td>
		</tr>
		<tr class="form-control">
			<th><?php esc_html_e( 'Phone Number' , 'zovonto' ) ; ?></th>
			<td><input type="text" name="staff[phone]" value="<?php echo esc_attr( $staff->get_phone() ) ; ?>"/></td>
		</tr>
		<tr class="form-control">
			<th><?php esc_html_e( 'Info' , 'zovonto' ) ; ?></th>
			<td><textarea name="staff[info]"><?php echo esc_html( $staff->get_info() ) ; ?></textarea></td>
		</tr>
	</table>
</div>
<?php
