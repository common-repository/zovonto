<?php
/* Staff Edit Panel */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="bsf_edit_staff">
	<div class="bsf_staff_tab_wrapper_content">
		<div class="bsf_staff_tab_content">
			<?php include_once 'general.php' ; ?>
		</div>
	</div>

	<div class="bsf_staff_tab_footer">
		<button type="button" class="bsf_staff_delete_btn"><?php esc_html_e( 'Delete' , 'zovonto' ) ; ?></button>
		<input name='bsf_save' class='button-primary bsf_save_btn' type='submit' value="<?php esc_attr_e( 'Update Staff' , 'zovonto' ) ; ?>" />
		<input type="hidden" name="edit_staff" value="edit"/>
		<input type="hidden" class="bsf_staff_id" name="staff[id]" value="<?php echo esc_attr( $staff->get_id() ) ; ?>"/>
		<?php
		wp_nonce_field( 'bsf_edit_staff' , '_bsf_nonce' , false , true ) ;
		?>
	</div>
</div>
<?php
