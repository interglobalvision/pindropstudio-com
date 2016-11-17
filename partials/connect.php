<div class="grid-row margin-top-mid margin-bottom-basic">
  <?php render_divider('Connect with Pindrop'); ?>
</div>

<form action="http://modernactivity.createsend.com/t/y/s/cjrdlh/" method="post" id="subForm">
  <div class="grid-row margin-bottom-mid">
    <div class="grid-item item-s-12 item-m-3 offset-m-1">
      <input id="fieldName" name="cm-name" type="text" placeholder="First name..." />
    </div>
    <div class="grid-item item-s-12 item-m-3">
      <input id="fieldcktlkk" name="cm-f-cktlkk" type="text" placeholder="Last Name..." />
    </div>
    <div class="grid-item item-s-12 item-m-3">
      <input id="fieldEmail" name="cm-cjrdlh-cjrdlh" type="email" placeholder="Email address..." required="" />
    </div>
    <div class="grid-item item-s-12 item-m-1">
      <button type="submit" class="connect-form-submit">
        <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-arrow.svg'); ?>
      </button>
    </div>
  </div>
</form>

<?php
  $twitter = IGV_get_option('_igv_site_options', '_igv_socialmedia_twitter');
  $facebook = IGV_get_option('_igv_site_options', '_igv_socialmedia_facebook_url');
  $instagram = IGV_get_option('_igv_site_options', '_igv_socialmedia_instagram');

  $social_divider_content = '';

  if ($twitter) {
    $social_divider_content .= '<a href="https://twitter.com/' . $twitter . '" class="link-button" target="_blank" rel="noreferrer">Twitter</a>';
  }
  if ($facebook) {
    $social_divider_content .= '&nbsp;<a href="' . $facebook . '" class="link-button" target="_blank" rel="noreferrer">Facebook</a>';
  }
  if ($instagram) {
    $social_divider_content .= '&nbsp;<a href="https://instagram.com/' . $twitter . '" class="link-button" target="_blank" rel="noreferrer">Instagram</a>';
  }
?>

<div class="grid-row margin-bottom-basic">
  <?php render_divider($social_divider_content); ?>
</div>