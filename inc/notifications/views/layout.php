<?php
/* Layout */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

$notifications = Zovonto::instance()->notifications() ;
?>
<h2 class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_notifications_title"><?php esc_html_e( 'Notifications' , 'zovonto' ) ; ?></h2>
<?php
foreach ( $notifications as $notification ) {
	if ( ! $notification->get_id() ) {
		continue ;
	}

	$notification_grid_class = ( $notification->is_enabled() ) ? $this->plugin_slug . '_notification_active' : $this->plugin_slug . '_notification_inactive' ;
	?>
	<div class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_notifications_grid">
		<input class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_notification_name" type="hidden" value="<?php echo esc_attr( $notification->get_id() ) ; ?>" />
		<div class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_notifications_grid_inner <?php echo esc_attr( $notification_grid_class ) ; ?>">
			<div class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_notifications_grid_inner_top">
				<h3><?php echo esc_html( $notification->get_title() ) ; ?></h3>
			</div>
			<div class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_notifications_grid_inner_bottom">
				<label class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_switch">
					<input class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_notifications_enabled" type="checkbox" value="true" <?php checked( $notification->is_enabled() , true ); ?>>
					<span class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_slider <?php echo esc_attr( $this->plugin_slug ) ; ?>_round"></span>
				</label>
				<?php
				if ( $notification->settings_link() ) {
					$display_style = ( ! $notification->is_enabled() ) ? 'bsf_hide' : '' ;
					?>
					<a class="<?php echo esc_attr( $this->plugin_slug ) ; ?>_settings_link <?php echo esc_attr( $display_style ) ; ?>" href="<?php echo esc_url( $notification->settings_link() ) ; ?>"><?php esc_html_e( 'Settings' , 'zovonto' ) ; ?></a>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
}
