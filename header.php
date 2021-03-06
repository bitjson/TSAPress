<?php

if($_SERVER['REQUEST_URI'] == "/login" || $_SERVER['REQUEST_URI'] == "/login/" ) Header( 'Location: ' . get_admin_url() ); //redirect [site]/login to login screen

tsapress_process_contact_form();

?>

<!DOCTYPE html>

<!--[if lt IE 9]><html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->

	<head>

		<meta charset=<?php bloginfo('charset'); ?>>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		
		<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
		<meta name="description" content="<?php bloginfo('description'); ?>">
		<?php //TODO: more meta? ?> 		
 		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/style.css" />
		
		<?php 
		/*
		
		Render and minify CSS and replace LESS with:
		
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen">
		
		
		   <!-- todo: feedburner feeds -->
		   <!-- todo: rel="prev" & rel="next" -->
		   <!-- todo: feedburner feeds -->
		   <!-- todo: apple icons, windows pin, favicon -->
		   <!-- todo: smooth scroll to # links -->
		
		
		*/ ?>
		<link rel="alternate" type="text/xml" title="<?php bloginfo('name'); ?> RSS 0.92 Feed" href="<?php bloginfo('rss_url'); ?>">
		<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>">
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">	
<?php
	if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) //if user agent is iPod or iPhone - saves bandwidth for other devices
	{
?>		
		<link rel="apple-touch-icon" sizes="144x144" href="<?php bloginfo('template_directory'); ?>/img/os/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('template_directory'); ?>/img/os/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('template_directory'); ?>/img/os/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="<?php bloginfo('template_directory'); ?>/img/os/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/img/os/apple-touch-icon.png" />
<?php } ?>		
<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	<div id="page-wrap">
   <!--[if lt IE 9]><p class=chromeframe><em>Oh no!</em> Your browser is outdated. <a href="http://www.google.com/chromeframe/">Get Chromeframe</a> <em>in just seconds</em> to view the internet <em>faster</em>, <em>safer</em>, and in all its <em>glory</em>.</p><![endif]-->
    <div id="TSA-Bar">  
    <span class="full-wrap">
    
    
        <a href="<?php echo home_url(); ?>" title="Home" id="root"><?php /* <img src="<?php bloginfo('template_directory'); ?>/img/TSA-emblem-icon.png" alt="Technology Student Association Emblem"> */ ?>Technology Student Association</a> <!-- TODO: on hover, background more opaque image? -->
			<ul>
			
<?php			
/*    TODO: Sharing Options        
            <li id="share">
            
                <a href="#">Share</a>
                <div class="hover-box">
              	  	<div>
                	    <p>Sharing options</p>
                    </div>
                </div>
            </li>
*/        
?>

<?php // TODO: develop state select menu updater, deploy after several states have launched
	// get_template_part( 'inc/stateselect' ); ?>

<?php get_search_form(); ?>       	
            	            
            <li id="RSS"><a title="<?php bloginfo('name'); ?> RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>">&nbsp;</a></li>      
        </ul>
     </span>   
    </div>
    
   <div class="full-wrap">
    <aside id="about">
       <div><a href="<?php echo home_url(); ?>" id="emblem">
       <img src="<?php
       	
       	$state_emblem_url = tsapress_clean_uploads(of_get_option('state_emblem'));
       
        	if (trim($state_emblem_url) == '') {
      			echo get_bloginfo('template_directory') . '/img/tsa-emblem.png'; //state_emblem was previously set but currently has no value
        	} else {	
         		echo $state_emblem_url;
         	} 
        
        ?>" alt="State TSA Emblem" width="175px" height="105px"></a></div> <!-- TODO: emblem hover effect - glow fade in/out? -->
       <div id="callout">
       <?php if(of_get_option('our_story_content', "") == "" ) { ?>
        <h1>We are <em><?php echo of_get_option('total_members', '5,000'); ?> students</em>, in <em><?php echo of_get_option('total_schools', '100'); ?> schools</em> across <?php echo of_get_option('state_name', 'the state'); ?> who believe that technology is the key to a better world.</h1>
        <p>We prepare our members to thrive in a technical world through competitive events, networking, and leadership opportunities.</p>
        <?php } else { echo of_get_option('our_story_content'); } ?>
<?php

$our_story_url = of_get_option('our_story_url');

if ($our_story_url != false) { ?>
        <a href="<?php echo $our_story_url; ?>">Check out our story</a>
<?php } ?>
        </div>
    </aside>

 <div class="content-wrap">
        <section id="content">
