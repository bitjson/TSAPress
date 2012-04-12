<?php

/* TODO: built-in contact form, like: http://www.catswhocode.com/blog/how-to-create-a-built-in-contact-form-for-your-wordpress-theme
	
	can we put in a simple captcha system?
	
	Form should pop out and turn into modal when the user starts filling it out


*/


/* TODO: theme event system- including custom fields, map location, categorize -> regions, google calendar sync?
	
	nice start: http://www.noeltock.com/web-design/wordpress/custom-post-types-events-pt1/
	and: http://www.noeltock.com/web-design/wordpress/how-to-custom-post-types-for-events-pt-2/
	

if ( function_exists('register_post_type') ) {
	register_post_type('tsapress-events',
		 array(	'label' => 'Events',
			 'description' => '',
			 'public' => true,
			 'show_ui' => true,
			 'show_in_menu' => true,
			 'capability_type' => 'post',
			 'hierarchical' => false,
			 'rewrite' => array('slug' => ''),
			 'query_var' => true,
			 'has_archive' => true,
			 'menu_position' => 8,
			 'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes',),
			 'taxonomies' => array('category','post_tag',),
			 'labels' => array (
				  'name' => 'Events',
				  'singular_name' => 'Event',
				  'menu_name' => 'Events',
				  'add_new' => 'Add New',
				  'add_new_item' => 'Add New Event',
				  'edit' => 'Edit',
				  'edit_item' => 'Edit Event',
				  'new_item' => 'New Event',
				  'view' => 'View Event',
				  'view_item' => 'View Event',
				  'search_items' => 'Search Events',
				  'not_found' => 'No Events Found',
				  'not_found_in_trash' => 'No Events found in Trash',
				  'parent' => 'Parent Event',
				),
			) 
		);
}

*/

function the_post_thumbnail_meta($meta) {
/*acceptable meta values: 'title', 'caption', 'description', 'alt' */
 	echo get_post_thumbnail_meta($meta);
}

function get_post_thumbnail_meta($meta) {
/*acceptable meta values: 'title', 'caption', 'description', 'alt' */

  global $post;

  $thumb_id = get_post_thumbnail_id($post->id);

  $args = array(
	'post_type' => 'attachment',
	'post_status' => null,
	'post_parent' => $post->ID,
	'include'  => $thumb_id
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
		'name' => 'Left Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => '</h1>',
	));
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

add_filter('img_caption_shortcode', 'tsapress_img_caption_shortcode_filter',10,3);


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
            preg_match_all("/$search_t+/i", $the_title, $matches);
            foreach ($matches as $match) {
                if (!in_array($match[0],$do_not_highlight)) {
                    $the_title = str_replace($match[0], "[m]" . $match[0] . "[mm]", $the_title);
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
            preg_match_all("/$search_t+/i", $the_excerpt, $matches);
            foreach ($matches as $match) {
                if (!in_array($match[0],$do_not_highlight)) {
                $the_excerpt = str_replace($match[0], "[m]" . $match[0] . "[mm]", $the_excerpt);
                }
            }
        }
 
        $find = array("[m]","[mm]");
        $replace = array("<mark>","</mark>");
        $highlighted_excerpt = str_replace($find,$replace,$the_excerpt);    
 
echo "<p>" . $highlighted_excerpt . "</p>";
 
}

/*cleans off search query by translating "+" to " "*/
function tsapress_clean_search_query() {
	echo strtr(get_search_query(), '+', ' ');
}
?>