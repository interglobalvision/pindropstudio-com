<?php
get_header();
?>

<main id="main-content">

<?php
if( have_posts() ) {

  $gallery = get_post_meta($post->ID, '_igv_gallery', true);
  $memberships = get_post_meta($post->ID, '_igv_join_memberships', true);

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
      <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic post-copy">
        <?php the_content(); ?>
      </div>
    </div>
    <?php
      if ($memberships) {
    ?>
    <div class="grid-row margin-top-basic margin-bottom-basic">
      <?php render_divider(); ?>
    </div>
    <div class="grid-row text-align-center margin-top-mid">
    <?php
        $i = 0;
        foreach ($memberships as $membership) {
          if ($membership['cost'] && $membership['name'] && $membership['link']) {
    ?>
      <div class="grid-item item-s-6 item-m-4 <?php
        if ($i % 2 === 0) {
          echo 'offset-m-2 ';
        }
      ?>margin-bottom-basic">
        <h1 class="font-size-big-number margin-bottom-tiny">£<?php echo $membership['cost']; ?></h1>
        <h3 class="margin-bottom-small"><?php echo $membership['name']; ?></h3>
        <a href="<?php echo $membership['link']; ?>" class="link-button">Sign Up Here</a>
      </div>
    <?php
          }
          $i++;
        }
    ?>
        </div>
    <div class="grid-row margin-top-basic margin-bottom-basic">
      <div class="grid-item item-s-12 text-align-center">
        Payments handled by Stripe.<br />
        <img src="<?php bloginfo('stylesheet_directory'); ?>/img/dist/cards.png" alt="Visa/Mastercard/Amex" />
      </div>
    </div>
    <?php
      }

      get_template_part('partials/connect'); ?>
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