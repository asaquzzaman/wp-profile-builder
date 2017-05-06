<?php
/**
 */
class WPUP_Shortcode_Members {

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
		wpup_get_template('members/members.php');
		
		WPUP_Js_Templates::members();

		WPUP_Frontend_Scripts::member_scripts();
	}

}
