<?php
get_header();
?>

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
  ?>
  <article id="single-luminary" <?php post_class('container'); ?>>
    <div class="grid-row">
      <div class="grid-item item-s-12 text-align-center">
        <?php the_post_thumbnail('l-4-square', array('class' => 'margin-bottom-basic')); ?>
      </div>
    </div>
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-mid">
        <h1 class="text-align-center margin-bottom-small"><?php the_title(); ?></h1>
        <div class="post-copy">
          <?php the_content(); ?>
        </div>
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
  <section id="single-luminary" class="container">
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