<?php
/**
 * Handle js template
 *
 * @class       WPUP_Js_Templates
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPUP_Js_Templates Class.
 */
class WPUP_Js_Templates {

	/**
	 * Members js template.
	 *
	 * @since  0.1
	 *
	 * @return void
	 */
	public static function members() {
		wpup_get_js_template( WPUP_PATH . '/templates/members/member-lists.php', 'wpup-member-lists');
		wpup_get_js_template( WPUP_PATH . '/templates/members/pagination.php', 'wpup-pagination');
		wpup_get_js_template( WPUP_PATH . '/templates/members/view-individual-user-profile.php', 'wpup-view-individual-user-profile');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/view-individual-user-profile/view-individual-user-profile.php', 'wpup-js-view-individual-user-profile');
		
		//js folder template
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/profile-header/profile-header.php', 'wpup-profile-header');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/row/row.php', 'wpup-row');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/text-field/text-field.php', 'wpup-text-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/select-field/select-field.php', 'wpup-select-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/radio-field/radio-field.php', 'wpup-radio-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/social-field/social-field.php', 'wpup-social-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/section-field/section-field.php', 'wpup-section-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/email-field/email-field.php', 'wpup-email-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/url-field/url-field.php', 'wpup-url-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/date-field/date-field.php', 'wpup-date-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/password-field/password-field.php', 'wpup-password-field');
	}

	/**
	 * Profile builder js template.
	 *
	 * @since  0.1
	 *
	 * @return void
	 */
	public static function profile_builder() {
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/profile-header/profile-header.php', 'wpup-profile-header');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/profile-builder-settings/profile-builder-settings.php', 'wpup-profile-builder-settings');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/row/row.php', 'wpup-row');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/text-field/text-field.php', 'wpup-text-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/select-field/select-field.php', 'wpup-select-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/radio-field/radio-field.php', 'wpup-radio-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/social-field/social-field.php', 'wpup-social-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/section-field/section-field.php', 'wpup-section-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/email-field/email-field.php', 'wpup-email-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/url-field/url-field.php', 'wpup-url-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/date-field/date-field.php', 'wpup-date-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/password-field/password-field.php', 'wpup-password-field');
	}

	/**
	 * My Profile js template.
	 *
	 * @since  0.1
	 *
	 * @return void
	 */
	public static function my_profile() {
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/profile-header/profile-header.php', 'wpup-profile-header');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/row/row.php', 'wpup-row');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/text-field/text-field.php', 'wpup-text-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/select-field/select-field.php', 'wpup-select-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/radio-field/radio-field.php', 'wpup-radio-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/social-field/social-field.php', 'wpup-social-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/section-field/section-field.php', 'wpup-section-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/email-field/email-field.php', 'wpup-email-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/url-field/url-field.php', 'wpup-url-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/date-field/date-field.php', 'wpup-date-field');
		wpup_get_js_template( WPUP_PATH . '/assets/js/components/password-field/password-field.php', 'wpup-password-field');
	}
}

