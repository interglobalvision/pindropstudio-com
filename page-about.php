<?php
get_header();

$people_core = new WP_Query(array(
  'post_type' => 'people',
  'posts_per_page' => -1,
  'orderby' => 'menu_order',
  'meta_query' => array(
    'relation' => 'OR',
    array(
      'key' => '_igv_circle',
      'value' => 'on',
      'compare' => '!='
    ),
    array(
      'key' => '_igv_circle',
      'compare' => 'NOT EXISTS'
    )
  )
));

$people_circle = new WP_Query(array(
  'post_type' => 'people',
  'posts_per_page' => -1,
  'orderby' => 'menu_order',
  'meta_query' => array(
    array(
      'key' => '_igv_circle',
      'value' => 'on',
      'compare' => '='
    )
  )
));
?>

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
  ?>
  <article id="page" <?php post_class('container'); ?>>
    <div class="grid-row margin-top-basic margin-bottom-basic">
      <?php render_divider('<span class="about-page-drawer-trigger u-pointer" data-target="people">People</span> | <span class="about-page-drawer-trigger u-pointer" data-target="partners">Partners</span> | <span class="about-page-drawer-trigger u-pointer active" data-target="about">About</span>'); ?>
    </div>
    <div id="about-drawer-people" class="about-page-drawer">
<?php
  if ($people_core->have_posts()) {
?>
    <div class="shuffle-section">
      <div class="stuffle-preloader"></div>
      <div class="shuffle-container grid-row hidden">
<?php
    while ($people_core->have_posts()) {
      $people_core->the_post();
      get_template_part('partials/people/person');
    }
?>
      </div>
    </div>
<?php
  }

  if ($people_circle->have_posts()) {
?>
    <div class="grid-row margin-top-basic margin-bottom-small">
      <?php render_divider('Pindrop Circle'); ?>
    </div>
    <div class="shuffle-section">
      <div class="stuffle-preloader"></div>
      <div class="shuffle-container grid-row hidden">
<?php
    while ($people_circle->have_posts()) {
      $people_circle->the_post();
      get_template_part('partials/people/person');
    }
?>
      </div>
    </div>
<?php
  }

  wp_reset_postdata();
?>
    </div>
    <div id="about-drawer-partners" class="about-page-drawer">
      <div class="grid-row">
      <?php
          render_partners();
      ?>
      </div>
    </div>
    <div id="about-drawer-about" class="about-page-drawer active">
      <div class="grid-row">
        <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic">
          <?php the_content(); ?>
        </div>
      </div>
    </div>

    <?php get_template_part('partials/connect'); ?>

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