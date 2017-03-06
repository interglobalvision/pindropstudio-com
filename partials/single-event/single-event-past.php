<?php
$hide_image = get_post_meta($post->ID, '_igv_no_image', true);
$gallery = get_post_meta($post->ID, '_igv_gallery', true);
$vimeo_id = get_post_meta($post->ID, '_igv_vimeo_id', true);
$soundcloud_url = get_post_meta($post->ID, '_igv_soundcloud_url', true);
$extra_content = get_post_meta($post->ID, '_igv_extra_content', true);
$time_meta = get_post_meta($post->ID, '_igv_event_datetime', true);

if ($time_meta) {
  $time = new \Moment\Moment('@' . $time_meta);
}

$query_string = $_SERVER['QUERY_STRING'];
$autoplay = false;
$autoplay_target = null;

if ($query_string == 'autoplay') {
  $autoplay = true;
}

if ($vimeo_id && $autoplay) {
  $autoplay_target = 'video';
} else if ($soundcloud_url && $autoplay) {
  $autoplay_target = 'audio';
}

?>
<article id="page" <?php post_class('container'); ?>>
<?php
  if (!$hide_image) {
?>
<div class="grid-row margin-bottom-basic">
  <div class="grid-item item-s-12 item-m-10 offset-m-1 item-l-8 offset-l-2 text-align-center">
    <?php
      if ($gallery) {
        echo do_shortcode($gallery);
      } else {
        the_post_thumbnail('l-8');
      } ?>
  </div>
</div>
<?php
  }
?>
<div class="grid-row">
  <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic">

    <h3 class="margin-bottom-small text-align-center"><?php the_title(); ?></h3>
    <?php
      if (is_singular('event')) {
    ?>
    <h4 class="font-style-micro margin-bottom-small text-align-center"><?php echo $time_meta ? $time->format('d F Y') : get_the_time('d F Y'); ?></h4>
    <?php
      }
    ?>

    <div class="post-copy">
      <?php the_content(); ?>
    </div>

    <div class="margin-top-small margin-bottom-small">
      <?php render_share(get_the_permalink()); ?>
    </div>
  </div>
</div>
<?php
  if ($vimeo_id) {
    $video_caption = get_post_meta($post->ID, '_igv_video_caption', true);
    $video_url = 'https://player.vimeo.com/video/' . $vimeo_id . '?title=0&byline=0&portrait=0';

    if ($autoplay_target === 'video') {
      $video_url = $video_url . '&autoplay=1';
    }
?>
<div class="grid-row margin-bottom-mid">
  <?php render_divider(); ?>
</div>
<div id="s-and-v-video" class="grid-row margin-bottom-mid">
  <div class="grid-item item-s-12 item-m-8 offset-m-2">
    <div class="u-video-embed-container">
      <iframe src="<?php echo $video_url; ?>" width="100%" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </div>
    <?php
      if ($video_caption) {
        render_embed_caption($video_caption);
      }
    ?>
  </div>
</div>
<?php
  }

  if ($soundcloud_url) {
    $audio_caption = get_post_meta($post->ID, '_igv_audio_caption', true);
?>
<div class="grid-row margin-bottom-mid">
  <?php render_divider(); ?>
</div>
<div id="s-and-v-audio" class="grid-row margin-bottom-mid">
  <div class="grid-item item-s-12 item-m-8 offset-m-2">
    <?php
      if ($autoplay_target === 'audio') {
        render_soundcloud_embed($soundcloud_url, true);
      } else {
        render_soundcloud_embed($soundcloud_url);
      }
      if ($audio_caption) {
        render_embed_caption($audio_caption);
      }
    ?>
  </div>
</div>
<?php
  }

  if ($extra_content) {
?>
<div class="grid-row margin-bottom-basic">
  <div class="grid-item item-s-12 item-m-8 offset-m-2">
    <?php echo apply_filters('the_content', $extra_content); ?>
  </div>
</div>
<?php
  }
?>
