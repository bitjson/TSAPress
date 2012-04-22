<?php

/* TODO: theme event system- including custom fields, map location, categorize -> regions, google calendar sync?
*	
*	nice start? : http://www.noeltock.com/web-design/wordpress/custom-post-types-events-pt1/ 
*	and: http://www.noeltock.com/web-design/wordpress/how-to-custom-post-types-for-events-pt-2/
*/



/*

Event System:

(summary)


Posts and Pages can be marked as "events"

Once marked as events, event options are enabled

Event Options include:




-Location	
	-Name
	-Google Map? Link?

-Upload
	-Name (Default: Registration Packet)
	-Upload field 

Possible?:
-Dress
-Contact
-Registration Link







*/


add_filter('cmb_meta_box_url', 'tsapress_cmb_meta_box_url');
/* redefines the URL to cmb assets */
function tsapress_cmb_meta_box_url($url) {	 
	$url = get_bloginfo('template_directory') . '/admin/';
	 return $url;
}


add_action( 'cmb_render_description', 'render_description', 10, 2 );
function render_description( $field, $meta ) {
    echo '<p class="cmb_metabox_description">', $field['desc'], '</p>';
}

add_filter( 'cmb_meta_boxes', 'cmb_event_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_event_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cmb_';



// Display "Event Options Disabled" message on Default Page template


$meta_boxes[] = array(
		'id'         => 'make_event_metabox',
		'title'      => 'Event Details',
		'pages'      => array('page'), // Post type
		'show_on'    => array( 'key' => 'page-template', 'value' => 'default' ), // only display for default page-template
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
								array(
									'name' => 'Event Options',
									'desc' => 'The Event template is not enabled. To add event details to this page, please select the "Event Page" template in the Page Attributes box above.',
									'id'   => $prefix . 'test_title',
									'type' => 'description',
									)
							)
					);


// Display Event Options on Event Page template


$meta_boxes[] = array(
		'id'         => 'event_metabox',
		'title'      => 'Event Details',
		'pages'      => array('page'), // Post type
		'show_on'    => array( 'key' => 'page-template', 'value' => 'page-event.php' ), // only display for event page-template
		'page-template' => 'event',
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
								
								array(
									'name'    => 'Event Type',
									'desc'    => 'Date/time range type for the event. <br />
									  <ul>
									  <li> <strong>Date/time</strong> events occur between certain times (like a Rally, Expo, or Conference).</li>
									  <li> <strong>Date</strong> events occur all day for each day within the date range (like Advisor Appreciation Week).</li>
									  <li> <strong>Deadline</strong> events occur at a specific moment in time (like submission deadlines for competitive events).</li>
									  <li> Date/times for <strong>TBD</strong> events are too be determined.</li>
									  </ul> ',
									'id'      => $prefix . 'datetime_range',
									'type'    => 'radio_inline',
									'options' => array(
										array( 'name' => 'Date/time', 'value' => 'datetime', ),
										array( 'name' => 'Date', 'value' => 'date', ),
										array( 'name' => 'Deadline', 'value' => 'deadline', ),
										array( 'name' => 'TBD', 'value' => 'tbd', )
													)
								),
								
								
								
								// Date/time event options
								
								array(
									'name' => 'Begins',
									'desc' => 'Date and time at which the event begins.',
									'id'   => $prefix . 'datetime_begin',
									'type' => 'text_datetime_timestamp',
								),
								
								array(
									'name' => 'Ends',
									'desc' => 'Date and time at which the event ends.',
									'id'   => $prefix . 'datetime_end',
									'type' => 'text_datetime_timestamp',
								),
								
								
								
								// Date event options
								
								array(
									'name' => 'Begins',
									'desc' => 'Date at which the event begins.',
									'id'   => $prefix . 'date_begin',
									'type' => 'text_date_timestamp',
								),
								
								array(
									'name' => 'Ends',
									'desc' => 'Date at which the event ends.',
									'id'   => $prefix . 'date_end',
									'type' => 'text_date_timestamp',
								),


								
								
								// Deadline event options
								
								array(
									'name' => 'Deadline',
									'desc' => 'Date and time at which the Deadline is set.',
									'id'   => $prefix . 'deadline_datetime',
									'type' => 'text_datetime_timestamp',
								),
								
								
								// Continue options for all event types
								
								
								array(
									'name' => 'Location',
									'desc' => 'Name of the event location. To be displayed as a link to a Google Map',
									'id'   => $prefix . 'test_text',
									'type' => 'text',
								)
								
								
								
								
							)
					);


/*Begin example Custom Meta Box*/
/*

	$meta_boxes[] = array(
		'id'         => 'test_metabox',
		'title'      => 'Sample Elements Metabox',
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Test Text',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_text',
				'type' => 'text',
			),
			array(
				'name' => 'Test Text Small',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textsmall',
				'type' => 'text_small',
			),
			array(
				'name' => 'Test Text Medium',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textmedium',
				'type' => 'text_medium',
			),
			array(
				'name' => 'Test Date Picker',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textdate',
				'type' => 'text_date',
			),
			array(
				'name' => 'Test Date Picker (UNIX timestamp)',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textdate_timestamp',
				'type' => 'text_date_timestamp',
			),
			array(
				'name' => 'Test Date/Time Picker Combo (UNIX timestamp)',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_datetime_timestamp',
				'type' => 'text_datetime_timestamp',
			),
			array(
	            'name' => 'Test Time',
	            'desc' => 'field description (optional)',
	            'id'   => $prefix . 'test_time',
	            'type' => 'text_time',
	        ),
			array(
				'name' => 'Test Money',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textmoney',
				'type' => 'text_money',
			),
			array(
	            'name' => 'Test Color Picker',
	            'desc' => 'field description (optional)',
	            'id'   => $prefix . 'test_colorpicker',
	            'type' => 'colorpicker',
				'std'  => '#ffffff'
	        ),
			array(
				'name' => 'Test Text Area',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textarea',
				'type' => 'textarea',
			),
			array(
				'name' => 'Test Text Area Small',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textareasmall',
				'type' => 'textarea_small',
			),
			array(
				'name' => 'Test Text Area Code',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textarea_code',
				'type' => 'textarea_code',
			),
			array(
				'name' => 'Test Title Weeeee',
				'desc' => 'This is a title description',
				'id'   => $prefix . 'test_title',
				'type' => 'title',
			),
			array(
				'name'    => 'Test Select',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'test_select',
				'type'    => 'select',
				'options' => array(
					array( 'name' => 'Option One', 'value' => 'standard', ),
					array( 'name' => 'Option Two', 'value' => 'custom', ),
					array( 'name' => 'Option Three', 'value' => 'none', ),
				),
			),
			array(
				'name'    => 'Test Radio inline',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'test_radio_inline',
				'type'    => 'radio_inline',
				'options' => array(
					array( 'name' => 'Option One', 'value' => 'standard', ),
					array( 'name' => 'Option Two', 'value' => 'custom', ),
					array( 'name' => 'Option Three', 'value' => 'none', ),
				),
			),
			array(
				'name'    => 'Test Radio',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'test_radio',
				'type'    => 'radio',
				'options' => array(
					array( 'name' => 'Option One', 'value' => 'standard', ),
					array( 'name' => 'Option Two', 'value' => 'custom', ),
					array( 'name' => 'Option Three', 'value' => 'none', ),
				),
			),
			array(
				'name'     => 'Test Taxonomy Radio',
				'desc'     => 'Description Goes Here',
				'id'       => $prefix . 'text_taxonomy_radio',
				'type'     => 'taxonomy_radio',
				'taxonomy' => '', // Taxonomy Slug
			),
			array(
				'name'     => 'Test Taxonomy Select',
				'desc'     => 'Description Goes Here',
				'id'       => $prefix . 'text_taxonomy_select',
				'type'     => 'taxonomy_select',
				'taxonomy' => '', // Taxonomy Slug
			),
			array(
				'name' => 'Test Checkbox',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_checkbox',
				'type' => 'checkbox',
			),
			array(
				'name'    => 'Test Multi Checkbox',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'test_multicheckbox',
				'type'    => 'multicheck',
				'options' => array(
					'check1' => 'Check One',
					'check2' => 'Check Two',
					'check3' => 'Check Three',
				),
			),
			array(
				'name'    => 'Test wysiwyg',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'test_wysiwyg',
				'type'    => 'wysiwyg',
				'options' => array(	'textarea_rows' => 5, ),
			),
			array(
				'name' => 'Test Image',
				'desc' => 'Upload an image or enter an URL.',
				'id'   => $prefix . 'test_image',
				'type' => 'file',
			),
		),
	);

	$meta_boxes[] = array(
		'id'         => 'about_page_metabox',
		'title'      => 'About Page Metabox',
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'show_on'    => array( 'key' => 'id', 'value' => array( 2, ), ), // Specific post IDs to display this metabox
		'fields' => array(
			array(
				'name' => 'Test Text',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_text',
				'type' => 'text',
			),
		)
	);

	// Add other metaboxes as needed

/*End example Custom Meta Box*/

	return $meta_boxes;
}



/*




// TODO: on post or page update, put event in custom post type for sorting and other use?
	

	$event_labels = array (
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
				  'parent' => 'Parent Event' );
	

if ( function_exists('register_post_type') ) {
	register_post_type('tsapress_events',
		 array(	'label' => 'Events',
			 'description' => '',
			 'public' => true,
			 'show_ui' => true,
			 'can_export' => true,
			 'show_in_menu' => true,
			 'capability_type' => 'post',
			 'rewrite' => array('slug' => 'events'),
			 'query_var' => true,
			 'hierarchical' => false,
			 'has_archive' => true,
			 'menu_position' => 8,
			 'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes',),
			 'taxonomies' => array('category','post_tag',),
			 'labels' => $event_labels,
			) 
		);
}


*/

