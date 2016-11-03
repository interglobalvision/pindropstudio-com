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

    <div class="grid-row margin-top-large margin-bottom-basic">
      <div class="grid-item item-s-12 font-style-micro text-align-center">
        <div class="dotted-divider">
          <div class="dotted-divider-side dotted-divider-left"></div>
          <div class="dotted-divider-center">Past Events</div>
          <div class="dotted-divider-side dotted-divider-right"></div>
        </div>
      </div>
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

      $past_events = array_merge($overrides, $past_event_ids);

      global $post;
      foreach ($past_events as $post_id) {
        $post = get_post($post_id);
        setup_postdata($post);
    ?>
      <div class="grid-item item-s-4 margin-bottom-small text-align-center">
        <div class="card">
          <a href="<?php the_permalink(); ?>">
            <div class="margin-bottom-tiny">
              <?php the_post_thumbnail('l-4-landscape'); ?>
            </div>
          </a>
          <a href="<?php the_permalink(); ?>">
            <h3 class="margin-bottom-tiny js-fix-widows"><?php the_title(); ?></h3>
          </a>
          <div class="text-align-center"><a href="<?php the_permalink(); ?>" class="link-button">Read More</a></div>
        </div>
      </div>
    <?php
      }
      wp_reset_postdata();
    ?>
    </div>

    <div class="grid-row margin-top-basic margin-bottom-basic">
      <div class="grid-item item-s-12 font-style-micro text-align-center">
        <div class="dotted-divider">
          <div class="dotted-divider-side dotted-divider-left"></div>
          <div class="dotted-divider-center"><a href="<?php echo home_url('sound-and-vision'); ?>" class="link-button">More Sound & Vision Posts +</a></div>
          <div class="dotted-divider-side dotted-divider-right"></div>
        </div>
      </div>
    </div>

    >>> quote goes here but from other feature branch

  </div>

</main>

<?php
get_footer();
?>