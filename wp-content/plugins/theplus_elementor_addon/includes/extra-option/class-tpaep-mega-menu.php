<?php
/**
 * The file store Database Default Entry
 *
 * @link       https://posimyth.com/
 * @since      5.6.7
 *
 * @package    the-plus-addons-for-elementor-page-builder
 */

/**Exit if accessed directly.*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tpaep_Mega_Menu' ) ) {

	/**
	 * Tpaep_Mega_Menu
	 *
	 * @since 1.0.0
	 */
	class Tpaep_Mega_Menu {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'tpaep_mega_menu_posts' ), 99 );
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since    1.0.0
		 */
		public function tpaep_mega_menu_posts() {

			$labels = array(
				'name'                  => _x( 'Plus Mega Menu', 'Post Type General Name', 'theplus' ),
				'singular_name'         => _x( 'Plus Mega Menu', 'Post Type Singular Name', 'theplus' ),
				'menu_name'             => esc_html__( 'Plus Mega Menu', 'theplus' ),
				'name_admin_bar'        => esc_html__( 'Plus Mega Menu', 'theplus' ),
				'archives'              => esc_html__( 'Item Archives', 'theplus' ),
				'attributes'            => esc_html__( 'Item Attributes', 'theplus' ),
				'parent_item_colon'     => esc_html__( 'Parent Item:', 'theplus' ),
				'all_items'             => esc_html__( 'All Items', 'theplus' ),
				'add_new_item'          => esc_html__( 'Add New Item', 'theplus' ),
				'add_new'               => esc_html__( 'Add New', 'theplus' ),
				'new_item'              => esc_html__( 'New Item', 'theplus' ),
				'edit_item'             => esc_html__( 'Edit Item', 'theplus' ),
				'update_item'           => esc_html__( 'Update Item', 'theplus' ),
				'view_item'             => esc_html__( 'View Item', 'theplus' ),
				'view_items'            => esc_html__( 'View Items', 'theplus' ),
				'search_items'          => esc_html__( 'Search Item', 'theplus' ),
				'not_found'             => esc_html__( 'Not found', 'theplus' ),
				'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'theplus' ),
				'featured_image'        => esc_html__( 'Featured Image', 'theplus' ),
				'set_featured_image'    => esc_html__( 'Set featured image', 'theplus' ),
				'remove_featured_image' => esc_html__( 'Remove featured image', 'theplus' ),
				'use_featured_image'    => esc_html__( 'Use as featured image', 'theplus' ),
				'insert_into_item'      => esc_html__( 'Insert into item', 'theplus' ),
				'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'theplus' ),
				'items_list'            => esc_html__( 'Items list', 'theplus' ),
				'items_list_navigation' => esc_html__( 'Items list navigation', 'theplus' ),
				'filter_items_list'     => esc_html__( 'Filter items list', 'theplus' ),
			);

			$args = array(
				'label'                 => esc_html__( 'Plus Mega Menu', 'theplus' ),
				'description'           => esc_html__( 'Mega Menu Content', 'theplus' ),
				'labels'                => $labels,
				'supports'              => array( 'title', 'editor','elementor' ),
				'hierarchical'          => false,
				'public'                => false,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 69,
				'menu_icon'             => 'dashicons-welcome-widgets-menus',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,
				'exclude_from_search'   => true,
				'publicly_queryable'    => true,
				//'capability_type'       => 'page',
				'taxonomies'          => array(),
				'query_var'           => true,
				'rewrite'             => true,
				'capability_type'     => 'post',
			);

			register_post_type( 'plus-mega-menu', $args );
		}
	}

	Tpaep_Mega_Menu::get_instance();
}