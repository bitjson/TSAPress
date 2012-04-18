<?php			
			if(is_single()) { ?>
			
			
			
			<nav id="pagination">
				<span class="next"><?php previous_post_link('%link');?></span><span class="prev"><?php next_post_link('%link'); ?></span> <?php // '%link' prevents directional arrows from being appended to title ?>
			</nav>
			
			<?php } else { ?>
			
			<nav id="pagination">
				<span class="next"><?php previous_posts_link('Newer Posts');?></span><span class="prev"><?php next_posts_link('Older Posts'); ?></span>
			</nav>
			
			<?php }