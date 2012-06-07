/* 
	All scripts go here.
*/


/* scripts TODO:
	on search, mark strings matching search term

*/

/*
	TODO: why are all events firing while binding? nothing is waiting for the event to happen. Need to fix contact form
*/

 jQuery(document).ready(function($) {
	    

	   
	   /*Contact Form
	   
	   console.log('adding event binders');
	   
	   $('#name').bind('click', expandContactForm());
	   $('#email').bind('change', expandContactForm());
	   $('#message').bind('change', expandContactForm());
	   
	   console.log('event binders added');
	   
	   function expandContactForm() {
	   		console.log('bam');
	   }
	   
	   */
	   
	   
	   	   
	   
	   $('#category-selector-simple').bind('change', function(e){    
			var selector = document.getElementById('category-selector-simple');
			var newURL = selector.options[selector.selectedIndex].value;
			window.location = newURL;
	   });
	   
	   if($('#region-map-canvas').length != 0) {
	   
		   console.log("found map-canvas, init map...")
		   
		    function initMap() {
			
			$canvas = $("#region-map-canvas");
			mapID = $canvas.attr("data-geo-map");
	
		  		TSARegionMapStyles = [
		  		 { featureType: "road", stylers: [ { visibility: "off" } ] },
		  		 { featureType: "poi", stylers: [ { visibility: "off" } ] },
		  		 { featureType: "transit", stylers: [ { visibility: "off" } ] },
		  		 { featureType: "landscape", stylers: [ { visibility: "off" } ] } ];
		  		 
		  		 
		  		 mapOriginElement = $("#category-selector li a[data-geo-map-origin]");
		  		 originLat = parseFloat(mapOriginElement.attr("data-geo-lat"));
		  		 originLon = parseFloat(mapOriginElement.attr("data-geo-lon"));
		  		 originZoom = parseInt(mapOriginElement.attr("data-geo-zoom"));
					  		 
		  		 mapOptions = {
		  		 
			  		zoom: originZoom,
		    		center: new google.maps.LatLng(originLat,originLon),
		    		
		    		disableDefaultUI: true,
		    		draggable: false,
		    		disableDoubleClickZoom: true,
		    		keyboardShortcuts: false,
		    		scrollwheel: false,	    		
		    		
		    		styles: TSARegionMapStyles,
	
		   			mapTypeId: google.maps.MapTypeId.ROADMAP
		  		}
		  		
		  		
		  		allRegions = new google.maps.FusionTablesLayer({
					query: {
					    select: 'geometry',
					    from: (mapID)		},
					});  			  		
		  			  		
		 	 	map = new google.maps.Map(document.getElementById("region-map-canvas"), mapOptions);
		 	 	allRegions.setMap(map);
			}
			
			
				$('#category-selector li a').mouseenter(function() {
		    		    	
		    	$el = $(this);
		    	$("#category-selector li a").removeClass("hover");
	        	$el.addClass("hover");
		    		    	
		    	//default zoom levels
		    	farZoom = 6;
		    	closeZoom = 7;
		    		    	
		    	if($el.attr("data-geo-map-origin")) { //data-geo-map-origin is set to true, change zoom to default far-zoom
		    		zoomLevel = farZoom;
		    	} else {
		    		zoomLevel = closeZoom;
		    	}
		    	
			    zoomSet = $el.attr("data-geo-zoom");
		    	if (!zoomSet == false) {
		    		zoomLevel = zoomSet;
		    	}
		    	
	
		    	// pan to new point
		          newPoint = new google.maps.LatLng(parseFloat($el.attr("data-geo-lat")), parseFloat($el.attr("data-geo-lon")));
		          map.panTo(newPoint);
		          map.setZoom(parseInt(zoomLevel));
		    	
		    		  
		    	if(typeof highlightLayer!='undefined') {highlightLayer.setMap(null)}; //clear highlight
	  	
	  			geoID = $el.attr("data-geo-id"); //grab region id of hovered region
		    	
		    	if (geoID != undefined){ //this is a highlightable region
		    		    	
		    	highlightLayer = new google.maps.FusionTablesLayer({
				  query: {
				    select: 'geometry',
				    from: (mapID),
				    where: ('id = ' + geoID)
				  }});	
				  
				highlightLayer.setMap(map);
		    	} else { //this is not a highlightable region ("all regions")
		    		if(typeof highlightLayer!='undefined') {highlightLayer.setMap(null)};
		    	}			    	
		    });
			
			
			initMap();
	
			
		}    	    	    		


/*

// Ajaxify site. Adapted for TSAPress from script by Chris Coyier (csstricks.com).

//$(".home li.home").removeClass("home").addClass("current_page_item");

$base_site_url = $('#root').attr('href');
$("#content").before("<img src='" + $base_site_url + "/img/ajax-loader.gif' id='ajax-loader' />");

var 
	$mainContent = $("#content"),
    $ajaxSpinner = $("#ajax-loader"),
    $searchInput = $("#search input"),
    $allLinks    = $("a"),
    $el;

$('#search form').submit(function() {  
	var s = $searchInput.val();
	if (s) {
		var query = '/?s=' + s;
		$.address.value(query);  
	}
	return false;
});



// URL internal is via plugin http://benalman.com/projects/jquery-urlinternal-plugin/
$('a:urlInternal').live('click', function(e) { 
	
	// Caching
	$el = $(this);

	if ((!$el.hasClass("comment-reply-link")) && ($el.attr("id") != 'cancel-comment-reply-link')) { 		
		var path = $(this).attr('href').replace(base, '');
		$.address.value(path);
		$(".current_page_item").removeClass("current_page_item");
		$allLinks.removeClass("current_link");
		$el.addClass("current_link").parent().addClass("current_page_item");
		return false;
	}
	
	// Default action (go to link) prevented for comment-related links (which use onclick attributes)
	e.preventDefault();
	
}); 


// Fancy ALL AJAX Stuff
$.address.change(function(event) {  
	if (event.value) {
		$ajaxSpinner.fadeIn();
		$mainContent
			.empty()
			.load(base + event.value + ' #inside', function() {
				$ajaxSpinner.fadeOut();
				$mainContent.fadeIn();
			});  
	} 

	var current = location.protocol + '//' + location.hostname + location.pathname;
	if (base + '/' != current) {
		var diff = current.replace(base, '');
		location = base + '/#' + diff;
	}
}); 

*/

});


