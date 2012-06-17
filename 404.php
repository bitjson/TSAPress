<?php 

header("HTTP/1.0 404 Not Found");

//TODO: fix this page design up!! see search.php

get_header(); 

$emailadmin_brokenlinks = false;

//email the administrator about the broken link / missing page
if($emailadmin_brokenlinks) {
	if(isset($_SERVER['HTTP_REFERER'])) {
		$adminemail = get_option('admin_email');
		$website = get_bloginfo('url');
		$websitename = get_bloginfo('name');
		$message = "Hey,\n\nThis is your TSAPress website, $website. Someone just tried to go to $website" . $_SERVER['REQUEST_URI'] ." and I couldn't find anything there. I didn't know what to do, so I just sent them a 404 Page Not Found error. Sorry. :(\n\n";
		$message .= "They clicked a bad link from ".$_SERVER['HTTP_REFERER'].".\n\n";
		$message .= "If the link is something we can fix, could you do that?\n\n";
		$message .= "If you have a lot of trouble, there are lots of great WordPress Plugins that you can use to redirect 404 errors, just login and go to *Plugins*. Then you can search for and add new plugins straight from the WordPress plugin site!\n\n";
		$message .= "Thanks! :)\n$websitename WordPress";
		
		mail($adminemail, "Bad Link To ".$_SERVER['REQUEST_URI'], $message, "From: $websitename <noreply@$website>"); //send email
	}
}


		$search_term = substr($_SERVER['REQUEST_URI'],1);
		$search_term = urldecode(stripslashes($search_term));
				
		$find = array ("'.html'", "'.+/(?!$)'", "'[-/_]'") ;
		$replace = " " ;
		$search_term = trim( preg_replace ( $find , $replace , $search_term ) );
				
		$search_term_q = trim(preg_replace('/ /', '+', $search_term));
		$search_url = rtrim(get_home_url(), "/") . '/search/';
		$full_search_url = $search_url . $search_term_q;

		global $wp_query;
		$wp_query->set('s', $search_term_q); //change the search term to display the parsed text from the broken url in the search box

?>
				<h1 class="current">Error 404 <small>Not Found</small></h1>
			<article>
				<p>So this is awkward, but we can't seem to find the page you're looking for…</p>
				<p><?php
				 if(isset($_SERVER['HTTP_REFERER'])) echo "It looks like the link you clicked was broken- it's probably our fault- we're really sorry. Our administrator has already been notified, and we promise we're trying to fix it.";
				 else echo "Either the address up there is mistyped, or we accidentally moved the content you're looking for (Sorry!). If you think we broke something, please <a href=\"#contact\">contact us</a> so we can fix it."; 
				 ?></p>
				<p>Perhaps you could try to find what you're looking for in our <a href="<?php echo $full_search_url ?>">search results for <?php echo "&ldquo;$search_term&rdquo;" ?></a> or by searching below.</p>
				<aside>
					<h1>How about a search?</h1>
					<p>You could try searching this site for <a href="<?php echo 'http://www.google.com/search?q=' . get_search_query() . '&sitesearch=' . urlencode (get_home_url()); ?>">&ldquo;<?php the_search_query(); ?>&rdquo; using Google</a>, or try the form below.</p>
					<?php get_search_form(); ?>
				</aside>


			</article>
		

<?php get_sidebar(); ?>

<?php get_footer(); ?>