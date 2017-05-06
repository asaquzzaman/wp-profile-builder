<?php
/**
 * Load assets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * WPUP_Admin_Assets Class.
 */
class WPUP_Admin_Assets {

	/**
     * Script and style suffix
     *
     * @var string
     */
    protected $suffix;

    /**
     * Script version number
     *
     * @var integer
     */
    protected $version;

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		$this->suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        $this->version = time();
   
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Enqueue styles.
	 */
	public function admin_styles() {
		wp_register_style( 'wpup-style', WPUP_ASSETS . '/css/admin/admin.css', false, $this->version, false );
	}

	/**
	 * Enqueue scripts.
	 */
	public function admin_scripts() {
		
	}

	/**
	 * Load profile builder scripts
	 *
	 * @since 0.1
	 * 
	 * @return void
	 */
	public static function profile_builder_scripts() {
		$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		WPUP_Frontend_Scripts::profile_builder_scripts();
		wp_enqueue_style( 'wpup-admin-profile-builder', WPUP_ASSETS . '/css/admin/profile-builder' . $suffix . '.css', array('wpup-front-end-style'), time(), false );

		//Load, after loading all style
		wp_enqueue_style( 'wpup-admin-style' );
	}

	/**
	 * Load members scripts
	 *
	 * @since 0.1
	 * 
	 * @return void
	 */
	public static function member_scripts() {
		WPUP_Frontend_Scripts::member_scripts();

		//Load, after loading all style
		wp_enqueue_style( 'wpup-admin-style' );
	}

	/**
	 * Load my profile scripts
	 *
	 * @since 0.1
	 * 
	 * @return void
	 */
	public static function my_profile_scripts() {
		WPUP_Frontend_Scripts::my_profile_scripts();

		//Load, after loading all style
		wp_enqueue_style( 'wpup-admin-style' );
	}
}

new WPUP_Admin_Assets();
