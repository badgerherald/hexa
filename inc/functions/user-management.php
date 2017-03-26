<?php
/**
 * !!! Warning !!!
 * Only touch this file if you absolutely know what you're doing. 
 * 
 * Doing the wrong thing here may mean giving students access to edit content.
 * 
 * -wjh
 */


/**
 * Set roles & capabilitities for Herald staff
 */
function _hexa_users_set_roles() {

	$alumni = array(										// ALUMNI CAN:
					'read'         				=> true,	// + read posts.
				);

	$contributor = array(									// CONTRIBUTORS CAN:
					'read'         				=> true,	// + read posts.
					'delete_posts' 				=> true,	// + delete posts (their own). 
					'edit_posts'   				=> true,	// + edit posts.
				);

	$associates = 
		$contributor + array(								// ASSOCIATES can do everything STAFF CONTRIBUTORS can, plus:
					'edit_others_posts'			=> true,	// + edit others posts
				);

	$copy = $contributor;									// COPY can do everything CONTRIBUTORS can,
															// just with a different name.
	
	$editor_role = get_role('editor');						// EDITORS get everthing the default WordPress editors
	$editors = $editor_role->capabilities;					// can do.

	$management = 
		$editors + array(									// MANAGEMENT can do everything EDITORS can, plus:
					'create_users'			=> true,		// + create users
					'edit_users'			=> true,		// + edit users
					'list_users'			=> true,		// + list users
					'promote_users'			=> true,		// + promote users
					'remove_users'			=> true,		// + remove users
					'edit_theme_options'	=> true,		// + edit menus and stuff
					'customize'				=> true,		// + access theme customizer
					'manage_options'		=> true,		// + manage options
				);

	add_role('alumni','Alumni',$alumni);
	add_role('staffwriter','Staff Writer',$contributor);
	add_role('associates','Associate',$associates);
	add_role('copy','Copy',$copy);
	add_role('management','Management',$management);

}
add_action( 'init', '_hexa_users_set_roles' );
