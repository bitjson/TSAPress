<?php

header("HTTP/1.0 404 Not Found");

get_header(); 


		$search_term = substr($_SERVER['REQUEST_URI'],1);
		$search_term = urldecode(stripslashes($search_term));
				
		$find = array ("'.html'", "'.+/(?!$)'", "'[-/_]'") ;
		$replace = " " ;
		$search_term = trim( preg_replace ( $find , $replace , $search_term ) );
				
		$search_term_q = trim(preg_replace('/ /', '+', $search_term));
		$search_url = rtrim(get_home_url(), "/") . '/search/';
		$full_search_url = $search_url . $search_term_q;


?>

			<article>
				<h1>Error 404 - Not Found</h1>
				<p>Sorry, but we can't seem to find that page. If you think we broke something, please <a href="#contact">send us a message</a> so we can look into the problem.</p>
				<p>You can try to find what you were looking for using the links to the left, view search results for <a href="<?php echo $full_search_url ?>"><?php echo $search_term ?></a>, or try searching below.</p>

<?php 

global $wp_query;
$wp_query->set('s', $search_term_q);

get_search_form(); ?>
			</article>
		

<?php get_sidebar(); ?>

<?php get_footer(); ?>