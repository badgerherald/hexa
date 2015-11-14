<?php
/**
 * Deal with ads in our theme.
 * 
 * Mostly ads are served with the help of a DoubleClick plugin for WordPress.
 * 
 * @see https://github.com/inn/DoubleClick-for-Wordpress
 * @since v0.2
 */

/**
 * Setup doubleclick breakpoints and network codes.
 * 
 * @see https://github.com/inn/DoubleClick-for-Wordpress
 */
function hexa_ad_setup() {

	global $DoubleClick;

	if( hrld_is_production() ) {
		// Production networkCode:
		$DoubleClick->networkCode = "8653162";
	} else {
		// Test networkCode:
		$DoubleClick->networkCode = "64222555";		// Ask Will Haynes for access.
	}

}
add_action('dfw_setup','hexa_ad_setup');