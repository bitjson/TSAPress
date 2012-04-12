		<?php 
			
			$displayFor = "mobile"; 
			
			global $wp_query;
			$current_category_id = get_query_var('cat');
			$current_category_link = get_category_link( $current_category_id );

		
			if ($displayFor == "mobile") { ?>
	
	<?php // For mobile devices: ?>

	<?php //TODO: clean category-selector urls 	?>	
	
	<span class="category-selector">Viewing news from:<form action="<?php bloginfo('url'); ?>/" method="get">
		<select name='category-selector-simple' id='category-selector-simple' >
		<option value="<?php bloginfo('url'); ?>/"<?php if (is_home()) { echo 'selected="selected"'; } ?>>All Regions</option>
		<?php 
				
		$categories=get_categories();
			
		  foreach ($categories as $cat) {
		  $selected = '';
		  	if ($current_category_id == $cat->cat_ID) {$selected = ' selected="selected"';}
			  echo '<option value="'. get_bloginfo('url') .'/'. get_option('category_base'). '/' . $cat->slug . '/"'. $selected .'>'. $cat->name .'</option>';
		  }
		
		
		//OLD: wp_dropdown_categories(array ('show_option_all' => 'All Regions', 'id' => 'category-selector', 'name' => 'category-selector', 'orderby' => 'name') ); 
		
		
		?>
		</select>
		<?php //TODO: implement noscript capability: <noscript><input type="submit" value="View" /></noscript> ?>
	</form></span>
	
	<?php }	else { //display for full browsers ?>		
	
	
	<?php // For full browsers: ?>
	
	<span id="category-selector" class="category-selector">Viewing news from: <a href="<?php echo $current_category_link ?>" title="<?php echo $current_category_name ?>"><?php echo $current_category_name ?></a>
	  <div class="hover-box">
		<div>
	    	<h1>View News from:</h1>
	    	<div id="region-map-canvas" data-geo-map="<?php $mapID="3332626"; echo $mapID; ?>"></div> 
				<ul>
					 <?php
						  $categories=  get_categories();
						  foreach ($categories as $cat) {
						  	
						  	$temp = $cat->category_description;
						  	list($geoLat, $geoLon, $geoZoom, $geoID) = split('[,]', $temp);
						  							  	
						  	$geoData = 'data-geo-lat="'.$geoLat.'" data-geo-lon="'.$geoLon.'"';
						  							  	
						  	if (!$geoZoom == false) { $geoData .= ' data-geo-zoom="'. $geoZoom .'"';}
						  	
						  	if (!$geoID == false) { $geoData .= ' data-geo-id="'. $geoID .'"';}

						  	
						  	if ($current_category_id == $cat->cat_ID) { $geoData .= ' data-geo-map-origin="true" class="current"';}  	
						  	
							echo '<li>'.'<a href="'.get_category_link($cat->cat_ID).'" '. $geoData .'>'. $cat->name .'</a>'.'</li>';
						  }
					 ?>
				</ul>  
	  		</div>
		</div>
	</span>
	
	<?php } ?>

