<?php

/**
 * @file
 * Contains Drupal\filefield_paths\Entity\FilePath.
 *
 * This contains our entity class.
 */

namespace Drupal\filefield_paths\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the FilePath entity.
 *
 * @ConfigEntityType(
 *   id = "filepath",
 *   label = @Translation("File Path"),
 *   admin_permission = "administer file paths",
 *   handlers = {
 *     "access" = "Drupal\filefield_paths\FilePathAccessController",
 *     "form" = {
 *       "config" = "Drupal\filefield_paths\Form\FilePathForm",
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *   },
 *   links = {
 *     "config-form" = "/admin/structure/types/manage/{type}/fields/{field}/filepath",
 *   }
 * )
 */
class FilePath extends ConfigEntityBase {

  /**
   * The FilePath ID.
   *
   * @var string
   */
  public $id;

  /**
   * FilePath enabled.
   *
   * @var boolean
   */
  public $enabled;

  /**
   * The FilePath path.
   *
   * @var string
   */
  public $path;


  /**
   * The FilePath filename.
   *
   * @var string
   */
  public $filename;
}
