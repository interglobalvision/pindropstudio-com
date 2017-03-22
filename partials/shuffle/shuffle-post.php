<article <?php post_class('media-item shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>">
  <div class="card">
    <div class="margin-bottom-small">
      <?php the_post_thumbnail('l-4-full', array('class' => 'u-block')); ?>
    </div>

    <a href="<?php the_permalink(); ?>">
      <?php
        if (!is_page() && get_post_type() !== 'luminary') {
      ?>
      <h4 class="font-style-micro font-size-small margin-bottom-small text-align-center">
        <?php the_time('d F Y'); ?>
      </h4>
      <?php
        }
      ?>
      <h3 class="margin-bottom-small text-align-center">
        <?php the_title(); ?>
      </h3>
    </a>

    <div class="text-align-center">
      <a class="link-button font-size-small" href="<?php the_permalink(); ?>">Read More</a>
    </div>
  </div>
</article>