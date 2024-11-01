<?php
/**
 * Help Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'BSF_Help_Tab' ) ) {
	return new BSF_Help_Tab() ;
}

/**
 * BSF_Help_Tab.
 */
class BSF_Help_Tab extends BSF_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'help' ;
		$this->code  = 'fa-life-ring' ;
		$this->label = __( 'Help' , 'zovonto' ) ;

		add_action( $this->plugin_slug . '_admin_field_output_help' , array( $this , 'output_help' ) ) ;

		parent::__construct() ;
	}

	/**
	 * Get settings array.
	 */
	public function get_settings( $current_section = '' ) {
		return array(
			array( 'type' => 'output_help' )
				) ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the help content
	 */
	public function output_help() {
		$support_site_url = '<a href="https://flintop.com/support" target="_blank"> ' ;
		?>
		<div class="bsf_help_content">
			<h3><?php esc_html_e( 'Documentation' , 'zovonto' ) ; ?></h3>
			<p> <?php esc_html_e( 'Please check the documentation as we have lots of information there. The documentation file can be found inside the documentation folder which you will find when you unzip the downloaded zip file.' , 'zovonto' ) ; ?></p>
			<h3><?php esc_html_e( 'Contact Support' , 'zovonto' ) ; ?></h3>
			<p id="bsf_support_content"> <?php printf( __( 'For support, feature request or any help, please %s register and open a support ticket on our site' , 'zovonto' ) , $support_site_url ) ; ?></a></p>   
		</div>
		<?php
	}

}

return new BSF_Help_Tab() ;
