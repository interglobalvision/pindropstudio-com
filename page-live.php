<?php
get_header();
?>

<main id="main-content">

  <div id="page-live" class="container">

    <?php
      $now = new \Moment\Moment('now', 'Europe/London');
      $tomorrow = $now->addDays(1);
      $midnight = $tomorrow->startOf('day');
      $midnight_timestamp = strtotime($midnight->format());

      $forthcoming_events = new WP_Query(array(
        'post_type' => 'event',
        'posts_per_page' => 3,

        'meta_query' => array(
          array(
            'key'     => '_igv_event_datetime',
            'value'   => $midnight_timestamp,
            'type'    => 'numeric',
            'compare' => '>',
          ),
        ),
      ));

      if ($forthcoming_events->have_posts()) {
    ?>

    <div class="grid-row margin-top-basic margin-bottom-mid">
      <?php render_divider('Forthcoming Live Events'); ?>
    </div>
    <?php
        $forthcoming_iterator = 0;
        while ($forthcoming_events->have_posts()) {
          $forthcoming_events->the_post();
          include(locate_template('partials/custom-pages/live/event-forthcoming.php'));
          $forthcoming_iterator++;
        }
    ?>

    <?php
      }
    ?>

    <div class="grid-row margin-top-large margin-bottom-basic">
      <?php render_divider('Past Events'); ?>
    </div>

    <div class="grid-row justify-center margin-top-basic margin-bottom-basic">
    <?php
      $overrides = IGV_get_option('_igv_live_options', '_igv_override_events');
      $posts = 5;
      $not_in = array();

      if ($overrides) {
        $overrides = explode(', ', $overrides);

        $posts = $posts - count($overrides);
        $not_in = $overrides;
      }

      $past_args = array(
        'post_type' => 'event',
        'posts_per_page' => $posts,
        'post__not_in' => $not_in,

        'meta_query' => array(
          array(
            'key'     => '_igv_event_datetime',
            'value'   => $midnight_timestamp,
            'type'    => 'numeric',
            'compare' => '<',
          ),
        ),
      );

      $past_events = get_posts($past_args);

      $filter_ids = function($post) {
        return $post->ID;
      };

      $past_event_ids = array_map($filter_ids, $past_events);

      if ($overrides) {
        $past_events = array_merge($overrides, $past_event_ids);
      } else {
        $past_events = $past_event_ids;
      }

      global $post;
      foreach ($past_events as $post_id) {
        $post = get_post($post_id);
        setup_postdata($post);
        get_template_part('partials/custom-pages/live/event-past');
      }
      wp_reset_postdata();
    ?>
    </div>

    <div class="grid-row margin-top-basic margin-bottom-basic">
      <?php
        $content = '<a href="' . home_url('sound-and-vision') . '" class="link-button">More Sound & Vision Posts +</a>';
        render_divider($content);
      ?>
    </div>

    >>> quote goes here but from other feature branch

  </div>

</main>

<?php
get_footer();
?>
