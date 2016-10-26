<?php

// Register Custom Post Type
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