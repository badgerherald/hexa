<?php

/**
 * Adds quick links to Posts submenu to filter by category.
 */
function _hexa_admin_section_filters() {
	$categorySlugs = array( 'news', 'opinion', 'artsetc', 'sports', 'banter', 'visuals' );
	$bullet = '&nbsp;&#8226; ';

	foreach( $categorySlugs as $slug ) {
		$category = get_category_by_slug($slug);
		if( $category ) {
			$name = $category->name;
			$id = $category->term_id;
			add_posts_page($name, $bullet . $name, 'edit_posts', 'edit.php?cat=' . $id);
		}
	}
}
add_action('admin_menu', '_hexa_admin_section_filters');

/**
 * This is a little gross, but re-arranges admin menus
 */
function _hexa_admin_reorder_submenu_pages( $menu_ord ) {
    global $submenu;

    // Dirty, but shift WordPress's index by 20 to make room for ours at top
	foreach ($submenu as $submenu_index => $menu) {
		foreach ($menu as $menu_index => $item) {
			$submenu[$submenu_index][$menu_index + 20] = $item;
			unset($submenu[$submenu_index][$menu_index]);
		}
	}
	
	$categorySlugs = array( 'news', 'opinion', 'artsetc', 'sports', 'banter', 'visuals' );

	foreach( array_reverse($categorySlugs) as $slug ) {
		$category = get_category_by_slug($slug);
		if($category) {
			$id = $category->term_id;
			_hexa_admin_reorder_move_page_to_top('edit.php','edit.php?cat=' . $id);
		}
	}
	
	_hexa_admin_reorder_move_page_to_top('edit.php','post-new.php');
	_hexa_admin_reorder_move_page_to_top('edit.php','edit.php');

    return $menu_ord;
}
add_filter( 'custom_menu_order', '_hexa_admin_reorder_submenu_pages' );

function _hexa_admin_reorder_move_page_to_top($slug, $page) {
	global $submenu;

	if ( !array_key_exists( $slug, $submenu ) ) {
		return;
	}

	$top_index = 19;
	while( $top_index > 0 && array_key_exists($top_index,$submenu[$slug]) ) {
		$top_index-=1;
	}

	foreach ($submenu[$slug] as $menu_index => $item) {
		if( $item[2] == $page ) {
			$submenu[$slug] = array( $top_index => $item ) + $submenu[$slug];
			unset($submenu[$slug][$menu_index]);
		}
	}
}


/**
 * Changes wordpress outbound email sender to The Badger Herald.
 *
 * @since v0.6
 */
function exa_mail_name( $email ){
  return get_bloginfo( 'name' ); // new sender name using site name.
}
add_filter( 'wp_mail_from_name', 'exa_mail_name' );

/**
 * Changes wordpress outbound email sender address to web@badgerherald.com
 *
 * @since v0.6
 */
function exa_mail_address( $email ){
  return str_replace('wordpress','web',$email); // new sender email account.
}
add_filter( 'wp_mail_from', 'exa_mail_address' );




