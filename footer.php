
</div> <?php //close div class="full-wrap" ?>

 <div id="basement">
    <div>
       <div>
       <div class="basement-line">
       <a href="#TSA-Bar" class="back-to-top">Back to Top</a>    
    
     <section class="updates">
    	<h1>Latests Updates</h1>
	    <ol>
	    <?php wp_get_archives(array ('type' => 'postbypost', 'limit' => 5, 'before' => '<li>', 'after' => '</li>')); ?>
   		</ol>
    	<a href="#">Previous updates</a>
    </section>
    
    <section class="events"><?php 

			$querystr = "SELECT wposts.*
			FROM ".$wpdb->posts." AS wposts
			INNER JOIN ".$wpdb->postmeta." AS wpostmeta
			ON wpostmeta.post_id = wposts.ID
			AND wpostmeta.meta_key = '_tsapress_event_major_event'
			AND wpostmeta.meta_value = 'on'";
			
			 $primary_events = $wpdb->get_results($querystr, OBJECT);
					
			 if ($primary_events): ?>
		 <h1>Conferences &amp; Events</h1>
			<ol><?php
				global $post; 
				
				foreach ($primary_events as $post): ?>
			    <?php setup_postdata($post); ?>
				<li><a href="<?php the_permalink() ?>"><?php the_title(); ?><?php echo get_tsapress_event_datetime_string($post->ID, 'short'); ?></a></li>
 				<?php endforeach; ?>
  			</ol>
  		<?php else : ?>
   		<h1 class="center">No Events</h1>
   			<p class="center">Sorry, there are no events yet.</p>
 		<?php endif; ?>




    	<a href="#">More important dates</a> <!-- TODO:reveal -->
    </section>
    
   
    
    <section  class="archives">
     <h1>News Archives</h1>
<?    
		$archive_query = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
	
		$key = md5($archive_query);
		$cache = wp_cache_get( 'tsapress_get_archives' , 'general');
		if ( !isset( $cache[ $key ] ) ) {
			$arcresults = $wpdb->get_results($archive_query);
			$cache[ $key ] = $arcresults;
			wp_cache_set( 'tsapress_get_archives', $cache, 'general' );
		} else {
			$arcresults = $cache[ $key ];
		}
		if ( $arcresults ) {

			$years_to_show = 3; //array of month arrays- 1-12, true or false			
			
			$newest_month = $arcresults[0];
			$oldest_month = end($arcresults);
			
			if (($newest_month->year - $oldest_month->year) > ($years_to_show - 1)) $last_year_displayed = $newest_month->year - ($years_to_show - 1);
			else $last_year_displayed = $oldest_month->year;
			
			for($i = $newest_month->year; $i >= $last_year_displayed; $i--) {
				if(!isset($years[$i])) $years[$i] = array_fill(1, 12, false); //set an array of months for each year, months are all false
			}
			
			foreach ( (array) $arcresults as $arcresult ) { //each object is a single month
				if(isset($years[$arcresult->year])) $years[$arcresult->year][$arcresult->month] = true; //set month to true for all months in which posts exist (within the limits of the years defined)
			}
						
			foreach($years as $year => $months){
				echo "<time datetime=\"$year\">";
				echo '<a href="' . get_year_link($year) . "\">$year:</a>";
				echo "\n<ul>\n";	
				
				foreach($months as $month_number => $posts_exist_in_month){
				
					$datetime = new DateTime_52(); //extended for compatability with PHP 5.2
					$datetime->setTimestamp(mktime(0, 0, 0, $month_number, 1, $year));
	
					$out = '<time datetime="' . $datetime->format('Y-m') . '">'. $datetime->format('M') . '</time>';
					if($posts_exist_in_month) $out = '<a href="' . get_month_link($year, $month_number) . '">' . $out . '</a>';
					echo "<li>$out</li>\n";
				
				}
				
				echo "\n</ul></time>\n";					
			}
		}	   
?>
		<a href="#">Full Archives</a>
    </section>
        
<?php tsapress_display_contact_form(); ?>
    	    
    <section id="partners">
    <h1>National TSA Partners</h1>
    <img src="<?php bloginfo('template_directory'); ?>/mock-content/national-tsa-partners.png" alt="National TSA Partners" height="100px" />
    </section>    
    
    <footer>
        <small class="copyright">&copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a><br>
        All Rights Reserved.</small>
        <a href="#" class="engine" title="Powered by WordPress, state-of-the-art semantic personal publishing platform"><small>Proudly powered by WordPress. <img src="<?php bloginfo('template_directory'); ?>/img/wordpress.png" alt="WordPress Logo"></small></a>
         <a href="#" class="developer" title="Developed by the Technology Advisory Group, TSA's cutting-edge, volunteer, technology taskforce"><small>Developed by the TAG. <img src="<?php bloginfo('template_directory'); ?>/img/TSA-TAG-monogram.png" alt="TSA:TAG Monogram"></small></a>
    </footer>
    </div>
  </div>
</div> 
    
    
     <?php /* TODO: Google Analytics
    
    <script type="text/javascript">
var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
    
        */ ?>

    
    
    </div>
    
<?php wp_footer(); ?>
    
    <script type="text/javascript">window.jQuery || document.write('<script src="<?php bloginfo('template_directory'); ?>/js/libs/jquery-1.7.1.min.js"><\/script>')</script><?php //if jQuery is not loaded from Google CDN, load it from the library ?>
    <script defer src="<?php bloginfo('template_directory'); ?>/js/script.js" type="text/javascript"></script>
</body>
</html>