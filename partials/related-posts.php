<?php
  $related_posts = get_post_meta($post->ID, '_igv_related_posts', true);
  if ($related_posts) {
    $related_posts = explode(', ', $related_posts);
?>
<div class="grid-row">
  <div class="grid-item item-s-12 margin-bottom-small font-style-micro font-size-small text-align-center">
    <div class="dotted-divider">
      <div class="dotted-divider-side dotted-divider-left"></div>
      <div class="dotted-divider-center font-uppercase">Related</div>
      <div class="dotted-divider-side dotted-divider-right"></div>
    </div>
  </div>
<?php
    global $post;
    foreach($related_posts as $post_id) {
      $post = get_post($post_id);
      setup_postdata($post);
?>
  <div class="grid-item item-s-12 item-m-4 text-align-center">
    <div class="card">
      <a href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail('l-4', array('class' => 'margin-bottom-tiny')); ?>
        <h3 class="margin-bottom-small"><?php the_title(); ?></h3>
      </a>
      <a href="<?php the_permalink(); ?>" class="link-button font-uppercase" >Read More</a>
    </div>
  </div>
<?php
    }
    wp_reset_postdata();
?>
</div>
<?php
  }
