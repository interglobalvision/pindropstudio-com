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

      $past_event_ids = array_map('array_map_filter_ids', $past_events);

      if ($overrides) {
        $past_event_ids = array_merge($overrides, $past_event_ids);
      }

      $past_events = new WP_Query(array(
        'post_type' => 'event',
        'post__in' => $past_event_ids,
      ));

      if ($past_events->have_posts()) {
        while ($past_events->have_posts()) {
          $past_events->the_post();
          get_template_part('partials/custom-pages/event-past');
        }
      }
    ?>
    </div>

    <div class="grid-row margin-top-basic margin-bottom-basic">
      <?php
        $content = '<a href="' . home_url('event') . '" class="link-button">More Past Events +</a>';
        render_divider($content);
      ?>
    </div>

    <?php
      $quote_text = IGV_get_option('_igv_quote_options', '_igv_live_quote_text');
      $quote_person = IGV_get_option('_igv_quote_options', '_igv_live_quote_person');
      $quote_luminary = IGV_get_option('_igv_quote_options', '_igv_live_quote_luminary');

      if ($quote_text && $quote_person) {
    ?>
    <div class="grid-row margin-top-large">
      <?php render_quote($quote_text, $quote_person, $quote_luminary); ?>
    </div>
    <?php
      }
    ?>

  </div>

</main>

<?php
get_footer();
?>
