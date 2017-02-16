<?php

include_once( 'lib/Parsedown.php' );

if( !defined( 'HEXA_WIKI_DOCUMENT_ROOT' ) ) {
	define( 'HEXA_WIKI_DOCUMENT_ROOT', __DIR__ . '/wiki/' );
}
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
	$page_hook_suffix = add_submenu_page( 'index.php', 'Wiki', 'Wiki', 'edit_users', 'wiki', 'hexa_wiki_content' );
	add_action('admin_print_scripts-' . $page_hook_suffix, '_hexa_writers_plugin_enqueue');
}
add_action( 'admin_menu', '_hexa_writers_admin_menu' );


function hexa_wiki_links($active) {
	$topics = scandir( HEXA_WIKI_DOCUMENT_ROOT, SCANDIR_SORT_DESCENDING );
	$wikiSlug = 'index.php?page=wiki&topic=';
	echo "<ul>";
	foreach ($topics as $topic) {
		if ( !( substr( $topic, 0, 1 ) === '.' ) && !( substr( $topic, 0, 1 ) === '_' ) )  { 
			$topicUrl = admin_url( $wikiSlug . $topic );
			$class = $active === $topic ? 'active' : '';
			echo "<li><a href='$topicUrl' class='$class'>$topic</a></li>";
		}
	}
	echo "</ul>";
}
/**
 *
 */
function hexa_wiki_content() {
	if ( !current_user_can( 'edit_users' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$topic = array_key_exists('topic', $_GET) ? $_GET['topic'] : "welcome.md";
	$markdown = hexa_wiki_get_markdown($topic);
	$editorialRpt = hexa_editorial_report();
	// Topics:
	echo "<div class='topic-bar'>";
	hexa_wiki_links($topic); 
	echo "</div>";

	// Markdown:
	echo "<div class='wikicard markdown-body'>";
	$Parsedown = new Parsedown();
	echo "<tt class='slug'>$topic</tt>";
	if( $topic === "editorial-report.md" ) {
		echo $Parsedown->text($editorialRpt); 
	} else {
		echo $Parsedown->text($markdown);
	}
	echo "</div>";

}

function hexa_wiki_get_markdown($slug) {
	return file_get_contents( HEXA_WIKI_DOCUMENT_ROOT . $slug );
}

function hexa_wiki_title_to_slug($title) {
	return sanitize_title($title);
}
