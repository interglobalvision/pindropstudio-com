<?php
  global $forthcoming_iterator;

  $location = get_post_meta($post->ID, '_igv_event_location', true);
  $address = get_post_meta($post->ID, '_igv_event_address', true);
  $time = get_post_meta($post->ID, '_igv_event_datetime', true);
  $booking = get_post_meta($post->ID, '_igv_event_booking_url', true);

  $time_moment = new \Moment\Moment('@' . $time);

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
        render_live_forthcoming_location($location, $address);
      } else {
        render_live_forthcoming_time($time_moment);
      }
      ?>
  </div>
  <div class="grid-item item-s-12 item-m-4">
    <?php the_post_thumbnail('l-4'); ?>
  </div>
  <div class="grid-item item-s-12 item-m-3 text-align-center live-forthcoming-event-meta">
    <?php
      if ($forthcoming_iterator % 2 === 0) {
        render_live_forthcoming_time($time_moment);
      } else {
        render_live_forthcoming_location($location, $address);
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