<?php

// RENDERS

function render_embed_caption($caption) {
  echo '<h5 class="text-align-center margin-top-tiny margin-bottom-tiny">' . $caption . '</h5>';
}

function render_divider() {
?>
<div class="grid-item item-s-12">
  <div class="dotted-divider-full"></div>
</div>
<?php
}

function render_divider_with_content($content) {
  if (!$content) {
    render_divider();
  }
?>
<div class="grid-item item-s-12 font-style-micro font-size-small text-align-center">
  <div class="dotted-divider">
    <div class="dotted-divider-side dotted-divider-left"></div>
    <div class="dotted-divider-center">
      <?php echo $content; ?>
    </div>
    <div class="dotted-divider-side dotted-divider-right"></div>
  </div>
</div>
<?php
}