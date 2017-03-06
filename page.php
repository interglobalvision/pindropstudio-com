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

    <?php get_template_part('partials/related-posts'); ?>
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