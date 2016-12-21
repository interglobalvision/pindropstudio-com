<?php
$time_meta = get_post_meta($post->ID, '_igv_event_datetime', true);
$soldout = get_post_meta($post->ID, '_igv_soldout', true);
$booking = get_post_meta($post->ID, '_igv_event_booking_url', true);

if ($time_meta) {
  $time = new \Moment\Moment('@' . $time_meta);
}
?>
<article id="page" <?php post_class('container'); ?>>
<div class="grid-row margin-bottom-basic">
  <div class="grid-item item-s-12 item-m-10 offset-m-1 item-l-8 offset-l-2 text-align-center">
    <?php the_post_thumbnail('l-8'); ?>
  </div>
</div>
<div class="grid-row">
  <div class="grid-item item-s-12 item-m-8 offset-m-2 margin-bottom-basic">

    <header class="margin-bottom-mid text-align-center">
      <h2 class="margin-bottom-small"><?php the_title(); ?></h2>
      <h4 class="margin-bottom-tiny font-style-micro"><?php echo $time->format('H:i'); ?> | <?php echo $time->format('l'); ?></h4>
      <h1 class="font-size-big-number margin-bottom-tiny"><?php echo $time->format('d'); ?></h1>
      <h4 class="font-style-micro"><?php echo $time->format('F'); ?></h4>
    </header>

    <ul class="button-list margin-bottom-mid">
      <?php
        if ($soldout) {
      ?>
      <li>Fully Booked</li>
      <?php
        } else if ($booking) {
      ?>
      <li><a href="<?php echo $booking; ?>" target="_blank" rel="noopener">Tickets</a></li>
      <?php
        }
      ?>
    </ul>
    <?php the_content(); ?>

    ///>> share goes here
  </div>
</div>