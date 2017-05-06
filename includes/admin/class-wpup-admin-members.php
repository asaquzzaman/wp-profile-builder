<?php
/**
 * Member lists
 *
 * @version 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPUP_Admin_Member Class.
 */
class WPUP_Admin_Members {

	/**
	 * Handles output of the member lists
	 */
	public static function output() {
		wpup_get_template('members/members.php');
		WPUP_Js_Templates::members();
	}
}
