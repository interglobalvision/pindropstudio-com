<?php
$now = new \Moment\Moment('now', 'Europe/London');
$tomorrow = $now->addDays(1);
$midnight = $tomorrow->startOf('day');
$midnight_timestamp = strtotime($midnight->format());

$forthcoming_events = new WP_Query(array(
  'post_type' => 'event',
  'posts_per_page' => 3,
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'meta_key' => '_igv_event_datetime',

  'post__not_in' => array($post->ID),

  'meta_query' => array(
    array(
      'key'     => '_igv_event_datetime',
      'value'   => $midnight_timestamp,
      'type'    => 'numeric',
      'compare' => '>',
    ),
  ),
));

if ($forthcoming_events->have_posts()) {
  $post_count = $forthcoming_events->post_count;
?>

<div class="grid-row margin-top-basic margin-bottom-small">
<?php render_divider('More Forthcoming Live Events'); ?>
</div>

<div class="grid-row justify-center margin-bottom-large">
<?php
  while ($forthcoming_events->have_posts()) {
    $forthcoming_events->the_post();
    get_template_part('partials/custom-pages/event-forthcoming');
  }
?>
</div>
<?php
}
?>