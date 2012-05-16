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