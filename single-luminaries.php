<?php
get_header();
?>

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
  ?>
  <article id="single-luminary" <?php post_class('container'); ?>>
    <div class="grid-row">
      <div class="grid-item item-s-12 text-align-center">
        <?php the_post_thumbnail('l-4-square', array('class' => 'margin-bottom-basic')); ?>
      </div>
    </div>
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-mid">
        <h1 class="text-align-center margin-bottom-small"><?php the_title(); ?></h1>
        <div class="post-copy">
          <?php the_content(); ?>
        </div>
        <div class="margin-top-small margin-bottom-small">
          <?php render_share(get_the_permalink()); ?>
        </div>
      </div>
    </div>
  <?php
    $related_posts = get_post_meta($post->ID, '_igv_related_posts', true);

    $related_post_ids = array();

    if ($related_posts) {
      $related_post_ids = explode(', ', $related_posts);
    }

    $args = array(
      'post_type' => array('event', 'recording'),
      'posts_per_page' => -1,
      'meta_query' => array(
        array(
            'key' => '_igv_related_luminaries',
            'value' => $post->ID,
            'compare' => 'IN'
        )
      ),
      'meta_key' => '_igv_event_datetime',
      'orderby' => 'meta_value_num',
    );

    $luminary_events = new WP_Query($args);

    $luminary_events_ids = array();

    if ($luminary_events->have_posts()) {
      while ($luminary_events->have_posts()) {
        $luminary_events->the_post();
        $luminary_events_ids[] = get_the_ID();
      }
    }

    $post_ids = array_merge($luminary_events_ids, $related_post_ids);
    $post_ids = array_unique($post_ids);

    if (!empty($post_ids)) {
  ?>
  <div class="grid-row margin-bottom-small">
    <?php render_divider('Related'); ?>
  </div>
  <div class="shuffle-section media-items margin-bottom-small">
    <div class="shuffle-preloader"></div>
    <div class="shuffle-container grid-row hidden">
  <?php
      global $post;
      foreach($post_ids as $post_id) {
        $post = get_post($post_id);
        if ($post) {
          setup_postdata($post);
          $post_type = get_post_type($post->ID);
          if ($post_type === 'event' || $post_type === 'recording') {
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
  ?>
  </article>
<?php
  }
} else {
?>
  <section id="single-luminary" class="container">
    <div class="grid-row">
      <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no posts matched your criteria'); ?></article>
    </div>
  </section>
<?php
} ?>

</main>

<?php
get_footer();
?>