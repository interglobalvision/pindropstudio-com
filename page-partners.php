<?php
get_header();
?>

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
    $partners = get_post_meta($post->ID, '_igv_partners_metabox_group', true);
  ?>
  <article id="page" <?php post_class('container'); ?>>
    <div class="grid-row margin-top-basic margin-bottom-basic">
      <div class="grid-item item-s-12 font-style-micro font-size-small text-align-center">
        <?php the_title(); ?>
      </div>
    </div>
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-mid">
        <?php the_content(); ?>
      </div>
    </div>
    <?php
      if ($partners) {
    ?>
    <div class="grid-row margin-bottom-mid">
      <div class="grid-item item-s-12">
        <div class="dotted-divider-full"></div>
      </div>
    </div>
    <div class="grid-row margin-bottom-mid">
    <?php
        $i = 0;
        foreach($partners as $partner) {
          if ($i % 2 === 0 && $i != 0) {
    ?>
    </div>
    <div class="grid-row margin-bottom-mid">
      <div class="grid-item item-s-12">
        <div class="dotted-divider-full"></div>
      </div>
    </div>
    <div class="grid-row margin-bottom-mid">
    <?php
          }
    ?>
      <div class="grid-item item-s-6 text-align-center">
    <?php
        if (!empty($partner['image'])) {
          echo wp_get_attachment_image($partner['image_id'], 'l-4', false, array('class' => 'margin-bottom-small'));
        } else if (!empty($partner['name'])) {
          echo '<h3 class="margin-bottom-small">' . $partner['name'] . '</h3>';
        }

        if (!empty($partner['text'])) {
          echo '<p>' . $partner['text'] . '</p>';
        }
    ?>
      </div>
    <?php
        $i++;
        }
    ?>
    </div>
    <div class="grid-row">
      <div class="grid-item item-s-12">
        <div class="dotted-divider-full"></div>
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