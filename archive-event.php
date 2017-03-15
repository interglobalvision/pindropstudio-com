<?php
get_header();
?>

<main id="main-content">
  <section id="posts" class="container">
    <div class="grid-row margin-bottom-basic">
      <?php render_divider('Past Events'); ?>
    </div>

    <div class="shuffle-section">
      <div class="shuffle-preloader"></div>
      <div class="shuffle-container grid-row hidden">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
    get_template_part('partials/custom-pages/event-archive');
  }
} else {
?>
        <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no posts matched your criteria :{'); ?></article>
<?php
} ?>
      </div>
    </div>
  </section>

  <?php get_template_part('partials/pagination'); ?>

</main>

<?php
get_footer();
?>
