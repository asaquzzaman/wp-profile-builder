<?php
/**
 * My Profile
 *
 * @version 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPUP_Admin_My_Profile Class.
 */
class WPUP_Admin_My_Profile {

	/**
	 * Handles output of the my profile
	 */
	public static function output() {
		wpup_get_template('my-profile/my-profile.php');
		WPUP_Js_Templates::my_profile();
	}
}
