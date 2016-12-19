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
      $tomorrow = $now->addDays(1);
      $midnight = $tomorrow->startOf('day');
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
    ?>

    <div class="grid-row margin-top-basic margin-bottom-small">
      <?php render_divider('Forthcoming Live Events'); ?>
    </div>

    <div class="grid-row justify-center">
    <?php
        while ($forthcoming_events->have_posts()) {
          $forthcoming_events->the_post();
          $booking = get_post_meta($post->ID, '_igv_event_booking_url', true);
          $time = get_post_meta($post->ID, '_igv_event_datetime', true);
          $time_moment = new \Moment\Moment('@' . $time);
        ?>
        <div class="grid-item <?php
          if ($forthcoming_events->post_count === 3) {
            echo 'item-s-4 item-m-4';
          } else if ($forthcoming_events->post_count === 2) {
            echo 'item-s-6 item-m-5';
          } else {
            echo 'item-s-12';
          }
        ?> text-align-center">
          <div class="card card-big">
            <a href="<?php the_permalink(); ?>">
              <?php
                if ($forthcoming_events->post_count === 1) {
                  the_post_thumbnail('l-5-square');
                } else {
                  the_post_thumbnail('l-4-square');
                }
              ?>
              <h2 class="margin-top-small margin-bottom-small item-s-6 item-m-5"><?php the_title(); ?></h2>
              <h4 class="margin-bottom-tiny font-style-micro"><?php echo $time_moment->format('H:i'); ?> | <?php echo $time_moment->format('l'); ?></h4>
              <h1 class="font-size-big-number margin-bottom-tiny"><?php echo $time_moment->format('d'); ?></h1>
              <h4 class="font-style-micro"><?php echo $time_moment->format('F'); ?></h4>
            </a>
            <?php
              if ($booking) {
            ?>
              <div class="margin-top-basic text-align-center"><a href="<?php echo $booking; ?>" class="link-button font-style-micro" target="_blank" rel="noopener">Booking Now</a></div>
            <?php
              }
            ?>
          </div>
        </div>
    <?php
        }
    ?>
    </div>

    <div class="grid-row margin-top-basic margin-bottom-large">
      <?php render_divider('<a href="' . home_url('live') . '" class="link-button">More Live Events +</a>'); ?>
    </div>

    <?php
      }

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
    <div class="grid-row margin-top-large margin-bottom-small">
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
