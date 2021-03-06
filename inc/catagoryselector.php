		<?php 
			
			
			$region_selector_is_on = of_get_option('region_selector', 1); //defaults to on		
			$region_gmap_is_on = of_get_option('region_gmap', 0); //defaults to off
			
			$mapID = of_get_option('google_fusion_table_numeric_id', '3332626'); //defaults to Virginia TSA Google Fusion Table Numeric ID (working example)
			
			global $wp_query;
			
			$home_category_name="All Regions";
			
			if(is_category()){
			
				$current_category_id = get_query_var('cat');
				$current_category_name = get_cat_name( $current_category_id );
				$current_category_link = get_category_link( $current_category_id );

				
			
			} else { //if not a category view, we must be at the home page
				
				$current_category_id = null;
				$current_category_name = $home_category_name;
				$current_category_link = get_home_url();
			}
			

		
			if ($region_selector_is_on == true && $region_gmap_is_on == 0) { ?>
	
	<?php //TODO: clean category-selector urls 	?>	
	
	<span class="category-selector">Viewing news from:<form action="<?php bloginfo('url'); ?>/" method="get">
		<select name='category-selector-simple' id='category-selector-simple' >
		<option value="<?php home_url(); ?>/"<?php if (is_home()) { echo 'selected="selected"'; } ?>>All Regions</option>
		<?php 
				
		$categories=get_categories();
			
		  foreach ($categories as $cat) {
		  $selected = '';
		  	if ($current_category_id == $cat->cat_ID) {$selected = ' selected="selected"';}
			  echo '<option value="'. get_home_url() .'/'. get_option('category_base'). '/' . $cat->slug . '/"'. $selected .'>'. $cat->name .'</option>';
		  }	
		?>
		</select><?php //TODO: implement noscript capability: <noscript><input type="submit" value="View" /></noscript> ?>
	</form></span>
	
	<?php }	elseif ($region_selector_is_on == true && $region_gmap_is_on == true) { ?>
	
	<span id="category-selector" class="category-selector">Viewing news from: <a href="<?php echo $current_category_link ?>" title="<?php echo $current_category_name ?>"><?php echo $current_category_name ?></a>
	  <div class="hover-box">
		<div>
	    	<h1>View News from:</h1>
	    	<div id="region-map-canvas" data-geo-map="<?php echo $mapID; ?>"></div> 
				
				
				<ul>
<?php
						  $categories=  get_categories();	  
						  
						  $category_list_items = array(0 => ''); //first list item will be all categories <li>

						  foreach ($categories as $cat) {
						  	
						  	$temp = $cat->category_description;
						  	
						  	$geoDataArray = explode(',', $temp);
							 
							  // Standard:
							  //	$geoDataArray[0] = Latitude of map center (on hover) :: required- fires warning if not given
							  //	$geoDataArray[1] = Longitude of map center (on hover) :: required- fires warning if not given
							  //	$geoDataArray[2] = Zoom Level of map center (to view whole region)
							  //	$geoDataArray[3] = Fusion Table Row ID (for region highlighting)					  	
						  	
						  							  	
						  	$geoData = 'data-geo-lat="'.$geoDataArray[0].'" data-geo-lon="'.$geoDataArray[1].'"';
						  							  	
						  	if (isset($geoDataArray[2])) { $geoData .= ' data-geo-zoom="'. $geoDataArray[2] .'"';}
						  	
						  	if (isset($geoDataArray[3])) { $geoData .= ' data-geo-id="'. $geoDataArray[3] .'"';
						  	 						  							  	 
						  	} else { //this is the $geoData for all categories
						  		
						  		$isCurrent = '';
						  		if (!is_category()) { $isCurrent = ' data-geo-map-origin="true" class="current"';}
						  		
						  		$category_list_items[0] = '<li>'.'<a href="'.get_home_url().'/" '. $geoData . $isCurrent .'>'. $home_category_name .'</a>'.'</li>';
						  	} 
						  	
						  	if ($current_category_id == $cat->cat_ID) { $geoData .= ' data-geo-map-origin="true" class="current"';}
						  	 	
						  	$category_list_items[] = '<li>'.'<a href="'.get_category_link($cat->cat_ID).'" '. $geoData .'>'. $cat->name .'</a>'.'</li>';
						  }
						  	
						  	foreach ($category_list_items as $category_list_item) {
						  		echo "					" . $category_list_item . "\n"; //5 tabs, <li>, new line
						  	}
						  
						  
					 ?>
				</ul>  		
	  		</div>
		</div>
	</span>
	
	<?php } ?>

