<?php
/**
 * Plugin Name: WP Profile Builder 
 * Plugin URI: https://github.com/asaquzzaman/wp-user-profile-builder
 * Description: With the Profile plugin you can create users profile template layout. 
 * Version: 0.1 
 * Author: asaquzzaman
 * Author URI: http://mishubd.com
 * Requires at least: 4.0
 * Tested up to: 4.7.3
 * Text Domain: wpupb
 * Domain Path: /i18n/languages/
 */

/**
 * Copyright (c) 2013 name (email: ). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 * **********************************************************************
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * UserProfile class
 *
 * @class UserProfile The class that holds the entire USER PROFILE plugin
 */
final class UserProfile {

    /**
     * Main UserProfile Instance
     *
     * @since 0.1
     * 
     * @return UserProfile - Main instance
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Constructor for the UserProfile class
     *
     * Sets up all the appropriate hooks and actions within this plugin.
     */
    public function __construct() { 
    	// Define constants
        $this->define_constants();

        // Include required files
        $this->includes();

        // Before active this plugin
        register_activation_hook( __FILE__, array( 'WPUP_Install', 'install' ) );

        // instantiate classes
        $this->instantiate();

        // Initialize the action hooks
        $this->init_actions();

        // load the modules
        $this->load_module();

        // Loaded action
        do_action( 'wpup_loaded' );
    }

    /**
     * Define UserProfile Constants
     *
     * @since 0.1
     * 
     * @return void
     */
    private function define_constants() {
        $this->define( 'WPUP_VERSION', '0.1' );
        $this->define( 'WPUP_DB_VERSION', '0.1' );
        $this->define( 'WPUP_PATH', dirname( __FILE__ ) );
        $this->define( 'WPUP_URL', plugins_url( '', __FILE__ ) );
        $this->define( 'WPUP_ASSETS', WPUP_URL . '/assets' );
        $this->define( 'WPUP_INCLUDES', WPUP_PATH . '/includes' );
        $this->define( 'WPUP_IS_ADMIN', is_admin() );
    }

    /**
     * Define constant if not already set
     *
     * @since 0.1
     *
     * @param  string $name
     * @param  string|bool $value
     * 
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Doing some action before active this plugin
     *
     * @since  0.1
     * 
     * @return void
     */
    function install() {
        update_option( 'wpup_supper_admin', get_current_user_id() );
        update_option( 'wpup_version', WPUP_VERSION );
        update_option( 'wpup_db_version', WPUP_DB_VERSION );
    }

    /**
     * Include the required files
     *
     * @since  0.1
     *
     * @return void
     */
    private function includes() {
        include_once( 'includes/wpup-functions.php' );
      	include_once( 'includes/class-wpup-autoloader.php' );
        include_once( 'includes/class-wpup-ajax.php' );
        include_once( 'includes/class-wpup-post-types.php' ); // Registers post types
        include_once( 'includes/class-wpup-install.php' );
        include_once( 'includes/class-wpup-scripts-register.php' );
        
        if ( $this->is_request( 'admin' ) ) {
        	$this->admin_includes();
        }

        if ( $this->is_request( 'frontend' ) ) {
			$this->frontend_includes();
		}


    }

    /**
     * Include the required admin file
     *
     * @since  0.1
     * 
     * @return void
     */
    function admin_includes() {
    	include_once( 'includes/admin/class-wpup-admin.php' );
    }

    /**
     * Include the required front-end file
     *
     * @since  0.1
     * 
     * @return void
     */
    function frontend_includes() {
        include_once( 'includes/class-wpup-frontend-scripts.php' );               // Frontend Scripts
        include_once( 'includes/class-wpup-shortcodes.php' );                     // Shortcodes class
    }

    /**
     * Instantiate classes
     *
     * @return void
     */
    private function instantiate() {

    }

    /**
     * Initialize WordPress action hooks
     *
     * @return void
     */
    private function init_actions() {
        add_action( 'init', array( 'WPUP_Shortcodes', 'init' ) );
    }

    /**
     * Load the current ERP module
     *
     * We don't load every module at once, just load
     * what is necessary
     *
     * @return void
     */
    public function load_module() {

    }

    /**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

    /**
     * Get the template path.
     * @return string
     */
    public function template_path() {
        return apply_filters( 'wpup_template_path', 'wpup/' );
    }
}


/**
 * Main instance of wpup.
 *
 * Returns the main instance of wpup to prevent the need to use globals.
 *
 * @since  0.1
 * 
 * @return UserProfile
 */
function WPUP() {
	return UserProfile::init();
}

// Global for backwards compatibility.
$GLOBALS['UserProfile'] = WPUP();
