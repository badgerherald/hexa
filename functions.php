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

	$baseArgs = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'order' => 'DESC',
		'posts_per_page' => 20,
	);
	$today = array( 'date_query' => array(
										array(
											'after' => '300 days ago',  // or '-2 days'
											'before' => '299 days ago',  // or '-2 days'
											'inclusive' => true,
										),
									),
								);
	$yesterday = array( 'date_query' => array(
										array(
											'after' => '303 days ago',  // or '-2 days'
											'before' => '302 days ago',  // or '-2 days'
											'inclusive' => true,
										),
									),
								);

	$todayQuery = new WP_Query($baseArgs + $today);
	$yesterdayQuery = new WP_Query($baseArgs + $yesterday);

	
	$t = date( "D", strtotime("Today") );
	$tminus1 = date( "D", strtotime("Yesterday") );


	$ret = "";
	$ret .= "# Editorial Report\n\n";
	$ret .= "Pageviews for content published in the past 2 days\n\n";
	$ret .= "| Post | $tminus1 | $t | Avg&nbsp;Time |\n";
	$ret .= "|:-----|---------:|---:|--------------:|\n";
	$ret .= _hexa_editorial_report_loop( $todayQuery );
	$ret .= "|  |  |  |  |\n";
	$ret .= _hexa_editorial_report_loop( $yesterdayQuery );
	$ret .= "\n";

	

	return $ret;
}

function _hexa_editorial_report_loop($query) {
	$ret = "";

	while ($query->have_posts()) : $query->the_post();

		$tViews = ak_metric(get_the_id(),'ga:pageviews','today') ?: "";
		$tMinus1Views = ak_metric(get_the_id(),'ga:pageviews','yesterday') ?: "";

		$tTop = intval( ak_metric(get_the_id(),'ga:avgTimeOnPage','today') ?: 0 );
		$tMinus1Top = intval( ak_metric(get_the_id(),'ga:avgTimeOnPage','yesterday') ?: 0 );
		$avgTop = "";

		if ( $tTop && $tMinus1Top ) {
			$avgTop = intval( ( $tTop + $tMinus1Top ) / 2 ) . "s";
		} else if ( $tTop + $tMinus1Top  ) {
			$avgTop = ( $tTop + $tMinus1Top ) . "s ";
		}
		
		$title = get_the_title();
		$editLink = "<a href='" . get_edit_post_link() . "'>Edit</a>";
		$viewLink = "<a href='" . get_permalink() . "'>View</a>";
		$links = "<span class='post-links'>&nbsp; $editLink &nbsp; $viewLink</span>";

		$ret .= "| $title $links | $tMinus1Views | $tViews | $avgTop |\n";
		
	endwhile;
	
	return $ret;
}