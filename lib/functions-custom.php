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


function render_quote($text = null, $person = null, $luminary = null) {
  if ($text === null || $person === null) {
    return false;
  }
?>
<div class="grid-item item-s-1 offset-s-1">
  <div id="quote-pin-holder">
    <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-pin.svg'); ?>
  </div>
</div>
<div class="grid-item item-s-8">
  <div class="quote-text">
    <?php echo $text; ?>
  </div>
  <div class="quote-person">
<?php
  if ($luminary) {
?>
    <a href="<?php echo get_permalink($luminary); ?>"><?php echo $person; ?></a>
<?php
  } else {
    echo $person;
  }
?>
  </div>
</div>
<?php
}