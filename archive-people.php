<?php
get_header();
?>

<main id="main-content">
  <section id="posts" class="container">
    <div class="grid-row margin-bottom-small">
      <?php render_divider('People'); ?>
    </div>
<?php
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
?>
  </section>

</main>

<?php
get_footer();
?>