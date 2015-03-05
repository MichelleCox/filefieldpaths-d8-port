<?php

/**
 * @file
 * Contains the \Drupal\filefield_paths\FileFieldPathsStringProcessor class.
 */

namespace Drupal\filefield_paths;

/**
 * Process and cleanup strings.
 */
class FileFieldPathsStringProcessor {

  public function tokenReplace($string, $data, $settings = array()){
    // @TODO: Optionally integrate with contrib Token.

    // @TODO: Remove this temp code.
    // This is just here as a way to see all available tokens in debugger.
    //$tokens = \Drupal::token()->getInfo();

    // Replace tokens with core Token service.
    $replaced = \Drupal::token()->replace($string, $data);

    // Ensure that there are no double-slash sequences due to empty token values.
    $replaced = preg_replace('/\/+/', '/', $replaced);

    return $replaced;
  }

  public function transliterate($string) {
    // @TODO: Make transliteration work.
    // @TODO: Check if core Transliteration will do the job.
    // See /core/lib/Drupal/Component/Transliteration/PhpTransliteration.php
    // Transliterate string.
    /*
    if (\Drupal::moduleHandler()->moduleExists('transliteration') && isset($settings['transliterate']) && $settings['transliterate']) {
      $path = transliteration_clean_filename($path);
    }
    */
    return $string;
  }

  public function pathAutoClean($string) {
    // Sanity check: Make sure PathAuto is installed before trying to use it.
    if (\Drupal::moduleHandler()->moduleExists('pathauto')) {
      $pathauto_manager = \Drupal::service('pathauto.manager');
      $string = $pathauto_manager->cleanString($string);
    }

    return $string;
  }
}
