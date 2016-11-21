<?php
get_header();
?>

<main id="main-content">
  <section id="posts" class="container">

    <div class="grid-row margin-bottom-basic">
      <?php render_divider(); ?>
    </div>

    <div id="shuffle-preloader"></div>
    <div id="shuffle-container" class="grid-row hidden">

<?php

// basic query. gets events which have video or audio meta set and orders them by the datetime meta (which also needs to be set)
$args = array(
  'post_type' => 'event',
  'posts_per_page' => get_option('posts_per_page'),
  'meta_query' => array(
    'relation' => 'OR',
    array(
      'key' => '_igv_vimeo_id',
      'compare' => 'EXISTS'
    ),
    array(
      'key' => '_igv_soundcloud_url',
      'compare' => 'EXISTS'
    )
  ),
  'meta_key' => '_igv_event_datetime',
  'orderby' => 'meta_value_num',
);

$events_query = new WP_Query($args);

if( $events_query->have_posts() ) {
  while( $events_query->have_posts() ) {
    $events_query->the_post();

    $time = get_post_meta($post->ID, '_igv_event_datetime', true);
    $time_moment = new \Moment\Moment('@' . $time);

    $location = get_post_meta($post->ID, '_igv_event_location', true);
?>
        <article <?php post_class('shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>">
          <div class="card">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail('l-4', array('class' => 'margin-bottom-tiny')); ?>
            </a>

            <a href="<?php the_permalink(); ?>">
              <h4 class="font-style-micro font-size-small margin-bottom-small text-align-center"><?php
                echo $time_moment->format('d F Y');

                if ($location) {
                  echo ' | ' . $location;
                }
              ?></h4>
              <h3 class="margin-bottom-small text-align-center"><?php the_title(); ?></h3>
            </a>

            <div class="text-align-center">
              <a class="link-button" href="<?php the_permalink(); ?>">Read More</a>
            </div>
          </div>
        </article>

<?php
  }
} else {
?>
        <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no posts matched your criteria'); ?></article>
<?php
} ?>

    </div>
  </section>

  <?php get_template_part('partials/pagination'); ?>

</main>

<?php
get_footer();
?>