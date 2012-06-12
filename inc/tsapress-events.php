<?php

/*

TODO?: location field: Like youtube "video location" field- on select, display dropdown map with result of geolocation call using current input

TODO?: Cache events database query

*/

function tsapress_is_event($post_id){
	if (get_post_meta($post_id, '_tsapress_event_is_event', true) == "on") return true;
	return false;
}

function tsapress_display_eventinfo($post_id){ ?>
	<aside id="event-info">
	<?php 
		//Event Summary
		$event_summary = get_post_meta($post_id, '_tsapress_event_summary', true);
		if ($event_summary != "") echo '<p class="summary">'. $event_summary .'</p>';
		
		//Event Date/time
		echo get_tsapress_event_datetime_string($post_id);
	
		//Event Location
		$event_location = get_post_meta($post_id, '_tsapress_event_location', true);
		if ($event_location != "") {	
			$event_location_query = get_post_meta($post_id, '_tsapress_event_google_maps_query', true);
			if ($event_location_query == "") echo '<span class="location">' . $event_location . '</span>';
			else echo '<a href="http://maps.google.com/maps?q=' . urlencode($event_location_query) . '" class="location" target="_blank">' . $event_location . '</a>';
		}
		
		echo '<p class="clear"></p>';
	?>
	</aside>
<?php 
}

function tsapress_get_major_events(){
	$args = array(
	    'numberposts'     => 99,
	    'orderby'         => 'menu_order',	//order by page order (in pages listing)
	    'order'           => 'DESC',		//descending
	    'meta_key'        => '_tsapress_event_major_event',
	    'meta_value'      => 'on',			//major event option checked
	    'post_type'       => 'any',			//pages or posts
	    'post_status'     => 'publish');	//published only
	
	return get_posts( $args );
}


function is_same_day($begin, $end){ if ($begin->format('Y-m-d') == $end->format('Y-m-d')) return true;
	return false; }

function is_same_month($begin, $end){ if ($begin->format('Y-m') == $end->format('Y-m')) return true;
	return false; }

function is_same_year($begin, $end){ if ($begin->format('Y') == $end->format('Y')) return true;
	return false; }

function Y_m_d($DateTime){ return $DateTime->format('Y-m-d'); }


/* outputs clean HTML5 <time> or <time> range */
function get_tsapress_pretty_time($length, $type, $begin_timestamp, $end_timestamp = false, $include_time = false){

	// create DateTimeZone object
	global $tz_string;
	$dtzone = new DateTimeZone($tz_string);
	 
	// create a DateTime object
	$begin = new DateTime_52();
	$end = new DateTime_52();
	
	// set it to the timestamp (PHP >= 5.3.0) normally requires PHP 5.3, but using DateTime_52 extended fix, from inc/php-5_2-fixes.php
	$begin->setTimestamp($begin_timestamp);
	if($end_timestamp != false) $end->setTimestamp($end_timestamp);
	 
	// convert to timezone
	$begin->setTimeZone($dtzone);
	$end->setTimeZone($dtzone);


	$out = '<time class="date" datetime="';

	if ($length == 'short')
	{
	
		if ($type == "deadline") $out .= $begin->format('Y-m-d\TH:i P') . '">' . $begin->format('n/d');
		else {	//type is datetime or date
			if (is_same_day($begin, $end)) 		 $out .= Y_m_d($begin) . '">' . $begin->format('n/d');
			else 								 $out .= Y_m_d($begin) . '/' . Y_m_d($end) . '">' . $begin->format('n/d') . '&ndash;' . $end->format('n/d');
		}
	
	} 
	
	else { //length is 'long' (default)
		
		if ($type == "deadline") 				 $out .= $begin->format('Y-m-d\TH:i P') . '">' . $begin->format('M jS, Y g:i A T');
		
		elseif ($type == "datetime")
		{
			
			if (is_same_day($begin, $end))		 $out .= $begin->format('Y-m-d\TH:i') . '">' . $begin->format('M jS, Y') . '<span>' . $begin->format('g:ia') . '-' . $end->format('g:ia') . '</span>';
			elseif (is_same_month($begin, $end)) $out .= $begin->format('Y-m-d\TH:i') . '/' . $end->format('Y-m-d\TH:i') . '">' . $begin->format('M jS') . '&ndash;' . $end->format('jS, Y') . '<span>' . $begin->format('g:ia') . '-' . $end->format('g:ia') . '</span>';
			elseif (is_same_year($begin, $end))  $out .= $begin->format('Y-m-d\TH:i') . '/' . $end->format('Y-m-d\TH:i') . '">' . $begin->format('M jS') . '&ndash;' . $end->format('M jS, Y') . '<span>' . $begin->format('g:ia') . '-' . $end->format('g:ia') . '</span>';
			
			
		} else { //type is date
			if (is_same_day($begin, $end)) 		 $out .= Y_m_d($begin) . '">' . $begin->format('M jS, Y');
			elseif (is_same_month($begin, $end)) $out .= Y_m_d($begin) . '/' . Y_m_d($end) . '">' . $begin->format('M jS') . '&ndash;' . $end->format('jS, Y');
			elseif (is_same_year($begin, $end))  $out .= Y_m_d($begin) . '/' . Y_m_d($end) . '">' . $begin->format('M jS') . '&ndash;' . $end->format('M jS, Y');
			else 								 $out .= Y_m_d($begin) . '/' . Y_m_d($end) . '">' . $begin->format('M jS, Y') . '&ndash;' . $end->format('M jS, Y');
		}
	}
	
	$out .= '</time>';
	
	return $out; 
		
	
}

/*use "$post->ID" as $post*/
function get_tsapress_event_datetime_string($post, $length = 'long'){

$prefix = '_tsapress_event_';

switch(get_post_meta($post, $prefix.'datetime_range', true)) {

	case 'datetime':
		$datetime_begin = get_post_meta($post, $prefix.'datetime_begin', true);
		$datetime_end = get_post_meta($post, $prefix.'datetime_end', true);
		return get_tsapress_pretty_time($length, "datetime", $datetime_begin, $datetime_end, true);
		break;
		
	case 'date':
		$date_begin = get_post_meta($post, $prefix.'date_begin', true);
		$date_end = get_post_meta($post, $prefix.'date_end', true);
		return get_tsapress_pretty_time($length, "date", $date_begin, $date_end);
		break;
		
	case 'deadline':
		$deadline_datetime = get_post_meta($post, $prefix.'deadline_datetime', true);
		return get_tsapress_pretty_time($length, "deadline", $deadline_datetime);
		break;
		
	default: //(case 'tbd':)
			return '<time datetime="TBD">TBD</time>';
		break;
	}

}

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
	$prefix = '_tsapress_event_';

// Display Event Options in Custom Metabox
$meta_boxes[] = array(
		'id'         => 'event_metabox',
		'title'      => 'Event Options',
		'pages'      => array('page'), // Post type
//		'show_on'    => array( 'key' => 'page-template', 'value' => 'page-event.php' ), // only display for event page-template
		'page-template' => 'event',
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
								
								array(
									'name' => 'Event',
									'desc' => 'Check this box if this page describes a specific event. I.e. Annual State Leadership Conference, State Leadership Academy, etc.',
									'id'   => $prefix . 'is_event',
									'type' => 'checkbox',
								),
								
								array(
									'name' => 'Event Summary',
									'desc' => 'A very concise summary of the event- intended to help the user understand the event in 1 sentence. E.g. "Compete in over 60 competitive events, network with other members, and experience a variety of leadership and educational opportunities."',
									'id'   => $prefix . 'summary',
									'type' => 'textarea_small',
								),
								array(
									'name' => 'Major Event',
									'desc' => 'Check this box to add this event to the primary events list, displayed in the events segment of the left sidebar and the events widget at the bottom of the page.',
									'id'   => $prefix . 'major_event',
									'type' => 'checkbox',
								),
								
								array(
									'name'    => 'Event Type',
									'desc'    => 'Date/time range type for the event. Current timezone: ' .date('T') . '. Wordpress Timezone can be changed in Settings > General. <br />
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
									'desc' => 'Date and time at which the Deadline is set. It is recommended to set the time for midnight deadlines to 11:59 PM for clarity.',
									'id'   => $prefix . 'deadline_datetime',
									'type' => 'text_datetime_timestamp',
								),
								
								
								// Continue options for all event types
								
								
								array(
									'name' => 'Location',
									'desc' => 'Name of the event location. This text be displayed as the link to a Google Map. E.g.: &ldquo;The White House&rdquo;. If this field is left blank, no location will be shown.',
									'id'   => $prefix . 'location',
									'type' => 'text',
								),
								
								array(
									'name' => 'Google Maps Query',
									'desc' => 'Search term for Google Maps; in most cases the text from the above field will work. If the resulting link does not point to the correct location, provide more detail like &ldquo;city, state&rdquo; or a GPS coordinate. E.g.: &ldquo;The White House, Washington, D.C.&rdquo; or &ldquo;38.897659,-77.036516&rdquo;. If this field is left blank, the location on the page will remain simple text.',
									'id'   => $prefix . 'google_maps_query',
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

