<?php
/**
 */
class WPUP_Shortcode_Profile_Builder {

    /**
     * Get the shortcode content.
     *
     * @param array $atts
     * @return string
     */
    public static function get( $atts ) {
        return WPUP_Shortcodes::shortcode_wrapper( array( __CLASS__, 'output' ), $atts );
    }

    /**
     * Output the shortcode.
     *
     * @param array $atts
     */
    public static function output( $atts ) {
        
        if ( wpup_get_user_id() ) {
            wpup_get_template('profile-builder/profile-builder.php');
            WPUP_Js_Templates::profile_builder();
            WPUP_Frontend_Scripts::profile_builder_scripts();
        
        } else {
            wp_login_form();
        }
    }
}
