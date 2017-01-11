<?php $title = get_post_meta($post->ID, '_igv_title', true); ?>
<article <?php post_class('shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>">
  <div class="card">
    <div>
      <?php the_post_thumbnail('l-4-full', array('class' => 'margin-bottom-tiny')); ?>
    </div>

    <header class="margin-bottom-small text-align-center">
      <h3><?php the_title(); ?></h3>

      <?php if ($title) { ?>
        <h4 class="font-style-micro font-size-small margin-top-tiny"><?php echo $title; ?></h4>
      <?php } ?>
    </header>

    <?php the_content(); ?>
  </div>
</article>