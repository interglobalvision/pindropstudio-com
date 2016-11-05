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
  )
);

IGV_init_options_page($page_key, $page_key, $page_title, $metabox);