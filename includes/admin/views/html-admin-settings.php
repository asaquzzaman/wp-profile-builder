<?php
/**
 * Admin View: Settings
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wrap wpup">
	<form method="<?php echo esc_attr( apply_filters( 'wpup_settings_form_method_tab_' . $wpup_current_tab, 'post' ) ); ?>" id="mainform" action="" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
			<?php
				foreach ( $tabs as $name => $label ) {
					echo '<a href="' . admin_url( 'admin.php?page=wpup-settings&tab=' . $name ) . '" class="nav-tab ' . ( $wpup_current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
				}
				do_action( 'wpup_settings_tabs' );
			?>
		</nav>
		<h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $wpup_current_tab ] ); ?></h1>
		<?php
			do_action( 'wpup_sections_' . $wpup_current_tab );

			self::show_messages();

			do_action( 'wpup_settings_' . $wpup_current_tab );
			do_action( 'wpup_settings_tabs_' . $wpup_current_tab ); // @deprecated hook
		?>
		<p class="submit">
			<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
				<input name="save" class="button-primary wpup-save-button" type="submit" value="<?php esc_attr_e( 'Save changes', 'wpup' ); ?>" />
			<?php endif; ?>
			<?php wp_nonce_field( 'wpup-settings' ); ?>
		</p>
	</form>
</div>
