<?php
/**
 * Handle scripts register
 *
 * @class       WPUP_Frontend_Scripts
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPUP_Frontend_Scripts Class.
 */
class WPUP_Scripts_Register {

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
	 * Hook in methods.
	 */
	public static function init() {
		self::$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '';
        self::$version = time();
		
		add_action( 'init', array( __CLASS__, 'register_scripts' ) );
		add_action( 'init', array( __CLASS__, 'register_style' ) );
	}

	/**
	 * Register all scripts.
	 *
	 * @since  0.1
	 *
	 * @return void
	 */
	public static function register_scripts() {
		
		$time_zone_string      = file_get_contents( WPUP_URL . '/assets/js/moment/latest.json' );
        $json_time_zone_string = json_decode($time_zone_string, true);

        wp_register_script( 'wpup-main', WPUP_ASSETS . '/js/wpup' . self::$suffix . '.js', array( 'jquery' ), self::$version, false );
        wp_localize_script( 'wpup-main', 'wpup', array(
			'nonce'   => wp_create_nonce( 'wpup-nonce' ),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'url'     => plugin_dir_url( dirname( __FILE__ ) ),
			'suffix'     => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min',
			'patch'      => dirname( dirname( __FILE__ ) ),
			'view_path'  => dirname( dirname( __FILE__ ) ) . '/views',
			'url'        => plugin_dir_url( dirname( __FILE__ ) ),
			'assets_url' => plugin_dir_url( dirname( __FILE__ ) ) . 'views/assets/',
			'path'       => plugin_dir_path( __FILE__ ),
			'basename' 	=> basename( dirname( dirname( __FILE__ ) ) )
        ));

        wp_register_script( 'wpup-bootstrap', WPUP_URL . '/views/assets/vendor/bootstrap.js', array(), self::$version );
   
   		wp_register_script( 'wpup-mixins', WPUP_URL . '/assets/js/mixins' . self::$suffix . '.js', array( 'jquery' ), self::$version, true );

   		wp_register_script( 'wpup-profile-builder-mixins', WPUP_URL . '/assets/js/profile-builder/mixins' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
   		wp_register_script( 'wpup-profile-builder-store', WPUP_URL . '/assets/js/profile-builder/store' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
   		wp_register_script( 'wpup-my-profile-store', WPUP_URL . '/assets/js/my-profile/store' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
   		wp_register_script( 'wpup-members-store', WPUP_URL . '/assets/js/members/store' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );

        wp_register_script( 'wpup-view-individual-user-profile', WPUP_URL . '/assets/js/components/view-individual-user-profile/view-individual-user-profile' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-profile-header', WPUP_URL . '/assets/js/components/profile-header/profile-header' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );

        wp_register_script( 'wpup-profile-bulder-settings', WPUP_URL . '/assets/js/components/profile-builder-settings/profile-builder-settings' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-row', WPUP_URL . '/assets/js/components/row/row' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-text-field', WPUP_URL . '/assets/js/components/text-field/text-field' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-select-field', WPUP_URL . '/assets/js/components/select-field/select-field' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-radio-field', WPUP_URL . '/assets/js/components/radio-field/radio-field' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-social-field', WPUP_URL . '/assets/js/components/social-field/social-field' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-section-field', WPUP_URL . '/assets/js/components/section-field/section-field' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-password-field', WPUP_URL . '/assets/js/components/password-field/password-field' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-email-field', WPUP_URL . '/assets/js/components/email-field/email-field' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-url-field', WPUP_URL . '/assets/js/components/url-field/url-field' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
        wp_register_script( 'wpup-date-field', WPUP_URL . '/assets/js/components/date-field/date-field' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );

        wp_register_script( 'wpup-moment', WPUP_URL . '/assets/js/moment/moment.min.js', false, self::$version, false, true );
        wp_register_script( 'wpup-moment-timezone', WPUP_URL . '/assets/js/moment/moment-timezone.min.js', false, self::$version, false, true );
        wp_register_script( 'wpup-uploader', WPUP_ASSETS . '/js/upload' . self::$suffix . '.js', false, self::$version, true );
        wp_register_script( 'wpup-toastr', WPUP_ASSETS . '/js/library/toastr/toastr.min.js', false, self::$version, true );
        wp_register_script( 'wpup-sortable', WPUP_ASSETS . '/js/library/sortable/sortable' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
		wp_register_script( 'wpup-vue', WPUP_ASSETS . '/js/library/vue/vue' . self::$suffix . '.js', array( 'jquery', 'wpup-main' ), self::$version, true );
		wp_register_script( 'wpup-vuex', WPUP_ASSETS . '/js/library/vue/vuex' . self::$suffix . '.js', array( 
			'jquery',
			'wpup-vue', 
		), self::$version, true );

		wp_register_script( 'wpup-vue-router', WPUP_ASSETS . '/js/library/vue/vue-router' . self::$suffix . '.js', array( 
			'jquery',
			'wpup-vue',
		), self::$version, true );

		wp_register_script( 'wpup-profile-builder', WPUP_ASSETS . '/js/profile-builder/profile-builder' . self::$suffix . '.js', array( 
			'jquery', 
			'wpup-vue', 
			'wpup-vuex',
			'wpup-vue-router',
			'wpup-main',
			'wpup-mixins',
			'wpup-profile-builder-mixins',
			'wpup-profile-builder-store',
			'wpup-profile-header',
			'wpup-profile-bulder-settings',
			'wpup-row',
			'wpup-text-field',
			'wpup-select-field',
			'wpup-radio-field',
			'wpup-social-field',
			'wpup-section-field',
			'wpup-password-field',
			'wpup-email-field',
			'wpup-url-field',
			'wpup-date-field',

		), self::$version, true );
		
		wp_register_script( 'wpup-members', WPUP_ASSETS . '/js/members/members' . self::$suffix . '.js', array( 
			'jquery', 
			'wpup-vue',
			'wpup-vuex', 
			'wpup-vue-router', 
			'wpup-members-store',
			'wpup-mixins',
			'wpup-main',
			'wpup-view-individual-user-profile',
			'wpup-profile-header',
			'wpup-row',
			'wpup-text-field',
			'wpup-select-field',
			'wpup-radio-field',
			'wpup-social-field',
			'wpup-section-field',
			'wpup-password-field',
			'wpup-email-field',
			'wpup-url-field',
			'wpup-date-field',

		), self::$version, true );
		
		wp_register_script( 'wpup-my-profile', WPUP_ASSETS . '/js/my-profile/my-profile' . self::$suffix . '.js', array( 
			'jquery', 
			'wpup-vue', 
			'wpup-vuex',
			'wpup-main',
			'wpup-mixins',
			'wpup-my-profile-store',
			'wpup-profile-header',
			'wpup-row',
			'wpup-text-field',
			'wpup-select-field',
			'wpup-radio-field',
			'wpup-social-field',
			'wpup-section-field',
			'wpup-password-field',
			'wpup-email-field',
			'wpup-url-field',
			'wpup-date-field', 
		), self::$version, true );

		wp_register_script( 'wpup-app', WPUP_URL . '/views/assets/js/app.js', array(), self::$version, true );
	}

	/**
	 * Register all style.
	 *
	 * @since  0.1
	 *
	 * @return void
	 */
	public static function register_style() {

		wp_register_style( 'wpup-toastr', WPUP_ASSETS . '/css/toastr/toastr.min.css', false, self::$version, false );
        wp_register_style( 'wpup-jquery-ui-custom-css', WPUP_ASSETS . '/css/jquery-ui-1.9.1.custom' . self::$suffix . '.css', false, self::$version, false );
		wp_register_style( 'wpup-front-end-style', WPUP_ASSETS . '/css/front-end/front-end' . self::$suffix . '.css', false, self::$version, false );
		wp_register_style( 'wpup-fontawesome', WPUP_ASSETS . '/css/fontawesome/font-awesome.min.css', false, self::$version, false );
		wp_register_style( 'wpup-admin-profile-builder', WPUP_ASSETS . '/css/admin/profile-builder' . self::$suffix . '.css', array( 'wpup-front-end-style' ), self::$version, false );
		wp_register_style( 'wpup-admin-style', WPUP_ASSETS . '/css/admin/admin' . self::$suffix . '.css', false, self::$version );

	}
}

WPUP_Scripts_Register::init();
