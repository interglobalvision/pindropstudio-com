<?php
  $ad1_text = IGV_get_option('_igv_home_options', '_igv_tall_ad1_text');
  $ad1_image = IGV_get_option('_igv_home_options', '_igv_tall_ad1_image_id');
  $ad1_link_internal = IGV_get_option('_igv_home_options', '_igv_tall_ad1_link');
  $ad1_link_external = IGV_get_option('_igv_home_options', '_igv_tall_ad1_link_external');
  $ad1_link_text = IGV_get_option('_igv_home_options', '_igv_tall_ad1_button');

  $ad2_text = IGV_get_option('_igv_home_options', '_igv_tall_ad2_text');
  $ad2_image = IGV_get_option('_igv_home_options', '_igv_tall_ad2_image_id');
  $ad2_link_internal = IGV_get_option('_igv_home_options', '_igv_tall_ad2_link');
  $ad2_link_external = IGV_get_option('_igv_home_options', '_igv_tall_ad2_link_external');
  $ad2_link_text = IGV_get_option('_igv_home_options', '_igv_tall_ad2_button');
?>
<div class="grid-row margin-top-large margin-bottom-large">
  <div class="grid-item item-s-12 item-m-6">
    <div class="card text-align-center">
      <?php
        if ($ad1_image) {
          echo wp_get_attachment_image($ad1_image, 'l-4');
        }

        if ($ad1_text) {
          echo '<h3 class="margin-top-basic margin-bottom-small">' . $ad1_text . '</h3>';
        }

        if ($ad1_link_internal && $ad1_link_text) {
          echo '<a href="' . get_permalink($ad1_link_internal) . '" class="link-button">' . $ad1_link_text . '</a>';
        } else if ($ad1_link_external && $ad1_link_text) {
          echo '<a href="' . $ad1_link_external . '" class="link-button">' . $ad1_link_text . '</a>';
        }
      ?>
    </div>
  </div>
  <div class="grid-item item-s-12 item-m-6">
    <div class="card text-align-center">
      <?php
        if ($ad2_image) {
          echo wp_get_attachment_image($ad2_image, 'l-4');
        }

        if ($ad2_text) {
          echo '<h3 class="margin-top-basic margin-bottom-small">' . $ad2_text . '</h3>';
        }

        if ($ad2_link_internal && $ad2_link_text) {
          echo '<a href="' . get_permalink($ad2_link_internal) . '" class="link-button">' . $ad2_link_text . '</a>';
        } else if ($ad2_link_external && $ad2_link_text) {
          echo '<a href="' . $ad2_link_external . '" class="link-button">' . $ad2_link_text . '</a>';
        }
      ?>
    </div>
  </div>
</div>