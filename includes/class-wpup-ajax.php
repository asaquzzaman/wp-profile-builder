<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WPUP_Shortcodes class
 *
 * @class       WPUP_Shortcodes
 * @version     0.1
 */
class WPUP_Ajax {
    /**
     * Hook in ajax handlers.
     */
    public static function init() {
        self::add_ajax_events();
    }

    /**
     * Hook in methods - uses WordPress ajax handlers (admin-ajax).
     */
    public static function add_ajax_events() {
        // woocommerce_EVENT => nopriv
        $ajax_events = array(
            'wpup_update_user_profile_settings' => false,
            'wpup_ajax_upload'                  => false,
            'wpup_remove_attachment'            => false,
            'wpup_new_profile'                  => false,
            'wpup_new_profile_picture'          => false,
            'wpup_get_users'                    => 'both',
            'wpup_get_user_profile'             => 'both',
        );

        foreach ( $ajax_events as $ajax_event => $nopriv ) {
            
            if ( $nopriv ) {
                add_action( 'wp_ajax_nopriv_' . $ajax_event, array( __CLASS__, $ajax_event ) );
            } else {
                add_action( 'wp_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
            }

            if ( 'both' == $nopriv ) {
                add_action( 'wp_ajax_nopriv_' . $ajax_event, array( __CLASS__, $ajax_event ) );
                add_action( 'wp_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
            }
        }
    }

    /**
     * Insert or update user profile settings
     * 
     * @since 0.1
     * 
     * @return void
     */
    public static function wpup_update_user_profile_settings() {

        check_ajax_referer('wpup-nonce');

        $postdata = $_POST;

        $postdata['header'] = json_decode( wp_unslash( $postdata['header'] ), true );
        $postdata['rows']   = json_decode( wp_unslash( $postdata['rows'] ), true );
        $postdata['cols']   = json_decode( wp_unslash( $postdata['cols'] ), true );
        $postdata['els']    = json_decode( wp_unslash( $postdata['els'] ), true );
        
        $id   = ( isset( $postdata['profile_id'] ) && intval( $postdata['profile_id'] ) ) ? intval( $postdata['profile_id'] ) : false;

        $args = array(
            'post_title'   => 'User Profile',
            'post_content' => 'Description',
            'post_status'  => 'publish',
            'post_type'    => 'wpup_user_profile'
        );
        
        if ( $id ) {
            $args['ID'] = $id;
            $post_id = wp_update_post( $args );
        
        } else {
            $post_id = wp_insert_post( $args );
        }

        self::update_post_meta( $post_id, $postdata );

        wp_send_json_success( array( 'success' => __( 'Sucessfull updated', 'cpm' ) ) );
    }

    /**
     * Update profile post meta
     * 
     * @since 0.1
     * 
     * @param  int $post_id  
     * @param  array $postdata 
     * 
     * @return void
     */
    public static function update_post_meta( $post_id, $postdata ) {
        
        update_post_meta( $post_id, '_rows', $postdata['rows'] );
        update_post_meta( $post_id, '_cols', $postdata['cols'] );
        update_post_meta( $post_id, '_els', $postdata['els'] );
        update_post_meta( $post_id, '_header', $postdata['header'] );
        update_post_meta( $post_id, '_content_width', $postdata['content_width'] );
        update_post_meta( $post_id, '_content_width_unit', $postdata['content_width_unit'] );
    }

    /**
     * File uploader
     * 
     * @since 0.1
     * 
     * @return array
     */
    public static function wpup_ajax_upload() {
        check_ajax_referer( 'wpup_ajax_upload', 'nonce' );
        
        $object_id   = isset( $_REQUEST['object_id'] ) ? intval( $_REQUEST['object_id'] ) : 0;
        $response    = WPUP_Uploader::upload_file( $object_id );

        if ( $response['success'] ) {
            $file = WPUP_Uploader::get_file( $response['file_id'] );
            
            wp_send_json_success( 
                array( 
                    'file' => array( 
                        'name'  => esc_attr( $file['name'] ),
                        'id'    => $file['id'],
                        'url'   => $file['url'],
                        'thumb' => $file['thumb'],
                        'type'  => $file['type']
                    )
                )
            );
        } else {
            wp_send_json_error( array( 'error' => $response['error'] ) );
        }
    }

    /**
     * Remove attachment file
     * 
     * @since 0.1
     * 
     * @return void
     */
    public static function wpup_remove_attachment() {
        check_ajax_referer('wpup-nonce');
        
        $attach_id = absint( $_POST['attach_id'] ) ? $_POST['attach_id'] : false; 
        
        WPUP_Uploader::delete_file( $attach_id );
        update_user_meta( get_current_user_id(), 'profile_picture_id', '' );
        
        wp_send_json_success();
    }

    /**
     * Update new profile
     * 
     * @since 0.1
     * 
     * @return void
     */
    public static function wpup_new_profile() {
        check_ajax_referer('wpup-nonce');
        
        $user_id  = get_current_user_id();
        $postdata = $_POST;
        $els      = $postdata['els'];
        $headers  = $postdata['header'];

        wpup_update_back_end_profile();

        foreach ( $els as $key => $el ) {
            $meta_key = 'ele_' . $el['ele_settings_field_val']['name'];
            
            update_user_meta( $user_id, $meta_key, $el );

            update_user_meta( $user_id, $el['ele_settings_field_val']['name'], $el['field_val'] );
        }
        
        wpup_update_header( $user_id, $headers );

        wp_send_json_success( array( 'success' => __( 'Sucessfull updated', 'wpup' ) ) );
    }

    /**
     * Update user profile picture id
     * 
     * @since 0.1
     * 
     * @return void
     */
    public static function wpup_new_profile_picture() {
        check_ajax_referer('wpup-nonce');
        $id = absint( $_POST['profile_pic_id'] );
        
        if ( ! $id ) {
            wp_send_json_error();
        }
        
        update_user_meta( get_current_user_id(), 'profile_picture_id', $id );

        wp_send_json_success( array( 'success' => __( 'Sucessfull updated', 'wpup' ) ) );
    }

    /**
     * Ajax request for getting users
     * 
     * @since 0.1
     * 
     * @return void
     */
    public static function wpup_get_users() {
        check_ajax_referer('wpup-nonce');
        $page_number = absint( $_POST['page_number'] );
        $user        = empty( $_POST['user'] ) ? false : trim( $_POST['user'] );
        $is_admin    = $_POST['is_admin'];

        $users = wpup_get_users( $page_number, $user, array( 'is_admin' => $is_admin ) );

        wp_send_json_success( array( 
            'success'     => __( 'Sucessfull updated', 'wpup' ), 
            'users'       => $users->results, 
            'total_users' => $users->total_users,
            'limit'       => get_option( 'wpup_members_per_page', '20' )
        ));
    }

    /**
     * Ajax request for getting individual user data
     * 
     * @since 0.1
     * 
     * @return void
     */
    public static function wpup_get_user_profile() {
        check_ajax_referer('wpup-nonce');

        $user_id = absint( $_POST['user_id'] );

        $profile_picture_id = get_user_meta( $user_id, 'profile_picture_id', true );
        $profile_picture_id = empty( $profile_picture_id ) ? false : $profile_picture_id;

        $user_profile = wpup_get_profile_data( $user_id );
        $user_profile->profile_picture_url = wpup_get_user_profile_picture_url( $user_id );
        $user_profile->profile_picture_id  = $profile_picture_id;
        $user_profile->current_user_id     = $user_id;
       
        wp_send_json_success( array( 'profile' => $user_profile ) );
    }
}

WPUP_Ajax::init();

