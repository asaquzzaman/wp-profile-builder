<?php
/**
 * User Profile General Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPUP_Settings_General' ) ) :

/**
 * WPUP_Admin_Settings_General.
 */
class WPUP_Settings_General extends WPUP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'general';
		$this->label = __( 'General', 'wpup' );

		add_filter( 'wpup_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'wpup_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'wpup_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = apply_filters( 'wpup_general_settings', array(

			array( 'title' => __( 'General Options', 'wpup' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

			// array(
			// 	'title'    => __( 'Profile Builder', 'wpup' ),
			// 	'id'       => 'wpup_frontend_page_id',
			// 	'type'     => 'single_select_page',
			// 	'default'  => '',
			// 	'class'    => 'wpup-enhanced-select-nostd',
			// 	'css'      => 'min-width:300px;',
			// ),

			array(
				'title'    => __( 'Users List', 'wpup' ),
				'id'       => 'wpup_members_page_id',
				'type'     => 'single_select_page',
				'default'  => '',
				'class'    => 'wpup-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'title'    => __( 'My Profile', 'wpup' ),
				'id'       => 'wpup_my_profile_page_id',
				'type'     => 'single_select_page',
				'default'  => '',
				'class'    => 'wpup-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array(
				'title'    => __( 'Users Per Page', 'wpup' ),
				'id'       => 'wpup_members_per_page',
				'type'     => 'text',
				'default'  => '20',
				'class'    => 'wpup-enhanced-select-nostd',
				'css'      => 'min-width:300px;',
			),

			array( 'type' => 'sectionend', 'id' => 'pricing_options' )

		) );

		return apply_filters( 'wpup_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output a colour picker input box.
	 *
	 * @param mixed $name
	 * @param string $id
	 * @param mixed $value
	 * @param string $desc (default: '')
	 */
	public function color_picker( $name, $id, $value, $desc = '' ) {
		echo '<div class="color_box">' . wc_help_tip( $desc ) . '
			<input name="' . esc_attr( $id ). '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
		</div>';
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();

		WPUP_Admin_Settings::save_fields( $settings );
	}

}

endif;

return new WPUP_Settings_General();
