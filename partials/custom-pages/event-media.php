<?php
$members_only = false;

if ($post->post_status === 'private') {
  $members_only = true;
}

$thumb_size = 'l-4-full';

if (is_page('home')) {
  $thumb_size = 'l-4-square';
}

$time = get_post_meta($post->ID, '_igv_event_datetime', true);
$time_moment = new \Moment\Moment('@' . $time);

$location = get_post_meta($post->ID, '_igv_event_location', true);

$soundcloud_url = get_post_meta($post->ID, '_igv_soundcloud_url', true);
$vimeo_id = get_post_meta($post->ID, '_igv_vimeo_id', true);

$title_override = get_post_meta($post->ID, '_igv_alt_title', true);
$subline_override = get_post_meta($post->ID, '_igv_alt_subline', true);
?>
  <article <?php post_class('media-item shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>" <?php

  if ($soundcloud_url) {
    echo 'data-soundcloud="' . $soundcloud_url . '"';
  }

  if ($vimeo_id) {
    echo 'data-vimeo="' . $vimeo_id . '"';
  }
?>>
<div class="card">
  <?php
    if ((!$soundcloud_url && !$vimeo_id) || ($members_only && !is_user_logged_in())) {
  ?>
  <div class="margin-bottom-small">
    <?php the_post_thumbnail($thumb_size, array('class' => 'u-block')); ?>
  </div>
  <?php
    } else {
  ?>
  <div class="media-item-image-holder u-pointer margin-bottom-small">
    <div class="media-item-play-holder">
      <div class="media-item-play">
        <div class="media-item-play-text">
          <span>Play</span>
        </div>
      </div>
    </div>
    <?php the_post_thumbnail($thumb_size, array('class' => 'media-item-image')); ?>
  </div>

  <?php
    }
  ?>

  <a href="<?php the_permalink(); ?>">
    <h4 class="font-style-micro font-size-small margin-bottom-small text-align-center"><?php
      if ($members_only) {
        echo '<span class="font-color-gold">Members</span> | ';
      }

      if ($subline_override) {
        echo $subline_override;
      } else {
        echo $time_moment->format('d F Y');

        if ($location) {
          echo ' | ' . $location;
        }
      }
    ?></h4>
    <h3 class="margin-bottom-small text-align-center"><?php
      if (!empty($title_override) && !is_page('live')) {
        echo apply_filters('the_title', $title_override);
      } else {
        the_title();
      }
    ?></h3>
  </a>

  <div class="text-align-center">
    <ul class="button-list font-size-small">
      <li><a href="<?php the_permalink(); ?>">Read More</a></li>
      <li class="media-stop-button">Stop</li>
    </ul>
  </div>
</div>
</article>
