<?php
get_header();
?>

<main id="main-content">
  <section class="container">
    <div class="grid-row margin-bottom-small">
      <?php render_divider('<div class="u-pointer"><span id="luminaries-sort-alphabetical">Sort Alphabetical +</span><span id="luminaries-sort-order">Sort Original +</span></div>'); ?>
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
            <?php the_post_thumbnail('l-3-square'); ?>
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

    <?php
      $quote_text = IGV_get_option('_igv_quote_options', '_igv_luminaries_quote_text');
      $quote_person = IGV_get_option('_igv_quote_options', '_igv_luminaries_quote_person');
      $quote_luminary = IGV_get_option('_igv_quote_options', '_igv_luminaries_quote_luminary');

      if ($quote_text && $quote_person) {
    ?>
    <div class="grid-row margin-top-large">
      <?php render_quote($quote_text, $quote_person, $quote_luminary); ?>
    </div>
    <?php
      }
    ?>

  </section>

</main>

<?php
get_footer();
?>