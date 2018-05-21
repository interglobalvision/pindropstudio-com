<div class="grid-row margin-top-mid margin-bottom-basic">
  <?php render_divider('Sign up to our newsletter'); ?>
</div>

<form id="captcha-form" action="https://modernactivity.createsend.com/t/y/s/sulhjr/" action="https://www.createsend.com/t/subscribeerror?description=" method="post" class="js-cm-form connect-form" data-id="191722FC90141D02184CB1B62AB3DC262F95B3347CC1ABDEF250925282703F163395E69468AD5F876CF8A8F96F467779A1A001B7E165D7A1C2E1654A34F6CA93">
  <div class="grid-row margin-bottom-mid">
    <div class="grid-item item-s-12 item-m-3 offset-m-1">
      <input id="fieldName" name="cm-name" type="text" placeholder="First name..." required />
    </div>
    <div class="grid-item item-s-12 item-m-3">
      <input id="fieldetltrj" name="cm-f-sduwd" type="text" placeholder="Last name..." />
    </div>
    <div class="grid-item item-s-12 item-m-3">
      <input id="fieldEmail" name="cm-gdhtyl-gdhtyl" class="js-cm-email-input" type="email" placeholder="Email address..." required />
    </div>
    <div class="grid-item item-s-12 item-m-1 text-align-center">
     <button type="submit" class="connect-form-submit js-cm-submit-button">
        <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-arrow.svg'); ?>
      </button>
    </div>
  </div>
</form>

<script type="text/javascript" src="https://js.createsend1.com/javascript/copypastesubscribeformlogic.js"></script>

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
    $social_divider_content .= '&nbsp;<a href="https://instagram.com/' . $instagram . '" class="link-button" target="_blank" rel="noreferrer">Instagram</a>';
  }
?>

<div class="grid-row margin-bottom-basic">
  <?php render_divider($social_divider_content); ?>
</div>





<!--
<form id="subForm" class="js-cm-form" action="https://www.createsend.com/t/subscribeerror?description=" method="post" data-id="191722FC90141D02184CB1B62AB3DC262F95B3347CC1ABDEF250925282703F163395E69468AD5F876CF8A8F96F467779A1A001B7E165D7A1C2E1654A34F6CA93">
    
    <p>
        <label for="fieldName">Name</label><br />
        <input id="fieldName" name="cm-name" type="text" />
    </p>
    <p>
        <label for="fieldEmail">Email</label><br />
        <input id="fieldEmail" class="js-cm-email-input" name="cm-gdhtyl-gdhtyl" type="email" required /> 
    </p>
    <p>
        <label for="fieldsduwd">Lastname</label><br />
        <input id="fieldsduwd" name="cm-f-sduwd" type="text" />
    </p>
    <p>
        <button class="js-cm-submit-button" type="submit">Subscribe</button> 
    </p>
</form>
    <script type="text/javascript" src="https://js.createsend1.com/javascript/copypastesubscribeformlogic.js"></script>
-->
