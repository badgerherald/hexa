<?php


/**
 * ================================================================================================
 *   #region: includes
 * ================================================================================================
 */



/**
 * Load more functions for develop enviornment.
 * 
 * Contents:
 *   - exa_dev_attachment_url()				(filter: wp_get_attachment_url)
 */
include_once('inc/functions/staff-page.php');
include_once('inc/functions/ads.php');
include_once('inc/functions/redirects.php');


/**
 * Enqueue hexa scripts and styles.
 * 
 */
function hexa_scripts() {
    wp_enqueue_style('hexa-style', get_stylesheet_directory_uri().'/style.css', array('exa-style'));
    if (is_page("revelry"))
    {
        wp_enqueue_script("exa-child-revelry-script", get_stylesheet_directory_uri().'/revelry/revelry.js', array('jquery'));
        wp_enqueue_style('hrld-showcase-style');
        wp_enqueue_script( 'hrld-showcase-script-class');
        wp_enqueue_script('exa-hrld-showcase-init', get_template_directory_uri().'/js/hrld-showcase-init.js', array('hrld-showcase-script-class', 'jquery'));
    }
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


