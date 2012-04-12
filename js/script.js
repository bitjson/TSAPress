/* 
	All scripts go here.
*/


/* scripts TODO:
	on search, mark strings matching search term

*/





/*
	TODO: why are all events firing while binding? nothing is waiting for the event to happen. Need to fix contact form
*/


console.log('opened javascript');

 jQuery(document).ready(function($) {
	    
	   console.log('document ready');

	   
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
	   
	   
	    function initMap() {
	
		console.log("loading map");
		
		$canvas = $("#region-map-canvas");
		mapID = $canvas.attr("data-geo-map");
		console.log(mapID);

	  		TSARegionMapStyles = [
	  		 { featureType: "road", stylers: [ { visibility: "off" } ] },
	  		 { featureType: "poi", stylers: [ { visibility: "off" } ] },
	  		 { featureType: "transit", stylers: [ { visibility: "off" } ] },
	  		 { featureType: "landscape", stylers: [ { visibility: "off" } ] } ];
	  		 
	  		 
	  		 mapOriginElement = $("#category-selector li a[data-geo-map-origin]");
	  		 originLat = parseFloat(mapOriginElement.attr("data-geo-lat"));
	  		 originLon = parseFloat(mapOriginElement.attr("data-geo-lon"));
	  		 originZoom = parseInt(mapOriginElement.attr("data-geo-zoom"));
			
			console.log("zoom =" + originZoom);
	  		 
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
	    	
	    	console.log("mouseentered");
	    	
	    	$el = $(this);
	    	$("#category-selector li a").removeClass("hover");
        	$el.addClass("hover");
	    	
	    	console.log($el);
	    	
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
	    		console.log("zoomLevel set to " + zoomLevel);
	    	}
	    	

	    	// pan to new point
	          newPoint = new google.maps.LatLng(parseFloat($el.attr("data-geo-lat")), parseFloat($el.attr("data-geo-lon")));
	          map.panTo(newPoint);
	          map.setZoom(parseInt(zoomLevel));
	    	
	    		  
	    	if(typeof highlightLayer!='undefined') {highlightLayer.setMap(null)}; //clear highlight
  	
  			geoID = $el.attr("data-geo-id"); //grab region id of hovered region
	    	
	    	if (geoID != undefined){ //this is a highlightable region
	    	
	    	console.log('ID = ' + geoID);
	    	
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

		
			    
	    
	    
	    
	    
	        	    	    		
});