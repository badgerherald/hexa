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

	<div id="taboola-below-article-thumbnails" class="taboola-below-article"></div>
	<script type="text/javascript">
		window._taboola = window._taboola || [];
		_taboola.push({
		  mode: 'alternating-thumbnails-a',
		  container: 'taboola-below-article-thumbnails',
		  placement: 'Below Article Thumbnails',
		  target_type: 'mix'
		});
	</script>

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