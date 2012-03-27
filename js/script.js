/* 
	All scripts go here.
*/

 $(document).ready(function() {
	    
	   
	   
	   
	   
	   
	   
	   
	   /* Google Map - region dropdown */
	   

	    
	   	//if javascript, sub out simple select with awesome map selector 			 			TODO: check for device capability (load for phones?)	    
	    $('#category-selector').remove();
		$('#category-selector-javascript').attr("id", "category-selector").attr("style","");
		initMap();
	    
	   
	    function initMap() {
	
		console.log("loaded map");

	  		TSARegionMapStyles = [
	  		 { featureType: "road", stylers: [ { visibility: "off" } ] },
	  		 { featureType: "poi", stylers: [ { visibility: "off" } ] },
	  		 { featureType: "transit", stylers: [ { visibility: "off" } ] },
	  		 { featureType: "landscape", stylers: [ { visibility: "off" } ] } ];
	  		 
	  		 mapOptions = {
	  		 
		  		zoom: 6,
	    		center: new google.maps.LatLng(37.779399,-79.519043),
	    		
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
				    from: '3332626'				  },
				});  			  		
	  			  		
	 	 	map = new google.maps.Map(document.getElementById("region-map-canvas"), mapOptions);
	 	 	allRegions.setMap(map);
		}

	    $('#category-selector li a[data-geo-map-origin]').addClass("hover");
	    
	    $('#category-selector li a').mouseenter(function() {
	    	
	    	$el = $(this);
	    	$("#category-selector li a").removeClass("hover");
        	$el.addClass("hover");
	    	

	    	// pan to new point
	          newPoint = new google.maps.LatLng($el.attr("data-geo-lat"), $el.attr("data-geo-lon"));
	          map.panTo(newPoint);
	    	
	    	if($el.attr("data-geo-map-origin")){
	    		map.setZoom(6);
	    		if(typeof highlightLayer!='undefined') {highlightLayer.setMap(null)};
	    	}else {
	   		 	map.setZoom(7);
	    	
	    	
	    	geoID = $el.attr("data-geo-id");
	    	
	    	console.log(geoID);
	    	
	    	if (geoID != undefined){
	    	
	    	console.log('ID = ' + geoID);
	    	
	    	if(typeof highlightLayer!='undefined') {highlightLayer.setMap(null)};
	    	
	    	highlightLayer = new google.maps.FusionTablesLayer({
			  query: {
			    select: 'geometry',
			    from: '3332626',
			    where: ('id = ' + geoID)
			  }});	
			  
			highlightLayer.setMap(map);
	    	}}			    	
	    });
	    	    	    		
		});




