/**
 * Controls the behaviours of custom metabox fields.
 */

/*jslint browser: true, devel: true, indent: 4, maxerr: 50, sub: true */
/*global jQuery, tb_show, tb_remove */

/**
 * Custom jQuery for Custom Metaboxes and Fields
 */
jQuery(document).ready(function ($) {
	'use strict';

	var $prefix = '_tsapress_event_';

	var formfield;
	
	//events enabled
	var $is_event;
	var $is_event_checkbox;
	
	//event options
	var $event_summary;
	var $event_major_event;
	var $event_datetime_range;
	var $event_location;
	var $event_google_maps_query;
	
	var $datetime_range;
	var $datetime_options;
	var $date_options;
	var $deadline_options;

	$is_event = $('.' + $prefix + 'is_event');
	$is_event_checkbox = $('#' + $prefix + 'is_event')
	
	$event_summary = $('.' + $prefix + 'summary');
	$event_major_event = $('.' + $prefix + 'major_event');
	$event_datetime_range = $('.' + $prefix + 'datetime_range');
	$event_location = $('.' + $prefix + 'location');
	$event_google_maps_query = $('.' + $prefix + 'google_maps_query');

	$datetime_range = $('.' + $prefix + 'datetime_range input');
	$datetime_options = $('.' + $prefix + 'datetime_begin, .' + $prefix + 'datetime_end');
	$date_options = $('.' + $prefix + 'date_begin, .' + $prefix + 'date_end');
	$deadline_options = $('.' + $prefix + 'deadline_datetime');
	
	
	
	function eventHider(){
		var checked;
		checked = $is_event_checkbox.is(':checked');
	
		if(checked == true){	
			$event_summary.slideDown('200');
			$event_major_event.slideDown('200');
			$event_datetime_range.slideDown('200');
			$event_location.slideDown('200');
			$event_google_maps_query.slideDown('200');
			datetimeShowHider();	
		}
		else {
			$event_summary.slideUp('200');
			$event_major_event.slideUp('200');
			$event_datetime_range.slideUp('200');
			$event_location.slideUp('200');
			$event_google_maps_query.slideUp('200');
			$datetime_options.slideUp('200');
			$date_options.slideUp('200');
			$deadline_options.slideUp('200');
		}
	}
	
	$is_event.bind('click', eventHider);
	eventHider();
	
	
	
	
	function datetimeShowHider(){
		var checked;
		checked = $datetime_range.filter(':checked');
			
		switch(checked.val()){
		
		case 'datetime':
			$datetime_options.show();
			$date_options.hide();
			$deadline_options.hide();	
			break;
		
		case 'date':
			$datetime_options.hide();
			$date_options.show();
			$deadline_options.hide();
			break;
		
		case 'deadline':
			$datetime_options.hide();
			$date_options.hide();
			$deadline_options.show();
			break;
			
		case 'tbd':
			$datetime_options.hide();
			$date_options.hide();
			$deadline_options.hide();
			break;
			
		default:
			$datetime_options.hide();
			$date_options.hide();
			$deadline_options.hide();
			break;
		}
		
	}
	
	$datetime_range.bind('click', datetimeShowHider);
	
	datetimeShowHider();







	/**
	 * Initialize timepicker
	 */
	$('.cmb_timepicker').each(function () {
		$('#' + jQuery(this).attr('id')).timePicker({
			show24Hours: false,
			separator: ':',
			step: 30
		});
	});




//Begin unmodified -------





	/**
	 * Initialize jQuery UI datepicker
	 */
	$('.cmb_datepicker').each(function () {
		$('#' + jQuery(this).attr('id')).datepicker();
		// $('#' + jQuery(this).attr('id')).datepicker({ dateFormat: 'yy-mm-dd' });
		// For more options see http://jqueryui.com/demos/datepicker/#option-dateFormat
	});
	// Wrap date picker in class to narrow the scope of jQuery UI CSS and prevent conflicts
	$("#ui-datepicker-div").wrap('<div class="cmb_element" />');
	
	/**
	 * Initialize color picker
	 */
    $('input:text.cmb_colorpicker').each(function (i) {
        $(this).after('<div id="picker-' + i + '" style="z-index: 1000; background: #EEE; border: 1px solid #CCC; position: absolute; display: block;"></div>');
        $('#picker-' + i).hide().farbtastic($(this));
    })
    .focus(function() {
        $(this).next().show();
    })
    .blur(function() {
        $(this).next().hide();
    });

	/**
	 * File and image upload handling
	 */
	$('.cmb_upload_file').change(function () {
		formfield = $(this).attr('name');
		$('#' + formfield + '_id').val("");
	});

	$('.cmb_upload_button').live('click', function () {
		var buttonLabel;
		formfield = $(this).prev('input').attr('name');
		buttonLabel = 'Use as ' + $('label[for=' + formfield + ']').text();
		tb_show('', 'media-upload.php?post_id=' + $('#post_ID').val() + '&type=file&cmb_force_send=true&cmb_send_label=' + buttonLabel + '&TB_iframe=true');
		return false;
	});

	$('.cmb_remove_file_button').live('click', function () {
		formfield = $(this).attr('rel');
		$('input#' + formfield).val('');
		$('input#' + formfield + '_id').val('');
		$(this).parent().remove();
		return false;
	});

	window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function (html) {
		var itemurl, itemclass, itemClassBits, itemid, htmlBits, itemtitle,
			image, uploadStatus = true;

		if (formfield) {

	        if ($(html).html(html).find('img').length > 0) {
				itemurl = $(html).html(html).find('img').attr('src'); // Use the URL to the size selected.
				itemclass = $(html).html(html).find('img').attr('class'); // Extract the ID from the returned class name.
				itemClassBits = itemclass.split(" ");
				itemid = itemClassBits[itemClassBits.length - 1];
				itemid = itemid.replace('wp-image-', '');
	        } else {
				// It's not an image. Get the URL to the file instead.
				htmlBits = html.split("'"); // jQuery seems to strip out XHTML when assigning the string to an object. Use alternate method.
				itemurl = htmlBits[1]; // Use the URL to the file.
				itemtitle = htmlBits[2];
				itemtitle = itemtitle.replace('>', '');
				itemtitle = itemtitle.replace('</a>', '');
				itemid = ""; // TO DO: Get ID for non-image attachments.
			}

			image = /(jpe?g|png|gif|ico)$/gi;

			if (itemurl.match(image)) {
				uploadStatus = '<div class="img_status"><img src="' + itemurl + '" alt="" /><a href="#" class="cmb_remove_file_button" rel="' + formfield + '">Remove Image</a></div>';
			} else {
				// No output preview if it's not an image
				// Standard generic output if it's not an image.
				html = '<a href="' + itemurl + '" target="_blank" rel="external">View File</a>';
				uploadStatus = '<div class="no_image"><span class="file_link">' + html + '</span>&nbsp;&nbsp;&nbsp;<a href="#" class="cmb_remove_file_button" rel="' + formfield + '">Remove</a></div>';
			}

			$('#' + formfield).val(itemurl);
			$('#' + formfield + '_id').val(itemid);
			$('#' + formfield).siblings('.cmb_upload_status').slideDown().html(uploadStatus);
			tb_remove();

		} else {
			window.original_send_to_editor(html);
		}

		formfield = '';
	};
});