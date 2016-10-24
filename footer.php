  <footer id="footer" class="container">
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-8 offset-m-2 fontstyle-micro text-align-center">
        <div id="footer-pin-holder">
          <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-pin.svg'); ?>
        </div>
        <ul class="footer-menu u-inline-list">
          <li>Live</li>
          <li>Sound & Vision</li>
          <li>Luminaries</li>
          <li>Shop</li>
          <li>Join</li>
          <li>Submit</li>
          <li>News</li>
        </ul>
        <ul class="footer-menu u-inline-list">
          <li>About</li>
          <li>People</li>
          <li>Partners</li>
          <li>Contact</li>
          <li>Credits</li>
        </ul>
        <ul id="footer-social-menu" class="footer-menu u-inline-list">
        <?php
          $twitter = IGV_get_option('_igv_site_options', '_igv_socialmedia_twitter');
          $facebook = IGV_get_option('_igv_site_options', '_igv_socialmedia_facebook_url');
          $instagram = IGV_get_option('_igv_site_options', '_igv_socialmedia_instagram');

          if ($twitter) {
        ?>
          <li><a href="https://twitter.com/<?php echo $twitter; ?>" target="_blank" rel="noreferrer">Twitter</a></li>
        <?php
          }
          if ($facebook) {
        ?>
          <li><a href="<?php echo $facebook; ?>" target="_blank" rel="noreferrer">Facebook</a></li>
        <?php
          }
          if ($instagram) {
        ?>
          <li><a href="https://instagram.com/<?php echo $instagram; ?>" target="_blank" rel="noreferrer">Instagram</a></li>
        <?php
          }
        ?>
        </ul>
        T&C's
      </div>
    </div>
  </footer>

</section>

<?php
  get_template_part('partials/scripts');
  get_template_part('partials/schema-org');
?>

</body>
</html>