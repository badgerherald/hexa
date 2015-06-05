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
include_once('inc/functions-staff-page.php');


function hexa_count_campaign($html, $charge_response) {

	global $post;

	$payment_details = array( 
		'charge' => $charge_response['amount'],
		'id' => $charge_response['id'],
		'email' => $charge_response['name']
	);

	$payment_ids = get_post_meta($post->ID,'_stripe_payment_ids',true);

	if( !in_array($charge_response['id'],$payment_ids) ) {

		$payment_ids[] = $charge_response['id'];
		add_post_meta($post->ID,'_stripe_payment',$payment_details);
		update_post_meta($post->ID,'_stripe_total', get_post_meta($post->ID,'_stripe_total',true) + $charge_response['amount']);
		update_post_meta($post->ID,'_stripe_payment_ids',$payment_ids);

	}


    // This is copied from the original output so that we can just add in our own details
    $html = '<div class="sc-payment-details-wrap">';
          
    $html .= '<p>' . __( 'Congratulations. Your payment went through!', 'sc' ) . '</p>' . "\n";
             
    $html .= '<p class="stripe-payment-details"><strong>' . __( 'Total Donation: ', 'sc' ) . sc_stripe_to_formatted_amount( $charge_response->amount, $charge_response->currency ) . ' ' . 
            strtoupper( $charge_response->currency ) . '</strong>' . "<br/><br/>\n";
      
    $html .= 'Your transaction ID is: ' . $charge_response->id . "<br/><br/>\n";
   
    //Our own new details
    // Let's add the last four of the card they used and the expiration date
    $html .= 'Card: ****-****-****-' . $charge_response->source->last4 . '<br>';
    $html .= 'Expiration: ' . $charge_response->source->exp_month . '/' . $charge_response->source->exp_year . '</p>';
      

    // We can show the Address provided - this requires shipping="true" in our shortcode
    if( ! empty( $charge_response->source->address_line1 ) ) {
        $html .= '<p>Address Line 1: ' . $charge_response->source->address_line1 . '</p>';
    }
      
    if( ! empty( $charge_response->source->address_line2 ) ) {
        $html .= '<p>Address Line 2: ' . $charge_response->source->address_line2 . '</p>';
    }
      
    if( ! empty( $charge_response->source->address_city ) ) {
        $html .= '<p>Address City: ' . $charge_response->source->address_city . '</p>';
    }
      
    if( ! empty( $charge_response->source->address_state ) ) {
        $html .= '<p>Address State: ' . $charge_response->source->address_state . '</p>';
    }
      
    if( ! empty( $charge_response->source->address_zip ) ) {
        $html .= '<p>Address Zip: ' . $charge_response->source->address_zip . '</p>';
    }
 
	$html .= "<p>Thank you for supporting student journalism! We think you're pretty awesome. ";
	$html .= "<img style='margin-left: 3px; margin-right: 3px; vertical-align: middle; -webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;' src='http://badgerherald.com/wordpress/wp-content/plugins/wp-emoji-one/icons/1F63B.png' alt='' width='20' height='20' /></p>";

	$html .= "<p><a href='https://twitter.com/intent/tweet?button_hashtag=GiveHerald' class='twitter-hashtag-button' data-size='large' data-related='badgerherald,heraldalumni' data-url='http://badgerherald.com/donate'>Tweet #GiveHerald</a></p>";
	$html .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
	
	$html .= "<div></div>";

    $html .= exa_get_sharebar();

    $html .= '</div>';
      
    return $html;

	return $html;

}
add_action('sc_payment_details','hexa_count_campaign',10,2);

function hexa_stripe_print_stripe_total( $atts ) {

	$atts = shortcode_atts( array(
        'target' => 50000,
        'post' => get_post(null)
    ), $atts );

	$post = get_post($atts["post"]);

	$total = get_post_meta($post->ID,'_stripe_total',true);
	$target = $atts["target"];

	$totalStr = '<span class="stripe-campaign-number">$' . number_format($total/100, 2) . '</span> donated';
	$targetStr = 'of <span class="stripe-campaign-number">$' . number_format($target/100, 2) . "</span> goal.";


	$percentage = 100*( intval($total)/intval($target) );


	$ret = '';

	$ret .= "<div class='stripe-campaign-ticker' style='position:relative;display:block;' />";
		$ret .= "<div class='stripe-campaign-ticker-strip-outer'><div class='stripe-campaign-ticker-strip-inner' style='width:{$percentage}%' ></div></div>";
		$ret .= "<span stripe-campaign-string' >$totalStr</span>";
		$ret .= "<span stripe-campaign-string' style='position:absolute;right:0'>$targetStr</span>";
	$ret .= "</div>";

	return $ret;

} add_shortcode( 'stripe_campaign_total', 'hexa_stripe_print_stripe_total' );

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
// add_filter('the_content', 'hexa_were_hiring');

function hexa_analytic_title( $title, $id = null ) {

	global $AnalyticBridge;

	if(!empty($AnalyticBridge)) :

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

	endif;

    return $title;
}
add_filter( 'the_title', 'hexa_analytic_title', 10, 2 );




