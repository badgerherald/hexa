<?php

/**
 * Includes
 */
include_once('inc/functions/staff-page.php');
include_once('inc/functions/ads.php');
include_once('inc/functions/redirects.php');
include_once('inc/functions/header-charm.php');
include_once('inc/functions/plugin-wp_nav_menu_extended.php');
include_once('inc/functions/admin.php');

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
