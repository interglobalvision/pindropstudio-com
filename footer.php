  <footer id="footer" class="container">
    <div class="grid-row">
      <div class="grid-item item-s-12 item-m-6 offset-m-3 font-style-micro text-align-center">
        <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-pin.svg'); ?>
        <ul id="footer-menu" class="u-inline-list">
          <li>Live</li>
          <li>Sound & Vision</li>
          <li>Luminaries</li>
          <li>News</li>
          <li>Shop</li>
          <li>Join</li>
          <li>Submit</li>
          <li>Credits</li>
        </ul>
        <ul id="footer-social-menu" class="u-inline-list">
          <li>Twitter</li>
          <li>Facebook</li>
          <li>Instagram</li>
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