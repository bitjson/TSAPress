
</div> <?php //close div class="full-wrap" ?>

 <div id="basement">
    <div>
       <div>
       <div class="basement-line">
       <a href="#TSA-Bar" class="back-to-top">Back to Top</a>
    
    <?php /* Cut for AHSTSA.org */ ?>
    
    <section class="events">
    <?php 

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
		<li><a href="<?php the_permalink() ?>"><?php the_title(); ?><span><?php echo get_tsapress_event_datetime_string($post->ID, 'short'); ?></span></a></li>
  <?php endforeach; ?>
  </ol>
  <?php else : ?>
    <h1 class="center">No Events</h1>
    <p class="center">Sorry, there are no events yet.</p>
 <?php endif; ?>
    

<?php	/* 
    
    	<h1>Conferences &amp; Events</h1>
	    <ol>
		    <li><a href="#">Fall Rallies 
		    <span><time datetime="2012-10-08">10/8</time></span></a></li>
		    <li><a href="#">State Leadership Academy
		    <span><time datetime="2012-11-06">11/6</time> - <time datetime="2012-11-08">11/8</time></span></a></li>
		    <li><a href="#">Regional Conferences
		    <span><time datetime="2013-03-10">3/10</time></span></a></li>
		    <li><a href="#">Technosphere 2013
		    <span><time datetime="2012-05-29">5/29</time> - <time datetime="2012-06-03">6/3</time></span></a></li>
	    <li><a href="#">Technosphere 2013 (II)
		    <span><time datetime="2012-05-29">5/29</time> - <time datetime="2012-06-03">6/3</time></span></a></li>
  		</ol>
*/ ?>




    	<a href="#">More important dates</a> <!-- TODO:reveal -->
    </section>
    
    <section class="updates">
    	<h1>Latests Updates</h1>
	    <ol>
		    <li><a href="#">Regional Fall Rallies</a></li>
		    <li><a href="#">Leadership Academy</a></li>
		    <li><a href="#">Regional Conferences</a></li>
		    <li><a href="#">Technosphere 2012</a></li>
		    <li><a href="#">Another Test</a></li>
   		</ol>
    	<a href="#">Previous updates</a>
    </section>
    
    <section  class="archives">
    	<h1>News Archives</h1>
	    <time datetime="2012">
	    	<span>2012:</span>
	    <ul>
	    	<li><a href="#"><time datetime="2012-01">Jan</time></a></li>
	    	<li><a href="#"><time datetime="2012-02">Feb</time></a></li>
	    	<li><a href="#"><time datetime="2012-03">Mar</time></a></li>
	    	<li><a href="#"><time datetime="2012-04">Apr</time></a></li>
	    	<li><a href="#"><time datetime="2012-05">May</time></a></li>
	    	<li><a href="#"><time datetime="2012-06">June</time></a></li>
	    </ul>
	    </time>
	    <time datetime="2011">
	    <span>2011:</span>
	    <ul>
	    	<li><a href="#"><time datetime="2012-01">Jan</time></a></li>
	    	<li><a href="#"><time datetime="2012-02">Feb</time></a></li>
	    	<li><a href="#"><time datetime="2012-03">Mar</time></a></li>
	    	<li><time datetime="2012-04">Apr</time></li>
	    	<li><a href="#"><time datetime="2012-05">May</time></a></li>
	    	<li><a href="#"><time datetime="2012-06">June</time></a></li>
	    	<li><time datetime="2012-07">July</time></li>
	    	<li><a href="#"><time datetime="2012-08">Aug</time></a></li>
	    	<li><a href="#"><time datetime="2012-09">Sept</time></a></li>
	    	<li><a href="#"><time datetime="2012-10">Oct</time></a></li>
	    	<li><a href="#"><time datetime="2012-11">Nov</time></a></li>
	    	<li><time datetime="2012-12">Dec</time></li>
	    </ul>
	    </time>
	    <time datetime="2010">
	    <span>2010:</span>
	    <ul>
	    	<li><a href="#"><time datetime="2012-01">Jan</time></a></li>
	    	<li><a href="#"><time datetime="2012-02">Feb</time></a></li>
	    	<li><a href="#"><time datetime="2012-03">Mar</time></a></li>
	    	<li><time datetime="2012-04">Apr</time></li>
	    	<li><a href="#"><time datetime="2012-05">May</time></a></li>
	    	<li><a href="#"><time datetime="2012-06">June</time></a></li>
	    	<li><time datetime="2012-07">July</time></li>
	    	<li><time datetime="2012-08">Aug</time></li>
	    	<li><a href="#"><time datetime="2012-09">Sept</time></a></li>
	    	<li><a href="#"><time datetime="2012-10">Oct</time></a></li>
	    	<a href="#"><time datetime="2012-11">Nov</time></a></li>
	    	<li><a href="#"><time datetime="2012-12">Dec</time></a></li>
	    </ul>	
	    </time>
    	<a href="#">Full Archives</a>
    </section>
    
    <section class="contact"> <!-- TODO: expand contact form on :focus for ease of use (fill basement?) -->
    	<h1>Contact Us</h1>
    	<!-- <p>Please leave us a message, and we'll get back to soon!</p> -->
		<form method="post" action="#">
			<input type="text" id="name" name="name" value="" placeholder="Name" required="required" />  
			<input type="email" id="email" name="email" value="" placeholder="Email" required="required" /> 
			<textarea id="message" name="message" placeholder="Questions? comments?" required="required" data-minlength="20"></textarea>   
			<input type="submit" value="Send" id="submit-button" /> 
		</form>
		<address>
		Virginia State University<br>
		PO Box 9045<br>
		Petersburg, Virginia 23806<br>
		phone: (804) 524-4449 | fax: (804) 524-6807<br>
		<a href="mailto:tsa@vatsa.org">tsa@vatsa.org</a>
		</address>
    </section>
    
    <section id="partners">
    <h1>National TSA Partners</h1>
    <img src="<?php bloginfo('template_directory'); ?>/mock-content/national-tsa-partners.png" alt="National TSA Partners" height="100px" />
    </section>
    
    
   <?php /* end cut */?>
    
    
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