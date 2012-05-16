<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php get_template_part( 'contentheader' ); ?>
				
			<article id="post-<?php the_ID(); ?>">

<?php get_template_part( 'childnav' ); ?>

<?php get_template_part( 'featuredimage' ); ?>

				<section>
					<?php edit_post_link('Edit'); ?>
					<?php the_content('Read more on "'.the_title('', '', false).'" &raquo;'); ?>

				</section>
				<footer>
					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				</footer>
			</article>

	<?php endwhile; else: ?>

			<article>
				<p>Sorry, no posts matched your criteria.</p>
			</article>
	
	<?php endif; ?>
	  
<?php get_sidebar(); ?>

<?php get_footer(); ?>