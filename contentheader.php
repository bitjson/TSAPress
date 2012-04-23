<?php

if	(is_page()) {

	if ( $post->post_parent == 0 ) echo '<h1 class="current">' . get_the_title() . '</h1>';
	else echo '<a href="' . get_permalink($post->post_parent) . '"><h1>' . get_the_title($post->post_parent) . '</h1></a>';
	
} else {

	echo '<h1 class="current">';
	
	if		(is_home() || is_single())	echo 'News'; 	
	elseif 	(is_search()) echo 'Search Results <small>Page ' . tsapress_get_current_page_number() . ' for &ldquo;' . get_search_query() . '&rdquo;</small>';
	elseif 	(is_archive()) 
	{
		if (have_posts()) { 
			$post = $posts[0]; // hack: set $post so that the_date() works 
			
			if 		(is_category()) 	single_cat_title(); 
			elseif	(is_tag()) 			echo 'News Tagged &ldquo;' . single_tag_title() . '&rdquo;';
			elseif 	(is_day()) 			echo 'News Archive for ' . the_time('F jS, Y');
			elseif 	(is_month()) 		echo 'News Archive for ' . the_time('F, Y');
			elseif 	(is_year()) 		echo 'News Archive for ' . the_time('Y');
			elseif 	(is_author()) 		echo 'Author Archive';
			
			elseif (isset($_GET['paged']) && !empty($_GET['paged'])) echo 'News Archives';
		}
	}
	
	echo '</h1>';	
}
