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

    <div class="grid-row margin-top-basic margin-bottom-basic">
      <div class="grid-item item-s-12 font-style-micro text-align-center">
        <div class="dotted-divider">
          <div class="dotted-divider-side dotted-divider-left"></div>
          <div class="dotted-divider-center">Forthcoming Live Events</div>
          <div class="dotted-divider-side dotted-divider-right"></div>
        </div>
      </div>
    </div>
    <?php
        $forthcoming_iterator = 0;
        while ($forthcoming_events->have_posts()) {
          $forthcoming_events->the_post();
          get_template_part('partials/custom-pages/live/event-forthcoming');
          $forthcoming_iterator++;
        }
    ?>

    <?php
      }
    ?>

    >>> past events go here. with the first posts optionally set in a site option but the total of posts always equalling 5.

    >>> quote goes here but from other feature branch

  </div>

</main>

<?php
get_footer();
?>