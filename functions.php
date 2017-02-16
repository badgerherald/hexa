<?php

global $AnalyticBridge;

/**
 * Includes
 */
include_once('inc/functions/staff-page.php');
include_once('inc/functions/ads.php');
include_once('inc/functions/redirects.php');
include_once('inc/functions/header-charm.php');
include_once('inc/functions/plugin-wp_nav_menu_extended.php');
include_once('inc/functions/admin.php');
include_once('inc/functions/wiki.php');

/**
 * Enqueue hexa scripts and styles.
 */
function hexa_scripts() {
	$mtime = filemtime(dirname(__FILE__) . '/js/hexa.js') ?: "";	
	wp_enqueue_script('hexa-script', get_stylesheet_directory_uri() . '/js/hexa.js',array('jquery'),$mtime,true);

    $mtime = filemtime(dirname(__FILE__) . '/style.css') ?: "";
    wp_enqueue_style('hexa-style', get_stylesheet_directory_uri().'/style.css', array('exa-style'),$mtime);
}
add_action('wp_enqueue_scripts', 'hexa_scripts');

/**
 * Filter banter container classes
 */
function hexa_banter_container_classes($classes,$container) {
	global $post;
	if($container->name == "headline" && hexa_is_banter()) {
		$classes .= " banter-headline";
	}
	return $classes;
}
add_filter("exa_container_classes","hexa_banter_container_classes",10,2);


function hexa_register_ad_menu() {
    register_nav_menu( 'ad-nav', __( 'Advertising Menu', 'hexa' ) );
}
add_action( 'after_setup_theme', 'hexa_register_ad_menu' );

function hexa_editorial_report() {

	global $AnalyticBridge;

	$ret = "# Editorial Report\n\n";

	$ret .= "Pageviews for content published in the past 3 days\n\n";

	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'order'=>'DESC',
		'posts_per_page' => 200,
		'date_query' => array(
     		array(
        	'after'     => 'midnight 3 days ago',  // or '-2 days'
         	'inclusive' => true,
     		),
 		),
	);
	$the_query = new WP_Query($args);

	$ret .= "| y | t | title |\n";
	$ret .= "|--:|--:|-------|\n";
	while ($the_query->have_posts()) : $the_query->the_post();
		$tPageviews = $AnalyticBridge->metric(get_the_id(),'ga:pageviews') ?: 0;
		$yPageviews = $AnalyticBridge->metric(get_the_id(),'ga:pageviews','yesterday') ?: 0;
		$title = get_the_title();
		$ret .= "| $yPageviews | $tPageviews | $title |\n";
	endwhile;
	$ret .= "\n";

	return $ret;
}