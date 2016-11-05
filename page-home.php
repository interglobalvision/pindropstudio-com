<?php
get_header();
?>

<main id="main-content">

  <div id="page-home" class="container">

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

    <div class="grid-row">
    <?php
        while ($forthcoming_events->have_posts()) {
          $forthcoming_events->the_post();
          get_template_part('partials/custom-pages/home/event-forthcoming.php');
        }
      }
    ?>
    </div>
    //>>> more live events link divider

    //>>> wide ad

    <div class="grid-row">
      <?php render_divider('Sound & Vision'); ?>
    </div>

    //>>> 3 Sound & Vision posts (all from theme options? or override like Live page)

    <div class="grid-row justify-center margin-top-basic margin-bottom-basic">
    <?php
      $overrides = IGV_get_option('_igv_home_options', '_igv_override_events');
      $posts = 3;
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
        get_template_part('partials/custom-pages/home/event-past');
      }
      wp_reset_postdata();
    ?>
    </div>

    //>>> quote goes here

    //>>> 2 ads for products?

    //>>> connect with pindrop

    //>>> wide ad

    <div class="grid-row margin-top-basic margin-bottom-mid">
      <?php render_divider('Recent Luminaries'); ?>
    </div>

    //>>> 4 recent luminaries

  </div>

</main>

<?php
get_footer();
?>
