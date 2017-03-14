<?php
get_header();
?>

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
  ?>
  <article id="page" <?php post_class('container'); ?>>
    <div class="grid-row margin-bottom-basic">
      <?php render_divider(get_the_title()); ?>
    </div>
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic">
        <div class="post-copy">
          <?php the_content(); ?>
        </div>
      </div>
    </div>

    <?php
      $member_posts = new WP_Query(array(
        'post_type' => array('event', 'recording'),
        'posts_per_page' => -1,
/*         'category__in' => 451, */
        'category__in' => 51,

      ));

      if ($member_posts->have_posts()) {
    ?>
<!--
    <div class="grid-row margin-bottom-small">
      <?php render_divider('Members Only'); ?>
    </div>
-->
    <div class="shuffle-section media-items margin-bottom-small">
      <div class="shuffle-preloader"></div>
      <div class="shuffle-container grid-row hidden">
    <?php
        while ($member_posts->have_posts()) {
          $member_posts->the_post();
          get_template_part('partials/custom-pages/event-media');
        }

        wp_reset_postdata();
    ?>
    </div>
    <?php
      }
    ?>
  </article>
<?php
  }
} else {
?>
  <section id="page" class="container">
    <div class="grid-row">
      <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no pages matched your criteria'); ?></article>
    </div>
  </section>
<?php
} ?>

</main>

<?php
get_footer();
?>