<?php
  $carousel_posts = IGV_get_option('_igv_home_options', '_igv_carousel_posts');

  if ($carousel_posts) {
?>
<div class="grid-row margin-bottom-large">
  <div class="grid-item item-s-12">
    <div class="swiper-container home-carousel text-align-center font-size-h3 font-style-shadow">
      <div class="swiper-wrapper">
      <?php
        global $post;
        foreach ($carousel_posts as $carousel_post) {
          $post = $carousel_post['_igv_carousel_post_id'];
          setup_postdata($post);
      ?>
        <div class="swiper-slide carousel-post">
          <?php
            if (!empty($carousel_post['_igv_carousel_image_override_id'])) {
              echo wp_get_attachment_image($carousel_post['_igv_carousel_image_override_id'], 'l-12-carousel');
            } else {
              the_post_thumbnail('l-12-carousel');
            }
          ?>
          <div class="carousel-content align-items-center">
            <h1><?php
              if (!empty($carousel_post['_igv_carousel_title_override'])) {
                echo $carousel_post['_igv_carousel_title_override'];
              } else {
                the_title();
              }
            ?></h1>
          </div>
        </div>
      <?php
        }
      ?>
      </div>
      <div class="carousel-pagination align-items-center justify-center">
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </div>
</div>
<?php
  }
?>