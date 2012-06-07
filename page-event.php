<?php
/*
Template Name: Event Page
*/

?>
<?php get_header(); ?>

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php get_template_part( 'inc/contentheader' ); ?>

<aside id="event-info">
<?php 
	//Event Summary
	$event_summary = get_post_meta($post->ID, '_tsapress_event_summary', true);
	if ($event_summary != "") echo '<p class="summary">'. $event_summary .'</p>';
	
	//Event Date/time
	echo get_tsapress_event_datetime_string($post->ID);

	//Event Location
	$event_location = get_post_meta($post->ID, '_tsapress_event_location', true);
	if ($event_location != "") {	
		$event_location_query = get_post_meta($post->ID, '_tsapress_event_google_maps_query', true);
		if ($event_location_query == "") echo '<span class="location">' . $event_location . '</span>';
		else echo '<a href="http://maps.google.com/maps?q=' . urlencode($event_location_query) . '" class="location" target="_blank">' . $event_location . '</a>';
	}
	
	echo '<p class="clear"></p>';
?>
</aside>

			<article id="post-<?php the_ID(); ?>">

<?php get_template_part( 'inc/childnav' ); ?>
				
<?php get_template_part( 'inc/featuredimage' ); ?>

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