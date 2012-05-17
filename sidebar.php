	 <?php /* TODO: widgetize <article class="widget"> </article>   <article class="widget"> </article>   */  ?>
	 
		 </section> <?php //close section#content ?>
	</div> <?php //close div.content-wrap ?>
	
	<?php
	
	//create reference to menus (to grap menu titles)
	$all_menu_locations = (array) get_nav_menu_locations();
	$primary_nav_menu = get_term_by( 'id', (int) $all_menu_locations[ 'primary-nav' ], 'nav_menu', ARRAY_A );
	$events_nav_menu = get_term_by( 'id', (int) $all_menu_locations[ 'events-nav' ], 'nav_menu', ARRAY_A );
	
	 ?>
	
	<nav id="left-nav"><?php 
		if ( has_nav_menu('primary-nav') ) : ?>
			<h1><?php echo $primary_nav_menu[ 'name' ]; ?></h1>
				<ul>
<li<?php if ( is_home() ) { echo ' class="current_page_item"'; }?>><a href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('name'); ?> News">News</a></li>
<?php wp_nav_menu( array( 'items_wrap' => '%3$s', 'theme_location' => 'primary-nav', 'container' => '', 'depth' => 1, ) ); ?>
				</ul>	
		<? else : ?>
				<h1><?php bloginfo('name'); ?> (auto)</h1>			
<?php wp_page_menu( array ('depth' => '1', 'title_li' => '', 'show_home' => 'News' ) ); ?>								
		<? endif; ?>
				
<?php 

$querystr = "SELECT wposts.*
FROM ".$wpdb->posts." AS wposts
INNER JOIN ".$wpdb->postmeta." AS wpostmeta
ON wpostmeta.post_id = wposts.ID
AND wpostmeta.meta_key = '_tsapress_event_major_event'
AND wpostmeta.meta_value = 'on'";

 $primary_events = $wpdb->get_results($querystr, OBJECT);
		
 if ($primary_events): ?>
 <h1>Events</h1>		
	<ul>
  <?php global $post; ?>
  <?php foreach ($primary_events as $post): ?>
    <?php setup_postdata($post); ?>
<li><a href="<?php the_permalink() ?>"><?php the_title(); ?><span><?php echo get_tsapress_event_datetime_string($post->ID); ?></span></a></li>
  <?php endforeach; ?>
  	</ul>
  <?php else : ?>
    <h1 class="center">No Events</h1>
    <p class="center">Sorry, there are no events yet.</p>
 <?php endif; ?>

			
<?php if ( function_exists('dynamic_sidebar') )  dynamic_sidebar(); /* simply adds on below other menus */ ?> 
			
	</nav> <?php //close nav#left-nav ?>
</div> <?php //close div.full-wrap ?>