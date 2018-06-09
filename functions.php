<?php

global $AnalyticBridge;

/**
 * Includes
 */

include( dirname( __FILE__ ) . '/inc/functions/staff-page.php');
include( dirname( __FILE__ ) . '/inc/functions/ads.php');
include( dirname( __FILE__ ) . '/inc/functions/redirects.php');
include( dirname( __FILE__ ) . '/inc/functions/header-charm.php');
include( dirname( __FILE__ ) . '/inc/functions/plugin-wp_nav_menu_extended.php');
include( dirname( __FILE__ ) . '/inc/functions/admin.php');
include( dirname( __FILE__ ) . '/inc/functions/wiki.php');
include( dirname( __FILE__ ) . '/inc/functions/user-management.php');
include( dirname( __FILE__ ) . '/inc/functions/dirty-bird.php');


/**
 * This is to fix a problem somewhere in our stack. From what I can tell
 * the php process/worker is never told it's running https. Basically
 * 
 * $_SERVER['https']='on'; should be set but never is.
 * 
 * Well, this will fix that I guess.
 * 
 */
function _hexa_enforce_https_in_template_urls($url) {
	if (strpos($url,"badgerherald.com") && !strpos($url,"staging.badgerherald.com")) {
		return preg_replace("/^http:/i", "https:", $url);
	}
	return $url;
}
add_filter('stylesheet_directory_uri','_hexa_enforce_https_in_template_urls');
add_filter('template_directory_uri','_hexa_enforce_https_in_template_urls');

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
	$yesterday = array( 'date_query' => array(
										array(
											'after' => 'yesterday',  // or '-2 days'
											'before' => 'today',  // or '-2 days'
											'inclusive' => true,
										),
									),
								);
	$today = array( 'date_query' => array(
										array(
											'after' => 'today',  // or '-2 days'
											'before' => 'now',  // or '-2 days'
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