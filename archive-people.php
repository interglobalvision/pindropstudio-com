<?php
get_header();
?>

<main id="main-content">
  <section id="posts" class="container">
    <div class="shuffle-section">
      <div class="stuffle-preloader"></div>
      <div class="shuffle-container grid-row hidden">

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
    while ($people_core->have_posts()) {
      $people_core->the_post();
      get_template_part('partials/people/person');
    }
  }

  if ($people_circle->have_posts()) {
    while ($people_circle->have_posts()) {
      $people_circle->the_post();
      get_template_part('partials/people/person');
    }
  }
?>
      </div>
    </div>
  </section>

</main>

<?php
get_footer();
?>