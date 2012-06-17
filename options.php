<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {
	// This gets the theme name from the stylesheet (lowercase and without spaces)
	if (function_exists('wp_get_theme')) $themename = wp_get_theme(); //New WP >= 3.4.0
	else $themename = wp_get_theme_(STYLESHEETPATH . '/style.css'); //Depreciated WP 3.4.0

	
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
}


/*gets the domain.ext of a URL - used in contact_email std field*/
	function tsapress_get_domain($url) {
		$wwwremoved = ereg_replace('www\.','',$url);
		$domain = parse_url($wwwremoved);
		if(!empty($domain["host"])) {
		     return $domain["host"];
		} else {
		    return $domain["path"];
		}	 
	}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
	
	
	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_bloginfo('template_directory') . '/images/';

		
	$options = array();



	$options[] = array( "name" => "Basic Settings",
						"type" => "heading");

	$options[] = array( "name" => "Site Title &amp; Tagline",
						"desc" => "Please insure that your Site Title and Tagline are configured correctly. \r Site Title should be the abreviated title of the state delegation. E.g. &ldquo;stateName TSA&rdquo;. \r Tagline should be the full, formal title of the state delegation. E.g. &ldquo;stateName Association of the Technology Student Association&rdquo;.\r\r Current Settings:\r   Site Title: ". get_bloginfo('name') . "\r    Tagline: ". get_bloginfo('description') . "\r\rTo modify these settings, visit Settings -> General.",
						"type" => "info");

	$options[] = array( "name" => "State Name",
						"desc" => "The name of the state in which " . get_bloginfo('name') . " operates." ,
						"id" => "state_name",
						"std" => str_replace(array("Technology Student Association ", " Technology Student Association", "Technology Student Association", "TSA ", " TSA", "TSA" ), "", get_bloginfo('name')),
						"class" => "mini",
						"type" => "text");	
							
						
	$options[] = array( "name" => "Members",
						"desc" => "The total number of members in " . get_bloginfo('name') . ". This number is displayed at the top of the theme. <br /> E.g. &ldquo;10,000&rdquo;" ,
						"id" => "total_members",
						"std" => "10,000",
						"class" => "mini",
						"type" => "text");
	
	$options[] = array( "name" => "Schools",
						"desc" => "The total number of chapters in " . get_bloginfo('name') . ". This number is displayed at the top of the theme. E.g. &ldquo;180&rdquo;" ,
						"id" => "total_schools",
						"std" => "180",
						"class" => "mini",
						"type" => "text");



	$options[] = array( "name" => "Contact Information",
						"desc" => "This contact info is used in the Contact Us section of the theme (at the very bottom of the page).",
						"id" => "contact_info",
						"std" => get_bloginfo('name') ."\rPO Box 1234\rCity, State 12345\rphone: (123) 456-7890 | fax: (123) 456-7891",
						"type" => "textarea"); 

				
	$options[] = array( "name" => "Contact Email",
						"desc" => "If set, the contact email is processed to be hidden from email-collecting spammers and displayed directly below the Contact Information.",
						"id" => "contact_email",
						"std" => "advisor@". tsapress_get_domain(get_bloginfo('url')),
						"type" => "text");
						
						
						

	$options[] = array( "name" => "Advanced Settings",
						"type" => "heading");
						
		
	$options[] = array( "name" => "About TSA Area",
						"desc" => 'The description of the state delegation given at the top of the website. Replaces default behavior if set. Content is HTML. Recommended tags: &lt;h1&gt;,&lt;em&gt;, and &lt;p&gt;.',
						"id" => "our_story_content",
						"std" => '',
						"type" => "textarea"); 
	
	$options[] = array( "name" => "Our Story URL",
						"desc" => "The URL pointing to an introduction to TSA. I.e. " . get_bloginfo('url'). "/our-story \nLink is displayed below the introduction to TSA at the very top of every page. If left empty, the link will not be Displayed." ,
						"id" => "our_story_url",
						"std" => "",
						"type" => "text");
	
	$options[] = array( "name" => "Calendar of Events URL",
						"desc" => "The URL pointing to a Calendar of Events. I.e. " . get_bloginfo('url'). "/our-story \nLink is displayed below the events menu on the left sidebar and below the Conferences & Events widget in the footer. If left empty, the link will not be Displayed." ,
						"id" => "cal_events_url",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "News Archives URL",
						"desc" => "The URL pointing to a full news archive. I.e. " . get_bloginfo('url'). "/our-story \nLink is displayed below the News Archives widget in the footer. If left empty, the link will not be Displayed." ,
						"id" => "archives_url",
						"std" => "",
						"type" => "text");
							
		
	$options[] = array( "name" => "State Emblem",
						"desc" => "The 175&times;105 pixel PNG image of the " . get_bloginfo('name') . " Emblem.",
						"id" => "state_emblem",
						"type" => "upload");
	
	$options[] = array( "name" => "Enable Region Menu",
						"desc" => "Check this box to enable the Region Selector while viewing news. (Default is on.)",
						"id" => "region_selector",
						"std" => "1", //defaults to on
						"type" => "checkbox");
	
	$options[] = array( "name" => "Activate Region Google Map",
						"desc" => "Check this box to activate the Google region map. The Region Google Map replaces the dropdown menu in the &ldquo;view news from:&rdquo; fliter.",
						"id" => "region_gmap",
						"std" => "0", //defaults to off
						"type" => "checkbox");
						
	//activate google map
	
	$options[] = array( "name" => "Google Fusion Table Numeric ID",
						"desc" => "The numeric ID of the State TSA Region Map overlay. To find the a map&rsquo;s Numeric ID, open the map in Google Fusion Tables, and select &ldquo;About&rdquo; from the File menu. The Numeric ID is listed in the About modal.",
						"id" => "google_fusion_table_numeric_id",
						"std" => "3332626", //defaults to Virginia TSA Google Fusion Table Numeric ID (working example)
						"class" => "mini",
						"type" => "text");
						
	$options[] = array( "name" => "Flush .htaccess",
						"desc" => "Flush .htaccess each time the admin panel is refreshed. <b>Warning: slow, possibly destructive.</b> If the &ldquo;/img/&rdquo; &ldquo;/css/&rdquo; and &ldquo;/js/&rdquo; folders are not redirecting to the correct theme files, there is likely a problem with the .htaccess file. Flip this on, save the settings, then flip it off. Repeat as necessary.",
						"id" => "flush_htaccess",
						"std" => "0", 
						"type" => "checkbox");
	
	
	/**
	 * Sample Options (for reference)  
	 */

/*
	
	// Test data
	$test_array = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
	
	// Multicheck Array
	$multicheck_array = array("one" => "French Toast", "two" => "Pancake", "three" => "Omelette", "four" => "Crepe", "five" => "Waffle");
	
	// Multicheck Defaults
	$multicheck_defaults = array("one" => "1","five" => "1");
	
	// Background Defaults
	
	$background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat','position' => 'top center','attachment'=>'scroll');
	
	
		
	$options[] = array( "name" => "Basic Settings Example",
						"type" => "heading");
							
	$options[] = array( "name" => "Input Text Mini",
						"desc" => "A mini text input field.",
						"id" => "example_text_mini",
						"std" => "Default",
						"class" => "mini",
						"type" => "text");
								
	$options[] = array( "name" => "Input Text",
						"desc" => "A text input field.",
						"id" => "example_text",
						"std" => "Default Value",
						"type" => "text");
							
	$options[] = array( "name" => "Textarea",
						"desc" => "Textarea description.",
						"id" => "example_textarea",
						"std" => "Default Text",
						"type" => "textarea"); 
						
	$options[] = array( "name" => "Input Select Small",
						"desc" => "Small Select Box.",
						"id" => "example_select",
						"std" => "three",
						"type" => "select",
						"class" => "mini", //mini, tiny, small
						"options" => $test_array);			 
						
	$options[] = array( "name" => "Input Select Wide",
						"desc" => "A wider select box.",
						"id" => "example_select_wide",
						"std" => "two",
						"type" => "select",
						"options" => $test_array);
						
	$options[] = array( "name" => "Select a Category",
						"desc" => "Passed an array of categories with cat_ID and cat_name",
						"id" => "example_select_categories",
						"type" => "select",
						"options" => $options_categories);
						
	$options[] = array( "name" => "Select a Page",
						"desc" => "Passed an pages with ID and post_title",
						"id" => "example_select_pages",
						"type" => "select",
						"options" => $options_pages);
						
	$options[] = array( "name" => "Input Radio (one)",
						"desc" => "Radio select with default options 'one'.",
						"id" => "example_radio",
						"std" => "one",
						"type" => "radio",
						"options" => $test_array);
							
	$options[] = array( "name" => "Example Info",
						"desc" => "This is just some example information you can put in the panel.",
						"type" => "info");
											
	$options[] = array( "name" => "Input Checkbox",
						"desc" => "Example checkbox, defaults to true.",
						"id" => "example_checkbox",
						"std" => "1",
						"type" => "checkbox");
						
	$options[] = array( "name" => "Advanced Settings Example",
						"type" => "heading");
						
	$options[] = array( "name" => "Check to Show a Hidden Text Input",
						"desc" => "Click here and see what happens.",
						"id" => "example_showhidden",
						"type" => "checkbox");
	
	$options[] = array( "name" => "Hidden Text Input",
						"desc" => "This option is hidden unless activated by a checkbox click.",
						"id" => "example_text_hidden",
						"std" => "Hello",
						"class" => "hidden",
						"type" => "text");
						
	$options[] = array( "name" => "Uploader Test",
						"desc" => "This creates a full size uploader that previews the image.",
						"id" => "example_uploader",
						"type" => "upload");
						
	$options[] = array( "name" => "Example Image Selector",
						"desc" => "Images for layout.",
						"id" => "example_images",
						"std" => "2c-l-fixed",
						"type" => "images",
						"options" => array(
							'1col-fixed' => $imagepath . '1col.png',
							'2c-l-fixed' => $imagepath . '2cl.png',
							'2c-r-fixed' => $imagepath . '2cr.png')
						);
						
	$options[] = array( "name" =>  "Example Background",
						"desc" => "Change the background CSS.",
						"id" => "example_background",
						"std" => $background_defaults, 
						"type" => "background");
								
	$options[] = array( "name" => "Multicheck",
						"desc" => "Multicheck description.",
						"id" => "example_multicheck",
						"std" => $multicheck_defaults, // These items get checked by default
						"type" => "multicheck",
						"options" => $multicheck_array);
							
	$options[] = array( "name" => "Colorpicker",
						"desc" => "No color selected by default.",
						"id" => "example_colorpicker",
						"std" => "",
						"type" => "color");
						
	$options[] = array( "name" => "Typography",
						"desc" => "Example typography.",
						"id" => "example_typography",
						"std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
						"type" => "typography");					
	
	/*end example options*/
	
	
						
								
	return $options;
}

/* 
 * This is an example of how to add custom scripts to the options panel.
 * This example shows/hides an option when a checkbox is clicked.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function($) {

	$('#example_showhidden').click(function() {
  		$('#section-example_text_hidden').fadeToggle(400);
	});
	
	if ($('#example_showhidden:checked').val() !== undefined) {
		$('#section-example_text_hidden').show();
	}
	
});
</script>

<?php
}