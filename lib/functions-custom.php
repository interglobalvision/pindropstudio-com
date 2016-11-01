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

  if (itemIsType($item, $type)) {
    if ($classes) {
      $returnClasses = 'active '. $classes;
    } else {
      $returnClasses = 'active';
    }
    return 'class="' . $returnClasses . '"';
  } else {
    if ($classes) {
      return 'class="' . $classes . '"';
    } else {
      return false;
    }
  }
}

// RENDERS

function render_embed_caption($caption) {
  echo '<h5 class="text-align-center margin-top-tiny margin-bottom-tiny">' . $caption . '</h5>';
}