<?php

add_action( 'init', 'cplat_partner_post_type' );

function cplat_partner_post_type() {
	$labels = array(
		'name'                => _x( 'Partners', 'Post Type General Name', 'cplat' ),
		'singular_name'       => _x( 'Partner', 'Post Type Singular Name', 'cplat' ),
		'menu_name'           => __( 'Partners', 'cplat' ),
		'parent_item_colon'   => __( 'Parent Partner:', 'cplat' ),
		'all_items'           => __( 'All Partners', 'cplat' ),
		'view_item'           => __( 'View Partner data', 'cplat' ),
		'add_new_item'        => __( 'Add New Partner data', 'cplat' ),
		'add_new'             => __( 'New Partner', 'cplat' ),
		'edit_item'           => __( 'Edit Partner data', 'cplat' ),
		'update_item'         => __( 'Update Partner data', 'cplat' ),
		'search_items'        => __( 'Search partners', 'cplat' ),
		'not_found'           => __( 'No partner data found', 'cplat' ),
		'not_found_in_trash'  => __( 'No partner data found in Trash', 'cplat' ),
	);

	$args = array(
		'label'               => __( 'Partner-data', 'cplat' ),
		'description'         => __( 'Partner-data information pages', 'cplat' ),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'thumbnail'),
		'hierarchical'        => true,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		// 'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'query_var'           => 'partner',
		'capability_type'     =>  'post',
		'map_meta_cap'        =>  true,
	);
	register_post_type( 'partner', $args );
}


add_action( 'init', 'cplat_partner_post_type' );

function cplat_manager_post_type() {
	$labels = array(
		'name'                => _x( 'Managers', 'Post Type General Name', 'cplat' ),
		'singular_name'       => _x( 'Manager', 'Post Type Singular Name', 'cplat' ),
		'menu_name'           => __( 'Managers', 'cplat' ),
		'parent_item_colon'   => __( 'Parent Manager:', 'cplat' ),
		'all_items'           => __( 'All Managers', 'cplat' ),
		'view_item'           => __( 'View Manager data', 'cplat' ),
		'add_new_item'        => __( 'Add New Manager data', 'cplat' ),
		'add_new'             => __( 'New Manager', 'cplat' ),
		'edit_item'           => __( 'Edit Manager data', 'cplat' ),
		'update_item'         => __( 'Update Manager data', 'cplat' ),
		'search_items'        => __( 'Search managers', 'cplat' ),
		'not_found'           => __( 'No manager data found', 'cplat' ),
		'not_found_in_trash'  => __( 'No manager data found in Trash', 'cplat' ),
	);

	$args = array(
		'label'               => __( 'Manager-data', 'cplat' ),
		'description'         => __( 'Manager-data information pages', 'cplat' ),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'thumbnail'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		// 'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'query_var'           => 'manager',
		'capability_type'     =>  'post',
		'map_meta_cap'        =>  true,
	);
	register_post_type( 'manager', $args );
}

add_action( 'init', 'cplat_manager_post_type' );

function cplat_platform_post_type() {
	$labels = array(
		'name'                => _x( 'Platforms', 'Post Type General Name', 'cplat' ),
		'singular_name'       => _x( 'Platform', 'Post Type Singular Name', 'cplat' ),
		'menu_name'           => __( 'Platforms', 'cplat' ),
		'parent_item_colon'   => __( 'Parent Platform:', 'cplat' ),
		'all_items'           => __( 'All Platforms', 'cplat' ),
		'view_item'           => __( 'View Platform data', 'cplat' ),
		'add_new_item'        => __( 'Add New Platform data', 'cplat' ),
		'add_new'             => __( 'New Platform', 'cplat' ),
		'edit_item'           => __( 'Edit Platform data', 'cplat' ),
		'update_item'         => __( 'Update Platform data', 'cplat' ),
		'search_items'        => __( 'Search platforms', 'cplat' ),
		'not_found'           => __( 'No platform data found', 'cplat' ),
		'not_found_in_trash'  => __( 'No platform data found in Trash', 'cplat' ),
	);

	$args = array(
		'label'               => __( 'Platform-data', 'cplat' ),
		'description'         => __( 'Platform-data information pages', 'cplat' ),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		// 'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'query_var'           => 'platform',
		'capability_type'     =>  'edit_platform_data',
		'map_meta_cap'        =>  true,
	);
	register_post_type( 'platform', $args );
}
add_action( 'init', 'cplat_platform_post_type' );

function cplat_guide_post_type() {
	$labels = array(
		'name'                => _x( 'Guides', 'Post Type General Name', 'cplat' ),
		'singular_name'       => _x( 'Guide', 'Post Type Singular Name', 'cplat' ),
		'menu_name'           => __( 'Guides', 'cplat' ),
		'parent_item_colon'   => __( 'Parent Guide:', 'cplat' ),
		'all_items'           => __( 'All Guides', 'cplat' ),
		'view_item'           => __( 'View Guide data', 'cplat' ),
		'add_new_item'        => __( 'Add New Guide data', 'cplat' ),
		'add_new'             => __( 'New Guide', 'cplat' ),
		'edit_item'           => __( 'Edit Guide data', 'cplat' ),
		'update_item'         => __( 'Update Guide data', 'cplat' ),
		'search_items'        => __( 'Search guides', 'cplat' ),
		'not_found'           => __( 'No guide data found', 'cplat' ),
		'not_found_in_trash'  => __( 'No guide data found in Trash', 'cplat' ),
	);

	$args = array(
		'label'               => __( 'Guide-data', 'cplat' ),
		'description'         => __( 'Guide-data information pages', 'cplat' ),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'thumbnail', 'comments'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		// 'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'query_var'           => 'guide',
		'capability_type'     =>  'post',
		'map_meta_cap'        =>  true,
		'taxonomies' => array('guide_tag')
	);
	register_post_type( 'guide', $args );
	register_taxonomy('guide_tag', 'guide', array(
	    'hierarchical' => false,
	    'label' => __('Guide Tags'),
	    'singular_name' => __('Guide Tags'),
	    'rewrite' => true,
	    'query_var' => true
	    )
	);
	$category_labels = array(
		'name'              => _x( 'Guide Categories', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Guide Category', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Guide Categorys', 'textdomain' ),
		'all_items'         => __( 'All Guide Categories', 'textdomain' ),
		'parent_item'       => __( 'Parent Guide Category', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Guide Category:', 'textdomain' ),
		'edit_item'         => __( 'Edit Guide Category', 'textdomain' ),
		'update_item'       => __( 'Update Guide Category', 'textdomain' ),
		'add_new_item'      => __( 'Add New Guide Category', 'textdomain' ),
		'new_item_name'     => __( 'New Guide Category Name', 'textdomain' ),
		'menu_name'         => __( 'Guide Category', 'textdomain' ),
	);

	$category_args = array(
		'hierarchical'      => true,
		'labels'            => $category_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'exclude_from_search' => false
	);

	register_taxonomy( 'guide_category', array( 'guide' ), $category_args );
}
add_action( 'init', 'cplat_guide_post_type' );


function cplat_jargon_post_type() {
	$labels = array(
		'name'                => _x( 'Jargon terms', 'Post Type General Name', 'cplat' ),
		'singular_name'       => _x( 'Jargon term', 'Post Type Singular Name', 'cplat' ),
		'menu_name'           => __( 'Jargon terms', 'cplat' ),
		'parent_item_colon'   => __( 'Parent Jargon term:', 'cplat' ),
		'all_items'           => __( 'All Jargon terms', 'cplat' ),
		'view_item'           => __( 'View Jargon term data', 'cplat' ),
		'add_new_item'        => __( 'Add New Jargon term data', 'cplat' ),
		'add_new'             => __( 'New Jargon term', 'cplat' ),
		'edit_item'           => __( 'Edit Jargon term data', 'cplat' ),
		'update_item'         => __( 'Update Jargon term data', 'cplat' ),
		'search_items'        => __( 'Search jargons', 'cplat' ),
		'not_found'           => __( 'No jargon data found', 'cplat' ),
		'not_found_in_trash'  => __( 'No jargon data found in Trash', 'cplat' ),
	);

	$args = array(
		'label'               => __( 'Jargon term-data', 'cplat' ),
		'description'         => __( 'Jargon term-data information pages', 'cplat' ),
		'labels'              => $labels,
		'supports'            => array('title'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		// 'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'query_var'           => 'jargon',
		'capability_type'     =>  'post',
		'map_meta_cap'        =>  true,
		'taxonomies' => array('jargon_letter')
	);
	register_post_type( 'jargon', $args );
	register_taxonomy('jargon_letter', 'jargon', array(
	    'hierarchical' => true,
	    'label' => __('Letter'),
	    'singular_name' => __('Letters'),
	    'rewrite' => true,
	    'query_var' => true
	    )
	);
}
//add_action( 'init', 'cplat_jargon_post_type' );

function cplat_investor_post_type() {
	$labels = array(
		'name'                => _x( 'Investors', 'Post Type General Name', 'cplat' ),
		'singular_name'       => _x( 'Investor', 'Post Type Singular Name', 'cplat' ),
		'menu_name'           => __( 'Investors', 'cplat' ),
		'parent_item_colon'   => __( 'Parent Investor:', 'cplat' ),
		'all_items'           => __( 'All Investors', 'cplat' ),
		'view_item'           => __( 'View Investor data', 'cplat' ),
		'add_new_item'        => __( 'Add New Investor data', 'cplat' ),
		'add_new'             => __( 'New Investor', 'cplat' ),
		'edit_item'           => __( 'Edit Investor data', 'cplat' ),
		'update_item'         => __( 'Update Investor data', 'cplat' ),
		'search_items'        => __( 'Search investors', 'cplat' ),
		'not_found'           => __( 'No investor data found', 'cplat' ),
		'not_found_in_trash'  => __( 'No investor data found in Trash', 'cplat' ),
	);

	$args = array(
		'label'               => __( 'Investor-data', 'cplat' ),
		'description'         => __( 'Investor-data information pages', 'cplat' ),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'thumbnail'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		// 'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'query_var'           => 'investor',
		'capability_type'     =>  'post',
		'map_meta_cap'        =>  true
	);
	register_post_type( 'investor', $args );

}
//add_action( 'init', 'cplat_investor_post_type' );

function cplat_waplatform_post_type() {
	$labels = array(
		'name'                => _x( 'What\'s a platforms', 'Post Type General Name', 'cplat' ),
		'singular_name'       => _x( 'What\'s a platform', 'Post Type Singular Name', 'cplat' ),
		'menu_name'           => __( 'What\'s a platforms', 'cplat' ),
		'parent_item_colon'   => __( 'Parent What\'s a platform:', 'cplat' ),
		'all_items'           => __( 'All What\'s a platforms', 'cplat' ),
		'view_item'           => __( 'View What\'s a platform data', 'cplat' ),
		'add_new_item'        => __( 'Add New What\'s a platform data', 'cplat' ),
		'add_new'             => __( 'New What\'s a platform', 'cplat' ),
		'edit_item'           => __( 'Edit What\'s a platform data', 'cplat' ),
		'update_item'         => __( 'Update What\'s a platform data', 'cplat' ),
		'search_items'        => __( 'Search waplatforms', 'cplat' ),
		'not_found'           => __( 'No waplatform data found', 'cplat' ),
		'not_found_in_trash'  => __( 'No waplatform data found in Trash', 'cplat' ),
	);

	$args = array(
		'label'               => __( 'What\'s a platform-data', 'cplat' ),
		'description'         => __( 'What\'s a platform-data information pages', 'cplat' ),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'thumbnail'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		// 'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'query_var'           => 'waplatform',
		'capability_type'     =>  'post',
		'map_meta_cap'        =>  true
	);
	register_post_type( 'waplatform', $args );

}
add_action( 'init', 'cplat_waplatform_post_type' );


// add post-formats to post_type 'page'
add_action('init', 'my_theme_slug_add_post_formats_to_page', 11);

function my_theme_slug_add_post_formats_to_page(){
    add_post_type_support( 'waplatform', 'post-formats' );
    register_taxonomy_for_object_type( 'post_format', 'waplatform' );
}