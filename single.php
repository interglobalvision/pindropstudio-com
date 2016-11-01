<?php
get_header();
?>

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
  ?>
  <article id="post" <?php post_class('container'); ?>>
    <div class="grid-row margin-top-basic margin-bottom-basic">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 text-align-center">
        <?php the_post_thumbnail('l-8'); ?>
      </div>
    </div>
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic">

        <h3 class="margin-bottom-small text-align-center"><?php the_title(); ?></h3>
        <h4 class="font-style-micro margin-bottom-small text-align-center"><?php the_time('d F Y'); ?></h4>

        <?php the_content(); ?>
      </div>
    </div>
    <?php
      get_template_part('partials/related-posts');
    ?>
  </article>
<?php
  }
} else {
?>
  <section id="page" class="container">
    <div class="grid-row">
      <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no posts matched your criteria'); ?></article>
    </div>
  </section>
<?php
} ?>

</main>

<?php
get_footer();
?>