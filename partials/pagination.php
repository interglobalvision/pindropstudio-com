<?php
if ( get_next_posts_link() || get_previous_posts_link() ) {
?>
  <!-- post pagination -->
  <nav id="pagination" class="container">
    <div class="grid-row margin-top-basic margin-bottom-large">
    <?php
      $previous = get_previous_posts_link('Newer');
      $next = get_next_posts_link('Older');
      $content = '';

      if ($previous) {
        $content .= $previous;
      }

      if ($previous && $next) {
        $content .= ' &mdash; ';
      }

      if ($next) {
        $content .= $next;
      }

      render_divider_with_content($content);
    ?>
    </div>
  </nav>
<?php
}
?>