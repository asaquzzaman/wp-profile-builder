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
		wpup_get_template('my-profile/my-profile.php');
		WPUP_Js_Templates::my_profile();
		WPUP_Frontend_Scripts::my_profile_scripts();
	}

}
