<?php
  $booking = get_post_meta($post->ID, '_igv_event_booking_url', true);
  $time = get_post_meta($post->ID, '_igv_event_datetime', true);
  $time_moment = new \Moment\Moment('@' . $time);
?>


<div class="grid-item item-s-4 item-m-4 text-align-center">
  <div class="card">
    <a href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail('l-4-square'); ?>
      <h2 class="margin-top-small margin-bottom-small"><?php the_title(); ?></h2>
      <h4 class="margin-bottom-tiny font-style-micro"><?php echo $time_moment->format('H:i'); ?> | <?php echo $time_moment->format('l'); ?></h4>
      <h1 class="font-size-big-number margin-bottom-tiny"><?php echo $time_moment->format('d'); ?></h1>
      <h4 class="font-style-micro"><?php echo $time_moment->format('F'); ?></h4>
    </a>
    <?php
      if ($booking) {
    ?>
      <div class="margin-top-basic text-align-center"><a href="<?php echo $booking; ?>" class="link-button font-style-micro" target="_blank" rel="noopener">Booking Now</a></div>
    <?php
      }
    ?>
  </div>
</div>