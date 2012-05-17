<?php

function tsapress_debug($object){
	die(print_r($object));
}

/* Set global var $tz_string with correct string from Wordpress settings */
global $tz_string;
if(get_option('timezone_string') != ""){ $tz_string = get_option('timezone_string'); }
	else {
		$timezones = array( 
		        '-12'=>'Pacific/Kwajalein', 
		        '-11'=>'Pacific/Samoa', 
		        '-10'=>'Pacific/Honolulu', 
		        '-9'=>'America/Juneau', 
		        '-8'=>'America/Los_Angeles', 
		        '-7'=>'America/Denver', 
		        '-6'=>'America/Mexico_City', 
		        '-5'=>'America/New_York', 
		        '-4'=>'America/Caracas', 
		        '-3.5'=>'America/St_Johns', 
		        '-3'=>'America/Argentina/Buenos_Aires', 
		        '-2'=>'Etc/GMT-2',// depreciated, but no cities exist here (i.e. this will never be used…) 
		        '-1'=>'Atlantic/Azores', 
		        '0'=>'Europe/London', 
		        '1'=>'Europe/Paris', 
		        '2'=>'Europe/Helsinki', 
		        '3'=>'Europe/Moscow', 
		        '3.5'=>'Asia/Tehran', 
		        '4'=>'Asia/Baku', 
		        '4.5'=>'Asia/Kabul', 
		        '5'=>'Asia/Karachi', 
		        '5.5'=>'Asia/Calcutta', 
		        '6'=>'Asia/Colombo', 
		        '7'=>'Asia/Bangkok', 
		        '8'=>'Asia/Singapore', 
		        '9'=>'Asia/Tokyo', 
		        '9.5'=>'Australia/Darwin', 
		        '10'=>'Pacific/Guam', 
		        '11'=>'Asia/Magadan', 
		        '12'=>'Asia/Kamchatka' 
		    );
		$tz_string = $timezones[get_option('gmt_offset')];
	}
date_default_timezone_set($tz_string); //Set running timezone to Wordpress timezone settings
	

function tsapress_get_total_posts_in_query(){
	
	global $wp_query;
	
	$total_posts = $wp_query->found_posts;
	return $total_posts;
}

function tsapress_get_current_page_number(){
	$current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
	return $current_page;
}

function tsapress_get_posts_per_page(){
	$per_page = get_query_var('posts_per_page');
	return $per_page;
}

function tsapress_get_number_of_last_page_in_query(){
	$last_page = ceil(tsapress_get_total_posts_in_query() / tsapress_get_posts_per_page());
	return $last_page;
}

function tsapress_is_paginated(){
	$is_paginated = (tsapress_get_total_posts_in_query() > tsapress_get_posts_per_page());
	return $is_paginated;
}


function the_post_thumbnail_meta($meta) {
/*acceptable meta values: 'title', 'caption', 'description', 'alt' */
 	echo get_post_thumbnail_meta($meta);
}

function get_post_thumbnail_meta($meta) {
/*acceptable meta values: 'title', 'caption', 'description', 'alt' */
	
	  global $post;
	
	  $thumbnail_id = get_post_thumbnail_id($post->ID);
	
	  $args = array(
		'post_type' => 'attachment',
		'post_status' => null,
		'post_parent' => $post->ID,
		'include'  => $thumbnail_id
		); 
	
	   $thumbnail_image = get_posts($args);
	
	   if ($thumbnail_image && isset($thumbnail_image[0])) {
	    
	    if ($meta == "title"){
	     return $thumbnail_image[0]->post_title;		//show thumbnail title
		} elseif ($meta == "caption") {
	     return $thumbnail_image[0]->post_excerpt;  	//show thumbnail caption
		} elseif ($meta == "description") {
	     return $thumbnail_image[0]->post_content;    //show thumbnail description
		} elseif ($meta == "alt") {
	     $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
	     if(count($alt)) return $alt;			        //show thumbnail alt field
	  } else {
	  	die("<h1>arguement passed to get_post_thumbnail_meta() is invalid. Only accepts 'title', 'caption', 'description', or 'alt'.</h1>");
	  }
	}
}


/* reregister new wp_caption shortcode to make captions use HTML5*/
function tsapress_img_caption_shortcode_filter($val, $attr, $content = null) {
  extract(shortcode_atts(array(
  	'id'	=> '',
  	'align'	=> '',
  	'width'	=> '',
  	'caption' => ''
  ), $attr));

  if ( 1 > (int) $width || empty($caption) ) {return $val;}

  return '<figure id="' . $id . '" class="wp-caption ' . esc_attr($align) . '" style="width: ' . $width . 'px;">'
  . do_shortcode( $content ) . '<figcaption>' . $caption . '</figcaption></figure>';
}

add_filter('img_caption_shortcode', 'tsapress_img_caption_shortcode_filter', 10, 3);


/* Nice Search from Roots (the HTML5 Wordpress Boilterplate Theme)*/
function roots_nice_search_redirect() {
    if (is_search() && strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === false && strpos($_SERVER['REQUEST_URI'], '/search/') === false) {
        wp_redirect(home_url('/search/' . str_replace( array(' ', '%20'), array('+', '+'), get_query_var( 's' ))));
        exit();
    }
}
add_action('template_redirect', 'roots_nice_search_redirect');

/*wrap search term in <mark> tags (Wordpress does not natively support) */ //TODO: not working?
 
function highlighted_title() {
 global $post;
 $uri = explode("/",$_SERVER["REQUEST_URI"]);
 $the_title = get_the_title();
 
$do_not_highlight = array( "a", "A", "is", "Is", "the", "The", "and", "And" );
 
 $search_term = explode("+",$uri[2]);
 
        foreach ($search_term as $search_t) {
            if(preg_match_all("/$search_t+/i", $the_title, $matches)){ //must have found matches
	            foreach ($matches as $match) {
	                if (!in_array($match[0],$do_not_highlight)) {
	                    $the_title = str_replace($match[0], "[m]" . $match[0] . "[mm]", $the_title);
	                }
	            }
            }
        }
 
        $find = array("[m]","[mm]");
        $replace = array("<mark>","</mark>");
        $highlighted_title = str_replace($find,$replace,$the_title);            
 
echo $highlighted_title;
 
}
 
// WP doesn't natively search the excerpt (only the the_content();), but chances good that the excerpt includes keywords. 
function highlighted_excerpt($the_excerpt) {
 
$uri = explode("/",$_SERVER["REQUEST_URI"]);
 
$do_not_highlight = array( "a", "A", "is", "Is", "the", "The", "and", "And" );
 
$search_term = explode("+",$uri[2]);
 
        foreach ($search_term as $search_t) {
            if(preg_match_all("/$search_t+/i", $the_excerpt, $matches)){ //must have found matches
	            foreach ($matches as $match) {
	                if (!in_array($match[0],$do_not_highlight)) {
	                $the_excerpt = str_replace($match[0], "[m]" . $match[0] . "[mm]", $the_excerpt);
	                }
	            }
            }
        }
 
        $find = array("[m]","[mm]");
        $replace = array("<mark>","</mark>");
        $highlighted_excerpt = str_replace($find,$replace,$the_excerpt);    
 
echo "<p>" . $highlighted_excerpt . "</p>";
 
}

/*cleans off search query by translating "+" to " "*/
function tsapress_clean_search_query($query) {
	echo strtr($query, '+', ' ');
}

add_action('the_search_query', 'tsapress_clean_search_query');
