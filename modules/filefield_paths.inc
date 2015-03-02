<?php
/**
 * @file
 * File (Field) Paths module integration.
 */

/**
 * Implements hook_filefield_paths_field_settings().
 */
function filefield_paths_filefield_paths_field_settings() {
  // @TODO: This is no longer used.
  return array(
    'file_path' => array(
      'title' => 'File path',
      'sql' => 'filepath',

      'form' => array(
        'value' => array(
          '#type' => 'textfield',
          '#title' => t('File path'),
          '#maxlength' => 512,
          '#size' => 128,
          '#element_validate' => array('_file_generic_settings_file_directory_validate'),
        ),
      ),
    ),

    'file_name' => array(
      'title' => 'File name',
      'sql' => 'filename',

      'form' => array(
        'value' => array(
          '#type' => 'textfield',
          '#title' => t('File name'),
          '#maxlength' => 512,
          '#size' => 128,
          '#default_value' => '[file:ffp-name-only-original].[file:ffp-extension-original]',
        ),
      ),
    )
  );
}

