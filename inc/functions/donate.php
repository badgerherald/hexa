<?php

/* stripe config DO NOT COMMIT!! */
require_once('lib/stripe-php-7.0.2/init.php');
\Stripe\Stripe::setApiKey("sk_test_4V2re3usbUZ0VcySXE3eGT5Y00DdsCMATo");

define( 'WOOCOMMERCE_CHECKOUT', true );

define( 'HEXA_DONATION_REOCCURANCE_ONCE', 0);
define( 'HEXA_DONATION_REOCCURANCE_SEMESTERLY', 1);
define( 'HEXA_DONATION_REOCCURANCE_MONTHLY', 2);

/**
 * Donation shortcode
 */
function hexa_donation_form( $atts ) {
	return "<exa-stripe class='shadow'></exa-stripe>";
}
add_shortcode( 'hexa_donor_form', 'hexa_donation_form' );

function hexa_process_donation( WP_REST_Request $request ) {
    $amount = $request->get_param( 'amount' );
    $token = $request->get_param( 'token' );
	$nonce = $request->get_param( 'nonce' );

	// todo, nonce check

	$email = $request->get_param( 'email' );
	$phone = $request->get_param( 'phone' );
	$address = array(
		"street" => $request->get_param( 'street' ),
		"apt" => $request->get_param( 'apt' ),
		"city" => $request->get_param( 'country' ),
		"country" => $request->get_param( 'country' ),
		"zip" => $request->get_param( 'zip' )
	);

	$contact_info = array(
		"address" => $address,
		"email" => $email,
		"phone" => $phone
	);

	$reoccurance = HEXA_DONATION_REOCCURANCE_SEMESTERLY;
    $amountInCents = $amount * 100;

	if($reoccuring) {
		$customer = \Stripe\Customer::create([
    		'source' => $token,
    		'email' => $email,
		]);
	}

    $charge = \Stripe\Charge::create([
        "amount" => $amountInCents,
        "currency" => "usd",
        "source" => $token, // obtained with Stripe.js
		"description" => "Donation to The Badger Herald",
		"customer" => $reoccuring ? $customer->id : null
	]);
	
	if(true) {
		hexa_donate_save_donation_from_form( $email, $amount, "asdf" ,$reoccuring ? $customer->id : null, $contact_info );
		wp_send_json(array(
			"success" => true
		));
	} 

}

function hexa_donate_save_donation_from_form( $email, $amount, $transaction_id, $frequency, $contact_info ) {
	$user_id = username_exists( $email );

	if ( !$user_id and email_exists($user_email) == false ) {
		$random_password = wp_generate_password( 12, false );
		$user_id = wp_create_user( $email, $random_password, $email );
	}

	$index = "";

	if( $frequency > 0 ) {
		$index = hexa_donate_create_reoccurance( $user_id, $frequency );
	}

	$contact_index = hexa_donate_save_contact_info( $user_id, $contact_info );
	hexa_donation_save_charge( $user_id, $amount, $index, $transaction_id, $contact_index );

}

function hexa_donate_create_reoccurance( $user_id, $frequency ) {
	// todo, verify $user_id

	$key = 'hexa_donation_reoccurances';

	$reoccurances = get_user_meta( $user_id, $key, true );

	$reoccurances[] = array(
		'created' => new Date(),
		'frequency' => $frequency,
		'stripe_customer_id' => $customer_id
	);

	update_user_meta( $user_id, $key, $reoccurances );

	return count($reoccurances);
}

function hexa_donation_save_charge( $user_id, $amount, $reoccurance_index, $transaction_id, $contact_index ) {
	$key = 'hexa_donation_reoccurances';

	$donation = array(
		"amount" => $amount,
		"transaction" => $transaction_id,
		"reoccurance_index" => $reoccurance_index,
		"date" => current_time('timestamp'),
		"contact_index" => $contact_index
	);

	add_user_meta( $user_id, $key, $donation, false );
}

function hexa_donate_save_contact_info( $user_id, $contact_info ) {
	$key = 'hexa_donation_contact_info';

	$contact = get_user_meta( $user_id, $key, true );
	$contact[] = $contact_info;

	update_user_meta( $user_id, $key, $contact );

	return count($contact);
}

function hexa_donate_register_rest_route() {
    register_rest_route( 'hexa/v1', 'process-donation', array(
        'methods'  => 'POST',
        'callback' => 'hexa_process_donation',
        'args' => array(
        'amount' => array(
            'validate_callback' => function($param, $request, $key) {
                return is_numeric( $param );
            },
            'required'=> true
        ),
        'token' => array('required'=> true),
        'nonce' => array('required'=> true)
        ),
    ));
}
add_action('rest_api_init', 'hexa_donate_register_rest_route');


function hexa_count_campaign($html, $charge_response) {
	global $post;
	$payment_details = array( 
		'charge' => $charge_response['amount'],
		'id' => $charge_response['id'],
		'email' => $charge_response['name']
	);
	$payment_ids = get_post_meta($post->ID,'_stripe_payment_ids');
	if( !in_array($charge_response['id'],$payment_ids) ) {
		$payment_ids[] = $charge_response['id'];
		add_post_meta($post->ID,'_stripe_payment',$payment_details);
		update_post_meta($post->ID,'_stripe_total', get_post_meta($post->ID,'_stripe_total',true) + $charge_response['amount']);
		update_post_meta($post->ID,'_stripe_payment_ids',$payment_ids);
	}
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
	$ret .= "!!</div>";
	return $ret;
} add_shortcode( 'stripe_campaign_total', 'hexa_stripe_print_stripe_total' );




