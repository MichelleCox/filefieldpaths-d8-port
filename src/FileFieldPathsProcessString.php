<?php

// @TODO: Consider renaming this to ProcessService or Processor instead of ProcessString to be more generic.

/**
 * @file
 * Contains the \Drupal\filefield_paths\FileFieldPathsProcessString class.
 */

namespace Drupal\filefield_paths;

use Drupal\Core\Utility\Token;

/**
 * Process and cleanup strings.
 */
class FileFieldPathsProcessString {

  public function tokenReplace($string, $data, $settings = array()){
    // @TODO: This is just temp code to easily see all available tokens.
    //$tokens = \Drupal::token()->getInfo();

    $replaced = \Drupal::token()->replace($string, $data);

    // Ensure that there are no double-slash sequences due to empty token values.
    $replaced = preg_replace('/\/+/', '/', $replaced);

    return $replaced;
  }

  public function transliterate() {
    // @TODO: Check if core Transliteration will do the job.
    // See /core/lib/Drupal/Component/Transliteration/PhpTransliteration.php
    // Transliterate string.
    /*
    if (\Drupal::moduleHandler()->moduleExists('transliteration') && isset($settings['transliterate']) && $settings['transliterate']) {
      $path = transliteration_clean_filename($path);
    }
    */
  }

  public function pathAutoClean() {
    // @TODO: Port this code to D8.
    /*
    $paths = explode('/', $value);
    foreach ($paths as $i => &$path) {

      // Cleanup with pathauto.
      if (\Drupal::moduleHandler()
          ->moduleExists('pathauto') && isset($settings['pathauto']) && $settings['pathauto'] == TRUE
      ) {
        module_load_include('inc', 'pathauto');
        if ('file_name' == $settings['context'] && count($paths) == $i + 1) {
          $pathinfo = pathinfo($path);
          $path = str_replace($pathinfo['filename'], pathauto_cleanstring($pathinfo['filename']), $path);
        }
        else {
          $path = pathauto_cleanstring($path);
        }
      }
      $value = implode('/', $paths);
    }
    */
  }
}
