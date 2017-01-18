<?php
get_header();

$luminary = get_query_var('luminary');
?>

<main id="main-content">
  <section id="posts" class="container">

    <div class="grid-row margin-bottom-small">
      <?php render_divider('<span class="drawer-toggle u-pointer" data-drawer-id="drawer-luminaries-sort">Sort Luminaries +</span>'); ?>
    </div>

    <div id="drawer-luminaries-sort" class="<?php if (empty($luminary)) {echo 'drawer-content ';} ?>margin-bottom-basic">
      <div class="grid-row">
        <div class="grid-item item-s-12">
          <ul id="luminaries-sort-list">
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
          <li><a href="?luminary=<?php the_id(); ?>" <?php if ($luminary == $post->ID) {echo 'class="font-underline"';} ?>><?php the_title(); ?></a></li>
<?php
  }
}
?>
          </ul>
        </div>
      </div>
    </div>

    <div class="shuffle-section media-items">
      <div class="shuffle-preloader"></div>
      <div class="shuffle-container grid-row hidden">

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
    get_template_part('partials/custom-pages/event-media');
?>


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
    </div>
  </section>

  <?php get_template_part('partials/pagination'); ?>

</main>

<?php
get_footer();
?>
