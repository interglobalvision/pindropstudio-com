<?php
if( get_next_posts_link() || get_previous_posts_link() ) {
?>
  <!-- post pagination -->
  <nav id="pagination" class="container">
    <div class="grid-row margin-top-basic margin-bottom-large">
      <div class="grid-item item-s-12 font-style-micro font-size-small text-align-center">
        <div class="dotted-divider">
          <div class="dotted-divider-side dotted-divider-left"></div>
          <div class="dotted-divider-center">
<?php
$previous = get_previous_posts_link('Newer');
$next = get_next_posts_link('Older');
if ($previous) {
  echo $previous;
}
if ($previous && $next) {
  echo ' &mdash; ';
}
if ($next) {
  echo $next;
}
?>
          </div>
          <div class="dotted-divider-side dotted-divider-right"></div>
        </div>
      </div>
    </div>
  </nav>
<?php
}
?>