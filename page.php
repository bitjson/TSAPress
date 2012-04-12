<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<h1><?php 	
				if ( $post->post_parent == 0 ) {
					the_title();
				}
				else{
					echo get_the_title($post->post_parent);
				}
				?></h1>
			
			<article id="post-<?php the_ID(); ?>">
				     <?php
				  
				      /*
				      TODO: Consider allowing for another level of navagation- need to develop display method- maybe drop downs?
				      */
				      
				      
					if($post->post_parent)
					$children = wp_list_pages("depth=1&title_li=&child_of=".$post->post_parent."&echo=0");
					else
					$children = wp_list_pages("depth=1&title_li=&child_of=".$post->ID."&echo=0");
					if ($children) { ?>
						
							<nav id="sub-nav">
								<ul>
									<?php echo $children; ?>
								</ul>
							</nav>	 
				  <?php }
				  
				  /*
				  
				  TODO: make Safari [reader] display the correct title for all pages
				  
				  <header><h1><?php the_title(); ?></h1></header>	
				  
				  */
				  
				  
				   ?>
				</header>
				
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