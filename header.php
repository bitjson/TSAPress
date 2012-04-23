<?php

if($_SERVER['REQUEST_URI'] == "/login" || $_SERVER['REQUEST_URI'] == "/login/" ) Header( 'Location: ' . get_admin_url() ); //redirect [site]/login to login screen

?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->

<html class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->

	<head>

		<meta charset=<?php bloginfo('charset'); ?>>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		
		<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php //TODO: more meta? ?>
		

		<link rel="stylesheet/less" href="<?php bloginfo('template_directory'); ?>/css/style.less">    
	    <script src="<?php bloginfo('template_directory'); ?>/js/libs/less-1.2.1.min.js" type="text/javascript"></script>
		<?php /*
		
		Render and minify CSS and replace LESS with:
		
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen">
		
		
		   <!-- todo: feedburner feeds -->
		   <!-- todo: rel="prev" & rel="next" -->
		   <!-- todo: feedburner feeds -->
		   <!-- todo: apple icons, windows pin, favicon -->
		   <!-- todo: copy Jason Dreyzehner's smooth scroll from s184.neoarx.com -->
		
		
		*/ ?>
		
		
		<link rel="alternate" type="text/xml" title="<?php bloginfo('name'); ?> RSS 0.92 Feed" href="<?php bloginfo('rss_url'); ?>">
		<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>">
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		
		
		<script src="<?php bloginfo('template_directory'); ?>/js/libs/modernizr-2.5.3-respond-1.1.0.min.js" type="text/javascript"></script>


<?php wp_head(); ?>


	</head>
	<body <?php body_class(); ?>>
	<div id="page-wrap">


   <!--[if lt IE 7]><p class=chromeframe>Your browser is somewhat <em>outdated</em>! <a href="http://browsehappy.com/">Please upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to view the internet in all its glory.</p><![endif]-->

    <div id="TSA-Bar"> <!-- TODO: snap bar to top with larger window dimensions (past certain height) -->
    
    <span class="full-wrap">
    
    
        <a href="<?php bloginfo('url'); ?>/" title="Home" id="root"><img src="<?php bloginfo('template_directory'); ?>/img/TSA-emblem-icon.png" alt="Technology Student Association Emblem">Technology Student Association</a> <!-- TODO: on hover, background more opaque image? -->
			<ul>
			
			
            
            <li id="share">
            
                <a href="#">Share</a>
                <div class="hover-box">
              	  	<div>
                	    <p>Sharing options</p>
                    </div>
                </div>
            </li>

<?php get_template_part( 'stateselect' ); ?>

<?php get_search_form(); ?>       	
            	            
            <li id="RSS"><a title="<?php bloginfo('name'); ?> RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>">&nbsp;</a></li> <!-- TODO: sprite this -->
            
        </ul>
        
        </span>
        
    </div>
    
   <div class="full-wrap">
    <aside id="about">
       <div><a href="<?php bloginfo('url'); ?>" id="emblem">
       <img src="<?php
       	
       	$state_emblem_url = tsapress_clean_uploads(of_get_option('state_emblem'));
       
        	if (trim($state_emblem_url) == '') {
      			echo get_bloginfo('template_directory') . '/img/TSA-emblem.png'; //state_emblem was previously set but currently has no value
        	} else {	
         		echo $state_emblem_url;
         	} 
        
        ?>" alt="State TSA Emblem"></a></div> <!-- TODO: emblem hover effect - glow fade in/out? -->
       <div id="callout">
        <h1>We are <em><?php echo of_get_option('total_members', '5,000'); ?> students</em>, in <em><?php echo of_get_option('total_schools', '100'); ?> schools</em> across <?php echo of_get_option('state_name', 'the state'); ?> who believe that technology is the key to a better world.</h1>
        <p>We prepare our members to thrive in a technical world through competitive events, networking, and leadership opportunities.</p><a href="#">Check out our story</a>
        </div>
    </aside>

 <div class="content-wrap">
        <section id="content">
