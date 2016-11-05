<?php
// Custom functions (like special queries, etc)

function itemIsType($item = null, $type = null) {
  if (!$item || !$type) {
    return false;
  }

  switch($type) {
    case 'postype':
      if(is_post_type_archive($item)) {
        return true;
        break;
      } else if (is_singular($item)) {
        return true;
        break;
      }
      break;
    case 'page':
      if(is_page($item)) {
        return true;
        break;
      }
  }
}

function menuActiveClasses($item = null, $type = null, $classes = null) {
  if (!$item || !$type) {
    return false;
  }

  $returnClasses = $classes;

  if (itemIsType($item, $type)) {
    $returnClasses = 'active ' . $returnClasses;
  }

  if (empty($returnClasses)) {
    return false;
  }

  return 'class="' . $returnClasses . '"';
}
