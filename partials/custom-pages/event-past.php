<?php
  $title_override = get_post_meta($post->ID, '_igv_alt_title', true);
?>
<div class="grid-item item-s-12 item-m-4 margin-bottom-small text-align-center">
  <div class="card">
    <a href="<?php the_permalink(); ?>">
      <div class="margin-bottom-tiny">
        <?php the_post_thumbnail('l-4-landscape'); ?>
      </div>
    </a>
    <a href="<?php the_permalink(); ?>">
      <h3 class="margin-bottom-tiny js-fix-widows"><?php
      if (!empty($title_override) && is_page(('home'))) {
        echo $title_override;
      } else {
        the_title();
      } ?></h3>
    </a>
    <div class="text-align-center"><a href="<?php the_permalink(); ?>" class="link-button font-size-small">Read More</a></div>
  </div>
</div>