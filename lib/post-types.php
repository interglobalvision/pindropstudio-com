<?php

// Register Custom Post: Event
function register_cpt_event() {

  $labels = array(
    'name' => _x( 'Events', 'event' ),
    'singular_name' => _x( 'Event', 'event' ),
    'add_new' => _x( 'Add New', 'event' ),
    'add_new_item' => _x( 'Add New Event', 'event' ),
    'edit_item' => _x( 'Edit Event', 'event' ),
    'new_item' => _x( 'New Event', 'event' ),
    'view_item' => _x( 'View Event', 'event' ),
    'search_items' => _x( 'Search Events', 'event' ),
    'not_found' => _x( 'No events found', 'project' ),
    'not_found_in_trash' => _x( 'No events found in Trash', 'project' ),
    'parent_item_colon' => _x( 'Parent Event:', 'event' ),
    'menu_name' => _x( 'Events', 'event' ),
  );

  $args = array(
    'labels' => $labels,
    'hierarchical' => false,

    'supports' => array( 'title', 'editor', 'thumbnail' ),

    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
		'menu_icon'             => 'dashicons-calendar-alt',
    'show_in_nav_menus' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => false,
    'has_archive' => true,
    'query_var' => true,
    'can_export' => true,
    'rewrite' => true,
    'capability_type' => 'post'
  );

  register_post_type( 'event', $args );
}
add_action( 'init', 'register_cpt_event' );

// Register Custom Post: Luminaries
function luminaries() {

	$labels = array(
		'name'                  => _x( 'Luminaries', 'Post Type General Name', 'igv' ),
		'singular_name'         => _x( 'Luminary', 'Post Type Singular Name', 'igv' ),
		'menu_name'             => __( 'Luminaries', 'igv' ),
		'name_admin_bar'        => __( 'Luminaries', 'igv' ),
		'archives'              => __( 'Item Archives', 'igv' ),
		'parent_item_colon'     => __( 'Parent Luminary:', 'igv' ),
		'all_items'             => __( 'All Luminaries', 'igv' ),
		'add_new_item'          => __( 'Add New Luminary', 'igv' ),
		'add_new'               => __( 'Add Luminary', 'igv' ),
		'new_item'              => __( 'New Luminary', 'igv' ),
		'edit_item'             => __( 'Edit Luminary', 'igv' ),
		'update_item'           => __( 'Update Luminary', 'igv' ),
		'view_item'             => __( 'View Luminary', 'igv' ),
		'search_items'          => __( 'Search Luminaries', 'igv' ),
		'not_found'             => __( 'Not found', 'igv' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'igv' ),
		'featured_image'        => __( 'Featured Image', 'igv' ),
		'set_featured_image'    => __( 'Set featured image', 'igv' ),
		'remove_featured_image' => __( 'Remove featured image', 'igv' ),
		'use_featured_image'    => __( 'Use as featured image', 'igv' ),
		'insert_into_item'      => __( 'Insert into item', 'igv' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'igv' ),
		'items_list'            => __( 'Items list', 'igv' ),
		'items_list_navigation' => __( 'Items list navigation', 'igv' ),
		'filter_items_list'     => __( 'Filter items list', 'igv' ),
	);
	$args = array(
		'label'                 => __( 'Luminary', 'igv' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-admin-users',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'luminaries', $args );

}
add_action( 'init', 'luminaries', 0 );

// OLD TYPES FOR MIGRATION

add_action( 'init', 'register_cpt_recording' );

function register_cpt_recording() {

    $labels = array(
        'name' => _x( 'Recordings', 'recording' ),
        'singular_name' => _x( 'Recording', 'recording' ),
        'add_new' => _x( 'Add New', 'recording' ),
        'add_new_item' => _x( 'Add New Recording', 'recording' ),
        'edit_item' => _x( 'Edit Recording', 'recording' ),
        'new_item' => _x( 'New Recording', 'recording' ),
        'view_item' => _x( 'View Recording', 'recording' ),
        'search_items' => _x( 'Search Recordings', 'recording' ),
        'not_found' => _x( 'No recordings found', 'recording' ),
        'not_found_in_trash' => _x( 'No recordings found in Trash', 'recording' ),
        'parent_item_colon' => _x( 'Parent Recording:', 'recording' ),
        'menu_name' => _x( 'Recordings', 'recording' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,

        'supports' => array( 'title', 'editor', 'thumbnail' ),

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,

        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'recording', $args );
}

add_action( 'init', 'register_cpt_people' );

function register_cpt_people() {

    $labels = array(
        'name' => _x( 'People', 'people' ),
        'singular_name' => _x( 'People', 'people' ),
        'add_new' => _x( 'Add New', 'people' ),
        'add_new_item' => _x( 'Add New People', 'people' ),
        'edit_item' => _x( 'Edit People', 'people' ),
        'new_item' => _x( 'New People', 'people' ),
        'view_item' => _x( 'View People', 'people' ),
        'search_items' => _x( 'Search People', 'people' ),
        'not_found' => _x( 'No people found', 'people' ),
        'not_found_in_trash' => _x( 'No people found in Trash', 'people' ),
        'parent_item_colon' => _x( 'Parent People:', 'people' ),
        'menu_name' => _x( 'People', 'people' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,

        'supports' => array( 'title', 'editor', 'thumbnail' ),

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,

        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'people', $args );
}