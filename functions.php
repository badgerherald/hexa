<?php

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

function hexa_below_article() { ?>

	<?php include('inc/block-taboola.php'); ?>

<?php }
add_action('exa_below_article','hexa_below_article');


/**
 * Scripts in <head> for taboola on article pages.
 * 
 * @since v0.1
 */
function hexa_taboola_header_scripts() { 

	if(is_single()):
	?>

	<script type="text/javascript">
		window._taboola = window._taboola || [];
		_taboola.push({article:'auto'});
		!function (e, f, u) {
		  e.async = 1;
		  e.src = u;
		  f.parentNode.insertBefore(e, f);
		}(document.createElement('script'),
		document.getElementsByTagName('script')[0],
		'http://cdn.taboola.com/libtrc/thebadgerherald/loader.js');
	</script>

	<?php 
	endif;

}
add_action('wp_head', 'hexa_taboola_header_scripts');

/**
 * Scripts inserted before </body> for taboola on article pages.
 * 
 * @since v0.1
 */
function hexa_taboola_footer_scripts() { 

	if(is_single()):
	?>

	<script type="text/javascript">
		window._taboola = window._taboola || [];
		_taboola.push({flush: true});
	</script>

	<?php 
	endif;

}
add_action('wp_footer', 'hexa_taboola_footer_scripts');


/**
 * Yo, we're hiring.
 * 
 */
function hexa_were_hiring($content) {

	$content .= "<h4 style='margin-top:60px;'><em>We're hiring! Check out our jobs page. Applications due April 27th. <a href='https://badgerherald.com/about/get-involved/hiring/'>badgerherald.com/about/get-involved/hiring</a>.</em></h4>";
	return $content;
}
add_filter('the_content', 'hexa_were_hiring');

function hexa_analytic_title( $title, $id = null ) {

	global $AnalyticBridge;

	if( !is_admin() && is_user_logged_in() && @current_user_can('edit_post') && array_key_exists('AnalyticBridge', $GLOBALS) ) {

		$today = $AnalyticBridge->metric($id,'ga:sessions','today');
		$yesterday = $AnalyticBridge->metric($id,'ga:sessions','yesterday');

		
		if($today|| $yesterday) {
			
			if(!$today) $today = 0;
			$title = "$title<span class='title-stats'><span class='stats-today'>t:$today</span>";
			if($yesterday) {
				$title = "$title &middot; <span class='stats-yesterday'>y:$yesterday</span>";
			}
			$title .= "</span>";
		}

	}

    return $title;
}
add_filter( 'the_title', 'hexa_analytic_title', 10, 2 );


