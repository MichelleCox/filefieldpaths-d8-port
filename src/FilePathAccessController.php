<?php

/**
 * @file
 * Contains \Drupal\filefield_paths\FilePathAccessController.
 */

namespace Drupal\config_entity_example;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines an access controller for the FilePathSettings entity.
 */
class FilePathAccessController extends EntityAccessControlHandler {
// @TODO: Do proper access checking.
  /**
   * {@inheritdoc}
   */
  public function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {
    return TRUE;

    //return parent::checkAccess($entity, $operation, $langcode, $account);
  }

}
