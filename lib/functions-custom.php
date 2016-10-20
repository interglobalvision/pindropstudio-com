<?php

// Custom functions (like special queries, etc)

function itemIsType($item = null, $type = null) {
  if (!$item || !$type) {
    return false;
  }

  switch($type) {
    case 'posttype':
      if(is_post_type_archive($item)) {
        return true;
        break;
      } else if (get_post_type() === $item) {
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

  if (itemIsType($item, $type)) {
    if ($classes) {
      $returneClasses = 'active '. $classes;
    } else {
      $returneClasses = 'active';
    }
    return 'class="' . $returneClasses . '"';
  } else {
    if ($classes) {
      return 'class="' . $classes . '"';
    } else {
      return false;
    }
  }
}