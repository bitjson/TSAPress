<?php

$partners_disabled = true;

if ($partners_disabled != true):
//create partner post type

add_action( 'init', 'register_cpt_partner' );

function register_cpt_partner() {

    $labels = array( 
        'name' => _x( 'Partners', 'partner' ),
        'singular_name' => _x( 'Partner', 'partner' ),
        'add_new' => _x( 'Add New', 'partner' ),
        'add_new_item' => _x( 'Add New Partner', 'partner' ),
        'edit_item' => _x( 'Edit Partner', 'partner' ),
        'new_item' => _x( 'New Partner', 'partner' ),
        'view_item' => _x( 'View Partner', 'partner' ),
        'search_items' => _x( 'Search Partners', 'partner' ),
        'not_found' => _x( 'No partners found', 'partner' ),
        'not_found_in_trash' => _x( 'No partners found in Trash', 'partner' ),
        'parent_item_colon' => _x( 'Parent Partner:', 'partner' ),
        'menu_name' => _x( 'Partners', 'partner' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'description' => 'Partners are used to populate the Partners area at the very bottom of the website. Each partner contains a name, an image (logo), and a link.',
        'supports' => array( 'title', /*'thumbnail', 'custom-fields', */ 'revisions' ),
        
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        
        'menu_icon' => home_url().'/img/admin/partners.png',
        'show_in_nav_menus' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'partner', $args );
}



/**
 * Include and setup partners custom metabox.
 */

add_filter( 'cmb_meta_boxes', 'cmb_partners_metaboxes' );


function cmb_partners_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_partner_';

	$meta_boxes[] = array(
		'id'         => 'partner_metabox',
		'title'      => 'Partner Details',
		'pages'      => array( 'partner' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Desitination URL',
				'desc' => 'When the Partner&rsquo;s logo is clicked, the user is directed to this URL. It is recommended that a &ldquo;Partners&rdquo; section be developed explaining the details about each partnership, in which case a url like &ldquo;'.home_url().'/partners/this-partner&rdquo; would be used, though external URLs may be used as well.',
				'id'   => $prefix . 'url',
				'type' => 'text',
			),
			array(
				'name' => 'Partner Logo',
				'desc' => 'Upload an image or enter an URL.',
				'id'   => $prefix . 'logo',
				'type' => 'file',
			),
		),
	);

	return $meta_boxes;
}

endif;
