<?php

// Custom filters (like pre_get_posts etc)

// Show all posts on luminaries archive
function luminaries_archive_query($query) {
  if ($query->is_post_type_archive('luminaries') && is_archive()) {
    $query->set('posts_per_page', -1);
    $query->set('orderby', 'menu_order');
  }
}
add_action('pre_get_posts','luminaries_archive_query');

// Show menu_order in post lists

function add_menuorder_col($cols){
  $cols['menuorder'] = __('Sort Order');
  return $cols;
}
add_filter('manage_luminaries_posts_columns', 'add_menuorder_col');

function display_menuorder_col($col, $id){
  switch($col) {
    case 'menuorder':

    global $post;
    echo $post->menu_order;

    break;
  }
}
add_action('manage_posts_custom_column', 'display_menuorder_col', 5, 2);

// Page Slug Body Class
function add_slug_body_class( $classes ) {
  global $post;
  if (isset($post) && !is_home() && !is_archive()) {
    $classes[] = $post->post_type . '-' . $post->post_name;
  }
  return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

// Custom img attributes to be compatible with lazysize
function add_lazysize_on_srcset($attr) {

  if (!is_admin()) {

    // if image has data-no-lazysizes attribute dont add lazysizes classes
    if (isset($attr['data-no-lazysizes'])) {
      unset($attr['data-no-lazysizes']);
      return $attr;
    }

    // Add lazysize class
    $attr['class'] .= ' lazyload';

    if (isset($attr['srcset'])) {
      // Add lazysize data-srcset
      $attr['data-srcset'] = $attr['srcset'];
      // Remove default srcset
      unset($attr['srcset']);
    } else {
      // Add lazysize data-src
      $attr['data-src'] = $attr['src'];
    }

    // Set default to white blank
    $attr['src'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAABCAQAAABTNcdGAAAAC0lEQVR42mNkgAIAABIAAmXG3J8AAAAASUVORK5CYII=';

  }

  return $attr;

}
add_filter('wp_get_attachment_image_attributes', 'add_lazysize_on_srcset');