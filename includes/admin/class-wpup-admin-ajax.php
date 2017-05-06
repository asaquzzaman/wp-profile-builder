<?php

class Admin_Ajax {

	function __construct() {
		add_action( 'wp_ajax_wpup_field_setting', array( $this, 'field_setting' ) );
	}

	function field_setting() {
		var_dump( $_POST ); die();
	}
}

return new Admin_Ajax();