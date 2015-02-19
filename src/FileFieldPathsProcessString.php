<?php

// @TODO: Make string processing a proper service.
// This is just the existing string processing tossed into a class. It hasn't
// had any additional work done, yet.

class FilefieldPathsProcessString {
  /**
   * Process and cleanup strings.
   */
  function processString($value, $data, $settings = array()) {
    // Process string tokens.
    $value = token_replace($value, $data, array('clear' => TRUE));

    $paths = explode('/', $value);
    foreach ($paths as $i => &$path) {

      // Cleanup with pathauto.
      if (\Drupal::moduleHandler()->moduleExists('pathauto') && isset($settings['pathauto']) && $settings['pathauto'] == TRUE) {
        module_load_include('inc', 'pathauto');
        if ('file_name' == $settings['context'] && count($paths) == $i + 1) {
          $pathinfo = pathinfo($path);
          $path = str_replace($pathinfo['filename'], pathauto_cleanstring($pathinfo['filename']), $path);
        }
        else {
          $path = pathauto_cleanstring($path);
        }
      }

      // Transliterate string.
      if (\Drupal::moduleHandler()->moduleExists('transliteration') && isset($settings['transliterate']) && $settings['transliterate']) {
        $path = transliteration_clean_filename($path);
      }
    }

    $value = implode('/', $paths);

    // Ensure that there are no double-slash sequences due to empty token values.
    $value = preg_replace('/\/+/', '/', $value);

    return $value;
  }
}
