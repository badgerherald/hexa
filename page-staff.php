<?php
/**
 * The default template for displaying content. Used for single.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

global $AnalyticBridge;
global $post;

?>

<?php

get_header();

exa_container('menu-search-bar');
exa_container('header',array('breakpoints' => array('mobile')));
exa_container('cover-hero'); 

			exa_container('content-two-column',array('hide-hero' => true, 'layout' => 'feature') ); 


get_template_part('footer');