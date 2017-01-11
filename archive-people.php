<?php
get_header();
?>

<main id="main-content">
  <section id="posts" class="container">
    <div id="shuffle-preloader"></div>
    <div id="shuffle-container" class="grid-row hidden">

<?php
  $people_core = new WP_Query(array(
    'post_type' => 'people',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'meta_query' => array(
      'relation' => 'OR',
      array(
        'key' => '_igv_circle',
        'value' => 'on',
        'compare' => '!='
      ),
      array(
        'key' => '_igv_circle',
        'compare' => 'NOT EXISTS'
      )
    )
  ));

  $people_circle = new WP_Query(array(
    'post_type' => 'people',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'meta_query' => array(
      array(
        'key' => '_igv_circle',
        'value' => 'on',
        'compare' => '='
      )
    )
  ));

  if ($people_core->have_posts()) {
    while ($people_core->have_posts()) {
      $people_core->the_post();
      $title = get_post_meta($post->ID, '_igv_title', true);
      ?>
      <article <?php post_class('shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>">
        <div class="card">
          <div>
            <?php the_post_thumbnail('l-4-full', array('class' => 'margin-bottom-tiny')); ?>
          </div>

          <?php if ($title) { ?>
            <h4 class="font-style-micro font-size-small margin-bottom-small text-align-center"><?php echo $title; ?></h4>
          <?php } ?>

          <h3 class="margin-top-small margin-bottom-small text-align-center"><?php the_title(); ?></h3>

          <?php the_content(); ?>
        </div>
      </article>
      <?php
    }
  }

  if ($people_circle->have_posts()) {
    while ($people_circle->have_posts()) {
      $people_circle->the_post();
      $title = get_post_meta($post->ID, '_igv_title', true);
      ?>
      <article <?php post_class('shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>">
        <div class="card">
          <div>
            <?php the_post_thumbnail('l-4-full', array('class' => 'margin-bottom-tiny')); ?>
          </div>

          <?php if ($title) { ?>
            <h4 class="font-style-micro font-size-small margin-bottom-small text-align-center"><?php echo $title; ?></h4>
          <?php } ?>

          <h3 class="margin-top-small margin-bottom-small text-align-center"><?php the_title(); ?></h3>

          <?php the_content(); ?>
        </div>
      </article>
      <?php
    }
  }
?>
    </div>
  </section>

</main>

<?php
get_footer();
?>