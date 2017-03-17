<?php
  $ad1_image = IGV_get_option('_igv_home_options', '_igv_tall_ad1_image_id');
  $ad1_text = IGV_get_option('_igv_home_options', '_igv_tall_ad1_text');
  $ad1_subtitle = IGV_get_option('_igv_home_options', '_igv_tall_ad1_subtitle');
  $ad1_link_internal = IGV_get_option('_igv_home_options', '_igv_tall_ad1_link');
  $ad1_link_autoplay = IGV_get_option('_igv_home_options', '_igv_tall_ad1_link_media');
  $ad1_link_external = IGV_get_option('_igv_home_options', '_igv_tall_ad1_link_external');
  $ad1_link_text = IGV_get_option('_igv_home_options', '_igv_tall_ad1_button');

  $ad2_image = IGV_get_option('_igv_home_options', '_igv_tall_ad2_image_id');
  $ad2_text = IGV_get_option('_igv_home_options', '_igv_tall_ad2_text');
  $ad2_subtitle = IGV_get_option('_igv_home_options', '_igv_tall_ad2_subtitle');
  $ad2_link_internal = IGV_get_option('_igv_home_options', '_igv_tall_ad2_link');
  $ad2_link_autoplay = IGV_get_option('_igv_home_options', '_igv_tall_ad2_link_media');
  $ad2_link_external = IGV_get_option('_igv_home_options', '_igv_tall_ad2_link_external');
  $ad2_link_text = IGV_get_option('_igv_home_options', '_igv_tall_ad2_button');
?>
<div class="grid-row margin-top-large margin-bottom-large js-match-height-group" data-match-height-targets='["tall-ad-title", "tall-ad-subtitle"]'>
  <?php
    render_tall_ad($ad1_image, $ad1_text, $ad1_subtitle, $ad1_link_internal, $ad1_link_autoplay, $ad1_link_external, $ad1_link_text);
    render_tall_ad($ad2_image, $ad2_text, $ad2_subtitle, $ad2_link_internal, $ad2_link_autoplay, $ad2_link_external, $ad2_link_text);
  ?>
</div>