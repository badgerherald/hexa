 <?php 


/**
 * Show a newsletter signup in the header.
 */
add_action( 'exa_header_charm', function( $content ) {
	echo "<h3>Weekly Newsletter</h3>";
	echo mc4wp_get_form();
});