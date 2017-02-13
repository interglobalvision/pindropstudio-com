<?php
  $related_posts = get_post_meta($post->ID, '_igv_related_posts', true);
  if ($related_posts) {
    $related_posts = explode(', ', $related_posts);
?>
<div class="grid-row margin-bottom-small">
  <?php render_divider('Related'); ?>
</div>
<div class="shuffle-section media-items margin-bottom-small">
  <div class="shuffle-preloader"></div>
  <div class="shuffle-container grid-row hidden">
<?php
    global $post;
    foreach($related_posts as $post_id) {
      $post = get_post($post_id);
      if ($post) {
        setup_postdata($post);
        if (get_post_type($post->ID) === 'event') {
          get_template_part('partials/custom-pages/event-media');
        } else {
          get_template_part('partials/shuffle/shuffle-post');
        }
        wp_reset_postdata();
      }
    }
?>
</div>
<?php
  }