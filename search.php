<?php get_header(); ?>

	<h1>Search Results <small>for &ldquo;<?php the_search_query(); ?>&rdquo;</small></h1>

			<?php if (have_posts()) : ?>

				<ol>
					<?php while (have_posts()) : the_post(); ?>

					<li>
						<article id="post-<?php the_ID(); ?>">
						<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php highlighted_title(); ?></a></h1>
						<blockquote><?php //the_content('Read More <span> -' . the_title('', '', false) . '</span>'); ?><?php highlighted_excerpt(get_the_excerpt()); ?></blockquote>
						</article>
					</li>

					<?php endwhile; ?>

				</ol>
				<aside>
					<h1>Can't find what you're looking for?</h1>
					<p>Try making your search less specific, or search for <a href="<?php echo 'http://www.google.com/search?q=' . get_search_query() . '&sitesearch=' . urlencode (get_home_url()); ?>">&ldquo;<?php the_search_query(); ?>&rdquo; on Google</a>.</p>
					<?php get_search_form(); ?>
				</aside>
			
			<nav id="pagination">
				<span class="next"><?php previous_posts_link('Newer Posts');?></span><span class="prev"><?php next_posts_link('Older Posts'); ?></span>
			</nav>

			<?php else : ?>

			<article>
				<h1>Not Found</h1>
				<aside>
					<h1>Sorry, but we can't seem to find what you're looking for.</h1>
					<p>You could make your search less specific, or you could try searching for <a href="<?php echo 'http://www.google.com/search?q=' . get_search_query() . '&sitesearch=' . urlencode (get_home_url()); ?>">&ldquo;<?php the_search_query(); ?>&rdquo; on Google</a>.</p>
					<?php get_search_form(); ?>
				</aside>
			</article>

			<?php endif; ?>

	
<?php get_sidebar(); ?>

<?php get_footer(); ?>