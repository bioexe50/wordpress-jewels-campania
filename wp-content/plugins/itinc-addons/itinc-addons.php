<?php
/*
Plugin Name: ITinc Theme Addons
Plugin URI: https://themesion.com/
Description: Addons for ITinc theme by Themesion
Version: 4.1
Author: Themesion Team
Author URI: https://themesion.com/
Text Domain: itinc-addons
Domain Path: /language
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ITINC_ADDON_VERSION', '4.1' );
define( 'ITINC_ADDON_PATH', plugin_dir_path( __FILE__ ) ); // with trailing slash
define( 'ITINC_ADDON_URL',  plugin_dir_url( __FILE__ )  ); // with trailing slash

/*
 *  All Shortcodes
 */
// Social Links
if( file_exists( ITINC_ADDON_PATH . '/shortcodes/thsn-social-links.php' ) ){
	include( ITINC_ADDON_PATH . '/shortcodes/thsn-social-links.php' );
}
// Icon buttons (for widget)
if( file_exists( ITINC_ADDON_PATH . '/shortcodes/thsn-icon-buttons.php' ) ){
	include( ITINC_ADDON_PATH . '/shortcodes/thsn-icon-buttons.php' );
}

// Core functions
if( file_exists( get_template_directory() . '/includes/core.php' ) ){
	include( get_template_directory() . '/includes/core.php' );
} else {
	include( ITINC_ADDON_PATH . 'core.php' );
}

/**
 *  W3 Validator warning - The type attribute is unnecessary
 */
add_action( 'template_redirect', function(){
    ob_start( function( $buffer ){
        $buffer = str_replace( array( '<script type="text/javascript"', "<script type='text/javascript'" ), '<script', $buffer );

        // Also works with other attributes...
        $buffer = str_replace( array( '<style type="text/css"', "<style type='text/css'" ), '<style', $buffer );
        $buffer = str_replace( array( ' type="text/css">', " type='text/css'>" ), '>', $buffer );
        $buffer = str_replace( array( 'frameborder="0"', "frameborder='0'" ), '', $buffer );
        $buffer = str_replace( array( 'scrolling="no"', "scrolling='no'" ), '', $buffer );

        return $buffer;
    });
});

if( !function_exists('thsn_itinc_addons_init') ){
function thsn_itinc_addons_init(){
	// Kirki - disable the telemetry module 
	add_filter( 'kirki_telemetry', '__return_false' );
}
}
add_action( 'init', 'thsn_itinc_addons_init' );

add_action( 'customize_save_after', 'thsn_itinc_addons_create_css', 10, 2 );
if( !function_exists('thsn_itinc_addons_create_css') ){
function thsn_itinc_addons_create_css( $data=array() ) {
	if( file_exists( get_template_directory() . '/css/theme-style.php' ) ){
		$content = '';
		ob_start();
		include( get_template_directory() . '/css/theme-style.php' );
		$content = ob_get_contents();
		ob_end_clean();

		// get site ID if multisite
		$blog_id = '';

		$css_dir_path	= ( is_multisite() ) ? WP_CONTENT_DIR . '/thsn-itinc-css/' . get_current_blog_id() . '/' : WP_CONTENT_DIR . '/thsn-itinc-css/' ;
		$css_path		= ( is_multisite() ) ? WP_CONTENT_DIR . '/thsn-itinc-css/' . get_current_blog_id() . '/theme-style.css' : WP_CONTENT_DIR . '/thsn-itinc-css/theme-style.css' ;
		$css_min_path	= ( is_multisite() ) ? WP_CONTENT_DIR . '/thsn-itinc-css/' . get_current_blog_id() . '/theme-style.min.css' : WP_CONTENT_DIR . '/thsn-itinc-css/theme-style.min.css' ;

		// create directory if not exists
		wp_mkdir_p( $css_dir_path );

		if( !function_exists('WP_Filesystem') ){
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		}

		WP_Filesystem();
		global $wp_filesystem;
		$wp_filesystem->put_contents( $css_path, $content );
		$wp_filesystem->put_contents( $css_min_path, thsn_minify_css($content) );

		// add unique version code for this css file
		$version = rand(100,999) . rand(100,999);
		update_option( 'thsn-theme-style-version', $version );

	}
	return $data;
}
}

if( !function_exists('thsn_itinc_check_dynamic_css') ){
function thsn_itinc_check_dynamic_css(){
	$thsn_theme_version		= get_option('thsn-itinc-theme-version');
	$current_theme			= wp_get_theme();
	$current_theme_version	= $current_theme->Version;
	if( $thsn_theme_version != $current_theme_version ){
		thsn_itinc_addons_create_css();
		update_option( 'thsn-itinc-theme-version', $current_theme_version );
	}
}
}
add_action( 'wp', 'thsn_itinc_check_dynamic_css', 26 );

// Generate dynamic style css file
if( !function_exists('thsn_itinc_auto_generate_dynamic_css') ){
function thsn_itinc_auto_generate_dynamic_css(){
	$min				= ( thsn_get_base_option('min')=='1' ) ?  '.min' : '' ;
	$dynamic_css_file	= thsn_get_base_option('dynamic-css-file');
	$version			= get_option('thsn-theme-style-version', '111111');

	$css_path		= ( is_multisite() ) ? WP_CONTENT_DIR . '/thsn-itinc-css/' . get_current_blog_id() . '/theme-style.css' : WP_CONTENT_DIR . '/thsn-itinc-css/theme-style.css' ;
	$css_min_path	= ( is_multisite() ) ? WP_CONTENT_DIR . '/thsn-itinc-css/' . get_current_blog_id() . '/theme-style.min.css' : WP_CONTENT_DIR . '/thsn-itinc-css/theme-style.min.css' ;
	$css_url		= ( is_multisite() ) ? content_url() . '/thsn-itinc-css/' . get_current_blog_id() . '/theme-style' . $min . '.css' : content_url() . '/thsn-itinc-css/theme-style' . $min . '.css' ;

	if( function_exists('thsn_itinc_addons_create_css') && ( !file_exists($css_path) || !file_exists($css_min_path) ) ){
		thsn_itinc_addons_create_css();
	}
	if( function_exists('is_customize_preview') && !is_customize_preview() && $dynamic_css_file==true ){
		wp_deregister_style( 'thsn-dynamic-style' );
		wp_enqueue_style('thsn-dynamic-style', esc_url($css_url), '', $version );
	}

	// For inline css
	global $thsn_inline_css;
	if( !empty($thsn_inline_css) ){
		if( function_exists('thsn_minify_css') ){
			$thsn_inline_css = thsn_minify_css( $thsn_inline_css );
		}
		wp_add_inline_style( 'thsn-dynamic-style', trim( $thsn_inline_css ) );
	}

	if( wp_style_is( 'elementor-global', 'enqueued' ) ){
		wp_deregister_style( 'elementor-global' );
	}

}
}
add_action( 'wp_enqueue_scripts', 'thsn_itinc_auto_generate_dynamic_css', 26 );

/**
 * Register a book post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
if( !function_exists('itinc_addons_register_post_types') ){
function itinc_addons_register_post_types() {

	// Default titles
	$portfolio_cpt_title			= esc_attr__('Portfolio','itinc-addons');
	$portfolio_cpt_singular_title	= esc_attr__('Portfolio','itinc-addons');
	$portfolio_cpt_slug				= esc_attr('portfolio');

	$portfolio_cat_title			= esc_attr__('Portfolio Categories','itinc-addons');
	$portfolio_cat_singular_title	= esc_attr__('Portfolio Category','itinc-addons');
	$portfolio_cat_slug				= esc_attr('portfolio-category');

	$service_cpt_title				= esc_attr__('Services','itinc-addons');
	$service_cpt_singular_title		= esc_attr__('Service','itinc-addons');
	$service_cpt_slug				= esc_attr('service');

	$service_cat_title				= esc_attr__('Service Categories','itinc-addons');
	$service_cat_singular_title		= esc_attr__('Service Category','itinc-addons');
	$service_cat_slug				= esc_attr__('service-category');

	$team_cpt_title					= esc_attr__('Team Members','itinc-addons');
	$team_cpt_singular_title		= esc_attr__('Team Member','itinc-addons');
	$team_cpt_slug					= esc_attr('team-member');

	$team_group_title				= esc_attr__('Team Groups','itinc-addons');
	$team_group_singular_title		= esc_attr__('Team Group','itinc-addons');
	$team_group_slug				= esc_attr('team-group');

	$testimonial_cpt_title			= esc_attr__('Testimonials','itinc-addons');
	$testimonial_cpt_singular_title	= esc_attr__('Testimonial','itinc-addons');
	$testimonial_cpt_slug			= esc_attr('testimonial');

	$testimonial_cat_title			= esc_attr__('Testimonial Categories','itinc-addons');
	$testimonial_cat_singular_title	= esc_attr__('Testimonial Category','itinc-addons');
	$testimonial_cat_slug				= esc_attr('testimonial-category');

	if( class_exists('Kirki') ){

		// Portfolio
		$portfolio_cpt_title2	= Kirki::get_option( 'portfolio-cpt-title' );
		$portfolio_cpt_title	= ( !empty($portfolio_cpt_title2) ) ? $portfolio_cpt_title2 : $portfolio_cpt_title ;

		// Portfolio - singular
		$portfolio_cpt_singular_title2	= Kirki::get_option( 'portfolio-cpt-singular-title' );
		$portfolio_cpt_singular_title	= ( !empty($portfolio_cpt_singular_title2) ) ? $portfolio_cpt_singular_title2 : $portfolio_cpt_singular_title ;

		// Portfolio Slug
		$portfolio_cpt_slug2	= Kirki::get_option( 'portfolio-cpt-slug' );
		$portfolio_cpt_slug	= ( !empty($portfolio_cpt_slug2) ) ? $portfolio_cpt_slug2 : $portfolio_cpt_slug ;

		// Portfolio Category
		$portfolio_cat_title2	= Kirki::get_option( 'portfolio-cat-title' );
		$portfolio_cat_title	= ( !empty($portfolio_cat_title2) ) ? $portfolio_cat_title2 : $portfolio_cat_title ;

		// Portfolio Category - singular
		$portfolio_cat_singular_title2	= Kirki::get_option( 'portfolio-cat-singular-title' );
		$portfolio_cat_singular_title	= ( !empty($portfolio_cat_singular_title2) ) ? $portfolio_cat_singular_title2 : $portfolio_cat_singular_title ;

		// Portfolio Category Slug
		$portfolio_cat_slug2	= Kirki::get_option( 'portfolio-cat-slug' );
		$portfolio_cat_slug	= ( !empty($portfolio_cat_slug2) ) ? $portfolio_cat_slug2 : $portfolio_cat_slug ;

		// Service
		$service_cpt_title2	= Kirki::get_option( 'service-cpt-title' );
		$service_cpt_title	= ( !empty($service_cpt_title2) ) ? $service_cpt_title2 : $service_cpt_title ;

		// Service - singular
		$service_cpt_singular_title2	= Kirki::get_option( 'service-cpt-singular-title' );
		$service_cpt_singular_title	= ( !empty($service_cpt_singular_title2) ) ? $service_cpt_singular_title2 : $service_cpt_singular_title ;

		// Service Slug
		$service_cpt_slug2	= Kirki::get_option( 'service-cpt-slug' );
		$service_cpt_slug	= ( !empty($service_cpt_slug2) ) ? $service_cpt_slug2 : $service_cpt_slug ;

		// Service Category
		$service_cat_title2	= Kirki::get_option( 'service-cat-title' );
		$service_cat_title	= ( !empty($service_cat_title2) ) ? $service_cat_title2 : $service_cat_title ;

		// Service Category - singular
		$service_cat_singular_title2	= Kirki::get_option( 'service-cat-singular-title' );
		$service_cat_singular_title	= ( !empty($service_cat_singular_title2) ) ? $service_cat_singular_title2 : $service_cat_singular_title ;

		// Service Category Slug
		$service_cat_slug2	= Kirki::get_option( 'service-cat-slug' );
		$service_cat_slug	= ( !empty($service_cat_slug2) ) ? $service_cat_slug2 : $service_cat_slug ;

		// Team
		$team_cpt_title2	= Kirki::get_option( 'team-cpt-title' );
		$team_cpt_title	= ( !empty($team_cpt_title2) ) ? $team_cpt_title2 : $team_cpt_title ;

		// Team - singular
		$team_cpt_singular_title2	= Kirki::get_option( 'team-cpt-singular-title' );
		$team_cpt_singular_title	= ( !empty($team_cpt_singular_title2) ) ? $team_cpt_singular_title2 : $team_cpt_singular_title ;

		// Team Slug
		$team_cpt_slug2	= Kirki::get_option( 'team-cpt-slug' );
		$team_cpt_slug	= ( !empty($team_cpt_slug2) ) ? $team_cpt_slug2 : $team_cpt_slug ;

		// Team Group
		$team_group_title2	= Kirki::get_option( 'team-group-title' );
		$team_group_title	= ( !empty($team_group_title2) ) ? $team_group_title2 : $team_group_title ;

		// Team Group - singular
		$team_group_singular_title2	= Kirki::get_option( 'team-group-singular-title' );
		$team_group_singular_title	= ( !empty($team_group_singular_title2) ) ? $team_group_singular_title2 : $team_group_singular_title ;

		// Team Group Slug
		$team_group_slug2	= Kirki::get_option( 'team-group-slug' );
		$team_group_slug	= ( !empty($team_group_slug2) ) ? $team_group_slug2 : $team_group_slug ;

		// Testimonial
		$testimonial_cpt_title2	= Kirki::get_option( 'testimonial-cpt-title' );
		$testimonial_cpt_title	= ( !empty($testimonial_cpt_title2) ) ? $testimonial_cpt_title2 : $testimonial_cpt_title ;

		// Testimonial - singular
		$testimonial_cpt_singular_title2	= Kirki::get_option( 'testimonial-cpt-singular-title' );
		$testimonial_cpt_singular_title	= ( !empty($testimonial_cpt_singular_title2) ) ? $testimonial_cpt_singular_title2 : $testimonial_cpt_singular_title ;

		// Testimonial Category
		$testimonial_cat_title2	= Kirki::get_option( 'testimonial-cat-title' );
		$testimonial_cat_title	= ( !empty($testimonial_cat_title2) ) ? $testimonial_cat_title2 : $testimonial_cat_title ;

		// Testimonial Category - singular
		$testimonial_cat_singular_title2	= Kirki::get_option( 'testimonial-cat-singular-title' );
		$testimonial_cat_singular_title	= ( !empty($testimonial_cat_singular_title2) ) ? $testimonial_cat_singular_title2 : $testimonial_cat_singular_title ;

	}

	/**** CPT - Portfolio ****/
	$portfolio_labels = array(
		'name'               => _x( $portfolio_cpt_title, 'post type general name', 'itinc-addons' ),
		'singular_name'      => _x( $portfolio_cpt_singular_title, 'post type singular name', 'itinc-addons' ),
		'add_new_item'       => sprintf( esc_attr__( 'Add New %1$s', 'itinc-addons' ) , $portfolio_cpt_singular_title ),
		'edit_item'          => sprintf( esc_attr__( 'Edit %1$s', 'itinc-addons' ) , $portfolio_cpt_singular_title ),
		'menu_name'          => _x( $portfolio_cpt_title, 'admin menu ', 'itinc-addons' ),
		'name_admin_bar'     => _x( $portfolio_cpt_singular_title, 'add new on admin bar', 'itinc-addons' ),
		'add_new'            => esc_attr__( 'Add New', 'itinc-addons' ),
		'new_item'           => sprintf( esc_attr__( 'New %1$s', 'itinc-addons' ) , $portfolio_cpt_singular_title ),
		'view_item'          => sprintf( esc_attr__( 'View %1$s', 'itinc-addons' ) , $portfolio_cpt_singular_title ),
		'all_items'          => sprintf( esc_attr__( 'All %1$s', 'itinc-addons' ) , $portfolio_cpt_title ),
		'search_items'       => sprintf( esc_attr__( 'Search %1$s', 'itinc-addons' ) , $portfolio_cpt_title ),
		'parent_item_colon'  => sprintf( esc_attr__( 'Parent %1$s:', 'itinc-addons' ) , $portfolio_cpt_title ),
		'not_found'          => sprintf( esc_attr__( 'No %1$s found', 'itinc-addons' ) , $portfolio_cpt_title ),
		'not_found_in_trash' => sprintf( esc_attr__( 'No %1$s found in Trash.', 'itinc-addons' ) , $portfolio_cpt_title )
	);

	$portfolio_args = array(
		'labels'             => $portfolio_labels,
		'menu_icon'			=> 'dashicons-welcome-widgets-menus',
		//'description'        => __( 'Description.', 'itinc-addons' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => esc_attr($portfolio_cpt_slug) ),  // important
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'  /*'excerpt'*/ )
	);

	register_post_type( 'thsn-portfolio', $portfolio_args );

	// Add new taxonomy, make it hierarchical (like categories)
	$portfolio_category_labels = array(
		'name'              => _x( $portfolio_cat_title, 'Portfolio Category general name', 'itinc-addons' ),
		'singular_name'     => _x( $portfolio_cat_singular_title, 'Portfolio Category singular name', 'itinc-addons' ),
		'search_items'      => sprintf( esc_attr__( 'Search %1$s', 'itinc-addons' ) , $portfolio_cat_title ),
		'all_items'         => sprintf( esc_attr__( 'All %1$s', 'itinc-addons' ) , $portfolio_cat_title ),
		'parent_item'       => sprintf( esc_attr__( 'Parent %1$s', 'itinc-addons' ) , $portfolio_cat_singular_title ),
		'parent_item_colon' => sprintf( esc_attr__( 'Parent %1$s:', 'itinc-addons' ) , $portfolio_cat_singular_title ),
		'edit_item'         => sprintf( esc_attr__( 'Edit %1$s', 'itinc-addons' ) , $portfolio_cat_singular_title ),
		'update_item'       => sprintf( esc_attr__( 'Update %1$s', 'itinc-addons' ) , $portfolio_cat_singular_title ),
		'add_new_item'      => sprintf( esc_attr__( 'Add New %1$s', 'itinc-addons' ) , $portfolio_cat_singular_title ),
		'new_item_name'     => sprintf( esc_attr__( 'New %1$s Name', 'itinc-addons' ) , $portfolio_cat_singular_title ),
		'menu_name'         => $portfolio_cat_singular_title,
	);

	$portfolio_category_args = array(
		'hierarchical'      => true,
		'labels'            => $portfolio_category_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => esc_attr($portfolio_cat_slug) ),
	);

	register_taxonomy( 'thsn-portfolio-category', array( 'thsn-portfolio' ), $portfolio_category_args );

	/**** CPT - Service ****/
	$service_labels = array(
		'name'               => _x( $service_cpt_title, 'post type general name', 'itinc-addons' ),
		'singular_name'      => _x( $service_cpt_singular_title, 'post type singular name', 'itinc-addons' ),
		'add_new_item'       => sprintf( esc_attr__( 'Add New %1$s', 'itinc-addons' ) , $service_cpt_singular_title ),
		'edit_item'          => sprintf( esc_attr__( 'Edit %1$s', 'itinc-addons' ) , $service_cpt_singular_title ),
		'menu_name'          => _x( $service_cpt_title, 'admin menu ', 'itinc-addons' ),
		'name_admin_bar'     => _x( $service_cpt_singular_title, 'add new on admin bar', 'itinc-addons' ),
		'add_new'            => esc_attr__( 'Add New', 'itinc-addons' ),
		'new_item'           => sprintf( esc_attr__( 'New %1$s', 'itinc-addons' ) , $service_cpt_singular_title ),
		'view_item'          => sprintf( esc_attr__( 'View %1$s', 'itinc-addons' ) , $service_cpt_singular_title ),
		'all_items'          => sprintf( esc_attr__( 'All %1$s', 'itinc-addons' ) , $service_cpt_title ),
		'search_items'       => sprintf( esc_attr__( 'Search %1$s', 'itinc-addons' ) , $service_cpt_title ),
		'parent_item_colon'  => sprintf( esc_attr__( 'Parent %1$s:', 'itinc-addons' ) , $service_cpt_title ),
		'not_found'          => sprintf( esc_attr__( 'No %1$s found', 'itinc-addons' ) , $service_cpt_title ),
		'not_found_in_trash' => sprintf( esc_attr__( 'No %1$s found in Trash.', 'itinc-addons' ) , $service_cpt_title )
	);

	$service_args = array(
		'labels'             => $service_labels,
		'menu_icon'			=> 'dashicons-analytics',
		//'description'        => __( 'Description.', 'itinc-addons' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => esc_attr($service_cpt_slug) ),  // important
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'  /*'excerpt'*/ )
	);

	register_post_type( 'thsn-service', $service_args );

	// Add new taxonomy, make it hierarchical (like categories)
	$service_category_labels = array(
		'name'              => _x( $service_cat_title, 'Service Category general name', 'itinc-addons' ),
		'singular_name'     => _x( $service_cat_singular_title, 'Service Category singular name', 'itinc-addons' ),
		'search_items'      => sprintf( esc_attr__( 'Search %1$s', 'itinc-addons' ) , $service_cat_title ),
		'all_items'         => sprintf( esc_attr__( 'All %1$s', 'itinc-addons' ) , $service_cat_title ),
		'parent_item'       => sprintf( esc_attr__( 'Parent %1$s', 'itinc-addons' ) , $service_cat_singular_title ),
		'parent_item_colon' => sprintf( esc_attr__( 'Parent %1$s:', 'itinc-addons' ) , $service_cat_singular_title ),
		'edit_item'         => sprintf( esc_attr__( 'Edit %1$s', 'itinc-addons' ) , $service_cat_singular_title ),
		'update_item'       => sprintf( esc_attr__( 'Update %1$s', 'itinc-addons' ) , $service_cat_singular_title ),
		'add_new_item'      => sprintf( esc_attr__( 'Add New %1$s', 'itinc-addons' ) , $service_cat_singular_title ),
		'new_item_name'     => sprintf( esc_attr__( 'New %1$s Name', 'itinc-addons' ) , $service_cat_singular_title ),
		'menu_name'         => $service_cat_singular_title,
	);

	$service_category_args = array(
		'hierarchical'      => true,
		'labels'            => $service_category_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => esc_attr($service_cat_slug) ),
	);

	register_taxonomy( 'thsn-service-category', array( 'thsn-service' ), $service_category_args );

	/**** CPT - Team Member ****/
	$team_members_labels = array(
		'name'               => _x( $team_cpt_title, 'post type general name', 'itinc-addons' ),
		'singular_name'      => _x( $team_cpt_singular_title, 'post type singular name', 'itinc-addons' ),
		'add_new_item'       => sprintf( esc_attr__( 'Add New %1$s', 'itinc-addons' ) , $team_cpt_singular_title ),
		'edit_item'          => sprintf( esc_attr__( 'Edit %1$s', 'itinc-addons' ) , $team_cpt_singular_title ),
		'menu_name'          => _x( $team_cpt_title, 'admin menu ', 'itinc-addons' ),
		'name_admin_bar'     => _x( $team_cpt_singular_title, 'add new on admin bar', 'itinc-addons' ),
		'add_new'            => esc_attr__( 'Add New', 'itinc-addons' ),
		'new_item'           => sprintf( esc_attr__( 'New %1$s', 'itinc-addons' ) , $team_cpt_singular_title ),
		'view_item'          => sprintf( esc_attr__( 'View %1$s', 'itinc-addons' ) , $team_cpt_singular_title ),
		'all_items'          => sprintf( esc_attr__( 'All %1$s', 'itinc-addons' ) , $team_cpt_title ),
		'search_items'       => sprintf( esc_attr__( 'Search %1$s', 'itinc-addons' ) , $team_cpt_title ),
		'parent_item_colon'  => sprintf( esc_attr__( 'Parent %1$s:', 'itinc-addons' ) , $team_cpt_title ),
		'not_found'          => sprintf( esc_attr__( 'No %1$s found', 'itinc-addons' ) , $team_cpt_title ),
		'not_found_in_trash' => sprintf( esc_attr__( 'No %1$s found in Trash.', 'itinc-addons' ) , $team_cpt_title )
	);

	$team_members_args = array(
		'labels'             => $team_members_labels,
		'menu_icon'			=> 'dashicons-id',
		//'description'        => __( 'Description.', 'itinc-addons' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => esc_attr($team_cpt_slug) ),  // important
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail', /* 'excerpt' */ )
	);

	register_post_type( 'thsn-team-member', $team_members_args );

	// Add new taxonomy, make it hierarchical (like categories)
	$team_member_group_labels = array(
		'name'              => _x( $team_group_title, 'Team Group general name', 'itinc-addons' ),
		'singular_name'     => _x( $team_group_singular_title, 'Team Group singular name', 'itinc-addons' ),
		'search_items'      => sprintf( esc_attr__( 'Search %1$s', 'itinc-addons' ) , $team_group_title ),
		'all_items'         => sprintf( esc_attr__( 'All %1$s', 'itinc-addons' ) , $team_group_title ),
		'parent_item'       => sprintf( esc_attr__( 'Parent %1$s', 'itinc-addons' ) , $team_group_singular_title ),
		'parent_item_colon' => sprintf( esc_attr__( 'Parent %1$s:', 'itinc-addons' ) , $team_group_singular_title ),
		'edit_item'         => sprintf( esc_attr__( 'Edit %1$s', 'itinc-addons' ) , $team_group_singular_title ),
		'update_item'       => sprintf( esc_attr__( 'Update %1$s', 'itinc-addons' ) , $team_group_singular_title ),
		'add_new_item'      => sprintf( esc_attr__( 'Add New %1$s', 'itinc-addons' ) , $team_group_singular_title ),
		'new_item_name'     => sprintf( esc_attr__( 'New %1$s Name', 'itinc-addons' ) , $team_group_singular_title ),
		'menu_name'         => $team_group_singular_title,
	);

	$team_member_group_args = array(
		'hierarchical'      => true,
		'labels'            => $team_member_group_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => esc_attr($team_group_slug) ),
	);

	register_taxonomy( 'thsn-team-group', array( 'thsn-team-member' ), $team_member_group_args );

	/**** CPT - Testimonials ****/
	$testimonial_labels = array(
		'name'               => _x( $testimonial_cpt_title, 'post type general name', 'itinc-addons' ),
		'singular_name'      => _x( $testimonial_cpt_singular_title, 'post type singular name', 'itinc-addons' ),
		'add_new_item'       => sprintf( esc_attr__( 'Add New %1$s', 'itinc-addons' ) , $testimonial_cpt_singular_title ),
		'edit_item'          => sprintf( esc_attr__( 'Edit %1$s', 'itinc-addons' ) , $testimonial_cpt_singular_title ),
		'menu_name'          => _x( $testimonial_cpt_title, 'admin menu ', 'itinc-addons' ),
		'name_admin_bar'     => _x( $testimonial_cpt_singular_title, 'add new on admin bar', 'itinc-addons' ),
		'add_new'            => esc_attr__( 'Add New', 'itinc-addons' ),
		'new_item'           => sprintf( esc_attr__( 'New %1$s', 'itinc-addons' ) , $testimonial_cpt_singular_title ),
		'view_item'          => sprintf( esc_attr__( 'View %1$s', 'itinc-addons' ) , $testimonial_cpt_singular_title ),
		'all_items'          => sprintf( esc_attr__( 'All %1$s', 'itinc-addons' ) , $testimonial_cpt_title ),
		'search_items'       => sprintf( esc_attr__( 'Search %1$s', 'itinc-addons' ) , $testimonial_cpt_title ),
		'parent_item_colon'  => sprintf( esc_attr__( 'Parent %1$s:', 'itinc-addons' ) , $testimonial_cpt_title ),
		'not_found'          => sprintf( esc_attr__( 'No %1$s found', 'itinc-addons' ) , $testimonial_cpt_title ),
		'not_found_in_trash' => sprintf( esc_attr__( 'No %1$s found in Trash.', 'itinc-addons' ) , $testimonial_cpt_title ),
		'featured_image'		=> sprintf( esc_attr__( '%1$s writer\'s image/logo', 'itinc-addons' ) , $testimonial_cpt_singular_title ),
		'set_featured_image'	=> esc_attr__( 'Set image/logo', 'itinc-addons' ),
		'remove_featured_image'	=> esc_attr__( 'Remove image/logo', 'itinc-addons' ),
		'use_featured_image'	=> sprintf( esc_attr__( 'Use as %1$s writer\'s image/logo', 'itinc-addons' ) , $testimonial_cpt_singular_title ),

	);

	$testimonial_args = array(
		'labels'             => $testimonial_labels,
		'menu_icon'			=> 'dashicons-testimonial',
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => esc_attr($testimonial_cpt_slug) ),  // important
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	);

	register_post_type( 'thsn-testimonial', $testimonial_args );

	// Add new taxonomy, make it hierarchical (like categories)
	$testimonial_cat_labels = array(
		'name'              => _x( $testimonial_cat_title, 'Team Group general name', 'itinc-addons' ),
		'singular_name'     => _x( $testimonial_cat_singular_title, 'Team Group singular name', 'itinc-addons' ),
		'search_items'      => sprintf( esc_attr__( 'Search %1$s', 'itinc-addons' ) , $testimonial_cat_title ),
		'all_items'         => sprintf( esc_attr__( 'All %1$s', 'itinc-addons' ) , $testimonial_cat_title ),
		'parent_item'       => sprintf( esc_attr__( 'Parent %1$s', 'itinc-addons' ) , $testimonial_cat_singular_title ),
		'parent_item_colon' => sprintf( esc_attr__( 'Parent %1$s:', 'itinc-addons' ) , $testimonial_cat_singular_title ),
		'edit_item'         => sprintf( esc_attr__( 'Edit %1$s', 'itinc-addons' ) , $testimonial_cat_singular_title ),
		'update_item'       => sprintf( esc_attr__( 'Update %1$s', 'itinc-addons' ) , $testimonial_cat_singular_title ),
		'add_new_item'      => sprintf( esc_attr__( 'Add New %1$s', 'itinc-addons' ) , $testimonial_cat_singular_title ),
		'new_item_name'     => sprintf( esc_attr__( 'New %1$s Name', 'itinc-addons' ) , $testimonial_cat_singular_title ),
		'menu_name'         => $testimonial_cat_singular_title,
	);

	$testimonial_cat_args = array(
		'public'            => false,
		'hierarchical'      => false,
		'labels'            => $testimonial_cat_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => esc_attr($testimonial_cat_slug) ),
	);

	register_taxonomy( 'thsn-testimonial-cat', array( 'thsn-testimonial' ), $testimonial_cat_args );

	/*******************************/

	// CPT - Clients
	$clients_labels = array(
		'name'					=> _x( 'Clients', 'post type general name', 'itinc-addons' ),
		'singular_name'			=> _x( 'Client', 'post type singular name', 'itinc-addons' ),
		'add_new_item'			=> esc_attr__( 'Add New Client', 'itinc-addons' ),
		'featured_image'		=> esc_attr__( 'Client Logo', 'itinc-addons' ),
		'set_featured_image'	=> esc_attr__( 'Set Client Logo', 'itinc-addons' ),
		'remove_featured_image'	=> esc_attr__( 'Remove Client Logo', 'itinc-addons' ),
		'use_featured_image'	=> esc_attr__( 'Use as Client Logo', 'itinc-addons' ),
	);

	$clients_args = array(
		'labels'             => $clients_labels,
		'menu_icon'			=> 'dashicons-grid-view',
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'client' ),  // important
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'thumbnail' )
	);

	register_post_type( 'thsn-client', $clients_args );

	/*******************************/
	// Add new taxonomy, make it hierarchical (like categories)
	$client_group_labels = array(
		'name'              => _x( 'Client Groups', 'Client Group general name', 'itinc-addons' ),
		'singular_name'     => _x( 'Client Group', 'Client Group singular name', 'itinc-addons' ),
		'search_items'      => __( 'Search Client Groups', 'itinc-addons' ),
		'all_items'         => __( 'All Client Groups', 'itinc-addons' ),
		'parent_item'       => __( 'Parent Client Group', 'itinc-addons' ),
		'parent_item_colon' => __( 'Parent Client Group:', 'itinc-addons' ),
		'edit_item'         => __( 'Edit Client Group', 'itinc-addons' ),
		'update_item'       => __( 'Update Client Group', 'itinc-addons' ),
		'add_new_item'      => __( 'Add New Client Group', 'itinc-addons' ),
		'new_item_name'     => __( 'New Client Group Name', 'itinc-addons' ),
		'menu_name'         => __( 'Client Group', 'itinc-addons' ),
	);

	$client_group_args = array(
		'public'            => false,
		'hierarchical'      => false,
		'labels'            => $client_group_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'thsn-client-group' ),
	);

	register_taxonomy( 'thsn-client-group', array( 'thsn-client' ), $client_group_args );

	// Move feature image box below title
	add_action('do_meta_boxes', 'themesion_client_image_box');
	function themesion_client_image_box() {
		remove_meta_box( 'postimagediv', 'thsn-client', 'side' );
		add_meta_box('postimagediv', esc_attr__('Select Client Logo', 'itinc-addons'), 'post_thumbnail_meta_box', 'thsn-client', 'normal', 'high');
	}

	/**************************/

	// Show featured image column
	add_filter( 'manage_posts_columns', 'themesion_addon_set_featured_image_column' );
	add_action( 'manage_posts_custom_column' , 'themesion_addon_set_featured_image_column_thumbnails', 10, 2 );
	if ( ! function_exists( 'themesion_addon_set_featured_image_column' ) ) {
	function themesion_addon_set_featured_image_column( $columns ) {
		$new_columns = array();
		foreach( $columns as $key=>$val ){
			$new_columns[$key] = $val;
			if( $key=='title' ){
				$new_columns['themesion_featured_image'] = esc_attr__( 'Featured Image', 'itinc-addons' );
			}
		}
		return $new_columns;
	}
	}
	if ( ! function_exists( 'themesion_addon_set_featured_image_column_thumbnails' ) ) {
	function themesion_addon_set_featured_image_column_thumbnails( $column, $post_id ) {
		if( $column == 'themesion_featured_image' ){
			echo '<a href="'. get_permalink($post_id) .'">';
			if ( has_post_thumbnail($post_id) ) {
				the_post_thumbnail('thumbnail');
			} else {
				echo '<img src="' . ITINC_ADDON_URL . 'images/no-img-150x150.png" />';
			}
			echo '</a>';
		}

	}
	}

	// Change title input placeholder
	if( !function_exists('themesion_change_title_text') ){
	function themesion_change_title_text( $title ){
		$screen = get_current_screen();

		$team_cpt_singular_title		= esc_attr__('Team Member','itinc-addons');
		if( class_exists('Kirki') ){

			$team_cpt_singular_title2	= Kirki::get_option( 'team-cpt-singular-title' );
			$team_cpt_singular_title	= ( !empty($team_cpt_singular_title2) ) ? $team_cpt_singular_title2 : $team_cpt_singular_title ;
		}

		if( 'thsn-testimonial' == $screen->post_type ){
			$title = esc_attr__('Enter writer name here', 'itinc-addons');
		} else if( 'thsn-team-member' == $screen->post_type ){
			$title = sprintf( esc_attr__('Enter %1$s name here', 'itinc-addons') , $team_cpt_singular_title );
		} else if( 'thsn-client' == $screen->post_type ){
			$title = esc_attr__('Enter Client/Company name here', 'itinc-addons');
		}
		return $title;
	}
	}
	add_filter( 'enter_title_here', 'themesion_change_title_text' );

}
}
add_action( 'init', 'itinc_addons_register_post_types', 1 );

// All widgets
include( ITINC_ADDON_PATH . 'widgets/list-all-posts-widget.php' );
include( ITINC_ADDON_PATH . 'widgets/category-list-widget.php' );
include( ITINC_ADDON_PATH . 'widgets/recent-post-widget.php' );
include( ITINC_ADDON_PATH . 'widgets/contact-widget.php' );

/**
 *  add Global Color class style
 */
add_action( 'admin_head', 'themesion_admin_globalcolor_css' );
if( !function_exists('themesion_admin_globalcolor_css') ){
function themesion_admin_globalcolor_css(){

	$white_color = '';
	if( function_exists('thsn_get_base_option') ){
		$white_color = thsn_get_base_option('white-color');
	}

	$blackish_color = '';
	if( function_exists('thsn_get_base_option') ){
		$blackish_color = thsn_get_base_option('blackish-color');
	}

	$light_bg_color = '';
	if( function_exists('thsn_get_base_option') ){
		$light_bg_color = thsn_get_base_option('light-bg-color');
	}

	$blackish_bg_color = '';
	if( function_exists('thsn_get_base_option') ){
		$blackish_bg_color = thsn_get_base_option('blackish-bg-color');
	}

	$global_color = '#ffff00';
	if( function_exists('thsn_get_base_option') ){
		$global_color = thsn_get_base_option('global-color');
	}

	$secondary_color = '#ffff00';
	if( function_exists('thsn_get_base_option') ){
		$secondary_color = thsn_get_base_option('secondary-color');
	}

	$gradient_first = '#ffff00';
	$gradient_last  = '#ffff00';
	if( function_exists('thsn_get_base_option') ){
		$gradient_colors = thsn_get_base_option('gradient-color');
		$gradient_first = ( !empty($gradient_colors['first']) ) ? $gradient_colors['first'] : '#ffff00' ;
		$gradient_last = ( !empty($gradient_colors['last']) ) ? $gradient_colors['last'] : '#ffff00' ;
	}

	?>
	<style>
		/* all button hover */
		.composer-switch a:hover{
			background-color: <?php echo thsn_color_luminance($global_color, '-0.50'); ?> !important;
		}
		.thsn-tab-main {
			font-weight: bold !important;
		}
		.thsn-imgselector-thumb-white img{
			background-color: <?php echo esc_attr($white_color); ?> !important;
		}
		.thsn-imgselector-thumb-blackish img{
			background-color: <?php echo esc_attr($blackish_color); ?> !important;
		}
		div.thsn-imgselector-thumb-light[data-selector="thsn-bg-color"] img{
			background-color: <?php echo esc_attr($light_bg_color); ?> !important;
		}
		div.thsn-imgselector-thumb-blackish[data-selector="thsn-bg-color"] img{
			background-color: <?php echo esc_attr($blackish_bg_color); ?> !important;
		}

		.thsn-imgselector-thumb-globalcolor img{
			background-color: <?php echo esc_attr($global_color); ?> !important;
		}
		.thsn-imgselector-thumb-secondarycolor img{
			background-color: <?php echo esc_attr($secondary_color); ?> !important;
		}
		.thsn-imgselector-thumb-gradientcolor img{
			background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> );
		}
		
		/* THSN Customize menu */
		.thsn-admin-customize-menu,
		.thsn-admin-customize-menu a:active,
		.thsn-admin-customize-menu a:hover,
		.thsn-admin-customize-menu a:visited,
		#adminmenu li.thsn-admin-customize-menu>a.menu-top:focus,
		#adminmenu li.thsn-admin-customize-menu.menu-top:hover,
		#adminmenu li.thsn-admin-customize-menu.opensub>a.menu-top{
			background-color: <?php echo esc_attr($global_color); ?> !important;
			color: #fff !important;
			text-shadow: 0 0 #fff !important;
		
		}
		.thsn-admin-customize-menu div.wp-menu-image.dashicons-before{
			font-family: dashicons;
			display: inline-block;
			line-height: 1;
			font-weight: 400;
			font-style: normal;
			speak: none;
			text-decoration: inherit;
			text-transform: none;
			text-rendering: auto;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
			width: 20px;
			height: 20px;
			font-size: 20px;
			vertical-align: top;
			text-align: center;
			transition: color .1s ease-in;

			font-size: 240px;
			width: 240px;
			height: 240px;
			overflow: visible;
		}
		#adminmenu li.thsn-admin-customize-menu a:focus div.wp-menu-image:before,
		#adminmenu li.thsn-admin-customize-menu.opensub div.wp-menu-image:before,
		#adminmenu li.thsn-admin-customize-menu:hover div.wp-menu-image:before{
			color: #fff !important;
		}

		.thsn-admin-customize-menu div.wp-menu-image.dashicons-before:before{
			content: "\f139";
		}

	</style>
	<script>
	jQuery( document ).ready(function($) {
		$( "button:contains('ITINC ELEMENTS')" ).addClass('thsn-tab-main');
	});
	</script>

	<?php

}
}

/**
 * This function adds some styles to the WordPress Customizer
 */
if( !function_exists('themesion_customizer_styles') ){
function themesion_customizer_styles() {

	$global_color = '#ffff00';
	if( function_exists('thsn_get_base_option') ){
		$global_color = thsn_get_base_option('global-color');
	}
	$secondary_color = '#ffff00';
	if( function_exists('thsn_get_base_option') ){
		$secondary_color = thsn_get_base_option('secondary-color');
	}

	$gradient_first = '#ffff00';
	$gradient_last  = '#ffff00';
	if( function_exists('thsn_get_base_option') ){
		$gradient_colors = thsn_get_base_option('gradient-color');
		$gradient_first = ( !empty($gradient_colors['first']) ) ? $gradient_colors['first'] : '#ffff00' ;
		$gradient_last = ( !empty($gradient_colors['last']) ) ? $gradient_colors['last'] : '#ffff00' ;
	}

	?>
	<style>
		/* Customizer option */
		#accordion-panel-itinc_base_options h3{
			background-color: <?php echo esc_attr($global_color); ?> !important;
			color: #ffffff !important;
			border-left-color: #2d2d2d !important;
		}
		#accordion-panel-itinc_base_options h3:after{
			color: #ffffff !important;
		}
		#accordion-panel-itinc_base_options:hover h3{
			border-left-color: #000000 !important;
		}
		#accordion-panel-itinc_base_options:hover h3:after{
			color: #000000 !important;
		}

		.accordion-section.control-section-kirki-default.control-subsection:hover h3,
		.accordion-section.control-section-kirki-default.control-subsection h3:focus{
			color: <?php echo esc_attr($global_color); ?> !important;
			border-left-color: <?php echo esc_attr($global_color); ?> !important;
		}
		.accordion-section.control-section-kirki-default.control-subsection:hover h3:after,
		.accordion-section.control-section-kirki-default.control-subsection h3:focus:after{
			color: <?php echo esc_attr($global_color); ?> !important;
		}

		/* Back Button */
		#sub-accordion-panel-itinc_base_options li.panel-meta .customize-panel-back{
			color: <?php echo esc_attr($global_color); ?> !important;
			border-left-color: <?php echo esc_attr($global_color); ?> !important;
		}
		ul.customize-pane-child.control-section-kirki-default .customize-section-back{
			color: <?php echo esc_attr($global_color); ?> !important;
			border-left-color: <?php echo esc_attr($global_color); ?> !important;
		}
		#sub-accordion-panel-itinc_base_options .panel-title{
			color: <?php echo esc_attr($global_color); ?> !important;
		}

		/* Customizer */
		label[class$="globalcolor"] img{
			background-color: <?php echo esc_attr($global_color); ?> !important;
		}
		label[class$="secondarycolor"] img{
			background-color: <?php echo esc_attr($secondary_color); ?> !important;
		}
		label[class$="gradientcolor"] img{
			background-color: <?php echo esc_attr($gradient_first); ?> !important;
			background-image: linear-gradient(to right, <?php echo esc_attr($gradient_first); ?> , <?php echo esc_attr($gradient_last); ?> ) !important;
			color: #ffffff !important;
		}
	</style>
	<?php
}
}
add_action( 'customize_controls_print_styles', 'themesion_customizer_styles' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

if( !function_exists('itinc_addons_widgets_init') ){
function itinc_addons_widgets_init() {

	// Default titles
	$portfolio_cpt_singular_title	= esc_attr__('Portfolio','itinc-addons');
	$portfolio_cat_singular_title	= esc_attr__('Portfolio Category','itinc-addons');
	$service_cpt_singular_title		= esc_attr__('Service','itinc-addons');
	$service_cat_singular_title		= esc_attr__('Service Category','itinc-addons');
	$team_cpt_singular_title		= esc_attr__('Team Member','itinc-addons');
	$team_group_singular_title		= esc_attr__('Team Group','itinc-addons');

	if( function_exists('thsn_get_base_option') ){

		// Portfolio - singular
		$portfolio_cpt_singular_title2	= thsn_get_base_option( 'portfolio-cpt-singular-title' );
		$portfolio_cpt_singular_title	= ( !empty($portfolio_cpt_singular_title2) ) ? $portfolio_cpt_singular_title2 : $portfolio_cpt_singular_title ;

		// Portfolio Category - singular
		$portfolio_cat_singular_title2	= thsn_get_base_option( 'portfolio-cat-singular-title' );
		$portfolio_cat_singular_title	= ( !empty($portfolio_cat_singular_title2) ) ? $portfolio_cat_singular_title2 : $portfolio_cat_singular_title ;

		// Service - singular
		$service_cpt_singular_title2	= thsn_get_base_option( 'service-cpt-singular-title' );
		$service_cpt_singular_title	= ( !empty($service_cpt_singular_title2) ) ? $service_cpt_singular_title2 : $service_cpt_singular_title ;

		// Portfolio Category - singular
		$service_cat_singular_title2	= thsn_get_base_option( 'service-cat-singular-title' );
		$service_cat_singular_title	= ( !empty($service_cat_singular_title2) ) ? $service_cat_singular_title2 : $service_cat_singular_title ;

		// Team - singular
		$team_cpt_singular_title2	= thsn_get_base_option( 'team-cpt-singular-title' );
		$team_cpt_singular_title	= ( !empty($team_cpt_singular_title2) ) ? $team_cpt_singular_title2 : $team_cpt_singular_title ;

		// Team Group - singular
		$team_group_singular_title2	= thsn_get_base_option( 'team-group-singular-title' );
		$team_group_singular_title	= ( !empty($team_group_singular_title2) ) ? $team_group_singular_title2 : $team_group_singular_title ;

	}

	register_sidebar( array(
		'name'          => sprintf( esc_attr__( '%1$s Sidebar', 'itinc-addons' ) , $portfolio_cpt_singular_title ),
		'id'            => 'thsn-sidebar-portfolio',
		'description'   => sprintf( esc_attr__( 'Add widgets for %1$s Sidebar', 'itinc-addons' ) , $portfolio_cpt_singular_title ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => sprintf( esc_attr__( '%1$s Sidebar', 'itinc-addons' ) , $portfolio_cat_singular_title ),
		'id'            => 'thsn-sidebar-portfolio-cat',
		'description'   => sprintf( esc_attr__( 'Add widgets for %1$s Sidebar', 'itinc-addons' ) , $portfolio_cat_singular_title ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => sprintf( esc_attr__( '%1$s Sidebar', 'itinc-addons' ) , $service_cpt_singular_title ),
		'id'            => 'thsn-sidebar-service',
		'description'   => sprintf( esc_attr__( 'Add widgets for %1$s Sidebar', 'itinc-addons' ) , $service_cpt_singular_title ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => sprintf( esc_attr__( '%1$s Sidebar', 'itinc-addons' ) , $service_cat_singular_title ),
		'id'            => 'thsn-sidebar-service-cat',
		'description'   => sprintf( esc_attr__( 'Add widgets for %1$s Sidebar', 'itinc-addons' ) , $service_cat_singular_title ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Team Member Sidebar', 'itinc-addons' ),
		'name'          => sprintf( esc_attr__( '%1$s Sidebar', 'itinc-addons' ) , $team_cpt_singular_title ),
		'id'            => 'thsn-sidebar-team',
		'description'   => sprintf( esc_attr__( 'Add widgets for %1$s Sidebar', 'itinc-addons' ) , $team_cpt_singular_title ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => sprintf( esc_attr__( '%1$s Sidebar', 'itinc-addons' ) , $team_group_singular_title ),
		'id'            => 'thsn-sidebar-team-group',
		'description'   => sprintf( esc_attr__( 'Add widgets for %1$s Sidebar', 'itinc-addons' ) , $team_group_singular_title ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	if( function_exists('is_woocommerce') ){
		register_sidebar( array(
			'name'			=> esc_html__( 'WooCommerce - Shop Page', 'itinc-addons' ),
			'id'			=> 'thsn-sidebar-wc-shop',
			'description'	=> esc_html__( 'Widgets for WooCommerce shop (product listing) page.', 'itinc-addons' ),
			'before_widget'	=> '<aside id="%1$s" class="widget %2$s">',
			'after_widget'	=> '</aside>',
			'before_title'	=> '<h3 class="widget-title">',
			'after_title'	=> '</h3>',
		) );
		register_sidebar( array(
			'name'			=> esc_html__( 'WooCommerce - Single Product Page', 'itinc-addons' ),
			'id'			=> 'thsn-sidebar-wc-single',
			'description'	=> esc_html__( 'Widgets for WooCommerce single product page.', 'itinc-addons' ),
			'before_widget'	=> '<aside id="%1$s" class="widget %2$s">',
			'after_widget'	=> '</aside>',
			'before_title'	=> '<h3 class="widget-title">',
			'after_title'	=> '</h3>',
		) );
	}

}
}
add_action( 'widgets_init', 'itinc_addons_widgets_init', 21 );

/**
 * Enqueue scripts and styles.
 */
if( !function_exists('itinc_addons_admin_scripts_styles') ){
function itinc_addons_admin_scripts_styles() {
	wp_enqueue_style( 'balloon', ITINC_ADDON_URL . 'libraries/balloon/balloon.min.css' );
}
}
add_action( 'admin_enqueue_scripts', 'itinc_addons_admin_scripts_styles' );

if ( ! function_exists( 'thsn_edit_link' ) ) :
/**
 * Returns an accessibility-friendly link to edit a post or page.
 *
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
function thsn_edit_link() {
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'itinc-addons' ),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

if( !function_exists('thsn_get_base_options') ) {
function thsn_get_base_options( $option='' ){
	$return = '';

	if( class_exists('Kirki') ){
		$return = Kirki::get_option( $option );
	} else {
		if( !$kirki_options_array ){
			include get_template_directory() . '/includes/customizer-options.php';
		}
		foreach( $kirki_options_array as $kirki_options ){
			if( !empty($kirki_options['section_fields']) ){
				foreach( $kirki_options['section_fields'] as $field ){
					if( !empty($field['settings']) && $field['settings']==$option && isset($field['default']) ){
						$return = $field['default'];
					}
				}
			}
		}
	}

	return $return;
}
}

/**
 *  Add CSS / JS / Tracking code in head
 */
if( !function_exists('themesion_custom_code') ){
function themesion_custom_code(){
	$tracking_code	= thsn_get_base_option('tracking-code');
	$css_code		= thsn_get_base_option('css-code');
	$css_code		= htmlspecialchars_decode($css_code);
	$css_code		= html_entity_decode($css_code, ENT_QUOTES);

	$js_code		= thsn_get_base_option('js-code');
	$js_code		= htmlspecialchars_decode($js_code);
	$js_code		= html_entity_decode($js_code, ENT_QUOTES);

	// Tracking code
	echo $tracking_code;

	// CSS Code
	if( !empty($css_code) ){
		echo '<style>'.$css_code.'</style>';
	}

	// JS Code
	if( !empty($js_code) ){
		echo '<script>'.$js_code.'</script>';
	}

}
}
add_action( 'wp_head', 'themesion_custom_code' );

// CSS Minifier => http://ideone.com/Q5USEF + improvement(s)
if( !function_exists('thsn_minify_css') ){
function thsn_minify_css($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
            // Remove unused white-space(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
            '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
            // Replace `:0 0 0 0` with `:0`
            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
            // Replace `background-position:0` with `background-position:0 0`
            '#(background-position):0(?=[;\}])#si',
            // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
            '#(?<=[\s:,\-])0+\.(\d+)#s',
            // Minify string value
            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
            // Minify HEX color code
            '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
            // Replace `(border|outline):none` with `(border|outline):0`
            '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
            // Remove empty selector(s)
            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
        ),
        array(
            '$1',
            '$1$2$3$4$5$6$7',
            '$1',
            ':0',
            '$1:0 0',
            '.$1',
            '$1$3',
            '$1$2$4$5',
            '$1$2$3',
            '$1:0',
            '$1$2'
        ),
    $input);
}
}

// JavaScript Minifier
if( !function_exists('thsn_minify_js') ){
function thsn_minify_js($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
            // Remove white-space(s) outside the string and regex
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
            // Remove the last semicolon
            '#;+\}#',
            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
            // --ibid. From `foo['bar']` to `foo.bar`
            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
        ),
        array(
            '$1',
            '$1$2',
            '}',
            '$1$3',
            '$1.$3'
        ),
    $input);
}
}

if( !function_exists('thsn_after_import') ){
function thsn_after_import( $selected_import ) {
	// Assign menus to their locations.
	$main_menu = get_term_by( 'name', esc_attr('Main Menu'), 'nav_menu' );
	set_theme_mod(
		'nav_menu_locations', array(
			'themesion-top' => $main_menu->term_id,
		)
	);
	// Assign front page and posts page (blog page).
	$query = new WP_Query( array( 'post_type' => 'page', 'title' => 'Home 01' ) );
	if ( ! empty( $query->post ) ) {
		$front_page_id = $query->post;
		if( isset($front_page_id->ID) && !empty($front_page_id->ID) ){
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page_id->ID );
		}
	}
	$query = new WP_Query( array( 'post_type' => 'page', 'title' => 'Blog Classic' ) );
	if ( ! empty( $query->post ) ) {
		$blog_page_id = $query->post;
		if( isset($blog_page_id->ID) && !empty($blog_page_id->ID) ){
			update_option( 'show_on_front', 'page' );
			update_option( 'page_for_posts', $blog_page_id->ID );
		}
	}

	// Changing "Hello World" post date
	$query = new WP_Query( array( 'post_title' => 'Hello world!', OBJECT, 'post_type' => 'post' ) );
	if ( ! empty( $query->post ) ) {
		$hello_world_post = $query->post;
			if( isset($hello_world_post->ID) && !empty($hello_world_post->ID) ){
				$hw_post = array(
					'ID'		=> $hello_world_post->ID,
					'post_date'	=> "2010-01-01 0:0:0" // Format : Y-m-d H:i:s
				);
				wp_update_post($hw_post);
			}
		}
	}
}
add_action( 'theme-demo-import/after_import', 'thsn_after_import' );

/**
 *  Dynamic Style Code
 */
if( !function_exists('thsn_auto_css') ){
function thsn_auto_css() {
	header("Content-Type: text/css");
	ob_start();
	include get_template_directory().'/css/theme-style.php'; // Fetching theme-style.php output and store in a variable
	$css    = ob_get_clean();

	// Minify
	if( function_exists('thsn_get_base_option') && thsn_get_base_option('min')=='1' && function_exists('thsn_minify_css') ){
		echo thsn_minify_css( $css );
	} else {
		echo $css;
	}

	exit;
}
}
add_action('wp_ajax_thsn_auto_css', 'thsn_auto_css');
add_action('wp_ajax_nopriv_thsn_auto_css', 'thsn_auto_css');

if( !function_exists('thsn_get_column_bg') ){
function thsn_get_column_bg( $content='', $type='first' ) {
	$class = $inline_css = $bg_img = '';

	preg_match_all('/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER);
	$total_columns = count($matches);

	if( $type=='first' ){
		$match = $matches[0];
		$atts  = shortcode_parse_atts( $match[3] );

	} else if( $type=='last' ){
		$match = $matches[ count($matches)-1 ];
		$atts  = shortcode_parse_atts( $match[3] );
	}

	// background color class
	if( !empty($atts['thsn-bg-color']) ){
		$class = 'thsn-bg-color-' . $atts["thsn-bg-color"];
	}

	// custom background
	if( !empty($atts['css']) ){
		$css = $atts['css'];

		$css2 = explode('{', $css );
		$css2 = explode('}', $css2[1] );
		$css2 = explode(';', $css2[0] );
		$x = 1;
		foreach( $css2 as $css ){

			if( substr( $css,0, 11 )=='background:' || substr( $css,0, 17 )=='background-image:' ){
				$inline_css .= $css.';';

				// Getting background image
				if( substr( $css,0, 11 )=='background:' ){
					$bg_img2 = explode('background:', $css );
					$bg_img2 = explode('url(', $bg_img2[1] );
					$bg_img2 = $bg_img2[1];
					$bg_img2 = str_replace( ') !important', '', $bg_img2 );

				} else if( substr( $css,0, 17 )=='background-image:' ){
					$bg_img2 = explode('background-image:', $css );
					$bg_img2 = explode('url(', $bg_img2[1] );
					$bg_img2 = $bg_img2[1];
					$bg_img2 = str_replace( ') !important', '', $bg_img2 );

				}

				$bg_img = $bg_img2;

			} else if( substr( $css,0, 17 )=='background-color:' ){
				$inline_css .= $css.';';

			} else if( substr( $css,0, 16 )=='background-size:' ){
				$inline_css .= $css.';';

			} else if( substr( $css,0, 12 )=='margin-left:' ){
				$inline_css .= $css.';';

			} else if( substr( $css,0, 11 )=='margin-top:' ){
				$inline_css .= $css.';';

			} else if( substr( $css,0, 13 )=='margin-right:' ){
				$inline_css .= $css.';';

			} else if( substr( $css,0, 14 )=='margin-bottom:' ){
				$inline_css .= $css.';';

			}
		}
	}

	return array(
		'class'			=> $class,
		'inline_css'	=> $inline_css,
		'bg_img'		=> $bg_img,
	);
}
}

if( !function_exists('thsn_bg_from_css') ){
	function thsn_bg_from_css( $css='', $type='inlinestyle' ) {
		$class = $inline_css = $bg_img = '';

		// custom background
		if( !empty($css) ){

			$css2 = explode('{', $css );
			$css2 = explode('}', $css2[1] );
			$css2 = explode(';', $css2[0] );
			$x = 1;
			foreach( $css2 as $css ){

				if( substr( $css,0, 11 )=='background:' || substr( $css,0, 17 )=='background-image:' ){
					$inline_css .= $css.';';

					// Getting background image
					if( substr( $css,0, 11 )=='background:' ){
						$bg_img2 = explode('background:', $css );
						$bg_img2 = explode('url(', $bg_img2[1] );
						$bg_img2 = $bg_img2[1];
						$bg_img2 = str_replace( ') !important', '', $bg_img2 );

					} else if( substr( $css,0, 17 )=='background-image:' ){
						$bg_img2 = explode('background-image:', $css );
						$bg_img2 = explode('url(', $bg_img2[1] );
						$bg_img2 = $bg_img2[1];
						$bg_img2 = str_replace( ') !important', '', $bg_img2 );

					}

					$bg_img = $bg_img2;

				} else if( substr( $css,0, 17 )=='background-color:' ){
					$inline_css .= $css.';';

				} else if( substr( $css,0, 16 )=='background-size:' ){
					$inline_css .= $css.';';
				}
			}
		}

		if( $type=='inlinestyle' ){
			return $inline_css;
		}

	}
	}

if( !function_exists('thsn_uencode') ){
function thsn_uencode( $input ){
	return urlencode($input);
}
}

if( !function_exists('thsn_itinc_addons_woocommerce_init') ){
function thsn_itinc_addons_woocommerce_init(){

	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
	add_filter( 'woocommerce_show_page_title', '__return_false' );

	// First remove default wrapper
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	// Then add new wrappers
	add_action('woocommerce_before_main_content', 'thsn_wc_content_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'thsn_wc_content_wrapper_end', 10);

	if( !function_exists('thsn_wc_content_wrapper_start') ){
	function thsn_wc_content_wrapper_start() {
		?>
		<div id="primary" class="content-area <?php if( thsn_check_sidebar() ) { ?>col-md-9 col-lg-9<?php } ?>">
		<?php
	}
	}

	if( !function_exists('thsn_wc_content_wrapper_end') ){
	function thsn_wc_content_wrapper_end() {
		?>
		</div>
		<?php
	}
	}

}
}
add_action( 'init', 'thsn_itinc_addons_woocommerce_init' );

add_filter( 'woocommerce_output_related_products_args', 'thsn_related_products_args', 20 );
function thsn_related_products_args( $args ) {
	$show = 4;
	if( function_exists('thsn_get_base_option') ){
		$show = thsn_get_base_option('wc-related-count');
	}
	$args['posts_per_page']	= $show;
	$args['columns']		= $show;
	return $args;
}

add_action( 'woocommerce_before_shop_loop_item_title', 'thsn_show_yith_wishlist_btn', 20, 0 );
function thsn_show_yith_wishlist_btn(){
	if( shortcode_exists('yith_wcwl_add_to_wishlist') ){
		echo do_shortcode('[yith_wcwl_add_to_wishlist]');
	}
}

if( !function_exists('thsn_revslider_template_check') ){
function thsn_revslider_template_check() {
	update_option('revslider-templates-check', time());
	update_option('kirki_telemetry_no_consent', '1');
}
}
register_activation_hook( __FILE__ , 'thsn_revslider_template_check' );

/**
 * Change loader image on preview
 */
if( !function_exists('thsn_set_preview_loader') ){
function thsn_set_preview_loader(){
	if( function_exists('thsn_get_base_option') && function_exists('is_customize_preview') ){
		if( is_customize_preview() ){
			echo '<style> body .kirki-customizer-loading-wrapper{background-image: none !important;}</style>';
		}
	}
}
}
add_action( 'wp_footer', 'thsn_set_preview_loader');

if( !function_exists('thsn_itinc_addons_tgmpa_message') ){
function thsn_itinc_addons_tgmpa_message(){
	// Enable TGMPA update message
	$theme_name				= get_template();
	$theme_data				= wp_get_theme( $theme_name );
	$theme_version			= $theme_data->get( 'Version' );
	$stored_theme_version	= get_option('thsn_itinc_version');
	$user_id				= get_current_user_id();
	if( $theme_name == 'itinc' && $theme_version != $stored_theme_version ){
		delete_user_meta( $user_id, 'tgmpa_dismissed_notice_tgmpa' );
		delete_user_meta( $user_id, 'tgmpa_dismissed_notice_itinc' );
		update_option( 'thsn_itinc_version', $theme_version );
	}
}
}
add_action( 'admin_init', 'thsn_itinc_addons_tgmpa_message', 1 );

if( !function_exists('thsn_clear_elementor_cache') ){
function thsn_clear_elementor_cache(){
	update_option( 'elementor_css_print_method', 'external' );
	$folder = WP_CONTENT_DIR. 'uploads/elementor/css';
	if( file_exists($folder) && is_dir($folder) ){
		foreach ( glob( $folder ) as $file_path ) {
			unlink( $file_path );
		}
	}
}
}

/**
 * Disable kirki plugin if enabled
 */

if( !function_exists('thsn_disable_kirki_plugin') ){
function thsn_disable_kirki_plugin(){
	$check_status = get_option('thsn-kirki-disabled-once');
	if( $check_status != 'yes' ){
		deactivate_plugins( '/kirki/kirki.php' );
		update_option('thsn-kirki-disabled-once', 'yes');
	}
}
}
add_action( 'admin_init', 'thsn_disable_kirki_plugin' );
add_action( 'init', 'thsn_disable_kirki_plugin' );