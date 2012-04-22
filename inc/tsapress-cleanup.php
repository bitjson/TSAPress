<?php

/*tsapress-cleanup : derived mostly from Roots HTML5 WordPress Theme*/


/*remove uneccessary functions*/

remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'noindex', 1);

// remove WordPress version from RSS feeds
function roots_no_generator() { return ''; }
add_filter('the_generator', 'roots_no_generator');

//remove localization script (unecessary)
function tsapress_remove_localization_script() {
	if (!is_admin()) {
		wp_deregister_script('l10n');
	}
}
add_action('wp_enqueue_scripts', 'tsapress_remove_localization_script');

// remove CSS from recent comments widget
function roots_remove_recent_comments_style() {
  global $wp_widget_factory;
  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
  }
}

add_action('wp_head', 'roots_remove_recent_comments_style', 1);

// remove CSS from gallery
function roots_gallery_style($css) {
  return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}

add_filter('gallery_style', 'roots_gallery_style');



/*
*	Slightly modified from Roots HTML5 Theme
*	Change: made compatible with WordPress installations in a subdirectory (not in root)
*
*   TODO: assets (wp-content/uploads) not working properly
*/

// rewrite [$base_dir]/wp-content/themes/tsapress/css/ to /css/
// rewrite [$base_dir]/wp-content/themes/tsapress/js/  to /js/
// rewrite [$base_dir]/wp-content/themes/tsapress/img/ to /img/
// rewrite [$base_dir]/wp-content/plugins/ to /plugins/

function roots_flush_rewrites() {
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}

/*
* returns the base directory in which wordpress is aternatively installed followed by a trailing "/"
* if wordpress is in the root directory, returns an empty string
* can be simply appended before a hardcoded directory structure
*/
function tsapress_base_dir(){
	$tsapress_base_dir = trim(next(explode(home_url(), site_url())), "/"); //grabs base directory structure if wordpress is installed in a subdirectory
	if ($tsapress_base_dir != "") $tsapress_base_dir .= "/";
	return $tsapress_base_dir;
}

function tsapress_add_rewrites($content) {
	
	$tsapress_base_dir = tsapress_base_dir();

	$theme_name = next(explode('/themes/', get_stylesheet_directory()));
	global $wp_rewrite;
	$roots_new_non_wp_rules = array(
		'css/(.*)'      => $tsapress_base_dir.'wp-content/themes/'. $theme_name . '/css/$1',
		'js/(.*)'       => $tsapress_base_dir.'wp-content/themes/'. $theme_name . '/js/$1',
		'img/(.*)'      => $tsapress_base_dir.'wp-content/themes/'. $theme_name . '/img/$1',
		'admin/(.*)'      => $tsapress_base_dir.'wp-content/themes/'. $theme_name . '/admin/$1',
		'plugins/(.*)'  => $tsapress_base_dir.'wp-content/plugins/$1'
	);
	
	$wp_rewrite->non_wp_rules += $roots_new_non_wp_rules;
}

 add_action('admin_init', 'roots_flush_rewrites'); // TODO: this is very slow!!! need to find a better place to execute



function tsapress_clean_assets($content) {
	$tsapress_base_dir = tsapress_base_dir();

    $theme_name = next(explode('/themes/', $content));
    $current_path = '/'.$tsapress_base_dir.'wp-content/themes/' . $theme_name;
    $new_path = '';
    $content = str_replace($current_path, $new_path, $content);
    return $content;
}

function tsapress_clean_plugins($content) {
	$tsapress_base_dir = tsapress_base_dir();

    $current_path = '/'.$tsapress_base_dir.'wp-content/plugins';
    $new_path = '/plugins';
    $content = str_replace($current_path, $new_path, $content);
    return $content;
}

add_action('generate_rewrite_rules', 'tsapress_add_rewrites');
if (!is_admin()) {
  add_filter('plugins_url', 'tsapress_clean_plugins');
  add_filter('bloginfo', 'tsapress_clean_assets');
  add_filter('stylesheet_directory_uri', 'tsapress_clean_assets');
  add_filter('template_directory_uri', 'tsapress_clean_assets');
  add_filter('script_loader_src', 'tsapress_clean_plugins');
  add_filter('style_loader_src', 'tsapress_clean_plugins');
}

function roots_root_relative_url($input) {
  $output = preg_replace_callback(
    '!(https?://[^/|"]+)([^"]+)?!',
    create_function(
      '$matches',
      // if full URL is site_url, return a slash for relative root
      'if (isset($matches[0]) && $matches[0] === site_url()) { return "/";' .
      // if domain is equal to site_url, then make URL relative
      '} elseif (isset($matches[0]) && strpos($matches[0], site_url()) !== false) { return $matches[2];' .
      // if domain is not equal to site_url, do not make external link relative
      '} else { return $matches[0]; };'
    ),
    $input
  );
  return $output;
}

// workaround to remove the duplicate subfolder in the src of JS/CSS tags
// example: /subfolder/subfolder/css/style.css
function roots_fix_duplicate_subfolder_urls($input) {
  $output = roots_root_relative_url($input);
  preg_match_all('!([^/]+)/([^/]+)!', $output, $matches);
  if (isset($matches[1]) && isset($matches[2])) {
    if ($matches[1][0] === $matches[2][0]) {
      $output = substr($output, strlen($matches[1][0]) + 1);
    }
  }
  return $output;
}

if (!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
  add_filter('bloginfo_url', 'roots_root_relative_url');
  add_filter('theme_root_uri', 'roots_root_relative_url');
  add_filter('stylesheet_directory_uri', 'roots_root_relative_url');
  add_filter('template_directory_uri', 'roots_root_relative_url');
  add_filter('script_loader_src', 'roots_fix_duplicate_subfolder_urls');
  add_filter('style_loader_src', 'roots_fix_duplicate_subfolder_urls');
  add_filter('plugins_url', 'roots_root_relative_url');
  add_filter('the_permalink', 'roots_root_relative_url');
  add_filter('wp_list_pages', 'roots_root_relative_url');
  add_filter('wp_list_categories', 'roots_root_relative_url');
  add_filter('wp_nav_menu', 'roots_root_relative_url');
  add_filter('the_content_more_link', 'roots_root_relative_url');
  add_filter('the_tags', 'roots_root_relative_url');
  add_filter('get_pagenum_link', 'roots_root_relative_url');
  add_filter('get_comment_link', 'roots_root_relative_url');
  add_filter('month_link', 'roots_root_relative_url');
  add_filter('day_link', 'roots_root_relative_url');
  add_filter('year_link', 'roots_root_relative_url');
  add_filter('tag_link', 'roots_root_relative_url');
  add_filter('the_author_posts_link', 'roots_root_relative_url');
}

// remove root relative URLs on any attachments in the feed
function roots_root_relative_attachment_urls() {
  if (!is_feed()) {
    add_filter('wp_get_attachment_url', 'roots_root_relative_url');
    add_filter('wp_get_attachment_link', 'roots_root_relative_url');
  }
}

add_action('pre_get_posts', 'roots_root_relative_attachment_urls');
