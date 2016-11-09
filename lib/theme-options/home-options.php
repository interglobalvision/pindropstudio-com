<?php
// SITE OPTIONS
$prefix = '_igv_';
$suffix = '_options';

$page_key = $prefix . 'home' . $suffix;
$page_title = 'Home Page Options';
$metabox = array(
  'id'         => $page_key, // id used as tab page slug, must be unique
  'title'      => $page_title,
  'show_on'    => array( 'key' => 'options-page', 'value' => array( $page_key ), ), //value must be same as id
  'show_names' => true,
  'fields'     => array(

    // carousel
    array(
      'name' => __( 'Carousel and dopple', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'above_title',
      'type' => 'title',
    ),
    array(
      'name' => __( 'Show Carousel', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'carousel_shown',
      'type' => 'checkbox',
    ),
    array(
      'name' => __( 'Choose up to 3 posts here to show on the carousel', 'IGV' ),
      'desc' => __( '', 'IGV' ),
      'id'   => $prefix . 'carousel_posts',
      'type' => 'post_search_text',
      'post_type' => array('post', 'event', 'luminary'),
    ),

    // Sound & Vision section
    array(
      'name' => __( 'Sound & Vision posts override', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'live_title',
      'type' => 'title',
    ),
    array(
      'name' => __( 'Choose up to 3 past events here to override the display of most recent past events', 'IGV' ),
      'desc' => __( '', 'IGV' ),
      'id'   => $prefix . 'override_events',
      'type' => 'post_search_text',
      'post_type' => array('event'),
    ),

    // Wide Ads
    array(
      'name' => __( 'Wide Ads', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'adds_title',
      'type' => 'title',
    ),
    array(
      'name' => __( 'Ad 1 Text', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'ad1_text',
      'type' => 'text',
    ),
    array(
      'name' => __( 'Ad 1 Image', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'ad1_image',
      'type' => 'file',
    ),
    array(
      'name' => __( 'Ad 1 Internal Link', 'IGV' ),
      'desc' => __( 'if linking inside the website choose the page to link to here', 'IGV' ),
      'id'   => $prefix . 'ad1_link',
      'type' => 'post_search_text',
      'post_type'  => array('post', 'page', 'event', 'luminaries'),
    ),
     array(
      'name' => __( 'Ad 1 External Link', 'cmb2' ),
      'desc' => __( 'if linking outside of the website put the URL here', 'cmb2' ),
      'id'   => $prefix . 'ad1_link_external',
      'type' => 'text_url',
    ),

  )
);

IGV_init_options_page($page_key, $page_key, $page_title, $metabox);