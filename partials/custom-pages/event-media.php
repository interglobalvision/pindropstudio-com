<?php
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
            <div class="media-item-image-holder u-pointer margin-bottom-tiny">
              <div class="media-item-play-holder">
                <div class="media-item-play">
                <div class="media-item-play-text"><?php
if ($vimeo_id) {
  echo 'Watch';
} else {
  echo 'Play';
} ?></div>
                </div>
              </div>
              <?php the_post_thumbnail('l-4-full', array('class' => 'media-item-image')); ?>
            </div>

            <a href="<?php the_permalink(); ?>">
            <h4 class="font-style-micro font-size-small margin-bottom-small text-align-center"><?php
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
if (!empty($title_override)) {
  echo $title_override;
} else {
  the_title();
} ?></h3>
            </a>

            <div class="text-align-center">
              <a class="link-button font-size-small" href="<?php the_permalink(); ?>">Read More</a>
            </div>
          </div>
        </article>
