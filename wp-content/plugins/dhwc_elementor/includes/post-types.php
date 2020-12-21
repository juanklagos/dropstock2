<?php
namespace DHWC_Elementor;

class Post_Types {
	
	public static function init()
	{
		add_action('init', array(__CLASS__, 'register_post_types'), 5);
		if(is_admin()){
			add_action( 'add_meta_boxes', array( __CLASS__, 'remove_meta_boxes' ), 1000 );
		}
	}
	
	public static function remove_meta_boxes(){ 
		remove_meta_box( 'vc_teaser', 'dhwc_template' , 'side' );
		remove_meta_box( 'commentsdiv', 'dhwc_template' , 'normal' );
		remove_meta_box( 'commentstatusdiv', 'dhwc_template' , 'normal' );
		remove_meta_box( 'slugdiv', 'dhwc_template' , 'normal' );
		remove_meta_box('mymetabox_revslider_0', 'dhwc_template', 'normal');
	}
	
	public static function register_post_types()
	{
		if (!is_blog_installed() || post_type_exists('dhwc_template')) {
			return;
		}
	
		register_post_type('dhwc_template',array(
			'labels' => array(
				'name' => __('Product Templates', 'dhwc_elementor'),
				'singular_name' => __('Product Template', 'dhwc_elementor'),
				'menu_name' => _x('Product Template', 'Admin menu name', 'dhwc_elementor'),
				'add_new' => __('Add Product Template', 'dhwc_elementor'),
				'add_new_item' => __('Add New Product Template', 'dhwc_elementor'),
				'edit' => __('Edit', 'dhwc_elementor'),
				'edit_item' => __('Edit Product Template', 'dhwc_elementor'),
				'new_item' => __('New Product Template', 'dhwc_elementor'),
				'view' => __('View Product Template', 'dhwc_elementor'),
				'view_item' => __('View Product Template', 'dhwc_elementor'),
				'search_items' => __('Search Product Templates', 'dhwc_elementor'),
				'not_found' => __('No Product Templates found', 'dhwc_elementor'),
				'not_found_in_trash' => __('No Product Templates found in trash', 'dhwc_elementor'),
				'parent' => __('Parent Product Template', 'dhwc_elementor')
			),
			'public' => true,
			'has_archive' => false,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => 'edit.php?post_type=product',
			'query_var' => true,
			'capability_type' => 'post',
			'map_meta_cap'=> true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title','editor')
		));
		
		register_post_type('dhwc_page_template',array(
			'labels' => array(
				'name' => __('Page Templates', 'dhwc_elementor'),
				'singular_name' => __('Page Template', 'dhwc_elementor'),
				'menu_name' => _x('Page Template', 'Admin menu name', 'dhwc_elementor'),
				'add_new' => __('Add Page Template', 'dhwc_elementor'),
				'add_new_item' => __('Add New Page Template', 'dhwc_elementor'),
				'edit' => __('Edit', 'dhwc_elementor'),
				'edit_item' => __('Edit Page Template', 'dhwc_elementor'),
				'new_item' => __('New Page Template', 'dhwc_elementor'),
				'view' => __('View Page Template', 'dhwc_elementor'),
				'view_item' => __('View Page Template', 'dhwc_elementor'),
				'search_items' => __('Search Page Templates', 'dhwc_elementor'),
				'not_found' => __('No Page Templates found', 'dhwc_elementor'),
				'not_found_in_trash' => __('No Page Templates found in trash', 'dhwc_elementor'),
				'parent' => __('Parent Page Template', 'dhwc_elementor')
			),
			'public' => true,
			'has_archive' => false,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => 'woocommerce',
			'query_var' => true,
			'capability_type' => 'post',
			'map_meta_cap'=> true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title','editor')
		));
	}
}
\DHWC_Elementor\Post_Types::init();
