<article <?php post_class('shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>">
  <div class="card">
    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('l-4-full', array('class' => 'margin-bottom-tiny')); ?></a>

    <a href="<?php the_permalink(); ?>">
      <h4 class="font-style-micro font-size-small margin-bottom-small text-align-center"><?php the_time('d F Y'); ?></h4>
      <h3 class="margin-bottom-small text-align-center"><?php the_title(); ?></h3>
    </a>

    <div class="text-align-center">
      <a class="link-button font-size-small" href="<?php the_permalink(); ?>">Read More</a>
    </div>
  </div>
</article>