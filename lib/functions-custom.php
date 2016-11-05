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

function render_embed_caption($caption) {
  echo '<h5 class="text-align-center margin-top-tiny margin-bottom-tiny">' . $caption . '</h5>';
}


function render_quote($text = null, $person = null, $luminary = null) {
  if ($text === null || $person === null) {
    return false;
  }
?>
<div class="grid-item item-s-1 offset-s-1">
  <div class="quote-pin-holder">
    <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-pin.svg'); ?>
  </div>
</div>
<div class="grid-item item-s-8">
  <div class="quote-text font-size-h2 font-italic">
    <?php echo $text; ?>
  </div>
  <div class="quote-person margin-top-tiny text-align-right font-style-micro font-size-small">
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

