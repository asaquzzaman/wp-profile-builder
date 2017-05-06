<?php
/**
 * WPUP Admin
 *
 * @class    WPUP_Admin
 * 
 * @since  0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPUP_Admin class.
 */
class WPUP_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_shortcode( 'wpup', array( $this, 'admin_shortcode' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		include_once WPUP_INCLUDES . '/wpup-functions.php';
		include_once( 'class-wpup-admin-ajax.php' );
		include_once( 'class-wpup-admin-assets.php' );
		include_once( 'class-wpup-admin-menus.php' );
	}

	/**
	 * Fornend form builder shortcode
	 */
	public function admin_shortcode( $atts ) {
		echo 'asdasdkjfhsd';
	}

}

return new WPUP_Admin();