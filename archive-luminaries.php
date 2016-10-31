<?php
get_header();
?>

<main id="main-content">
  <section class="container">
    <div class="grid-row">
      <div class="grid-item item-s-12 margin-bottom-small font-style-micro text-align-center">
        <div class="dotted-divider">
          <div class="dotted-divider-side dotted-divider-left"></div>
          <div class="dotted-divider-center font-uppercase">
            <span id="luminaries-sort-alphabetical">Sort Alphabetical +</span>
            <span id="luminaries-sort-order">Sort By Order +</span>
          </div>
          <div class="dotted-divider-side dotted-divider-right"></div>
        </div>
      </div>
    </div>
    <div id="posts" class="grid-row">

<?php
if( have_posts() ) {
  $i = $wp_query->post_count;
  while( have_posts() ) {
    the_post();
    $surname = get_post_meta($post->ID, '_igv_surname', true);
?>
        <article <?php post_class('grid-item item-s-6 item-m-3 margin-bottom-small'); ?> id="post-<?php the_ID(); ?>" data-sort-order="<?php echo ($i + $post->menu_order); ?>" data-sort-alphabetical="<?php echo ($surname ? $surname : 'zz'); ?>">
          <a href="<?php the_permalink(); ?>">
          <?php the_post_thumbnail(); ?>
          <h3 class="text-align-center"><?php the_title(); ?></h3>
          </a>
        </article>

<?php
    $i--;
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