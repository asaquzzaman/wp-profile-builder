<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin Menu
 */
class Admin_Menu {

    /**
     * Script and style suffix
     *
     * @var string
     */
    static $suffix;

    /**
     * Script version number
     *
     * @var integer
     */
    static $version;

    /**
     * Kick-in the class
     */
    public function __construct() {
        self::$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        self::$version = time();
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Register the admin menu
     *
     * @return void
     */
    public function admin_menu() {
        $profile    = add_menu_page( __( 'Profile', 'wpup' ), __( 'Profile', 'wpup' ), 'administrator', 'wpup-profile-builder', array( $this, 'user_profile_page' ), 'dashicons-id', null );
        $profile    = add_submenu_page( 'wpup-profile-builder', __('Build Profile', 'wpup' ), __( 'Build Profile', 'wpup' ), 'administrator', 'wpup-profile-builder', array( $this, 'user_profile_page' ), 'dashicons-id', null );
        $my_profile = add_submenu_page( 'wpup-profile-builder', __( 'My Profile', 'wpup' ), __( 'My Profile', 'wpup' ), 'read', 'wpup-profile', array( $this, 'my_profile' ), 'dashicons-id', null );
        $members    = add_submenu_page( 'wpup-profile-builder', __( 'Users List', 'wpup' ), __( 'Users List', 'wpup' ), 'read', 'wpup-members', array( $this, 'members' ), 'dashicons-id', null );
        $settings   = add_submenu_page( 'wpup-profile-builder', __( 'Profile', 'wpup' ), __( 'Settings', 'wpup' ), 'administrator', 'wpup-settings', array( $this, 'settings_page' ), 'dashicons-id', null );
        
        add_action( 'admin_print_styles-' . $profile, array( $this, 'profile_scripts' ) );
        add_action( 'admin_print_styles-' . $members, array( $this, 'member_scripts' ) );
        add_action( 'admin_print_styles-' . $my_profile, array( $this, 'my_profile_scripts' ) );
    }

   /**
     * Load for profile menu sacripts
     *
     * @since  0.1
     *
     * @return void
     */
    function profile_scripts() {  
        WPUP_Admin_Assets::profile_builder_scripts();
    }

    /**
     * Load member lists sacripts
     *
     * @since  0.1
     *
     * @return void
     */
    function member_scripts() {
        WPUP_Admin_Assets::member_scripts();
    }

    /**
     * Load my profile sacripts
     *
     * @since  0.1
     *
     * @return void
     */
    function my_profile_scripts() {
        WPUP_Admin_Assets::my_profile_scripts();
    }

    /**
     * view admin profile menu for this plugin
     *
     * @return void
     */
    function user_profile_page() {
        require_once WPUP_PATH . '/views/index.html';
        //WPUP_Admin_Form_Builder::output();
    }

    /**
     * Members lists page
     *
     * @return void
     */
    function members() {
        WPUP_Admin_Members::output();
    }

    /**
     * My profile page
     *
     * @return void
     */
    function my_profile() {
        WPUP_Admin_My_Profile::output();
    }

    /**
     * view user profile admin settings
     *
     * @return void
     */
    function settings_page() {
        WPUP_Admin_Settings::output();
    }
}

return new Admin_Menu();