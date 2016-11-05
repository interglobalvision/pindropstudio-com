<?php
get_header();
?>

<main id="main-content">

  <div id="page-home" class="container">

    <div class="grid-row">
    //>>> carousel
    </div>

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

    <div class="grid-row margin-top-basic margin-bottom-small">
      <?php render_divider('Forthcoming Live Events'); ?>
    </div>

    <div class="grid-row">
    <?php
        while ($forthcoming_events->have_posts()) {
          $forthcoming_events->the_post();
          get_template_part('partials/custom-pages/home/event-forthcoming');
        }
      }
    ?>
    </div>

    <div class="grid-row margin-top-basic margin-bottom-large">
      <?php render_divider('<a href="' . home_url('live') . '" class="link-button">More Live Events +</a>'); ?>
    </div>

    <?php
      $ad1_text = IGV_get_option('_igv_home_options', '_igv_ad1_text');
      $ad1_image_id = IGV_get_option('_igv_home_options', '_igv_ad1_image_id');
      $ad1_link = IGV_get_option('_igv_home_options', '_igv_ad1_link');
      $ad1_link_external = IGV_get_option('_igv_home_options', '_igv_ad1_link_external');

      if ($ad1_text && $ad1_image_id) {
    ?>
    <div class="grid-row margin-bottom-mid">
      <?php render_ad($ad1_text, $ad1_image_id, $ad1_link, $ad1_link_external); ?>
    </div>
    <?php
      }
    ?>

    <div class="grid-row margin-bottom-small">
      <?php render_divider('Sound & Vision'); ?>
    </div>

    <div class="grid-row justify-center margin-bottom-basic">
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
        get_template_part('partials/custom-pages/event-past');
      }
      wp_reset_postdata();
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

    <div class="grid-row">
    //>>> 2 ads for products?
    </div>

    <div class="grid-row">
    //>>> connect with pindrop
    </div>

    <?php
      $ad2_text = IGV_get_option('_igv_home_options', '_igv_ad2_text');
      $ad2_image_id = IGV_get_option('_igv_home_options', '_igv_ad2_image_id');
      $ad2_link = IGV_get_option('_igv_home_options', '_igv_ad2_link');
      $ad2_link_external = IGV_get_option('_igv_home_options', '_igv_ad2_link_external');

      if ($ad2_text && $ad2_image_id) {
    ?>
    <div class="grid-row margin-bottom-mid">
      <?php render_ad($ad2_text, $ad2_image_id, $ad2_link, $ad2_link_external); ?>
    </div>
    <?php
      }
    ?>

    <?php
      $args = array(
        'posts_per_page' => 4,
        'post_type' => 'luminaries',
      );

      $recent_luminaries = new WP_Query($args);

      if ($recent_luminaries->have_posts()) {
    ?>
    <div class="grid-row margin-top-basic margin-bottom-small">
      <?php render_divider('Recent Luminaries'); ?>
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
