<?php get_header(); ?>
	
<?php get_template_part( 'inc/contentheader' ); ?>
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>">
			<?php  edit_post_link('Edit'); ?>
				<header>
					<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
					<small>Posted on <?php the_time('F jS, Y'); ?> by <?php the_author(); ?></small>
				</header>

<?php get_template_part( 'inc/featuredimage' ); ?>

				<section>
					<?php the_content('Read more on "'.the_title('', '', false).'" &raquo;'); ?>
				</section>
				<footer>
					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

					<small>
						<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>

						<p>This entry was posted on <?php the_time('l, F jS, Y'); ?> at <?php the_time(); ?> and is filed under <?php the_category(', ') ?>. 
						You can follow any responses to this entry through the <?php post_comments_feed_link('RSS 2.0'); ?> feed.

						<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// both comments and pings open ?>
							You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.

						<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// only pings are open ?>
							Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

						<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// comments are open, pings are not ?>
							You can skip to the end and leave a response. <?php //Pinging is currently not allowed. ?>

						<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// neither comments nor pings are open ?>
							<?php //Both comments and pings are currently closed. ?>

						<?php } ?>

					</small>
				</footer>
			</article>

<?php comments_template(); ?>

<?php get_template_part( 'inc/pagination' ); 
			/*
			
			
			?>
			
			<nav>
				<p><?php previous_post_link(); ?> &bull; <?php next_post_link(); ?></p>
			</nav>

	<?php */ endwhile; else: ?>

			<article>
				<p>Sorry, no posts matched your criteria.</p>
			</article>

	<?php endif; ?>

<?php get_sidebar(); ?>	 
  
<?php get_footer(); ?>