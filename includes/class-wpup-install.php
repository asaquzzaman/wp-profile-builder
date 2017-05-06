<?php
/**
 * Installation related functions and actions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Install Class.
 */
class WPUP_Install {

    /** @var array DB updates that need to be run */
    private static $updates = [
        '1.0' => 'updates/update-1.0.php',
    ];

    /**
     * Binding all events
     *
     * @since 0.1
     *
     * @return void
     */
    public static function init() {
        add_action( 'admin_notices', 'show_update_notice' );
        add_action( 'admin_init', 'do_updates' );
    }

    public static function install() {
    	self::create_pages();
    }

    /**
     * Create WPUP frontend required page
     *
     * @since  1.0
     *
     * @return  void
     */
    public static function create_pages() {
        
        $pages = apply_filters( 'wpup_frontend_page', array(
            array(
                'name'    => 'profile-builder',
                'title'   => 'Profile Builder',
                'content' => '[wpup_profile_builder]',
                'option'  => 'wpup_frontend_page_id'
            ),
            array(
                'name'    => 'members',
                'title'   => 'Members',
                'content' => '[wpup_members]',
                'option'  => 'wpup_members_page_id'
            ),
            array(
                'name'    => 'my-profile',
                'title'   => 'My Profile',
                'content' => '[wpup_my_profile]',
                'option'  => 'wpup_my_profile_page_id'
            ),
        ));
        
        foreach ( $pages as $key => $page ) {
            wpup_frontend_create_page( esc_sql( $page['name'] ), $page['option'], $page['title'], $page['content'] );
        }
    }

    /**
     * Check if need any update
     *
     * @since 1.0
     *
     * @return boolean
     */
    public static function is_needs_update() {
        $installed_version = get_option( 'wpup_version' );

        // may be it's the first install
        if ( ! $installed_version ) {
            return false;
        }

        if ( version_compare( $installed_version, WPUP_VERSION, '<' ) ) {
            return true;
        }

        return false;
    }

    /**
     * Show update notice
     *
     * @since 1.0
     *
     * @return void
     */
    public static function show_update_notice() {
        if ( ! current_user_can( 'update_plugins' ) || ! $this->is_needs_update() ) {
            return;
        }

        if ( ! is_null( WPUP_VERSION ) && version_compare( WPUP_VERSION, end( array_keys( self::$updates ) ), '<=' ) ) {
            ?>
                <div id="message" class="updated">
                    <p><?php _e( '<strong>WP ERP Data Update Required</strong> &#8211; We need to update your install to the latest version', 'erp' ); ?></p>
                    <p class="submit"><a href="<?php echo add_query_arg( [ 'WPUP_do_update' => true ], $_SERVER['REQUEST_URI'] ); ?>" class="WPUP-update-btn button-primary"><?php _e( 'Run the updater', 'erp' ); ?></a></p>
                </div>

                <script type="text/javascript">
                    jQuery('.WPUP-update-btn').click('click', function(){
                        return confirm( '<?php _e( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', 'erp' ); ?>' );
                    });
                </script>
            <?php
        } else {
            update_option( 'wpup_version', WPUP_VERSION );
        }

        ?>
        <?php
    }

    /**
     * Do all updates when Run updater btn click
     *
     * @since 1.0
     *
     * @return void
     */
    public static function do_updates() {
        if ( isset( $_GET['WPUP_do_update'] ) && $_GET['WPUP_do_update'] ) {
            $this->perform_updates();
        }
    }

    /**
     * Perform all updates
     *
     * @since 1.0
     *
     * @return void
     */
    public static function perform_updates() {
        if ( ! $this->is_needs_update() ) {
            return;
        }

        $installed_version = get_option( 'wpup_version' );

        $this->enable_all_erp_modules();

        foreach ( self::$updates as $version => $path ) {
            if ( version_compare( $installed_version, $version, '<' ) ) {
                include $path;
                update_option( 'wpup_version', $version );
            }
        }

        $location = remove_query_arg( ['WPUP_do_update'], $_SERVER['REQUEST_URI'] );
        wp_redirect( $location );
        exit();
    }
}

//WPUP_Install::init();
