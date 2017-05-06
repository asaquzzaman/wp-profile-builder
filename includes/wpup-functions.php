<?php
/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function wpup_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
    if ( ! empty( $args ) && is_array( $args ) ) {
        extract( $args );
    }

    $located = wpup_locate_template( $template_name, $template_path, $default_path );

    if ( ! file_exists( $located ) ) {
        //_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '0.1' );
        //return;
    }

    // Allow 3rd party plugin filter template file from their plugin.
    $located = apply_filters( 'wpup_get_template', $located, $template_name, $args, $template_path, $default_path );

    do_action( 'wpup_before_template_part', $template_name, $template_path, $located, $args );

    include( $located );

    do_action( 'wpup_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *      yourtheme       /   $template_path  /   $template_name
 *      yourtheme       /   $template_name
 *      $default_path   /   $template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function wpup_locate_template( $template_name, $template_path = '', $default_path = '' ) {
    if ( ! $template_path ) {
        $template_path = WPUP()->template_path();
    }

    if ( ! $default_path ) {
        $default_path = WPUP_PATH . '/templates/';
    }

    // Look within passed path within the theme - this is priority.
    $template = locate_template(
        array(
            trailingslashit( $template_path ) . $template_name,
            $template_name
        )
    );

    // Get default template/
    if ( ! $template  ) {
        $template = $default_path . $template_name;
    }

    // Return what we found.
    return apply_filters( 'wpup_locate_template', $template, $template_name, $template_path );
}

/**
 * Check current user or Get user id
 * 
 * @since 0.1
 * 
 * @return int or boolean
 */
function wpup_get_user_id() {
    
    if ( ! empty( $_GET['user_id'] ) && get_user_by( 'id', absint( $_GET['user_id'] ) ) ) {
        return $_GET['user_id'];
    } 

    if ( get_current_user_id() ) {
        return get_current_user_id();
    }

    return false;
}

/**
 * Default profile details 
 * 
 * @return array
 */
function wpup_default_profile_details() {
    return array(
        array(
            'content' => __( 'Label Content', 'wpup' ),
            'label'      => __( 'Label', 'wpup' ),
            'span'       => 4,
            'type'       => 'section_field',
            'visibility' => true,

            'ele_settings_field' => array(
                'content' => true,
                'label'   => true
            ),
            'ele_settings_field_val' => array(
                'content' => __( 'Label Content', 'wpup' ),
                'label'   => __( 'Profile Details', 'wpup' ),
            ),
            
        ),
        array(
            'content' => __( 'Add your first name', 'wpup' ),
            'label'      => __( 'Label', 'wpup' ),
            'span'       => 4,
            'type'       => 'text_field',
            'visibility' => true,

            'ele_settings_field' => array(
                'content' => true,
                'label'   => true
            ),
            'ele_settings_field_val' => array(
                'content' => __( 'Add your first name', 'wpup' ),
                'label'   => __( 'First name', 'wpup' ),
            ),
        ),
        array(
            'content' => __( 'Add your last name', 'wpup' ),
            'label'      => __( 'Label', 'wpup' ),
            'span'       => 4,
            'type'       => 'text_field',
            'visibility' => true,

            'ele_settings_field' => array(
                'content' => true,
                'label'   => true
            ),
            'ele_settings_field_val' => array(
                'content' => __( 'Add your last name', 'wpup' ),
                'label'   => __( 'Last name', 'wpup' ),
            ),
            
        ),
        array(
            'content'    => __( 'Add your gender', 'wpup' ),
            'label'      => __( 'Label', 'wpup' ),
            'name'       => 'gender',
            'span'       => 4,
            'type'       => 'radio_field',
            'visibility' => true,

            'ele_settings_field' => array(
                'content' => false,
                'gender'  => true,
                'label'   => true,
            ),
            'radio_options' => array(
                '1' => __( 'Male', 'wpup' ),
                '2' => __( 'Female', 'wpup' )
            ),
            'ele_settings_field_val' => array(
                'content' => __( 'Add your gender', 'wpup' ),
                'gender'  => 1,
                'label'   => __( 'Gender', 'wpup' )
            )
        ),
        array(
            'content' => __( 'Add your biography', 'wpup' ),
            'label'      => __( 'Label', 'wpup' ),
            'span'       => 4,
            'type'       => 'text_field',
            'visibility' => true,

            'ele_settings_field' => array(
                'content' => true,
                'label'   => true
            ),
            'ele_settings_field_val' => array(
                'content' => __( 'Add your biography', 'wpup' ),
                'label'   => __( 'Biography', 'wpup' ),
            ),
            
        ), 
        array(
            'content' => __( 'Label Content', 'wpup' ),
            'label'      => "Label",
            'span'       => 4,
            'type'       => "text_field",
            'visibility' => true,

            'ele_settings_field' => array(
                'content' => true,
                'label'   => true
            ),

            'ele_settings_field_val' => array(
                'content' => __( 'Add your address', 'wpup'),
                'label'   => __( 'Address', 'wpup' )
            ),
            
        ),
        array(
            'content'          => __( 'Add your birthday', 'wpup' ),
            'type'             => 'date_field',
            'label'            => __( 'Label', 'cpm' ),
            'span'             => 4,
            'visibility'       => true,

            'ele_settings_field' => array(
                'content' => false,
                'date'   => true,
                'label'  => true
            ),

            'ele_settings_field_val' => array(
                'label' => __( 'Birthday', 'wpup' ),
                'date'  => '',
                'content' => __( 'Add your birthday', 'wpup' )
            ),
        ),

        array(
            'label'      => __( 'Label', 'wpup' ),
            'span'       => 4,
            'type'       => 'text_field',
            'visibility' => true,
            'content' => __( 'Add your phone number', 'wpup' ),
            
            'ele_settings_field' => array(
                'content' => true,
                'label'   => true,
            ),
            
            'ele_settings_field_val' => array(
                'content' => __( 'Add your phone number', 'wpup' ),
                'label'   => __( 'Phone', 'wpup' )
            ),
            
        ),
        array(
            'label'      => __( 'Label', 'wpup' ),
            'name'       => 'country',
            'span'       => 4, 
            'type'       => 'select_field',
            'visibility' => true,
            'content'    => __( 'Add your country', 'wpup' ),
            
            'ele_settings_field' => array(
                'content'        => false,
                'country'        => true,
                'label'          => true,
                'select_options' => include WPUP_PATH . '/i18n/countries.php'
            ),

            'ele_settings_field_val' => array(
                'content' => __( 'Add your contry', 'wpup' ),
                'country' => 'BD',
                'label'   => __( 'Country', 'wpup' )
            ),
            
        ),
    );
}

function wpup_social_profile() {
    return array(
        // array(
        //     'content'    => "Label Content",
        //     'label'      => "Label",
        //     'span'       => 4,
        //     'type'       => "section_field",
        //     'visibility' => true,
            
        //     'ele_settings_field' => array(
        //         'content' => false,
        //         'label'   => true
        //     ),
            
        //     'ele_settings_field_val' => array(
        //         'content' => "Label Content",
        //         'label'   => "Social Profile",
        //     )
        // ),
        
        array(
            'content'    => "Label Content",
            'label'      => "Label",
            'span'       => 4,
            'type'       => "social_field",
            'visibility' => true,
            
            'ele_settings_field' => array(
                'content' => true,
                'label'   => true,
                'url'     => true
            ),
            
            'ele_settings_field_val' => array(
                'content' => "View Profile",
                'label'   => "Facebook Page",
                'url'     => 'http://example.com'
            )
        ),

        array(
            'content'    => "Label Content",
            'label'      => "Label",
            'span'       => 4,
            'type'       => "social_field",
            'visibility' => true,
            
            'ele_settings_field' => array(
                'content' => true,
                'label'   => true,
                'url'     => true
            ),
            
            'ele_settings_field_val' => array(
                'content' => "View Profile",
                'label'   => "Twitter",
                'url'     => 'http://example.com'
            )
        ),

        // array(
        //     'content'    => "Label Content",
        //     'label'      => "Label",
        //     'span'       => 4,
        //     'type'       => "social_field",
        //     'visibility' => true,
            
        //     'ele_settings_field' => array(
        //         'content' => true,
        //         'label'   => true,
        //         'url'     => true
        //     ),
            
        //     'ele_settings_field_val' => array(
        //         'content' => "View Profile",
        //         'label'   => "Google+",
        //         'url'     => 'http://example.com'
        //     )
        // ),

        // array(
        //     'content'    => "Label Content",
        //     'label'      => "Label",
        //     'span'       => 4,
        //     'type'       => "social_field",
        //     'visibility' => true,
            
        //     'ele_settings_field' => array(
        //         'content' => true,
        //         'label'   => true,
        //         'url'     => true
        //     ),
            
        //     'ele_settings_field_val' => array(
        //         'content' => "View Profile",
        //         'label'   => "Wedsite (URL)",
        //         'url'     => 'http://example.com'
        //     )
        // ),
    );
}

function wpup_default_profile_header( $user ) {
    return array(

        array(
            'type'       => 'display_name',
            'title'      => __( 'My name is', 'wpup' ),
            'content'    => $user ? $user->display_name : '',
            'icon'       => 'fa-user',
            'visibility' => 1,
            'enable'     => true,
            'content_field' => false,
            'name'       => false
        ),
        array(
            'type'       => 'email',
            'title'      => __( 'My email is', 'wpup' ),
            'content'    => $user ? $user->user_email : '',
            'icon'       => 'fa-envelope',
            'visibility' => 0,
            'enable'     => true,
            'content_field'    => false,
            'name'      => false
        ),
        array(
            'type'       => 'birthday',
            'title'      => __( 'My birthday is', 'wpup' ),
            'content'    => $user ? get_user_meta( $user->ID, 'wpup_birthday', true ) : '',
            'icon'       => 'fa-calendar',
            'visibility' => 0,
            'enable'     => true,
            'date_field' => true,
            'content_field'    => false,
            'name'       => 'wpup_birthday' 
        ),
        array(
            'type'       => 'location',
            'title'      => __( 'My location is', 'wpup' ),
            'content'    => $user ? get_user_meta( $user->ID, 'wpup_location', true ) : '',
            'icon'       => 'fa-map-marker',
            'visibility' => 0,
            'enable'     => true,
            'name'       => 'wpup_location'
        ),
        array(
            'type'       => 'phone',
            'title'      => __( 'My phone number is', 'wpup' ),
            'content'    => $user ? get_user_meta( $user->ID, 'wpup_phone', true ) : '',
            'icon'       => 'fa-phone',
            'visibility' => 0,
            'enable'     => true,
            'name'       => 'wpup_phone'
        ),
        array(
            'type'       => 'logout',
            'title'      => __( 'Logout', 'wpup' ),
            'content'    => __( 'Terminating a login session.', 'wpup' ),
            'icon'       => 'fa-sign-out',
            'logout_url' => wp_logout_url( home_url() ),
            'visibility' => 0,
            'enable'     => is_user_logged_in() ? true : false,
            'name'       => false
        ),
        // array(
        //     'type'       => 'edit',
        //     'title'      => __( 'Edit', 'wpup' ),
        //     'content'    => __( 'Update your profile', 'wpup' ),
        //     'icon'       => 'fa-sign-out',
        //     'visibility' => 0,
        //     'enable'     => is_user_logged_in() ? true : false,
        //     'name'       => false
        // )
    );
}
//01727442622
/**
 * Get initial profile data
 * 
 * @since 0.1
 * 
 * @return array
 */
function wpup_get_profile_data( $user_id = false ) {
    $profile = get_posts( array(
        'post_type'  => 'wpup_user_profile',
        'post_satus' => 'publish'
    ) );

    $user_id = absint( $user_id ) ? $user_id : wpup_get_user_id();
    $profile = reset( $profile );
    $user    = get_user_by( 'id', $user_id );
    
    // Assign default profile value
    if ( ! $profile ) {
        $profile = new stdClass();
        $profile->rows   = array();
        $profile->cols   = array();
        $profile->els    = array();
        $profile->header = wpup_default_profile_header( $user );
   
        return $profile;
    }

    $user_meta_els  = [];
    $post_meta_eles = [];

    
    $user_els = get_user_meta( $user_id, 'wpup_ele', true );
    $post_els = get_post_meta( $profile->ID, '_els', true );
    $user_els = $user_els ? $user_els : array();
    
    foreach ( $post_els as $key => $post_el ) {
        $name = $post_el['ele_settings_field_val']['name'];

        $post_els[$key]['field_val'] = get_user_meta( $user_id, $name, true );
        
        if ( $post_el['group_type'] == 'profile_details' && $post_el['group_key'] == 'web' ) {
            $post_els[$key]['field_val']  = $user->user_url;
        }

        if ( $post_el['group_type'] == 'profile_details' && $post_el['group_key'] == 'email' ) {
            $post_els[$key]['field_val']  = $user->user_email;
        }

        if ( $post_el['group_type'] == 'profile_details' && $post_el['group_key'] == 'display_name' ) {
            $post_els[$key]['select_options'] = wpup_dispaly_name_dropdown_array( $user_id );
            $post_els[$key]['field_val']  = wpup_get_selected_display_name_key( $user_id );
        }

        if ( $post_el['group_type'] == 'profile_details' && $post_el['group_key'] == 'user' ) {
            $post_els[$key]['field_val']  = $user->user_login;
        }

        if ( $post_el['group_type'] == 'profile_details' && $post_el['group_key'] == 'pass' ) {
            $post_els[$key]['field_val']  = '';
        }
    }

    $post_headers  = get_post_meta( $profile->ID, '_header', true );
    $post_headers  = wpup_default_profile_header( $user );
    
    $content_width = get_post_meta( $profile->ID, '_content_width', true );
    $content_width_unit = get_post_meta( $profile->ID, '_content_width_unit', true );
    $profile->rows   = get_post_meta( $profile->ID, '_rows', true );
    $profile->cols   = get_post_meta( $profile->ID, '_cols', true );
    $profile->els    = $post_els;
    $profile->header = $post_headers;

    if ( $content_width ) {
        $profile->content_width      = $content_width;
        $profile->content_width_unit = $content_width_unit;
    } else {
        $profile->content_width      = 400; 
        $profile->content_width_unit = '=';
    }
    
    return $profile;
}

/**
 * Create a page and store the ID in an option.
 *
 * @param string $slug 
 * @param string $option 
 * @param string $page_title 
 * @param string $page_content 
 * @param int $post_parent (default: 0) Parent for the new page
 *
 * @since  0.1
 * 
 * @return int page ID
 */
function wpup_frontend_create_page( $slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0 ) {
    global $wpdb;

    $option_value = get_option( $option );

    if ( $option_value > 0 ) {
        $page_object = get_post( $option_value );
        
        if ( $page_object && 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array( 'pending', 'trash', 'future', 'auto-draft' ) ) ) {
            // Valid page is already in place
            return $page_object->ID;
        }
    }
    
    if ( strlen( $page_content ) > 0 ) {
        // Search for an existing page with the specified page content (typically a shortcode)
        $valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
    } else {
        // Search for an existing page with the specified page slug
        $valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
    }

    if ( $valid_page_found ) {
        if ( $option ) {
            update_option( $option, $valid_page_found );
        }

        return $valid_page_found;
    }

    // Search for a matching valid trashed page
    if ( strlen( $page_content ) > 0 ) {
        // Search for an existing page with the specified page content (typically a shortcode)
        $trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
    } else {
        // Search for an existing page with the specified page slug
        $trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
    }

    if ( $trashed_page_found ) {
        $page_id   = $trashed_page_found;
        $page_data = array(
            'ID'             => $page_id,
            'post_status'    => 'publish',
        );
        
        wp_update_post( $page_data );
    
    } else {
        $page_data = array(
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_author'    => 1,
            'post_name'      => $slug,
            'post_title'     => $page_title,
            'post_content'   => $page_content,
            'post_parent'    => $post_parent,
            'comment_status' => 'closed'
        );
        
        $page_id = wp_insert_post( $page_data );
    }

    if ( $option ) {

        update_option( $option, $page_id );
    }

    return $page_id;
}

/**
 * Get url for front-end profile builder
 * 
 * @since  0.1
 *
 * @return string
 */
function wpup_frontend_builder_url() {
    $page_id = get_option( 'wpup_frontend_page_id' );

    return get_permalink( $page_id );
}

/**
 * Check current user admin or not
 * 
 * @since  0.1
 * 
 * @return boolean 
 */
function is_current_user_admin() {
    $user = wp_get_current_user();

    if ( in_array( 'administrator', $user->roles) ) {
        return true;
    } 

    return false;
}

/**
 * Current logged in user can update profile or not
 * 
 * @since 0.1
 * 
 * @return boolean
 */
function wpup_user_can_update_profile() {
    return ( wpup_get_user_id() == get_current_user_id() ) ? true : false;
}

/**
 * Get user profile image url
 * 
 * @since  0.1
 *
 * @param  int  $user_id 
 *
 * @return  string
 */
function wpup_get_user_profile_picture_url( $user_id = false ) {
    $user_id = $user_id ? $user_id : wpup_get_user_id();

    $id   = get_user_meta( $user_id, 'profile_picture_id', true );

    if ( empty( $id ) ) {
        return get_avatar_url( $user_id );
    }

    $file = WPUP_Uploader::get_file( $id );

    if ( $file ) {
        return $file['thumb'];
    } 

    return get_avatar_url( $user_id );
}

/**
 * Get users
 * 
 * @since  0.1
 *
 * @param  integer  $pagenum  
 * @param  boolean  $user     
 *
 * @return array
 */
function wpup_get_users( $pagenum = 1, $user = false, $params = array() ) {

    $limit  = get_option( 'wpup_members_per_page', '20' );
    $offset = ( $pagenum - 1 ) * $limit;
    
    $args = array(
        'offset'       => $offset,
        'number'       => $limit,
        'count_total'  => true,
       // 'fields'       => array('description')
    ); 

    if ( $user ) {
        $args['search'] = '*' . $user . '*';
        $args['search_columns'] = array( 'user_login', 'user_email', 'user_nicename' );
    }

    
    $users = new WP_User_Query( $args );

    // global $wpdb;
    // error_log( print_r( $wpdb->last_query, true ) );
    // 
    $is_admin = empty( $params['is_admin'] ) ? 'no' : $params['is_admin'];

    if ( $is_admin == 'yes' ) {
        $profile = admin_url( 'admin.php?page=wpup-profile' );
    } else {
        $profile = get_permalink( get_option( 'wpup_frontend_page_id' ) );
    }

    foreach ( $users->results as $key => $user ) {
        $description       = get_user_meta( $user->ID, 'description', true );
        $user->avatar      = wpup_get_user_profile_picture_url( $user->ID );
        $user->profile     = add_query_arg( array( 'user_id' => $user->ID ), $profile );
        $user->description = empty( $description ) ? false : $description;
    }

    return $users;
}

/**
 * Embed a JS template page with its ID
 * 
 * @since  0.1
 *
 * @param  string  the file path of the file
 * @param  string  the script id
 *
 * @return void
 */
function wpup_get_js_template( $file_path, $id ) {
    
    if ( file_exists( $file_path ) ) {
        echo '<script type="text/html" id="tmpl-' . $id . '">' . "\n";
        include_once $file_path;
        echo "\n" . '</script>' . "\n";
    }
}

/**
 * woocommerce clean
 * 
 * @since  0.1
 *
 * @param  array  $var
 *
 * @return void
 */
function wpup_clean( $var ) {
    if ( is_array( $var ) ) {
        return array_map( 'wpup_clean', $var );
    } else {
        return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
    }
}

/**
 * Generate array for display name
 * 
 * @since  0.1
 *
 * @param  int $user_id
 *
 * @return void
 */
function wpup_dispaly_name_dropdown_array( $user_id = false ) {
    $user_id = $user_id ? $user_id : wpup_get_user_id();
    $profileuser = get_user_by( 'id', $user_id );
    $public_display = array();
    $public_display['display_nickname']  = $profileuser->nickname;
    $public_display['display_username']  = $profileuser->user_login;

    if ( !empty($profileuser->first_name) )
        $public_display['display_firstname'] = $profileuser->first_name;

    if ( !empty($profileuser->last_name) )
        $public_display['display_lastname'] = $profileuser->last_name;

    if ( !empty($profileuser->first_name) && !empty($profileuser->last_name) ) {
        $public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
        $public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
    }

    if ( !in_array( $profileuser->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
        $public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;

    $public_display = array_map( 'trim', $public_display );
    $public_display = array_unique( $public_display );

    return $public_display;
}

/**
 * Generate array for display name key
 * 
 * @since  0.1
 *
 * @param  int $user_id
 *
 * @return void
 */
function wpup_get_selected_display_name_key( $user_id = false ) {
    $user_id = $user_id ? $user_id : wpup_get_user_id();
    $profileuser = get_user_by( 'id', $user_id );

    $public_display = array();
    $public_display['display_nickname']  = $profileuser->nickname;
    $public_display['display_username']  = $profileuser->user_login;

    if ( !empty($profileuser->first_name) )
        $public_display['display_firstname'] = $profileuser->first_name;

    if ( !empty($profileuser->last_name) )
        $public_display['display_lastname'] = $profileuser->last_name;

    if ( !empty($profileuser->first_name) && !empty($profileuser->last_name) ) {
        $public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
        $public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
    }

    if ( !in_array( $profileuser->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
        $public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;

    $public_display = array_map( 'trim', $public_display );
    $public_display = array_unique( $public_display );
    $selected       = 'display_nickname';
    
    foreach ( $public_display as $id => $item ) {
        if ( $profileuser->display_name == $item ) {
            $selected = $id;
        }
    }

    return $selected;
}

/**
 * Save WP backend default profile data
 * 
 * @since  0.1
 *
 * @return void
 */
function wpup_update_back_end_profile() {
    $fields_name = array(
        'first_name',
        'last_name',
        'nickname',
        'display_name',
        'email',
        'url',
        'description',
        'pass1'
    );

    $display = wpup_dispaly_name_dropdown_array();

    $els = empty( $_POST['els'] ) ? array() : $_POST['els'];
    
    foreach ( $els as $key => $el ) {
        $name = $el['ele_settings_field_val']['name'];
        
        if ( in_array( $name, $fields_name ) ) {
            
            if ( $name == 'display_name' ) {
                $val                   = $el['field_val'];
                $_POST['display_name'] = $display[$val];
            
            } else if ( $name == 'pass1' && !empty( $el['field_val'] ) ) {
                $_POST['pass1'] = $el['field_val'];
                $_POST['pass2'] = $el['field_val'];
            }
            else {
                $_POST[$name] = $el['field_val'];
            }
            
            unset( $_POST['els'][$key] );
        }
    }

    edit_user( get_current_user_id() );
}

/**
 * Update profile header information
 * 
 * @since  0.1
 *
 * @return void
 */
function wpup_update_header( $user_id = false, $headers = array() ) {
    $user_id = absint( $user_id ) ? $user_id : false;
    
    if ( ! $user_id ) {
        return;
    }

    foreach ( $headers as $key => $header ) {
        if ( $header['name'] ) {
            update_user_meta( $user_id, $header['name'], $header['content'] );
        }
    }

    update_user_meta( $user_id, 'wpup_header', $headers );
}





