<?php
/**
 * This was stripped out of an old implementation and could use
 * some future clean up.
 * 
 * A shortcode is registered that builds DOM for displaying userdata.
 * 
 * Use the shortcode like:
 * 
 * 		[displaystaff users="whaynes,tgolshan,kcaron"]
 * 
 */


/**
 * Shortcode for displaying pretty mugs on the staff page.
 * 
 * @param array $atts attributes passed into shortcode.
 * @return string DOM string for insertion of staff.
 */
function hexa_dispaystaff( $atts ) {

    $a = shortcode_atts( array(
        'users' => '',
    ), $atts );

    $staffArray = explode(',',$a['users']);

    $return = "<div class='staff-container'>";
    
	$i = 0;
	foreach($staffArray as $staff) :
		$classes = "";
		if($i%2!=0) {
			$classes .= "odd ";
		} $i+=1;

		if( is_string($staff) ) {
			$user = get_user_by('login', $staff);
			if (!$user) {
				continue;
			}
			$staff = $user->ID;
		}

		$return .= "<div class='staff-box'>";
		$aMug = get_wp_user_avatar_src($staff, 'small-thumbnail');
		
		// Mug
		$return .= "<div class='staff-about-mug-box'><img src='$aMug' /></div>";

		// Name
		$return .= "<span class='staff-box-name'>" . get_the_author_meta("display_name",$staff) . "</span>";
		
		// Position
		$return .= "<span class='staff-box-current-position'>";
		get_hrld_author("hrld_current_position",$staff);
		$return .= "</span>";

		// Twitter and more.

		$return .= "<span class='staff-box-twitter-more'>";
			// If twitter
			if(hrld_author_has("hrld_twitter_handle",$staff)) {
				$twitter_handle = get_hrld_author("hrld_twitter_handle",$staff);
				$return .= "<a href='https://twitter.com/$twitter_handle' class='twitter-follow-button' data-show-count='false' data-show-screen-name='false'>Follow @$twitter_handle</a>";
				$return .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
			}
			// More
			$return .= "<div class='staff-box-more'>";
				$return .= "<span class='staff-box-more-button'>More<span class='staff-box-more-arrow'></span></span>";
				$return .= "<div class='staff-box-more-hover $classes'>";
				$return .= "<div class='staff-box-more-hover-card'>";
					if(hrld_author_has("hrld_staff_description",$staff)) {
						$return .= "<p class='staff-box-more-description'>";
							get_hrld_author("hrld_staff_description",$staff);
						$return .= "</p>";
					}

					if(hrld_author_has("hrld_staff_semesters",$staff)) {
						$return .= "<p> Semesters at the Herald: ";
						get_hrld_author("hrld_staff_semesters",$staff);
						$return .= ".</p>";
					}
					
					$return .= "<p class='staff-box-more-email'>";
						$return .= "<a href='mailto:" . get_the_author_meta( "email", $staff ) . "'>" . get_the_author_meta( "email", $staff ) . "</a>";
						if(hrld_author_has("hrld_staff_extension",$staff)) {
							$return .= "<br/>608-257-4712 " . get_hrld_author("hrld_staff_extension",$staff);
						}
					$return .= "</p>";
				$return .= "</span>";
				$return .= "</div>";
				$return .= "</div>";
			$return .= "</div>";
		$return .= "</span>";
			
		$return .= "</div>";
		
	endforeach;

	$return .= "<div class='clearfix'></div>";
	$return .= "</div>";

	return $return;

}
add_shortcode( 'displaystaff', 'hexa_dispaystaff' );

// Add a body class to target styling.
function about_class($classes) {
	
	global $post;

	if($post->post_parent == 0) 
		return $classes;

  	$post_data = get_post($post->post_parent);

	if($post_data->post_name == "about") {
		$classes[] = 'about-page';
	}
	return $classes;

} add_filter('body_class','about_class');


/**
 * 
 * An old function that when called will list contributors that
 * have had 3 stories published in the past semester in the
 * given section.
 * 
 * Could use some major cleanup. Here just as an example.
 * 
 */ /*
function listWriters($section,$exclude = null) {

	// people like staff, editorial board, &c.
	$globalExclude = array(1023,4,2792,2857,2858);
	if(!$exclude) {
		$exclude = array();
	} else {
		// support usernames passed in instead of ids.
		foreach($exclude as $i => $e) {
			if ( is_string($e) ) {
			$user = get_userdatabylogin($e);
			$exclude[$i] = $user->ID;
			}
		}
	}
	$exclude = array_merge($globalExclude,$exclude);

	$sdate = new DateTime();				// Time now.
	$sdate->sub(new DateInterval('P4M'));	// Subtract 4 months.


	if( class_exists('hrld_get_writers') ) {

		echo "Writers: ";
		$newsWriters = new hrld_get_writers();
		$newsWriters->section($section);
		$newsWriters->num_published(3,'more',$sdate);
		$writers = $newsWriters->query();
		$first = true;
		foreach($writers as $w) {
			if( !in_array($w->user_id,$exclude) ) :
				if(!$first) {
					echo ", ";
				} $first = false;
				echo "<a href='" . get_author_posts_url( $w->user_id ) . "'>$w->display_name</a>";
			endif;
		}
	}

}
*/
