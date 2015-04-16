<?php

function exa_child_scripts()
{
    wp_enqueue_style('exa-style', get_template_directory_uri().'/style.css');
    wp_enqueue_style('exa-child-style', get_stylesheet_directory_uri().'/style.css', array('exa-style'));
    if (is_page("revelry"))
    {
        wp_enqueue_script("exa-child-revelry-script", get_stylesheet_directory_uri().'/revelry/revelry.js', array('jquery'));
        wp_enqueue_style('hrld-showcase-style');
        wp_enqueue_script( 'hrld-showcase-script-class');
        wp_enqueue_script('exa-hrld-showcase-init', get_template_directory_uri().'/js/hrld-showcase-init.js', array('hrld-showcase-script-class', 'jquery'));
    }
}
add_action('wp_enqueue_scripts', 'exa_child_scripts');