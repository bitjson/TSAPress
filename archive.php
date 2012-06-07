<?php get_header(); ?>

<?php get_template_part( 'inc/catagoryselector' ); ?>

<?php get_template_part( 'inc/contentheader' ); ?>
		
<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>">
			<?php edit_post_link('Edit'); ?> 
				<header>
					<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
					<small>By <a href="<?php the_author_link(); ?>" rel="author"><?php the_author(); ?></a> | <time datetime="<?php the_time('Y-m-d'); ?>" pubdate><?php the_time('M j, Y'); ?></time> | <?php the_category(' &bull; '); ?></small>
				</header>

<?php get_template_part( 'inc/featuredimage' ); ?>
			
				<section>					
					<?php the_content('Read More <span> -' . the_title('', '', false) . '</span>'); ?>

				</section>
			</article>

			<?php endwhile; ?>

<?php get_template_part( 'inc/pagination' ); ?>

			<?php else : ?>

			<article>																			<?php //TODO: fix this up? ?>
				<h1>Not Found</h1>
				<p>Sorry, but the requested resource was not found on this site.</p>
<?php get_search_form(); ?>
			</article>

<?php endif; ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
