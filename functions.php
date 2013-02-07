<?php

require_once('inc/php-5_2-fixes.php'); //Extends PHP 5.2 functionality to lower TSAPress PHP version requirements (from 5.3 for DateTime class) 

@ini_set( 'upload_max_size' , '10M' );
@ini_set( 'post_max_size', '10M');
@ini_set( 'max_execution_time', '300' );

require_once('inc/tsapress-cleanup.php'); //cleans urls, wp_head, hides wp_content : derived from Roots HTML5 Boilerplate WordPress Theme
require_once('inc/tsapress-utils.php'); //functions that should have come with WordPress
require_once('inc/tsapress-events.php');
require_once('inc/tsapress-partners.php'); //TODO: partner post type
require_once('inc/tsapress-contact.php');
require_once('inc/tsapress-widgets.php');
require_once('inc/tsapress-shortcodes.php');



//TODO: init function, runnable from theme options or something - set the category base to "regions"


//remove "Links" admin menu
function remove_menus () {
global $menu;
	$restricted = array(__('Links'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_menus');


//register the primary nav menu
if ( function_exists( 'register_nav_menu' ) ) {
	
	register_nav_menus(
    array(
      'primary-nav' => 'Primary Navigation',
    )
  );
	
}

//add thumbnail size for featured images
if ( function_exists( 'add_theme_support' ) ) { 
  add_theme_support( 'post-thumbnails' ); 
  set_post_thumbnail_size( 690, 255, true ); // 690 pixels wide by 255 pixels tall, crop mode
}



//register widget area below menus
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Additional Left Menus',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => '</h1>',
	));
}

/* Load the Options Panel */
if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_bloginfo('template_directory') . '/admin/' );
	require_once dirname( __FILE__ ) . '/admin/options-framework.php';
}


function tsapress_load_scripts() {
   
    // Register Modernizr
    wp_register_script( 'modernizr', get_template_directory_uri() . '/js/libs/modernizr.min.01276.js', false ); //$in_footer = false; place script in wp_head
    wp_enqueue_script( 'modernizr' );  
   
   /* reregister jquery to use Google CDN */
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', true); //$in_footer = true; place script in wp_footer

    wp_register_script( 'google-maps', 'http://maps.googleapis.com/maps/api/js?key=AIzaSyAnRwiaJO9vIJUSkHQoxqQPGhF4OMCTl68&sensor=true', true); //$in_footer = true; place script in wp_footer
    wp_enqueue_script( 'jquery' );
    if(is_home() || is_category()) wp_enqueue_script( 'google-maps' );
    
}    
 
add_action('wp_enqueue_scripts', 'tsapress_load_scripts');


add_action( 'init', 'tsapress_overwrite_category_core_taxonomy' );
/* 	Re-label Wordpress core Categories taxonomy to clarify that Categories = Regions in the backend 
*
*	Derived from create_initial_taxonomies(); in wp-includes/taxonomy.php
*
*	+ 'labels' key
*	+ also use for 'tsapress_events' custom post type
*/
function tsapress_overwrite_category_core_taxonomy()
{
	global $wp_rewrite;

	$region_labels = array (
					  'name' => 'Regions',
					  'singular_name' => 'Region',
					  'menu_name' => 'Regions',
					  'add_new' => 'Add New',
					  'add_new_item' => 'Add New Region',
					  'edit' => 'Edit',
					  'edit_item' => 'Edit Region',
					  'new_item' => 'New Region',
					  'view' => 'View Region',
					  'view_item' => 'View Region',
					  'search_items' => 'Search Regions',
					  'not_found' => 'No Regions Found',
					  'not_found_in_trash' => 'No Regions found in Trash',
					  'parent' => 'Parent Region',
					  'update_item' => 'Update Region',
					  'new_item_name' => 'New Region Name',
					  'parent_item' => 'Parent Region',
					  'parent_item_colon' => 'Parent Region:' );
	
	register_taxonomy( 'category', array('post', 'tsapress_events'), array(
			'hierarchical' => true,
			'query_var' => 'category_name',
			'rewrite' => did_action( 'init' ) ? array(
						'hierarchical' => true,
						'slug' => get_option('category_base') ? get_option('category_base') : 'category',
						'with_front' => ( get_option('category_base') && ! $wp_rewrite->using_index_permalinks() ) ? false : true ) : false,
			'public' => true,
			'show_ui' => true,
			'_builtin' => true,
			'labels' => $region_labels )
			 );		 
			 
			
	//		 global $wp_taxonomies;
	//		 tsapress_debug($wp_taxonomies['category']);
			 
}


add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once('admin/metaboxes.php');
		
}

