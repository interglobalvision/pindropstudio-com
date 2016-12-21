<?php

// RENDERS

function render_share($url) {
  $url = urlencode($url);
?>
<div class="share-widget">
  <span class="link-button share-trigger">Share</span>
  <ul class="button-list share-list">
    <li><a href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noreferrer">Facebook</a></li>
    <li><a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>" target="_blank" rel="noreferrer">Twitter</a></li>
  </ul>
</div>
<?php
}

function render_embed_caption($caption) {
  echo '<h5 class="text-align-center margin-top-tiny margin-bottom-tiny">' . $caption . '</h5>';
}

function render_divider($content = false) {
  if (!$content) {
?>
<div class="grid-item item-s-12">
  <div class="dotted-divider-full"></div>
</div>
<?php
  } else {
?>
<div class="grid-item item-s-12 font-style-micro font-size-small text-align-center">
  <div class="dotted-divider">
    <div class="dotted-divider-side dotted-divider-left"></div>
    <div class="dotted-divider-center">
      <?php echo $content; ?>
    </div>
    <div class="dotted-divider-side dotted-divider-right"></div>
  </div>
</div>
<?php
  }
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

function render_ad($text = null, $image_id = null, $link_id = null, $link_external = null) {
  if ($text === null || $image_id === null) {
    return false;
  }

  $link = false;

  if ($link) {
    $link = '<a href="' . get_permalink($link) . '" class="link-button font-style-micro">Read More</a>';
  } else {
    $link = '<a href="' . $link_external . '" class="link-button font-style-micro" target="_blank" rel="noopener">Read More</a>';
  }
?>
<div class="grid-item item-s-12 wide-size-ad text-align-center">
  <?php echo wp_get_attachment_image($image_id, 'l-12-wide-ad'); ?>
  <h2 class="wide-ad-text font-style-shadow"><?php echo $text; ?></h2>
  <?php
    if ($link) {
  ?>
  <div class="wide-ad-link-holder">
    <?php echo $link; ?>
  </div>
  <?php
    }
  ?>
</div>
<?php
}
