<?php
  $time = get_post_meta($post->ID, '_igv_event_datetime', true);
  $time_moment = new \Moment\Moment('@' . $time);
?>
<h4 class="margin-bottom-tiny font-style-micro"><?php echo $time_moment->format('l'); ?></h4>
<h1 class="font-size-big-number margin-bottom-tiny"><?php echo $time_moment->format('d'); ?></h1>
<h4 class="font-style-micro"><?php echo $time_moment->format('F'); ?></h4>