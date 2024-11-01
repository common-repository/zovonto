<?php
/* Edit Staff */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="bsf_staff_wrapper">
	<div class="bsf_staff_frame">
		<button class="bsf_add_staff_popup"><?php esc_html_e( 'Add Staff' , 'zovonto' ) ; ?></button>
		<div class="bsf_staff_details">
			<?php
			if ( $bsf_staff_id ) {
				bsf_get_staff_edit_panels( $bsf_staff_id ) ;
			}
			?>
		</div>

	</div>
</div>
