<?php
  $location = get_post_meta($post->ID, '_igv_event_location', true);
  $address = get_post_meta($post->ID, '_igv_event_address', true);
?>
<h4 class="margin-bottom-tiny font-style-micro"><?php echo $location; ?></h4>
<h3><?php echo $address; ?></h3>