<?php
get_header();
?>

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();

    $gallery = get_post_meta($post->ID, '_igv_gallery', true);
    $vimeo_id = get_post_meta($post->ID, '_igv_vimeo_id', true);
    $soundcloud_url = get_post_meta($post->ID, '_igv_soundcloud_url', true);
    $extra_content = get_post_meta($post->ID, '_igv_extra_content', true);
    $luminaries = get_post_meta($post->ID, '_igv_related_luminaries', true);
    $time_meta = get_post_meta($post->ID, '_igv_event_datetime', true);

    if ($time_meta) {
      $time = new \Moment\Moment('@' . $time_meta);
    }

  ?>
  <article id="page" <?php post_class('container'); ?>>
    <div class="grid-row margin-top-basic margin-bottom-basic">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 text-align-center">
        <?php
          if ($gallery) {
            echo do_shortcode($gallery);
          } else {
            the_post_thumbnail('l-8');
          } ?>
      </div>
    </div>
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic">

        <h3 class="margin-bottom-small text-align-center"><?php the_title(); ?></h3>
        <h4 class="font-style-micro margin-bottom-small text-align-center"><?php echo $time_meta ? $time->format('d F Y') : get_the_time('d F Y'); ?></h4>

        <?php the_content(); ?>
      </div>
    </div>
    <?php
      if ($vimeo_id) {
        $video_caption = get_post_meta($post->ID, '_igv_video_caption', true);
    ?>
    <div class="grid-row margin-bottom-mid">
      <?php render_divider(); ?>
    </div>
    <div class="grid-row margin-bottom-mid">
      <div class="grid-item item-s-12 item-m-8 offset-m-2">
        <div class="u-video-embed-container">
          <iframe src="https://player.vimeo.com/video/<?php echo $vimeo_id; ?>?title=0&byline=0&portrait=0" width="100%" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
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
    <div class="grid-row margin-bottom-mid">
      <div class="grid-item item-s-12 item-m-8 offset-m-2">
        <iframe src="https://w.soundcloud.com/player/?url=<?php echo urlencode($soundcloud_url); ?>&amp;color=000&amp;auto_play=false&amp;hide_related=true&amp;show_comments=false&amp;show_user=false&amp;show_reposts=false" width="100%" height="120" scrolling="no" frameborder="no"></iframe>
        <?php
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

      if ($luminaries) {
        $luminaries = explode(', ', $luminaries);
    ?>
    <div class="grid-row justify-center margin-bottom-basic">
    <?php
        global $post;
        foreach($luminaries as $post_id) {
          $post = get_post($post_id);
          setup_postdata($post);
    ?>
      <div class="grid-item item-s-12 item-m-4 text-align-center">
        <a href="<?php the_permalink(); ?>">
          <?php the_post_thumbnail('l-4-square', array('class' => 'margin-bottom-basic')); ?>
          <h3 class="margin-bottom-small"><?php the_title(); ?></h3>
        </a>
      </div>
    <?php
        }
        wp_reset_postdata();
    ?>
    </div>
    <?php
      }

      get_template_part('partials/related-posts');
    ?>
  </article>
<?php
  }
} else {
?>
  <section id="page" class="container">
    <div class="grid-row">
      <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no pages matched your criteria'); ?></article>
    </div>
  </section>
<?php
} ?>

</main>

<?php
get_footer();
?>
