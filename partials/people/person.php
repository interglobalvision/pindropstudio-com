<?php
  $title = get_post_meta($post->ID, '_igv_title', true);
  $explode_content = explode( '<!--more-->', $post->post_content );
?>
<article <?php post_class('shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>">
  <div class="card">
    <div>
      <?php the_post_thumbnail('l-4-full', array('class' => 'margin-bottom-tiny', 'data' => 'no-lazysizes')); ?>
    </div>

    <header class="margin-bottom-small text-align-center">
      <h3><?php the_title(); ?></h3>

      <?php if ($title) { ?>
        <h4 class="font-style-micro font-size-small margin-top-tiny"><?php echo $title; ?></h4>
      <?php } ?>
    </header>

    <?php
    if (sizeof($explode_content) > 1) {
      $content_before = apply_filters( 'the_content', $explode_content[0] );
      $content_after = apply_filters( 'the_content', $explode_content[1] );
    ?>
      <div class="expandable-post">
        <div class="expandable-excerpt post-copy">
          <?php echo $content_before; ?>
        </div>
          <p class="text-align-right"><a class="font-style-micro font-uppercase font-size-small u-pointer expandable-toggle" data-exapandable-id="expandable-<?php echo $post->ID; ?>">Read more</a></p>
        <div id="expandable-<?php echo $post->ID; ?>" class="expandable-content post-copy">
          <?php echo $content_after; ?>
        </div>
      </div>
    <?php
    } else {
      the_content();
    }
    ?>
  </div>
</article>