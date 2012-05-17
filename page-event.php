<?php
/*
Template Name: Event Page
*/

?>
<?php get_header(); ?>

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<section id="event-info">
<?php 

 

echo get_tsapress_event_datetime_string($post->ID);

	
	
	
	
	$event_location = get_post_meta($post->ID, '_tsapress_event_location', true);
	$event_location_query = get_post_meta($post->ID, '_tsapress_event_google_maps_query', true);
	if ($event_location_query == "") $event_location_query = $event_location;
	
	echo '<a href="http://maps.google.com/maps?q=' . urlencode($event_location_query) . '" target="_blank">' . $event_location . '</a>';
	
	 ?>
</section>

<?php get_template_part( 'contentheader' ); ?>

			<article id="post-<?php the_ID(); ?>">

<?php get_template_part( 'childnav' ); ?>
				
<?php get_template_part( 'featuredimage' ); ?>

				<section>
				
	<?php	/*
	
		TODO:
		
		Option to display event countdown?
		
	
	*/ ?>
	
	<!-- //TODO: remove this dump
	<?php print_r(get_post_custom($post->ID)); ?>
	-->
	

				
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