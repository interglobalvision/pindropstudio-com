<?php
get_header();

$luminary = get_query_var('luminary');
?>

<main id="main-content">
  <section id="posts" class="container">

    <div class="grid-row margin-bottom-basic">
      <?php render_divider('<span class="drawer-toggle u-pointer" data-drawer-id="drawer-luminaries-sort">Sort Luminaries +</span>'); ?>
    </div>

    <div id="drawer-luminaries-sort" class="<?php if (empty($luminary)) {echo 'drawer-content ';} ?>margin-bottom-basic">
      <div class="grid-row">
<?php
$luminaries = new WP_Query(array(
  'post_type' => 'luminaries',
  'posts_per_page' => -1,
  'order' => 'ASC',
  'orderby' => 'meta_value',
  'meta_key' => '_igv_surname'
));

if ($luminaries->have_posts()) {
  while ($luminaries->have_posts()) {
    $luminaries->the_post();
?>
        <div class="grid-item item-s-6 item-m-4 item-l-3">
          <a href="?luminary=<?php the_id(); ?>" <?php if ($luminary == $post->ID) {echo 'class="font-underline"';} ?>><?php the_title(); ?></a>
        </div>
<?php
  }
}
?>
      </div>
    </div>

    <div id="shuffle-preloader"></div>
    <div id="shuffle-container" class="grid-row hidden">

<?php
if (!empty($luminary)) {
  // filter query. gets events which have video or audio meta set and orders them by the datetime meta (which also needs to be set)
  $args = array(
    'post_type' => 'event',
    'posts_per_page' => get_option('posts_per_page'),
    'meta_query' => array(
      array(
          'key' => '_igv_related_luminaries',
          'value' => $luminary,
          'compare' => 'IN'
      ),
      array(
        'relation' => 'OR',
        array(
          'key' => '_igv_vimeo_id',
          'compare' => 'EXISTS'
        ),
        array(
          'key' => '_igv_soundcloud_url',
          'compare' => 'EXISTS'
        )
      )
    ),
    'meta_key' => '_igv_event_datetime',
    'orderby' => 'meta_value_num',
  );

} else {
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
}

$events_query = new WP_Query($args);

if( $events_query->have_posts() ) {
  while( $events_query->have_posts() ) {
    $events_query->the_post();

    $time = get_post_meta($post->ID, '_igv_event_datetime', true);
    $time_moment = new \Moment\Moment('@' . $time);

    $location = get_post_meta($post->ID, '_igv_event_location', true);

    $soundcloud_url = get_post_meta($post->ID, '_igv_soundcloud_url', true);
    $vimeo_id = get_post_meta($post->ID, '_igv_vimeo_id', true);
?>
        <article <?php post_class('media-item shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>" <?php
          if ($soundcloud_url) {
            echo 'data-soundcloud="' . $soundcloud_url . '"';
          }

          if ($vimeo_id) {
            echo 'data-vimeo="' . $vimeo_id . '"';
          }
        ?>>
          <div class="card">
            <div class="media-item-image-holder u-pointer margin-bottom-tiny">
              <?php the_post_thumbnail('l-4', array('class' => 'media-item-image')); ?>
            </div>

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
        <article class="u-alert shuffle-item item-s-12">
          <?php
            if (!empty($luminary)) {
              _e('Sorry, this luminary has no recordings yet');
            } else {
              _e('Sorry, no posts matched your criteria');
            }
          ?>
        </article>
<?php
} ?>

    </div>
  </section>

  <?php get_template_part('partials/pagination'); ?>

</main>

<?php
get_footer();
?>