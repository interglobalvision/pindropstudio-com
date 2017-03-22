<?php
  global $post_count;

  $booking = get_post_meta($post->ID, '_igv_event_booking_url', true);
  $soldout = get_post_meta($post->ID, '_igv_soldout', true);
  $time = get_post_meta($post->ID, '_igv_event_datetime', true);
  $time_moment = new \Moment\Moment('@' . $time);
?>
<div class="grid-item item-s-12<?php
  if ($post_count === 3 || $post_count > 5) {
    echo ' item-m-4';
  } else if ($post_count === 2 || $post_count === 4) {
    echo ' item-m-6 item-l-5';
  } else {
    echo ' item-m-12';
  }
?> margin-bottom-small text-align-center">
  <div class="card card-big">
    <a href="<?php the_permalink(); ?>">
      <?php
        if ($post_count === 1) {
          the_post_thumbnail('l-5-square');
        } else {
          the_post_thumbnail('l-4-square');
        }
      ?>
      <h2 class="event-forthcoming-title margin-top-small margin-bottom-small"><?php the_title(); ?></h2>
      <h4 class="event-forthcoming-datetime margin-bottom-tiny font-style-micro"><?php echo $time_moment->format('H:i'); ?> | <?php echo $time_moment->format('l'); ?></h4>
      <h1 class="font-size-big-number margin-bottom-tiny"><?php echo $time_moment->format('d'); ?></h1>
      <h4 class="font-style-micro"><?php echo $time_moment->format('F'); ?></h4>
    </a>
      <div class="event-forthcoming-buttons margin-top-basic text-align-center">
        <ul class="button-list">
    <?php
      if ($soldout) {
    ?>
          <li>Fully Booked</li>
    <?php
      } else if ($booking) {
    ?>
          <li><a href="<?php echo $booking; ?>" target="_blank" rel="noopener">Book Now</a></li>
    <?php
      }
    ?>
          <li><a href="<?php the_permalink(); ?>" class="">Read More</a></li>
        </ul>
      </div>
  </div>
</div>