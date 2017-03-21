<?php
get_header();
?>

<main id="main-content">

  <div id="page-live" class="container">

    <?php
      $now = new \Moment\Moment('now', 'Europe/London');
      $midnight = $now->startOf('day');
      $midnight_timestamp = strtotime($midnight->format());

      $forthcoming_events = new WP_Query(array(
        'post_type' => 'event',
        'posts_per_page' => 3,
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_key' => '_igv_event_datetime',

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
        $post_count = $forthcoming_events->post_count;
    ?>

    <div class="grid-row margin-bottom-small">
      <?php render_divider('Forthcoming Live Events'); ?>
    </div>

    <div class="grid-row justify-center js-match-height-group" data-match-height-targets='["event-forthcoming-title", "event-forthcoming-datetime", "event-forthcoming-buttons"]'>
    <?php
        while ($forthcoming_events->have_posts()) {
          $forthcoming_events->the_post();
          get_template_part('partials/custom-pages/event-forthcoming');
        }
    ?>
    </div>

    <?php
      }
    ?>

    <div class="grid-row margin-top-large margin-bottom-basic">
      <?php render_divider('<a href="' . home_url('event') . '">Past Events +</a>'); ?>
    </div>

    <div class="shuffle-section media-items margin-top-basic margin-bottom-large">
      <div class="shuffle-preloader"></div>
      <div class="shuffle-container grid-row hidden">
    <?php
      $overrides = IGV_get_option('_igv_live_options', '_igv_override_events');
      $total_posts = 5;
      $posts_needed = $total_posts;
      $not_in = array();

      if ($overrides) {
        $overrides = explode(', ', $overrides);

        $posts_needed = $posts_needed - count($overrides);
        $not_in = $overrides;
      }

      if ($posts_needed > 0) {

        $past_args = array(
          'post_type' => 'event',
          'posts_per_page' => $posts_needed,
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

      } else {

        $past_event_ids = $overrides;

      }

      $past_events = new WP_Query(array(
        'post_type' => 'event',
        'posts_per_page' => $total_posts,
        'post__in' => $past_event_ids,

        'meta_query' => array(
          array(
            'key'     => '_igv_event_datetime',
            'value'   => $midnight_timestamp,
            'type'    => 'numeric',
            'compare' => '<',
          ),
        ),
      ));

      if ($past_events->have_posts()) {
        while ($past_events->have_posts()) {
          $past_events->the_post();
          get_template_part('partials/custom-pages/event-archive');
        }
      }
    ?>
      </div>
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
