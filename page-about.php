<?php
get_header();
?>

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();

    $people = IGV_get_option('_igv_about_options', '_igv_people');
    $partners = IGV_get_option('_igv_about_options', '_igv_partners');
  ?>
  <article id="page" <?php post_class('container'); ?>>
    <div class="grid-row margin-top-basic margin-bottom-basic">
      <?php render_divider('<span class="about-page-drawer-trigger u-pointer" data-target="people">People</span> | <span class="about-page-drawer-trigger u-pointer" data-target="partners">Partners</span> | <span class="about-page-drawer-trigger u-pointer active" data-target="about">About</span>'); ?>
    </div>
    <div id="about-drawer-people" class="about-page-drawer">
      <div class="grid-row">
        <?php
          if ($people) {
            foreach($people as $person) {
        ?>
        <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-large">
          <header class="text-align-center margin-bottom-basic">
          <?php
            if (!empty($person['_igv_image_id'])) {
              echo wp_get_attachment_image($person['_igv_image_id'], 'l-4', false, array('class' => 'margin-bottom-small', 'data' => 'no-lazysizes'));
            }
          ?>
          <h3><?php echo $person['_igv_name']; ?></h3>
          <h4 class="font-style-micro font-size-small margin-top-tiny"><?php echo $person['_igv_title']; ?></h4>
          </header>
          <?php echo apply_filters('the_content', $person['_igv_text'])?>
        </div>
        <?php
            }
          }
        ?>
      </div>
    </div>
    <div id="about-drawer-partners" class="about-page-drawer">
      <div class="grid-row">
        <?php
          if ($partners) {
            foreach($partners as $partner) {
        ?>
        <div class="grid-item item-s-12 item-m-6 text-align-center margin-bottom-basic">
          <?php
            if (!empty($partner['_igv_image_id'])) {
              echo wp_get_attachment_image($partner['_igv_image_id'], 'l-4', false, array('class' => 'margin-bottom-small', 'data' => 'no-lazysizes'));
            } else if (!empty($partner['_igv_name'])) {
              echo '<h3 class="margin-bottom-small">' . $partner['_igv_name'] . '</h3>';
            }

            if (!empty($partner['_igv_text'])) {
              echo '<p>' . $partner['_igv_text'] . '</p>';
            }
          ?>
        </div>
        <?php
            }
          }
        ?>
      </div>
    </div>
    <div id="about-drawer-about" class="about-page-drawer active">
      <div class="grid-row">
        <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic">
          <?php the_content(); ?>
        </div>
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