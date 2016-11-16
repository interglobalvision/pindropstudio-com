<?php
// SITE OPTIONS
$prefix = '_igv_';
$suffix = '_options';

$page_key = $prefix . 'submit' . $suffix;
$page_title = 'Submit Options';

$metabox = array(
  'id'         => $page_key, // id used as tab page slug, must be unique
  'title'      => $page_title,
  'show_on'    => array( 'key' => 'options-page', 'value' => array( $page_key ), ), //value must be same as id
  'show_names' => true,
  'fields'     => array(

    // Submit
    array(
      'name' => __( 'Open Submissions', 'cmb2' ),
      'desc' => __( '', 'cmb2' ),
      'id'   => $prefix . 'open_submissions',
      'type' => 'group',
      'repetable' => true,
      'options'     => array(
        'group_title'   => __( 'Submission {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add another open submission', 'cmb2' ),
        'remove_button' => __( 'Remove Submission', 'cmb2' ),
        'sortable'      => true, // beta
      ),
      'fields' => array(
        array(
          'name' => __( 'Title', 'cmb2' ),
          'id'   => $prefix . 'submission_title',
          'type' => 'text',
        ),
        array(
          'name' => __( 'Image', 'cmb2' ),
          'id'   => $prefix . 'submission_image',
          'type' => 'file',
        ),
        array(
          'name' => __( 'Description', 'cmb2' ),
          'id'   => $prefix . 'submission_desc',
          'type' => 'wysiwyg',
        ),
        array(
          'name' => __( 'Button Text', 'cmb2' ),
          'id'   => $prefix . 'submission_button_text',
          'type' => 'text',
        ),
        array(
          'name' => __( 'Form', 'cmb2' ),
          'id'   => $prefix . 'submission_form',
          'type' => 'select',
          'options_cb' => 'show_forms_options',
        ),
      ),
    )
  )
);

IGV_init_options_page($page_key, $page_key, $page_title, $metabox);

// Callback function that returns available forms
function show_forms_options( $field ) {
  $forms = GFAPI::get_forms();

  $available_forms = array();

  foreach($forms as $form) {
    $available_forms[$form['id']] = __($form['title'], 'cmb2');
  }

  return $available_forms;
}
