<?php

// RENDERS

function render_share($url) {
  $url = urlencode($url);
?>
<div class="share-widget font-size-small">
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

  if ($link_id) {
    $link = '<a href="' . get_permalink($link_id) . '">';
  } else {
    $link = '<a href="' . $link_external . '" target="_blank" rel="noopener">';
  }
?>
<div class="grid-item item-s-12 wide-size-ad text-align-center">
  <?php echo $link; ?>
    <?php echo wp_get_attachment_image($image_id, 'l-12-wide-ad'); ?>
    <h2 class="wide-ad-text font-style-shadow"><?php echo $text; ?></h2>
    <?php
      if ($link) {
    ?>
    <div class="wide-ad-link-holder">
      <span class="link-button font-style-micro">Read More</span>
    </div>
    <?php
      }
    ?>
  </a>
</div>
<?php
}

function render_tall_ad($image, $text, $subtitle, $link_internal, $link_external, $link_text) {
?>
<div class="grid-item item-s-12 item-m-6 margin-bottom-small">
<?php
  if ($link_internal && $link_text) {
    echo '<a href="' . get_permalink($link_internal) . '">';
  } else if ($link_external && $link_text) {
    echo '<a href="' . $link_external . '">';
  }
?>
  <div class="card card-big text-align-center">
<?php
  if ($image) {
    echo wp_get_attachment_image($image, 'l-4-tall-ad', false, array('class' => 'margin-top-small margin-bottom-basic'));
  }

  if ($subtitle) {
    echo '<h4 class="margin-bottom-small">' . $subtitle . '</h4>';
  }

  if ($text) {
    echo '<h3 class="tall-ad-title margin-bottom-small">' . $text . '</h3>';
  }

  if (($link_internal && $link_text) || ($link_external && $link_text)) {
    echo '<span class="link-button">' . $link_text . '</span>';
  }
?>
  </div>
<?php
  if (($link_internal && $link_text) || ($link_external && $link_text)) {
    echo '</a>';
  }
?>
</div>
<?php
}

function render_hidden_gallery($gallery, $post_id) {
  if ($gallery === null || $post_id === null) {
    return false;
  }

  preg_match('/\[gallery.*ids=.(.*).\]/', $gallery, $gallery_ids);
  $gallery_ids = explode(",", $gallery_ids[1]);

  $selector = "gallery-{$post_id}";

  $i = 0;

  $output = "<div id='$selector' class='gallery galleryid-{$post_id} container u-hidden'><nav class='swiper-nav swiper-button-prev'><div>" . url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-gallery-arrow-prev.svg') . "</div></nav><nav class='swiper-nav swiper-button-next'><div>" . url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-gallery-arrow-next.svg') . "</div></nav><div class='swiper-wrapper'>";

  foreach ($gallery_ids as $id => $attachment_id) {

    $tag = '';

    $img = wp_get_attachment_image($attachment_id, 'lightbox');

    // If caption is set make a variable of it

    /*  WHAT ABUOT CAPTIONS ??
    if ( $captiontag && trim($attachment->post_excerpt) ) {
      $tag = "
        <{$captiontag} class='wp-caption-text gallery-caption'>
        " . wptexturize($attachment->post_excerpt) . "
        </{$captiontag}>";
    } else {
      $tag = null;
    }
     */

    $output .= "<div class='swiper-slide'>{$img}</div>";
  }

  // Finish markup and return

  $output .= "</div><div class='swiper-pagination'></div></div>\n";

  echo $output;

}

function render_soundcloud_embed($url) {
  $url_parts = explode('/',$url);

  // Check for token on url
  if (sizeof($url_parts) == 6) {
    // Replace token in path as a parameter
    $url = str_replace($url_parts[5], '?secret_token=' . $url_parts[5], $url);
  }

  // Encode url
  $url = urlencode($url);

  // Form src url
  $src = 'https://w.soundcloud.com/player/?url='. $url .'&amp;color=000&amp;auto_play=false&amp;hide_related=true&amp;show_comments=false&amp;show_user=false&amp;show_reposts=false';

  // Echo embed
  echo '<iframe src="' . $src . '" width="100%" height="120" scrolling="no" frameborder="no"></iframe>';
}

function render_partners() {
  $partners = IGV_get_option('_igv_about_options', '_igv_partners');

  if (count($partners) === 0) {
    return '';
  }

  $i = 0;
  foreach($partners as $partner) {
    if ($i % 2 === 0 && $i != 0) {
?>
</div>
<div class="grid-row margin-bottom-mid">
<?php render_divider(); ?>
</div>
<div class="grid-row margin-bottom-mid">
<?php
    }
?>
<div class="grid-item item-s-6 text-align-center">
<?php
  if (!empty($partner['_igv_image'])) {
    echo wp_get_attachment_image($partner['_igv_image_id'], 'l-4', false, array('class' => 'margin-bottom-small', 'data' => 'no-lazysizes'));
  } else if (!empty($partner['_igv_name'])) {
    echo '<h3 class="margin-bottom-small">' . $partner['_igv_name'] . '</h3>';
  }

  if (!empty($partner['_igv_text'])) {
    echo '<p>' . $partner['_igv_text'] . '</p>';
  }
?>
</div>
<?php
  $i++;
  }
}
