		<li id="search"> <!-- TODO: Implement bootstrap typeahead, Google search? plugin or something like http://tutorialzine.com/2010/09/google-powered-site-search-ajax-jquery/ -->    	
            <form method="get" action="<?php bloginfo('url'); ?>/">
				<input type="text" name="s" value="<?php the_search_query(); ?>" placeholder="Search"> <?php //name="s" tells wordpress that this is the search field ?>
			</form>			
					<!-- TODO: do we want a submit button? Are we hi-tech enough to know to press enter?
					<input type="submit" value="▶" /> -->
            </li>


					