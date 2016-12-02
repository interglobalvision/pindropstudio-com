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

 // LUMINARIES METABOXES

  $luminaries_meta = new_cmb2_box( array(
    'id'            => $prefix . 'luminaries_meta',
    'title'         => esc_html__( 'Luminary Meta', 'cmb2' ),
    'object_types'  => array( 'luminaries', ), // Post type
  ) );

  $luminaries_meta->add_field( array(
    'name'    => 'Surname',
    'desc'    => esc_html__( 'for alphabetical sorting', 'cmb2' ),
    'id'      => $prefix . 'surname',
    'type'    => 'text',
  ) );

  /**
   * Events Metaboxes
   * */
  $event_metabox = new_cmb2_box( array(
    'id'            => $prefix . 'event_meta',
    'title'         => __( 'Event Meta', 'cmb2' ),
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

  $event_metabox->add_field( array(
    'name'    => 'Vimeo video',
    'description' => __( 'Just the ID of the Vimeo video', 'cmb2' ),
    'id'      => $prefix . 'vimeo_id',
    'type'    => 'text',
  ) );

  $event_metabox->add_field( array(
    'name'    => 'Video caption (optional)',
    'id'      => $prefix . 'video_caption',
    'type'    => 'text',
  ) );

  $event_metabox->add_field( array(
    'name'    => 'Soundcloud audio',
    'description' => __( 'the URL of the Soundcloud recording', 'cmb2' ),
    'id'      => $prefix . 'soundcloud_url',
    'type'    => 'text_url',
  ) );

  $event_metabox->add_field( array(
    'name'    => 'Audio caption (optional)',
    'id'      => $prefix . 'audio_caption',
    'type'    => 'text',
  ) );

  $event_metabox->add_field( array(
    'name'    => 'Copy after embeds',
    'description' => __( 'optional copy after the embed blocks', 'cmb2' ),
    'id'      => $prefix . 'extra_content',
    'type'    => 'wysiwyg',
  ) );

  $event_metabox->add_field( array(
    'name'    => 'Luminary',
    'description' => __( 'one (or more) Luminaries to related to this event', 'cmb2' ),
    'id'      => $prefix . 'related_luminaries',
    'type'       => 'post_search_text',
    'post_type'  => array('luminaries'),
  ) );

  $event_metabox->add_field( array(
    'name'    => 'Gallery',
    'description' => __( 'a Wordpress gallery', 'cmb2' ),
    'id'      => $prefix . 'gallery',
    'type'    => 'wysiwyg',
  ) );

  // PAGE: PARTNERS

  $partners_metabox = new_cmb2_box( array(
    'id'            => $prefix . 'partners_metabox',
    'title'         => __( 'Partners', 'cmb2' ),
    'object_types' => array( 'page' ),
    'show_on'      => array( 'key' => 'id', 'value' => get_id_by_slug('partners') ),
  ) );

  $partners_group = $partners_metabox->add_field( array(
      'id'          => $prefix . 'partners_metabox_group',
      'type'        => 'group',
      'description' => __( 'Add Partners', 'cmb2' ),
      'options'     => array(
        'group_title'   => __( 'Partner {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add Another Partner', 'cmb2' ),
        'remove_button' => __( 'Remove Partner', 'cmb2' ),
        'sortable'      => true, // beta
      ),
  ) );

  $partners_metabox->add_group_field( $partners_group, array(
    'name' => 'Partner Name',
    'id'   => 'name',
    'type' => 'text',
  ) );

  $partners_metabox->add_group_field( $partners_group, array(
    'name' => 'Partner Text',
    'id'   => 'text',
    'type' => 'textarea',
  ) );

  $partners_metabox->add_group_field( $partners_group, array(
    'name' => 'Partner Image',
    'id'   => 'image',
    'type' => 'file',
  ) );

  // RELATED POSTS METABOXES

   $related_meta = new_cmb2_box( array(
    'id'            => $prefix . 'related_meta',
    'title'         => esc_html__( 'Related Posts Metabox', 'cmb2' ),
    'object_types'  => array( 'post', 'page', 'event', 'luminaries', ), // Post type
  ) );

  $related_meta->add_field( array(
    'name'       => esc_html__( 'Related posts', 'cmb2' ),
    'desc'       => esc_html__( 'select multiple other posts here to display as related posts', 'cmb2' ),
    'id'         => $prefix . 'related_posts',
    'type'       => 'post_search_text',
    'post_type'  => array('post', 'page', 'event', 'luminaries'),
  ) );

  // **********************************************************

  // HISTORICAL FOR MIGRATION

  $prefix = '_cmb_';

  $migration_postype_metabox = new_cmb2_box( array(
    'id'            => $prefix . 'migration_postype_metabox',
    'title'         => __( 'OLD Event Meta FOR MIGRATION ONLY', 'cmb2' ),
    'object_types'  => array( 'programme', 'event'), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
  ) );

  $migration_postype_metabox->add_field( array(
    'name' => 'Where?',
    'id'      => $prefix . 'where',
    'type' => 'wysiwyg',
  ) );

  $migration_postype_metabox->add_field( array(
    'name' => 'Ticket link',
    'id'      => $prefix . 'tickets',
    'type' => 'text_url',
  ) );

  $migration_postype_metabox->add_field( array(
    'name' => 'Sold Out?',
    'id'      => $prefix . 'soldout',
    'type' => 'checkbox',
  ) );

  $migration_recording_metabox = new_cmb2_box( array(
    'id'            => $prefix . 'migration_recording_metabox',
    'title'         => __( 'Meta', 'cmb2' ),
    'object_types'  => array( 'recording'), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
  ) );

  $migration_recording_metabox->add_field( array(
    'name' => 'Vimeo clip ID [just the numbers not the url]',
    'id'      => $prefix . 'recording',
    'type' => 'text',
  ) );

  $migration_recording_metabox->add_field( array(
    'name' => 'Audio Recording. insert the Audioboom url here',
    'id'      => $prefix . 'recording_audio',
    'type' => 'text',
  ) );

  $migration_post_metabox = new_cmb2_box( array(
    'id'            => $prefix . 'migration_post_metabox',
    'title'         => __( 'Meta', 'cmb2' ),
    'object_types'  => array( 'post'), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
  ) );

  $migration_post_metabox->add_field( array(
    'name' => 'Vimeo clip ID [just the numbers not the url]',
    'id'      => $prefix . 'recording',
    'type' => 'text',
  ) );

  $migration_post_metabox->add_field( array(
    'name' => 'Gallery',
    'id'      => $prefix . 'gallery',
    'type' => 'wysiwyg',
  ) );

}
?>
