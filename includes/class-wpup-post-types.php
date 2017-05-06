<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies.
 *
 * @class     WPUP_Post_types
 * @version   2.5.0
 * @category  Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WPUP_Post_types Class.
 */
class WPUP_Post_types {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
	}

	/**
	 * Register core taxonomies.
	 */
	public static function register_post_types() {
		if ( post_type_exists( 'wpup_user_profile' ) ) {
			return;
		}

		register_post_type( 'wpup_user_profile',
			array(
				'labels'  => array(
					'name'                  => __( 'wpup', 'wpup' ),
					'singular_name'         => __( 'Product', 'wpup' ),
					'menu_name'             => _x( 'wpup', 'Admin menu name', 'wpup' ),
					'add_new'               => __( 'Add Product', 'wpup' ),
					'add_new_item'          => __( 'Add New Product', 'wpup' ),
					'edit'                  => __( 'Edit', 'wpup' ),
					'edit_item'             => __( 'Edit Product', 'wpup' ),
					'new_item'              => __( 'New Product', 'wpup' ),
					'view'                  => __( 'View Product', 'wpup' ),
					'view_item'             => __( 'View Product', 'wpup' ),
					'search_items'          => __( 'Search wpup', 'wpup' ),
					'not_found'             => __( 'No wpup found', 'wpup' ),
					'not_found_in_trash'    => __( 'No wpup found in trash', 'wpup' ),
					'parent'                => __( 'Parent Product', 'wpup' ),
					'featured_image'        => __( 'Product Image', 'wpup' ),
					'set_featured_image'    => __( 'Set product image', 'wpup' ),
					'remove_featured_image' => __( 'Remove product image', 'wpup' ),
					'use_featured_image'    => __( 'Use as product image', 'wpup' ),
					'insert_into_item'      => __( 'Insert into product', 'wpup' ),
					'uploaded_to_this_item' => __( 'Uploaded to this product', 'wpup' ),
					'filter_items_list'     => __( 'Filter wpup', 'wpup' ),
					'items_list_navigation' => __( 'wpup navigation', 'wpup' ),
					'items_list'            => __( 'wpup list', 'wpup' ),
				),
				'description'         => __( 'This is where you can add new wpup to your store.', 'wpup' ),
				'public'              => true,
				'show_ui'             => true,
				'capability_type'     => 'product',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
				'query_var'           => true,
				'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'page-attributes', 'publicize', 'wpcom-markdown' ),
				'show_in_nav_menus'   => true
			)
		);
	}

}

WPUP_Post_types::init();
