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
      'name' => __( 'Home Carousel', 'IGV' ),
      'desc' => __( 'Choose up to 3 posts here to show on the carousel', 'IGV' ),
      'id'   => $prefix . 'carousel_posts',
      'type' => 'group',
      'fields' => array(
        array(
          'name' => __( 'Post', 'IGV' ),
          'desc' => __( 'Pick a post', 'IGV' ),
          'id'   => $prefix . 'carousel_post_id',
          'type' => 'post_search_text',
          'post_type' => array('post', 'event', 'luminary'),
          'select_behavior' => 'replace',
          'select_type' => 'radio',
        ),
        array(
          'name' => __( 'Title override', 'cmb2' ),
          'desc' => __( '', 'cmb2' ),
          'id'   => $prefix . 'carousel_title_override',
          'type' => 'textarea_code',
        ),
        array(
          'name' => __( 'Image override', 'cmb2' ),
          'desc' => __( '', 'cmb2' ),
          'id'   => $prefix . 'carousel_image_override',
          'type' => 'file',
        ),
      ),
    ),

/*
    array(
      'name' => __( 'Choose up to 3 posts here to show on the carousel', 'IGV' ),
      'desc' => __( '', 'IGV' ),
      'id'   => $prefix . 'carousel_posts',
      'type' => 'post_search_text',
      'post_type' => array('post', 'event', 'luminary'),
    ),
*/

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
      'select_behavior' => 'replace',
      'select_type' => 'radio',
    ),
     array(
      'name' => __( 'Ad 1 External Link', 'cmb2' ),
      'desc' => __( 'if linking outside of the website put the URL here', 'cmb2' ),
      'id'   => $prefix . 'ad1_link_external',
      'type' => 'text_url',
    ),
    array(
      'name' => __( 'Ad 2 Text', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'ad2_text',
      'type' => 'text',
    ),
    array(
      'name' => __( 'Ad 2 Image', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'ad2_image',
      'type' => 'file',
    ),
    array(
      'name' => __( 'Ad 2 Internal Link', 'IGV' ),
      'desc' => __( 'if linking inside the website choose the page to link to here', 'IGV' ),
      'id'   => $prefix . 'ad2_link',
      'type' => 'post_search_text',
      'post_type'  => array('post', 'page', 'event', 'luminaries'),
      'select_behavior' => 'replace',
      'select_type' => 'radio',
    ),
     array(
      'name' => __( 'Ad 2 External Link', 'cmb2' ),
      'desc' => __( 'if linking outside of the website put the URL here', 'cmb2' ),
      'id'   => $prefix . 'ad2_link_external',
      'type' => 'text_url',
    ),

    // Product Ads
    array(
      'name' => __( 'Product/Tall Ads', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'tall_ads_title',
      'type' => 'title',
    ),
    array(
      'name' => __( 'Show Product/Tall Ads?', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'tall_ads_shown',
      'type' => 'checkbox',
    ),
    array(
      'name' => __( 'Ad 1 Text', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'tall_ad1_text',
      'type' => 'text',
    ),
    array(
      'name' => __( 'Ad 1 Image', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'tall_ad1_image',
      'type' => 'file',
    ),
    array(
      'name' => __( 'Ad 1 Internal Link', 'IGV' ),
      'desc' => __( 'if linking inside the website choose the page to link to here', 'IGV' ),
      'id'   => $prefix . 'tall_ad1_link',
      'type' => 'post_search_text',
      'post_type'  => array('post', 'page', 'event', 'luminaries'),
      'select_behavior' => 'replace',
      'select_type' => 'radio',
    ),
     array(
      'name' => __( 'Ad 1 External Link', 'cmb2' ),
      'desc' => __( 'if linking outside of the website put the URL here', 'cmb2' ),
      'id'   => $prefix . 'tall_ad1_link_external',
      'type' => 'text_url',
    ),
      array(
      'name' => __( 'Ad 1 Button Text', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'tall_ad1_button',
      'type' => 'text',
    ),
    array(
      'name' => __( 'Ad 2 Text', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'tall_ad2_text',
      'type' => 'text',
    ),
    array(
      'name' => __( 'Ad 2 Image', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'tall_ad2_image',
      'type' => 'file',
    ),
    array(
      'name' => __( 'Ad 2 Internal Link', 'IGV' ),
      'desc' => __( 'if linking inside the website choose the page to link to here', 'IGV' ),
      'id'   => $prefix . 'tall_ad2_link',
      'type' => 'post_search_text',
      'post_type'  => array('post', 'page', 'event', 'luminaries'),
      'select_behavior' => 'replace',
      'select_type' => 'radio',
    ),
     array(
      'name' => __( 'Ad 2 External Link', 'cmb2' ),
      'desc' => __( 'if linking outside of the website put the URL here', 'cmb2' ),
      'id'   => $prefix . 'tall_ad2_link_external',
      'type' => 'text_url',
    ),
      array(
      'name' => __( 'Ad 2 Button Text', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'tall_ad2_button',
      'type' => 'text',
    ),

  )
);

IGV_init_options_page($page_key, $page_key, $page_title, $metabox);
