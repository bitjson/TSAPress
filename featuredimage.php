				<figure>
        			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" alt="<?php the_post_thumbnail_meta('alt'); ?>"><?php the_post_thumbnail(); ?></a>
        			<?php if (get_post_thumbnail_meta('caption')) : ?><figcaption><?php the_post_thumbnail_meta('caption'); ?></figcaption><?php endif; ?>
				</figure>