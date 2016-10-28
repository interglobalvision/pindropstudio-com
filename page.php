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
    <div class="grid-row margin-top-basic margin-bottom-basic">
      <div class="grid-item item-s-12 font-style-micro text-align-center">
        <div class="dotted-divider">
          <div class="dotted-divider-side dotted-divider-left"></div>
          <div class="dotted-divider-center"><?php the_title(); ?></div>
          <div class="dotted-divider-side dotted-divider-right"></div>
        </div>
      </div>
    </div>
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic">
        <?php the_content(); ?>
      </div>
    </div>
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