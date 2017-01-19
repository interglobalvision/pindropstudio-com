<?php
get_header();
?>

<main id="main-content">
  <section id="posts" class="container">
    <div class="shuffle-section">
      <div class="shuffle-preloader"></div>
      <div class="shuffle-container grid-row hidden">

<?php
if ( have_posts() ) {
  while( have_posts() ) {
    the_post();
    $lightbox_data = null;
    $explode_content = explode( '<!--more-->', $post->post_content );
    $lightbox_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'lightbox');

    $gallery = get_post_meta($post->ID, '_igv_gallery', true);

    if ($gallery) {
      $lightbox_data = ' class="thumb-for-lightbox u-pointer" data-lightbox="gallery" data-lightbox-title="' . get_the_title() . '" data-lightbox-image="' . $lightbox_image[0] . '"';
    }

?>
        <article <?php post_class('shuffle-item item-s-12 item-m-6 item-l-4'); ?> id="post-<?php the_ID(); ?>">
          <div class="card">
            <div <?php echo $lightbox_data; ?>>
              <?php
                if ($gallery) {
                  echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-icon-lightbox.svg');
                }

                the_post_thumbnail('l-4-full', array('class' => 'margin-bottom-tiny')); ?>
            </div>

            <h4 class="font-style-micro font-size-small margin-bottom-small text-align-center"><?php the_time('d F Y'); ?></h4>
            <h3 class="margin-bottom-small text-align-center"><?php the_title(); ?></h3>

    <?php
    if (sizeof($explode_content) > 1) {
      $content_before = apply_filters( 'the_content', $explode_content[0] );
      $content_after = apply_filters( 'the_content', $explode_content[1] );
    ?>
      <div class="expandable-post">
        <div class="expandable-excerpt">
          <?php echo $content_before; ?>
        </div>
          <p class="text-align-right"><a class="font-style-micro font-uppercase font-size-small u-pointer expandable-toggle" data-exapandable-id="expandable-<?php echo $post->ID; ?>">Read more</a></p>
        <div id="expandable-<?php echo $post->ID; ?>" class="expandable-content">
          <?php echo $content_after; ?>
        </div>
      </div>
    <?php
    } else {
      the_content();
    }

    if ($gallery) {
      render_hidden_gallery($gallery, $post->ID);
    }
    ?>
          </div>
        </article>

<?php
  }
} else {
?>
        <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no posts matched your criteria :{'); ?></article>
<?php
} ?>

      </div>
    </div>
  </section>

  <?php get_template_part('partials/pagination'); ?>

</main>

<?php
get_footer();
?>
