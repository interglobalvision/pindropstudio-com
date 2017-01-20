<?php
  $carousel_posts = IGV_get_option('_igv_home_options', '_igv_carousel_posts');

  if ($carousel_posts) {
?>
<div class="grid-row margin-bottom-large">
  <div class="grid-item item-s-12">
    <div class="swiper-container home-carousel text-align-center font-style-shadow">
      <div class="swiper-wrapper">
      <?php
        global $post;
        foreach ($carousel_posts as $carousel_post) {
          $post = $carousel_post['_igv_carousel_post_id'];
          setup_postdata($post);

          if (!empty($carousel_post['_igv_carousel_image_override_id'])) {
            $background_url = wp_get_attachment_image_src($carousel_post['_igv_carousel_image_override_id'], 'l-12-carousel');
          } else {
            $background_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'l-12-carousel');
          }
      ?>
        <div class="swiper-slide carousel-post" data-background="<?php echo $background_url[0]; ?>">
          <div class="carousel-content-holder grid-column align-items-center justify-between">
            <header class="carousel-header margin-top-small">
              <?php
                $post_type = get_post_type($post);

                if ($post_type === 'event') {
                  $now = new \Moment\Moment('now', 'Europe/London');
                  $tomorrow = $now->addDays(1);
                  $midnight = $tomorrow->startOf('day');
                  $midnight_timestamp = strtotime($midnight->format());

                  $time_meta = get_post_meta($post, '_igv_event_datetime', true);
                  $time_moment = new \Moment\Moment('@' . $time_meta);

                  echo '<h4 class="font-style-micro margin-bottom-small">';

                  if ($time_meta > $midnight_timestamp) {
                    echo 'Live | ' . $time_moment->format('l j F') . ' | ' . $time_moment->format('H:i');
                  } else {
                    echo 'Sound & Vision';
                  }

                  echo '</h4>';

                } else if ($post_type === 'luminaries') {
                  echo '<h4 class="font-style-micro margin-bottom-small">Luminary</h4>';
                } else if ($post_type === 'post') {
                  echo '<h4 class="font-style-micro margin-bottom-small">News</h4>';
                }
              ?>
            </header>
            <div class="carousel-content margin-bottom-small">
              <h1><a href="<?php the_permalink(); ?>"><?php
                if (!empty($carousel_post['_igv_carousel_title_override'])) {
                  echo $carousel_post['_igv_carousel_title_override'];
                } else {
                  the_title();
                }
              ?></a></h1>
              <ul class="carousel-links margin-top-small font-style-micro u-inline-list">
                <li><a href="<?php the_permalink(); ?>">Read More</a></li>
              </ul>
            </div>
          </div>
        </div>
      <?php
        }
      ?>
      </div>
      <div class="carousel-pagination align-items-center justify-center">
        <div class="swiper-pagination font-size-h3"></div>
      </div>
    </div>
  </div>
</div>
<?php
  }
?>