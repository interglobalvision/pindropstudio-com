<?php
// SITE OPTIONS
$prefix = '_igv_';
$suffix = '_options';

$page_key = $prefix . 'quote' . $suffix;
$page_title = 'Quote Options';
$metabox = array(
  'id'         => $page_key, // id used as tab page slug, must be unique
  'title'      => $page_title,
  'show_on'    => array( 'key' => 'options-page', 'value' => array( $page_key ), ), //value must be same as id
  'show_names' => true,
  'fields'     => array(
    // custom page home
    array(
      'name' => __( 'Home page', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'home_title',
      'type' => 'title',
    ),
    array(
      'name' => __( 'Home page quote text', 'IGV' ),
      'desc' => __( '', 'IGV' ),
      'id'   => $prefix . 'home_quote_text',
      'type' => 'textarea_code',
    ),
    array(
      'name' => __( 'Home page quote person', 'IGV' ),
      'desc' => __( '', 'IGV' ),
      'id'   => $prefix . 'home_quote_person',
      'type' => 'text',
    ),
    array(
      'name' => __( 'Home page quote luminary', 'IGV' ),
      'desc' => __( '(optionally relate quote to luminary. Will link the name to their luminary page)', 'IGV' ),
      'id'   => $prefix . 'home_quote_luminary',
      'type' => 'post_search_text',
      'post_type' => array('luminaries'),
      'select_type' => 'radio',
      'select_behavior' => 'replace',
    ),

    // custom page live
    array(
      'name' => __( 'Live page', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'live_title',
      'type' => 'title',
    ),
    array(
      'name' => __( 'Live page quote text', 'IGV' ),
      'desc' => __( '', 'IGV' ),
      'id'   => $prefix . 'live_quote_text',
      'type' => 'textarea_code',
    ),
    array(
      'name' => __( 'Live page quote person', 'IGV' ),
      'desc' => __( '', 'IGV' ),
      'id'   => $prefix . 'live_quote_person',
      'type' => 'text',
    ),
    array(
      'name' => __( 'Home page quote luminary', 'IGV' ),
      'desc' => __( '(optionally relate quote to luminary. Will link the name to their luminary page)', 'IGV' ),
      'id'   => $prefix . 'live_quote_luminary',
      'type' => 'post_search_text',
      'post_type' => array('luminaries'),
      'select_type' => 'radio',
      'select_behavior' => 'replace',
    ),

    // luminaries archive
    array(
      'name' => __( 'Luminaries page', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'luminaries_title',
      'type' => 'title',
    ),
    array(
      'name' => __( 'Luminaries page quote text', 'IGV' ),
      'desc' => __( '', 'IGV' ),
      'id'   => $prefix . 'luminaries_quote_text',
      'type' => 'textarea_code',
    ),
    array(
      'name' => __( 'Luminaries page quote person', 'IGV' ),
      'desc' => __( '', 'IGV' ),
      'id'   => $prefix . 'luminaries_quote_person',
      'type' => 'text',
    ),
        array(
      'name' => __( 'Home page quote luminary', 'IGV' ),
      'desc' => __( '(optionally relate quote to luminary. Will link the name to their luminary page)', 'IGV' ),
      'id'   => $prefix . 'luminaries_quote_luminary',
      'type' => 'post_search_text',
      'post_type' => array('luminaries'),
      'select_type' => 'radio',
      'select_behavior' => 'replace',
    ),
  )
);

IGV_init_options_page($page_key, $page_key, $page_title, $metabox);