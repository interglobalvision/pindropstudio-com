<?php
  $booking = get_post_meta($post->ID, '_igv_event_booking_url', true);

  if ($forthcoming_iterator !== 0) {
?>
<div class="grid-row margin-bottom-large">
  <div class="grid-item item-s-12">
    <div class="dotted-divider-full"></div>
  </div>
</div>
<?php
  }
?>

<div class="grid-row margin-bottom-small">
  <div class="grid-item item-s-12 item-m-3 offset-m-1 text-align-center live-forthcoming-event-meta">

    <?php
      if ($forthcoming_iterator % 2 === 0) {
        get_template_part('partials/custom-pages/live/event-forthcoming-location');
      } else {
        get_template_part('partials/custom-pages/live/event-forthcoming-time');
      }
      ?>
  </div>
  <div class="grid-item item-s-12 item-m-4">
    <?php the_post_thumbnail('l-4'); ?>
  </div>
  <div class="grid-item item-s-12 item-m-3 text-align-center live-forthcoming-event-meta">
    <?php
      if ($forthcoming_iterator % 2 === 0) {
        get_template_part('partials/custom-pages/live/event-forthcoming-time');
      } else {
        get_template_part('partials/custom-pages/live/event-forthcoming-location');
      }
      ?>
  </div>

</div>
<div class="grid-row margin-bottom-mid">
  <div class="grid-item- item-s-12 item-m-8 offset-m-2">
    <h2 class="text-align-center margin-bottom-small"><?php the_title(); ?></h2>
    <?php
      the_content();

      if ($booking) {
    ?>
        <div class="margin-top-basic text-align-center"><a href="<?php echo $booking; ?>" class="link-button" target="_blank" rel="noopener">Book Here</a></div>
    <?php
      }
    ?>
  </div>
</div>
