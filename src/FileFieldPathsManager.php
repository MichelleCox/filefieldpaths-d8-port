<?php

namespace Drupal\filefield_paths;

use Drupal\file\Entity\File;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Config\Entity\ThirdPartySettingsInterface;

class FileFieldPathsManager {
  protected $stringProcessor;
  protected $contentEntity;
  protected $fieldPathSettings;

  public function __construct(FileFieldPathsStringProcessor $processor) {
    // The processor handles cleaning up strings and token replacement.
    $this->stringProcessor = $processor;
  }

  public function setContentEntity(ContentEntityInterface $entity) {
    $this->contentEntity = $entity;
  }

  protected function setFieldPathSettings(array $settings) {
    $this->fieldPathSettings = $settings;
  }

  public function processContentEntity() {
    if ($this->contentEntity instanceof ContentEntityInterface) {
      // Get a list of the types of fields that have files. (File, integer, video)
      $field_types = _filefield_paths_get_field_types();

      // Get a list of the fields on this entity.
      $fields = $this->contentEntity->getFields();

      // Iterate through all the fields looking for ones in our list.
      foreach ($fields as $key => $field) {
        // Get the field definition which holds the type and our settings.
        $field_info = $field->getFieldDefinition();

        // Get the field type, ie: file.
        $field_type = $field_info->getType();

        // Check the field type against our list of fields.
        if (isset($field_type) && in_array($field_type, $field_types)) {
          $this->processField($field_info);
        }
      }
    }
  }

  public function processField(ThirdPartySettingsInterface $field_info) {
    // Retrieve the settings we added to the field.
    $this->setFieldPathSettings($field_info->getThirdPartySettings('filefield_paths'));

    // If FFP is enabled on this field, process it.
    if ($this->fieldPathSettings['enabled']) {

      // Get the machine name of the field.
      $field_name = $field_info->field_name;

      // Go through each item on the field.
      foreach ($this->contentEntity->{$field_name} as $item) {
        // Get the file entity associated with the item.
        $file_entity = $item->entity;

        // Process the file.
        $this->processFile($file_entity);
      }
    }
  }

  public function processFile($file_entity) {
    // Retrieve the path/name strings with the tokens from settings.
    $tokenized_path = $this->fieldPathSettings['filepath'];
    $tokenized_filename = $this->fieldPathSettings['filename'];

    // Replace tokens.
    $entity_type = $this->contentEntity->getEntityTypeId();
    $data = array($entity_type => $this->contentEntity, 'file' => $file_entity);
    $path = $this->stringProcessor->tokenReplace($tokenized_path, $data);
    $filename = $this->stringProcessor->tokenReplace($tokenized_filename, $data);

    // Clean with PathAuto.
    if ($this->fieldPathSettings['path_options']['pathauto_path']) {
      $path_segments = explode("/", $path);
      $cleaned_segments = array();
      foreach ($path_segments as $segment) {
        $cleaned_segments[] = $this->stringProcessor->pathAutoClean($segment);
      }
      $path = implode("/", $cleaned_segments);
    }

    if ($this->fieldPathSettings['name_options']['pathauto_filename']) {
      $name_parts = pathinfo($filename);
      $cleaned_base = $this->stringProcessor->pathAutoClean($name_parts['filename']);
      $cleaned_extension = $this->stringProcessor->pathAutoClean($name_parts['extension']);

      $filename = $cleaned_base . '.' . $cleaned_extension;
    }

    // Transliterate: core only - this does not support the transliteration module.
    if ($this->fieldPathSettings['path_options']['transliteration_path'] || $this->fieldPathSettings['name_options']['transliteration_filename']) {
      // Use the current default interface language.
      $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
      // Instantiate the transliteration class.
      $trans = \Drupal::transliteration();
      if ($this->fieldPathSettings['path_options']['transliteration_path']) {
        // Use this to transliterate the path.
        $path = $trans->transliterate($path, $langcode);
      }
      if ($this->fieldPathSettings['name_options']['transliteration_filename']) {
        // Use this to transliterate the file name.
        $filename = $trans->transliterate($filename, $langcode);
      }
    }
    // @TODO: Sanity check to be sure we don't end up with an empty path or name.
    // If path is empty, just change filename?
    // If filename is empty, use original?

    // Move the file to its new home.
    $destination = file_build_uri($path);
    file_prepare_directory($destination, FILE_CREATE_DIRECTORY);
    file_move($file_entity, $destination . DIRECTORY_SEPARATOR . $filename);
  }

}
