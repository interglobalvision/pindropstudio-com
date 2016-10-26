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
        <article <?php post_class('grid-item item-s-6 item-m-3 margin-bottom-small'); ?> id="post-<?php the_ID(); ?>">
          <a href="<?php the_permalink(); ?>">
          <?php the_post_thumbnail(); ?>
          <h3 class="text-align-center"><?php the_title(); ?></h3>
          </a>
        </article>

<?php
  }
} else {
?>
        <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no posts matched your criteria'); ?></article>
<?php
} ?>

    </div>
  </section>

  <?php get_template_part('partials/pagination'); ?>

</main>

<?php
get_footer();
?>