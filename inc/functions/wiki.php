<?php

/**
 *
 */
function hexa_wiki_init() {
	/* Register our script. */
	wp_register_script( '_hexa_wiki_script', get_stylesheet_directory_uri() . '/js/admin/wiki.js' , array( 'jquery' ), 1, false );
	wp_register_style(  '_hexa_wiki_style' , get_stylesheet_directory_uri() . '/css/admin/wiki.css' );
}
add_action( 'admin_init', 'hexa_wiki_init' );

/**
 * @author Will Haynes
 */
function _hexa_writers_plugin_enqueue() {
	wp_enqueue_script( '_hexa_wiki_script' );
	wp_enqueue_style( '_hexa_wiki_style' );
}

/**
 * 
 */
function _hexa_writers_admin_menu() {
	$page_hook_suffix = add_submenu_page( 'index.php', 'Wiki', 'Wiki', 'edit_posts', 'find_writers', 'hexa_wiki_content' );
	add_action('admin_print_scripts-' . $page_hook_suffix, '_hexa_writers_plugin_enqueue');
}
add_action( 'admin_menu', '_hexa_writers_admin_menu' );

/**
 *
 */
function hexa_wiki_content() {
	if ( !current_user_can( 'edit_posts' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	echo "<div class='wrap'>";
	echo "<h2>Wiki</h2>";
	echo "<p>This is our new wiki!</p>";
	echo "</div>"; // wrap.
}
