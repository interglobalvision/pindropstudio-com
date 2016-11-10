<?php
get_header();
?>

<main id="main-content">
  <section class="open-submissions">
<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();

    $open_submissions = IGV_get_option('_igv_submit_options', '_igv_open_submissions');

    if (!empty($open_submissions)) {
      foreach($open_submissions as $index => $submission) {
  ?>
  <article <?php post_class('container submission'); ?>>
      <?php
        if (!empty($submission['_igv_submission_image_id'])) {
      ?>
    <div class="grid-row margin-top-basic margin-bottom-basic u-flex-center">
      <div class="grid-item item-s-6">
        <?php echo wp_get_attachment_image($submission['_igv_submission_image_id'], 'l-6'); ?>
      </div>
    </div>
      <?php
        }

        if (!empty($submission['_igv_submission_title'])) {
      ?>
    <div class="grid-row u-flex-center">
      <div class="grid-item item-s-9 margin-bottom-basic text-align-center">
        <h3><?php echo $submission['_igv_submission_title']; ?></h3>
      </div>
    </div>
      <?php
        }

        if (!empty($submission['_igv_submission_desc'])) {
      ?>
    <div class="grid-row u-flex-center">
      <div class="grid-item item-s-8 margin-bottom-basic">
        <?php echo apply_filters('the_content', $submission['_igv_submission_desc']); ?>
      </div>
    </div>
      <?php
        }

        if (!empty($submission['_igv_submission_button_text'])) {
      ?>
    <div class="grid-row u-flex-center">
      <div class="grid-item item-s-8 margin-bottom-basic">
      <div class="text-align-center"><a class="expandable-toggle no-hide link-button" data-exapandable-id="expandable-<?php echo $index; ?>"><?php echo $submission['_igv_submission_button_text']; ?></a></div>
      </div>
    </div>
      <?php
        }

        if (!empty($submission['_igv_submission_form'])) {
      ?>
    <div id="expandable-<?php echo $index; ?>" class="expandable-content grid-row u-flex-center">
      <div class="grid-item item-s-8 offset-s-2 margin-bottom-basic">
        <?php gravity_form($submission['_igv_submission_form']); ?>
      </div>
    </div>
      <?php
        }
      ?>
  </article>
<?php
      }
    }
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

</section>
</main>

<?php
get_footer();
?>
