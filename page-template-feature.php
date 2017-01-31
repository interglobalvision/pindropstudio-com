<?php
/**
 * Template Name: Collection Page
 */

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
      <div class="grid-item item-s-12">
        <h2 class="text-align-center"><?php the_title(); ?></h2>
      </div>
    </div>
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic post-copy">
        <?php the_content(); ?>
      </div>
    </div>

    <?php
  $related_posts = get_post_meta($post->ID, '_igv_related_posts', true);
  if ($related_posts) {
    $related_posts = explode(', ', $related_posts);
?>
    <div class="shuffle-section media-items margin-bottom-basic">
      <div class="shuffle-preloader"></div>
      <div class="shuffle-container grid-row hidden">
<?php
    global $post;
    foreach($related_posts as $post_id) {
      $post = get_post($post_id);
      if ($post) {
        setup_postdata($post);
        if (get_post_type($post->ID) === 'event') {
          get_template_part('partials/custom-pages/event-media');
        } else {
?>
          <article <?php post_class('media-item shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>">
            <div class="card">
              <div class="margin-bottom-small">
                <?php the_post_thumbnail('l-4-full', array('class' => 'u-block')); ?>
              </div>

              <a href="<?php the_permalink(); ?>">
                <h4 class="font-style-micro font-size-small margin-bottom-small text-align-center">
                  <?php the_time('d F Y'); ?>
                </h4>
                <h3 class="margin-bottom-small text-align-center">
                  <?php the_title(); ?>
                </h3>
              </a>

              <div class="text-align-center">
                <a class="link-button font-size-small" href="<?php the_permalink(); ?>">Read More</a>
              </div>
            </div>
          </article>
<?php
        }
        wp_reset_postdata();
      }
    }
?>
      </div>
    </div>
<?php
  }
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