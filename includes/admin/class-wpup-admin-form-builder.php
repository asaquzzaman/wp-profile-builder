<?php
/**
 * Form builder page
 *
 * @version 0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPUP_Admin_Form_builder Class.
 */
class WPUP_Admin_Form_builder {

	/**
	 * Handles output of the form builder page in admin.
	 */
	public static function output() {
		
		if ( wpup_get_user_id() ) {
            wpup_get_template('profile-builder/profile-builder.php');
            WPUP_Js_Templates::profile_builder();
        
        } else {
            wp_login_form();
        }
	}
}
