<?php

require_once('inc/tsapress-cleanup.php'); //cleans urls, wp_head, hides wp_content : derived from Roots HTML5 Boilerplate WordPress Theme
require_once('inc/tsapress-utils.php'); //functions that should have come with WordPress
require_once('inc/tsapress-events.php'); //TODO: event post type
require_once('inc/tsapress-contact.php'); //TODO: built-in contact form
require_once('inc/tsapress-widgets.php');


//TODO: init function, runnable from theme options or something - set the category base to "regions", and wp-uploads to "assets"




if ( function_exists( 'register_nav_menu' ) ) {
	
	register_nav_menus(
    array(
      'primary-nav' => 'Primary Navigation',
      'events-nav' => 'Featured Events'
    )
  );
	
}

if ( function_exists( 'add_theme_support' ) ) { 
  add_theme_support( 'post-thumbnails' ); 
  set_post_thumbnail_size( 690, 255, true ); // 690 pixels wide by 255 pixels tall, crop mode
}

function register_my_menus() {
  
}
add_action( 'init', 'register_my_menus' );


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
   
   /* reregister jquery to use Google CDN */
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    wp_register_script( 'google-maps', 'http://maps.googleapis.com/maps/api/js?key=AIzaSyAnRwiaJO9vIJUSkHQoxqQPGhF4OMCTl68&sensor=true');    

    wp_enqueue_script( 'jquery' );
    if(is_home() || is_category()) wp_enqueue_script( 'google-maps' );
    
}    
 
add_action('wp_enqueue_scripts', 'tsapress_load_scripts');

