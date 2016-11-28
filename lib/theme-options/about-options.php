<?php
// SITE OPTIONS
$prefix = '_igv_';
$suffix = '_options';

$page_key = $prefix . 'about' . $suffix;
$page_title = 'People & Partners Options';
$metabox = array(
  'id'         => $page_key, // id used as tab page slug, must be unique
  'title'      => $page_title,
  'show_on'    => array( 'key' => 'options-page', 'value' => array( $page_key ), ), //value must be same as id
  'show_names' => true,
  'fields'     => array(
    // custom page home
    array(
      'name' => __( 'People & Partners', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'home_title',
      'type' => 'title',
    ),
    array(
      'name' => __( 'People', 'IGV' ),
      'desc' => __( '(displays on the About page)', 'IGV' ),
      'id'   => $prefix . 'people',
      'type' => 'group',
      'fields' => array(
        array(
          'name' => __( 'Person Name', 'IGV' ),
          'id'   => $prefix . 'name',
          'type' => 'text',
        ),
        array(
          'name' => __( 'Person Title', 'IGV' ),
          'id'   => $prefix . 'title',
          'type' => 'text',
        ),
        array(
          'name' => __( 'Person Image', 'IGV' ),
          'id'   => $prefix . 'image',
          'type' => 'file',
        ),
        array(
          'name' => __( 'Person Text', 'IGV' ),
          'id'   => $prefix . 'text',
          'type' => 'wysiwyg',
        ),
      ),
    ),
    array(
      'name' => __( 'Partners', 'IGV' ),
      'desc' => __( '(displays on the About page)', 'IGV' ),
      'id'   => $prefix . 'partners',
      'type' => 'group',
      'fields' => array(
        array(
          'name' => __( 'Partner Name', 'IGV' ),
          'id'   => $prefix . 'name',
          'type' => 'text',
        ),
        array(
          'name' => __( 'Partner Text', 'IGV' ),
          'id'   => $prefix . 'text',
          'type' => 'textarea',
        ),
        array(
          'name' => __( 'Partner Image', 'IGV' ),
          'id'   => $prefix . 'image',
          'type' => 'file',
        ),
      ),
    ),
  )
);

IGV_init_options_page($page_key, $page_key, $page_title, $metabox);