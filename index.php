<?php
get_header();
?>

<main id="main-content">
  <section id="posts" class="container">
    <div class="grid-row">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
?>
        <article <?php post_class('grid-item item-s-12 item-m-4'); ?> id="post-<?php the_ID(); ?>">

          <?php the_post_thumbnail(); ?>

          <h4 class="fontstyle-micro"><?php the_time('d F Y'); ?></h4>
          <h3><?php the_title(); ?></h3>

          <?php the_excerpt(); ?>

        </article>

<?php
  }
} else {
?>
        <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no posts matched your criteria :{'); ?></article>
<?php
} ?>

    </div>
  </section>

  <?php get_template_part('partials/pagination'); ?>

</main>

<?php
get_footer();
?>