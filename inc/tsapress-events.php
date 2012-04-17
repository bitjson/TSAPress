<?php

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
