<?php

/* Get post objects for select field options */
function get_post_objects( $query_args ) {
  $args = wp_parse_args( $query_args, array(
    'post_type' => 'post',
  ) );
  $posts = get_posts( $args );
  $post_options = array();
  if ( $posts ) {
    foreach ( $posts as $post ) {
      $post_options [ $post->ID ] = $post->post_title;
    }
  }
  return $post_options;
}


/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Hook in and add metaboxes. Can only happen on the 'cmb2_init' hook.
 */
add_action( 'cmb2_init', 'igv_cmb_metaboxes' );
function igv_cmb_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_igv_';

	/**
	 * Metaboxes declarations here
   * Reference: https://github.com/WebDevStudios/CMB2/blob/master/example-functions.php
	 */

  /**
   * Events Metaboxes
   * */
  $event_metabox = new_cmb2_box( array(
    'id'            => $prefix . 'event_meta',
    'title'         => __( 'Meta', 'cmb2' ),
    'object_types'  => array( 'event', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
  ) );

  $event_metabox->add_field( array(
    'name'    => 'City / Location',
    'id'      => $prefix . 'event_location',
    'type'    => 'text',
  ) );

  $event_metabox->add_field( array(
    'name'    => 'Address',
    'id'      => $prefix . 'event_address',
    'type'    => 'textarea',
  ) );

  $event_metabox->add_field( array(
    'name' => 'Date / Time',
    'id'      => $prefix . 'event_datetime',
    'type' => 'text_datetime_timestamp',
    // 'timezone_meta_key' => 'wiki_test_timezone',
    // 'date_format' => 'l jS \of F Y',
  ) );

  $event_metabox->add_field( array(
    'name'    => 'Booking link',
    'id'      => $prefix . 'event_booking_url',
    'type'    => 'text_url',
  ) );

}
?>
