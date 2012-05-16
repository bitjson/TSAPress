<?php
/*
Template Name: Event Page
*/
?>
<?php get_header(); ?>

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<section id="event-info">
<?php 


/* outputs clean HTML5 <time> or <time> range */
function get_tsapress_pretty_time($begin, $end = false, $include_time = false){

	$output = '<time class="date" datetime="';
	
	//format deadline
	if ($end == false) {
		$output .= gmdate('Y-m-d\TH:i', $begin) . date('P') . '">' . gmdate('M jS, Y g:i A ', $begin). date('T'); //Timezones: Display timezone set via wordpress (tsapress_utils: tsapress_clarify_timezone())
	}
	
	//include hh:mm - hh:mm
	elseif ($include_time){
		
		//same day
		if (gmdate('Y-m-d', $begin) == gmdate('Y-m-d', $end)) {
			$output .= gmdate('Y-m-d\TH:i', $begin) . '">' . gmdate('M jS, Y', $begin) . '<span>' . gmdate('g:ia', $begin) . '-' . gmdate('g:ia', $end) . '</span>';
		}
		//same month
		elseif (gmdate('Y-m', $begin) == gmdate('Y-m', $end)) {
			$output .= gmdate('Y-m-d\TH:i', $begin) . '/' . gmdate('Y-m-d\TH:i', $end) . '">' . gmdate('M jS', $begin) . '&ndash;' . gmdate('jS, Y', $end) . '<span>' . gmdate('g:ia', $begin) . '-' . gmdate('g:ia', $end) . '</span>';
		}
		//same year
		elseif (gmdate('Y', $begin) == gmdate('Y', $end)) {
			$output .= gmdate('Y-m-d\TH:i', $begin) . '/' . gmdate('Y-m-d\TH:i', $end) . '">' . gmdate('M jS', $begin) . '&ndash;' . gmdate('M jS, Y', $end) . '<span>' . gmdate('g:ia', $begin) . '-' . gmdate('g:ia', $end) . '</span>';
		}
		
		
	} else {	
		//same day
		if (gmdate('Y-m-d', $begin) == gmdate('Y-m-d', $end)) {
			$output .= gmdate('Y-m-d', $begin) . '">' . gmdate('M jS, Y', $begin);
		}
		//same month
		elseif (gmdate('Y-m', $begin) == gmdate('Y-m', $end)) {
			$output .= gmdate('Y-m-d', $begin) . '/' . gmdate('Y-m-d', $end) . '">' . gmdate('M jS', $begin) . '&ndash;' . gmdate('jS, Y', $end);
		}
		//same year
		elseif (gmdate('Y', $begin) == gmdate('Y', $end)) {
			$output .= gmdate('Y-m-d', $begin) . '/' . gmdate('Y-m-d', $end) . '">' . gmdate('M jS', $begin) . '&ndash;' . gmdate('M jS, Y', $end);
		}
	}
	
	$output .= '</time>';
	
	return $output; 
		
		
}

switch(get_post_meta($post->ID, '_tsapress_event_datetime_range', true)) {

	case 'datetime':
		$datetime_begin = get_post_meta($post->ID, '_tsapress_event_datetime_begin', true);
		$datetime_end = get_post_meta($post->ID, '_tsapress_event_datetime_end', true);
		echo get_tsapress_pretty_time($datetime_begin, $datetime_end, true);
		break;
		
	case 'date':
		$date_begin = get_post_meta($post->ID, '_tsapress_event_date_begin', true);
		$date_end = get_post_meta($post->ID, '_tsapress_event_date_end', true);
		echo get_tsapress_pretty_time($date_begin, $date_end);
		break;
		
	case 'deadline':
		$deadline_datetime = get_post_meta($post->ID, '_tsapress_event_deadline_datetime', true);
		echo get_tsapress_pretty_time($deadline_datetime);
		break;		
		
	default: //(case 'tbd':)
			echo '<time datetime="TBD">TBD</time>';
		break;
	}
	
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
	
	<!--
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