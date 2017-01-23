<?php
get_header();
?>

<main id="main-content">

<?php
if( have_posts() ) {

  $gallery = get_post_meta($post->ID, '_igv_gallery', true);

  while( have_posts() ) {
    the_post();
  ?>
  <article id="page-join" <?php post_class('container'); ?>>
    <div class="grid-row">
      <div class="grid-item item-s-12 text-align-center margin-bottom-basic">
        <?php
          if ($gallery) {
            echo do_shortcode($gallery);
          } else {
            the_post_thumbnail('l-8');
          } ?>
      </div>
    </div>
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic">
        <?php the_content(); ?>
      </div>
    </div>
    <div class="grid-row margin-top-basic margin-bottom-basic">
      <?php render_divider(); ?>
    </div>
    <div class="grid-row margin-top-basic margin-bottom-basic">
      <div class="grid-item item-s-12 text-align-center">
        Payments handled by Stripe.<br />
        <img src="<?php bloginfo('stylesheet_directory'); ?>/img/dist/cards.png" alt="Visa/Mastercard/Amex" />
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