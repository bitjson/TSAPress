<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post();
	
	//define global IDs for highlighting events in sidebar.php
	global $tsapress_primary_post_id;
	global $tsapress_primary_parent_id;
	
	$tsapress_primary_post_id = $post->ID;
	$tsapress_primary_parent_id = $post->post_parent;
	 ?>

<?php get_template_part( 'inc/contentheader' ); ?>

<?php

//if parent is event, and page is not event, display parent eventinfo
if(tsapress_is_event($post->post_parent) && !tsapress_is_event($post->ID)) tsapress_display_eventinfo($post->post_parent);
//if parent is not event, and page is event, display page eventinfo
else if(!tsapress_is_event($post->post_parent) && tsapress_is_event($post->ID)) tsapress_display_eventinfo($post->ID);

?>
	<article id="post-<?php the_ID(); ?>">

<?php get_template_part( 'inc/childnav' ); ?>

<?php

//if parent is event, and page is event, display page eventinfo
if(tsapress_is_event($post->post_parent) && tsapress_is_event($post->ID)) tsapress_display_eventinfo($post->ID);

?>

<?php get_template_part( 'inc/featuredimage' ); ?>

				<section>
					<?php edit_post_link('Edit'); ?>
					<?php the_content('Read more on "'.the_title('', '', false).'" &raquo;'); ?>
<?php
//Post metadata debug					
//echo '<!--'; print_r(get_post_custom($post->ID));  echo '-->';
?>
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