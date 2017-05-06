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
class WPUP_Shortcodes {

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
			'wpup_profile_builder' => __CLASS__ . '::profile_builder',
			'wpup_members'         => __CLASS__ . '::members',
			'wpup_my_profile'      => __CLASS__ . '::my_profile',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @param string[] $function
	 * @param array $atts (default: array())
	 * @return string
	 */
	public static function shortcode_wrapper(
		$function,
		$atts    = array(),
		$wrapper = array(
			'class'  => 'wpup',
			'before' => null,
			'after'  => null
		)
	) {
		ob_start();

		echo empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		call_user_func( $function, $atts );
		echo empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];

		return ob_get_clean();
	}

	/**
	 * profile builder shortcode.
	 *
	 * @since  1.0
	 *
	 * @param mixed $atts
	 * 
	 * @return string
	 */
	public static function profile_builder( $atts ) {
		return self::shortcode_wrapper( array( 'WPUP_Shortcode_Profile_Builder', 'output' ), $atts );
	}

	/**
	 * building form views shortcode.
	 *
	 * @since  1.0
	 *
	 * @param mixed $atts
	 * 
	 * @return string
	 */
	public static function members( $atts ) {
		return self::shortcode_wrapper( array( 'WPUP_Shortcode_Members', 'output' ), $atts );
	}

	/**
	 * building my profile shortcode.
	 *
	 * @since  1.0
	 *
	 * @param mixed $atts
	 * 
	 * @return string
	 */
	public static function my_profile( $atts ) {
		return self::shortcode_wrapper( array( 'WPUP_Shortcode_My_Profile', 'output' ), $atts );
	}

}
