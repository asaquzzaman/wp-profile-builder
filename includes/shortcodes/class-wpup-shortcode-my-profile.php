<?php
/**
 */
class WPUP_Shortcode_My_Profile {

	/**
	 * Get the shortcode content.
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function get( $atts ) {
		return WPUP_Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
	}

	/**
	 * Output the shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts = array() ) {
		echo '<div id="wpup-user-profile"></div>';

		WPUP_Frontend_Scripts::profile_builder_scripts();
	}

}
