  <footer id="footer" class="container">
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 font-style-micro font-size-small text-align-center">
        <div id="footer-pin-holder" class="u-pointer">
          <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-pin.svg'); ?>
        </div>
        <ul class="footer-menu u-inline-list">
          <li><a href="<?php echo home_url('live'); ?>">Live</a></li>
          <li><a href="<?php echo home_url('sound-and-vision'); ?>">Sound & Vision</a></li>
          <li><a href="<?php echo home_url('luminaries'); ?>">Luminaries</a></li>
          <?php
            $shop_link = IGV_get_option('_igv_site_options', '_igv_shop_link');
            if ($shop_link) {
          ?>
          <li><a href="<?php echo $shop_link; ?>" target="_blank" rel="noopener">Shop</a></li>
          <?php
            }
          ?>
          <li><a href="<?php echo home_url('join'); ?>">Join</a></li>
          <li><a href="<?php echo home_url('submit'); ?>">Submit</a></li>
          <li><a href="<?php echo home_url('news'); ?>">News</a></li>
        </ul>
        <ul class="footer-menu u-inline-list">
          <li><a href="<?php echo home_url('about'); ?>">About</a></li>
          <li><a href="<?php echo home_url('people'); ?>">People</a></li>
          <li><a href="<?php echo home_url('partners'); ?>">Partners</a></li>
          <li><a href="<?php echo home_url('contact'); ?>">Contact</a></li>
          <li><a href="<?php echo home_url('credits'); ?>">Credits</a></li>
          <li><a href="<?php echo home_url('privacy'); ?>">Privacy</a></li>
        </ul>
        <ul id="footer-social-menu" class="footer-menu u-inline-list margin-bottom-mid">
        <?php
          $twitter = IGV_get_option('_igv_site_options', '_igv_socialmedia_twitter');
          $facebook = IGV_get_option('_igv_site_options', '_igv_socialmedia_facebook_url');
          $instagram = IGV_get_option('_igv_site_options', '_igv_socialmedia_instagram');

          if ($twitter) {
        ?>
          <li><a href="https://twitter.com/<?php echo $twitter; ?>" class="link-button" target="_blank" rel="noreferrer">Twitter</a></li>
        <?php
          }
          if ($facebook) {
        ?>
          <li><a href="<?php echo $facebook; ?>" target="_blank" class="link-button" rel="noreferrer">Facebook</a></li>
        <?php
          }
          if ($instagram) {
        ?>
          <li><a href="https://instagram.com/<?php echo $instagram; ?>" target="_blank" class="link-button" rel="noreferrer">Instagram</a></li>
        <?php
          }
        ?>
        </ul>
        <a href="<?php echo home_url('terms-and-conditions'); ?>">T&C's</a>
      </div>
    </div>
  </footer>
</section>
<?php
  get_template_part('partials/lightbox');

  get_template_part('partials/scripts');
  get_template_part('partials/schema-org');
?>
</body>
</html>