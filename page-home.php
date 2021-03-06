<?php
get_header();
?>

<main id="main-content">

  <div id="page-home" class="container">

    <?php
      if (IGV_get_option('_igv_home_options', '_igv_carousel_shown')) {
        get_template_part('partials/custom-pages/home/carousel');
      }
    ?>

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

    <div class="grid-row margin-top-basic margin-bottom-small">
      <?php render_divider('<a href="' . home_url('live') . '">Forthcoming Live Events +</a>'); ?>
    </div>

    <div class="grid-row justify-center margin-bottom-mid js-match-height-group" data-match-height-targets='["event-forthcoming-title", "event-forthcoming-datetime", "event-forthcoming-buttons"]'>
    <?php
        while ($forthcoming_events->have_posts()) {
          $forthcoming_events->the_post();
          get_template_part('partials/custom-pages/event-forthcoming');
        }
    ?>
    </div>

    <?php
        wp_reset_postdata();
      }

      $ad1_text = IGV_get_option('_igv_home_options', '_igv_ad1_text');
      $ad1_image_id = IGV_get_option('_igv_home_options', '_igv_ad1_image_id');
      $ad1_link = IGV_get_option('_igv_home_options', '_igv_ad1_link');
      $ad1_link_autoplay = IGV_get_option('_igv_home_options', '_igv_ad1_link_media');
      $ad1_link_external = IGV_get_option('_igv_home_options', '_igv_ad1_link_external');

      if ($ad1_text && $ad1_image_id) {
    ?>
    <div class="grid-row margin-bottom-mid">
      <?php render_ad($ad1_text, $ad1_image_id, $ad1_link, $ad1_link_autoplay, $ad1_link_external); ?>
    </div>
    <?php
      }
    ?>

    <div class="grid-row margin-bottom-small">
      <?php render_divider('<a href="' . home_url('sound-and-vision') . '">Sound & Vision +</a>'); ?>
    </div>

    <div class="shuffle-section media-items margin-bottom-basic">
      <div class="shuffle-preloader"></div>
      <div class="shuffle-container grid-row hidden js-match-height-group" data-match-height-targets='["media-post-title"]'>

    <?php
      $overrides = IGV_get_option('_igv_home_options', '_igv_override_events');
      $total_posts = 3;
      $posts_needed = $total_posts;
      $not_in = array();

      if ($overrides) {
        $overrides = explode(', ', $overrides);

        $posts_needed = $posts_needed - count($overrides);
        $not_in = $overrides;
      }

      if ($posts_needed > 0) {

        $past_args = array(
          'post_type' => array('event', 'recording'),
          'posts_per_page' => $posts_needed,
          'post__not_in' => $not_in,

          'meta_query' => array(
            array(
              'key'     => '_igv_event_datetime',
              'value'   => $midnight_timestamp,
              'type'    => 'numeric',
              'compare' => '<',
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
        'post_type' => array('event', 'recording'),
        'posts_per_page' => $total_posts,
        'post__in' => $past_event_ids,
        'meta_query' => array(
          array(
            'key'     => '_igv_event_datetime',
            'value'   => $midnight_timestamp,
            'type'    => 'numeric',
            'compare' => '<',
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
      ));

      if ($past_events->have_posts()) {
        while ($past_events->have_posts()) {
          $past_events->the_post();
          get_template_part('partials/custom-pages/event-media');
        }
        wp_reset_postdata();
      }
    ?>
    </div>

    <?php
      $quote_text = IGV_get_option('_igv_quote_options', '_igv_home_quote_text');
      $quote_person = IGV_get_option('_igv_quote_options', '_igv_home_quote_person');
      $quote_luminary = IGV_get_option('_igv_quote_options', '_igv_home_quote_luminary');

      if ($quote_text && $quote_person) {
    ?>
    <div class="grid-row margin-top-large">
      <?php render_quote($quote_text, $quote_person, $quote_luminary); ?>
    </div>
    <?php
      }
    ?>

    <?php
      if (IGV_get_option('_igv_home_options', '_igv_tall_ads_shown')) {
        get_template_part('partials/custom-pages/home/tall-ads');
      }
    ?>

    <?php get_template_part('partials/connect'); ?>

    <?php
      $ad2_text = IGV_get_option('_igv_home_options', '_igv_ad2_text');
      $ad2_image_id = IGV_get_option('_igv_home_options', '_igv_ad2_image_id');
      $ad2_link = IGV_get_option('_igv_home_options', '_igv_ad2_link');
      $ad2_link_autoplay = IGV_get_option('_igv_home_options', '_igv_ad2_link_media');
      $ad2_link_external = IGV_get_option('_igv_home_options', '_igv_ad2_link_external');

      if ($ad2_text && $ad2_image_id) {
    ?>
    <div class="grid-row margin-bottom-basic">
      <?php render_ad($ad2_text, $ad2_image_id, $ad2_link, $ad2_link_autoplay, $ad2_link_external); ?>
    </div>
    <?php
      }
    ?>

    <?php
      $overrides = IGV_get_option('_igv_home_options', '_igv_override_luminaries');
      $total_posts = 4;
      $posts_needed = $total_posts;
      $not_in = array();

      if ($overrides) {
        $overrides = explode(', ', $overrides);

        $posts_needed = $posts_needed - count($overrides);
        $not_in = $overrides;
      }

      if ($posts_needed > 0) {

        $recent_luminaries = get_posts(array(
          'post_type' => array('luminaries'),
          'posts_per_page' => $posts_needed,
          'post__not_in' => $not_in,
          'orderby' => 'menu_order',
        ));

        $luminaries_ids = array_map('array_map_filter_ids', $recent_luminaries);

        if ($overrides) {
          $luminaries_ids = array_merge($overrides, $luminaries_ids);
        }

      } else {

        $luminaries_ids = $overrides;

      }

      $args = array(
        'post_type' => array('luminaries'),
        'posts_per_page' => $total_posts,
        'post__in' => $luminaries_ids,
        'orderby' => 'menu_order',
      );

      $recent_luminaries = new WP_Query($args);

      if ($recent_luminaries->have_posts()) {
    ?>
    <div class="grid-row margin-top-large margin-bottom-small">
      <?php render_divider('<a href="' . home_url('luminaries') . '">Featured Luminaries +</a>'); ?>
    </div>

    <div class="grid-row">
    <?php
        while ($recent_luminaries->have_posts()) {
          $recent_luminaries->the_post();
          get_template_part('partials/custom-pages/home/luminary-recent');
        }
    ?>
    </div>
    <?php
      }
    ?>

  </div>

</main>

<?php
get_footer();
?>
