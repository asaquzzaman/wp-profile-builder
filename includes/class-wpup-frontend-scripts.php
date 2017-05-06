<?php
/**
 * Handle frontend scripts
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
class WPUP_Frontend_Scripts {

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
		self::$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        self::$version = time();
		//add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );
	}

	/**
	 * Register/queue frontend profile builder scripts.
	 *
	 * @since  0.1
	 *
	 * @return void
	 */
	public static function profile_builder_scripts() {
		if ( ! wpup_get_user_id() ) {
			return;
		}

		self::$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        self::$version = time();
		
		global $post;

		$time_zone_string      = file_get_contents( WPUP_URL . '/assets/js/moment/latest.json' );
        $json_time_zone_string = json_decode($time_zone_string, true);
        $current_user          = get_user_by( 'id', wpup_get_user_id() );

		wp_enqueue_media();
		wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );
        wp_enqueue_script( 'plupload-handlers' );
        wp_enqueue_script( 'wpup-moment' );
        wp_enqueue_script( 'wpup-moment-timezone' );
        wp_enqueue_script( 'wpup-uploader' );
        wp_enqueue_script( 'wpup-toastr' );
        wp_enqueue_script( 'wpup-sortable' );
		wp_enqueue_script( 'wpup-profile-builder' );
		
		wp_localize_script( 'wpup-main', 'wpup', array(
			'nonce'                        => wp_create_nonce( 'wpup-nonce' ),
			'ajaxurl'                      => admin_url( 'admin-ajax.php' ),
			'countries'                    => include WPUP_PATH . '/i18n/countries.php',
			'time_zones'                   => $json_time_zone_string['zones'],
			'time_links'                   => $json_time_zone_string['links'],
			'current_user'                 => $current_user,
			'current_logged_in_user'       => wp_get_current_user(),
			'profile_data'                 => wpup_get_profile_data(),
			'profile_picture_url'          => wpup_get_user_profile_picture_url(), 
			'profile_picture_id'           => empty( get_user_meta( wpup_get_user_id(), 'profile_picture_id', true ) ) ? false : get_user_meta( wpup_get_user_id(), 'profile_picture_id', true ),
			'profile_details'              => wpup_default_profile_details(),
			'social_profile'               => wpup_social_profile(),
			'is_user_admin'                => is_current_user_admin(),
			'user_can_update_profile'      => wpup_user_can_update_profile(),
			'wp_date_format'               => get_option( 'date_format' ),
			'wp_time_format'               => get_option( 'time_format' ),
			'display_name_drop_down_array' => wpup_dispaly_name_dropdown_array(),
			'selected_display_name_key'    => wpup_get_selected_display_name_key(),
			//'profile_fields'			   => wpup_profile_fields(),
			'default_header'	           => wpup_default_profile_header( $current_user ),
			'user_meta'               => array(
				'first_name'  => get_user_meta( wpup_get_user_id(), 'first_name', true ),
				'last_name'   => get_user_meta( wpup_get_user_id(), 'last_name', true ),
				'description' => get_user_meta( wpup_get_user_id(), 'description', true ),
			),
			'plupload'      => array(
                'browse_button'       => 'wpup-upload-pickfiles',
                'container'           => 'wpup-upload-container',
                'max_file_size'       => 2097152 . 'b',
                'url'                 => admin_url( 'admin-ajax.php' ) . '?action=wpup_ajax_upload&nonce=' . wp_create_nonce( 'wpup_ajax_upload' ),
                'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
                'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
                'filters'             => array( array( 'title' => __( 'Allowed Files' ), 'extensions' => '*' ) ),
                'resize'              => array( 'width' => ( int ) get_option( 'large_size_w' ), 'height' => ( int ) get_option( 'large_size_h' ), 'quality' => 100 )
            )
        ));

		wp_enqueue_style( 'wpup-toastr' );
        wp_enqueue_style( 'wpup-jquery-ui-custom-css' );
		wp_enqueue_style( 'wpup-front-end-style' );
		wp_enqueue_style( 'wpup-fontawesome' );
	}

	/**
	 * Member lists scripts.
	 *
	 * @since  0.1
	 *
	 * @return void
	 */
	public static function member_scripts() {

		$users = wpup_get_users($pagenum = 1, $user = false, array( 
			'is_admin' =>  is_admin() ? 'yes' : 'no'
		));

		$time_zone_string      = file_get_contents( WPUP_URL . '/assets/js/moment/latest.json' );
        $json_time_zone_string = json_decode($time_zone_string, true);
        $current_user          = get_user_by( 'id', wpup_get_user_id() );

		wp_enqueue_media();
		wp_enqueue_script( 'plupload-handlers' );
        wp_enqueue_script( 'wpup-moment' );
        wp_enqueue_script( 'wpup-moment-timezone' );
        wp_enqueue_script( 'wpup-uploader' );
		wp_enqueue_script( 'wpup-members' );

		wp_localize_script( 'wpup-main', 'wpup', array(
			'nonce'        => wp_create_nonce( 'wpup-nonce' ),
			'ajaxurl'      => admin_url( 'admin-ajax.php' ),
			'current_user' => $current_user,
			'current_logged_in_user'       => wp_get_current_user(),
			'time_zones'   => $json_time_zone_string['zones'],
			'time_links'   => $json_time_zone_string['links'],
			'default_header' => wpup_default_profile_header( $current_user ),
        ));

		wp_localize_script( 'wpup-members', 'WPUP_Member', array(
			'nonce'        => wp_create_nonce( 'wpup-nonce' ),
			'ajaxurl'      => admin_url( 'admin-ajax.php' ),
			'users'        => $users->results,
			'current_user' => get_user_by( 'id', wpup_get_user_id() ),
			'is_admin'     => is_admin() ? 'yes' : 'no',
			
        ));

        wp_enqueue_style( 'wpup-toastr' );
        wp_enqueue_style( 'wpup-jquery-ui-custom-css' );
		wp_enqueue_style( 'wpup-front-end-style' );
		wp_enqueue_style( 'wpup-fontawesome' );
	}

	/**
	 * My profile scripts.
	 *
	 * @since  0.1
	 *
	 * @return void
	 */
	public static function my_profile_scripts() {
		if ( ! wpup_get_user_id() ) {
			return;
		}

		self::$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        self::$version = time();
		
		global $post;

		$time_zone_string      = file_get_contents( WPUP_URL . '/assets/js/moment/latest.json' );
        $json_time_zone_string = json_decode($time_zone_string, true);
        $current_user          = get_user_by( 'id', wpup_get_user_id() );

		wp_enqueue_media();
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'plupload-handlers' );
        wp_enqueue_script( 'wpup-moment' );
        wp_enqueue_script( 'wpup-moment-timezone' );
        wp_enqueue_script( 'wpup-uploader' );
        wp_enqueue_script( 'wpup-toastr' );
		wp_enqueue_script( 'wpup-my-profile' );
		wp_localize_script( 'wpup-main', 'wpup', array(
			'nonce'                        => wp_create_nonce( 'wpup-nonce' ),
			'ajaxurl'                      => admin_url( 'admin-ajax.php' ),
			'countries'                    => include WPUP_PATH . '/i18n/countries.php',
			'time_zones'                   => $json_time_zone_string['zones'],
			'time_links'                   => $json_time_zone_string['links'],
			'current_user'                 => $current_user,
			'current_logged_in_user'       => wp_get_current_user(),
			'profile_data'                 => wpup_get_profile_data(),
			'profile_picture_url'          => wpup_get_user_profile_picture_url(), 
			'profile_picture_id'           => empty( get_user_meta( wpup_get_user_id(), 'profile_picture_id', true ) ) ? false : get_user_meta( wpup_get_user_id(), 'profile_picture_id', true ),
			'profile_details'              => wpup_default_profile_details(),
			'social_profile'               => wpup_social_profile(),
			'is_user_admin'                => is_current_user_admin(),
			'user_can_update_profile'      => wpup_user_can_update_profile(),
			'wp_date_format'               => get_option( 'date_format' ),
			'wp_time_format'               => get_option( 'time_format' ),
			'display_name_drop_down_array' => wpup_dispaly_name_dropdown_array(),
			'selected_display_name_key'    => wpup_get_selected_display_name_key(),
			//'profile_fields'			   => wpup_profile_fields(),
			'user_meta'               => array(
				'first_name'  => get_user_meta( wpup_get_user_id(), 'first_name', true ),
				'last_name'   => get_user_meta( wpup_get_user_id(), 'last_name', true ),
				'description' => get_user_meta( wpup_get_user_id(), 'description', true ),
			),
			'plupload'      => array(
                'browse_button'       => 'wpup-upload-pickfiles',
                'container'           => 'wpup-upload-container',
                'max_file_size'       => 2097152 . 'b',
                'url'                 => admin_url( 'admin-ajax.php' ) . '?action=wpup_ajax_upload&nonce=' . wp_create_nonce( 'wpup_ajax_upload' ),
                'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
                'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
                'filters'             => array( array( 'title' => __( 'Allowed Files' ), 'extensions' => '*' ) ),
                'resize'              => array( 'width' => ( int ) get_option( 'large_size_w' ), 'height' => ( int ) get_option( 'large_size_h' ), 'quality' => 100 )
            )
        ));

		wp_enqueue_style( 'wpup-toastr' );
        wp_enqueue_style( 'wpup-jquery-ui-custom-css' );
        //wp_enqueue_style( 'wpup-admin-profile-builder' );
		wp_enqueue_style( 'wpup-front-end-style' );
		wp_enqueue_style( 'wpup-fontawesome' );
	}
}

WPUP_Frontend_Scripts::init();
