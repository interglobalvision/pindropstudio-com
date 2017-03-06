<?php
get_header();
?>

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();

    get_template_part('partials/single-event/single-event-past');
?>
    <div class="grid-row">
      <?php render_divider(); ?>
    </div>
<?php
    $luminaries = get_post_meta($post->ID, '_igv_related_luminaries', true);

    if ($luminaries) {
      $luminaries = explode(', ', $luminaries);
    ?>
    <div class="grid-row justify-center margin-top-large margin-bottom-large">
    <?php
        global $post;
        foreach($luminaries as $post_id) {
          $post = get_post($post_id);
          setup_postdata($post);
    ?>
      <div class="grid-item item-s-6 item-m-4 text-align-center">
        <a href="<?php the_permalink(); ?>">
          <?php the_post_thumbnail('l-4-square', array('class' => 'margin-bottom-basic')); ?>
          <h3 class="margin-bottom-small"><?php the_title(); ?></h3>
        </a>
      </div>
    <?php
        }
        wp_reset_postdata();
    ?>
    </div>
    <?php
    }
    get_template_part('partials/related-posts');
    ?>
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
